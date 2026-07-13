<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    private $mediaPath;

  


    public function __construct()
    {
        // Use your custom path
        $this->mediaPath = base_path() . '/../assets/images/post';
        
        // Ensure the directory exists
        if (!File::exists($this->mediaPath)) {
            File::makeDirectory($this->mediaPath, 0755, true);
        }
    }
	  public function mediaManager()
    {
        return view('admin.profile.media');
    }
	
    public function getfiles(Request $request): JsonResponse
    {
        $folderPath = $request->get('folder_path', '');
        $search = $request->get('search');
        $type = $request->get('type');

        $currentPath = $this->mediaPath;
        if ($folderPath) {
            $currentPath .= '/' . $folderPath;
        }
        
        // Fix: Pass empty string if folderPath is null
        $relativePath = $folderPath ?: '';
        $items = $this->getFolderContents($currentPath, $relativePath, $search, $type);
      if ($folderPath) {
		   return response()->json([
            'success' => true,
            'data' => $items,
            'current_path' => $folderPath,
            'breadcrumb' => $this->getBreadcrumb($folderPath)
        ]);
	  }else{
		   return response()->json([
            'success' => true,
            'data' => $items,
            'current_path' => "",
            'breadcrumb' => ""
        ]);
	  }
       
    }

    // Fix: Make $relativePath parameter optional with default value
    private function getFolderContents(string $fullPath, string $relativePath = '', ?string $search = null, ?string $type = null): array
    {
        $items = [];
        
        if (!File::exists($fullPath)) {
            return $items;
        }

        // Get directories
        $directories = File::directories($fullPath);
        
        foreach ($directories as $directory) {
            $dirName = basename($directory);
            $dirRelativePath = $relativePath ? $relativePath . '/' . $dirName : $dirName;
            
            // Skip hidden directories (starting with .)
            if (strpos($dirName, '.') === 0) {
                continue;
            }
            
            // Skip if search doesn't match
            if ($search && !Str::contains(strtolower($dirName), strtolower($search))) {
                continue;
            }
            
            $itemCount = count(File::files($directory)) + count(File::directories($directory));
            
            $items[] = [
                'id' => $directory,
                'name' => $dirName,
                'type' => 'folder',
                'item_count' => $itemCount,
                'path' => $dirRelativePath,
                'modified' => File::lastModified($directory),
            ];
        }

        // Get files
        $files = File::files($fullPath);
        
        foreach ($files as $file) {
            $fileName = $file->getFilename();
            $fileType = $this->getFileType($file->getPathname());
            $fileRelativePath = $relativePath ? $relativePath . '/' . $fileName : $fileName;
            
            // Skip hidden files (starting with .)
            if (strpos($fileName, '.') === 0) {
                continue;
            }
            
            // Skip if search doesn't match
            if ($search && !Str::contains(strtolower($fileName), strtolower($search))) {
                continue;
            }
            
            // Skip if type filter doesn't match
            if ($type && $type !== 'folder' && $fileType !== $type) {
                continue;
            }
            
            // Generate URL for the file
            $fileUrl = $this->getFileUrl($fileRelativePath);
            
            $items[] = [
                'id' => $file->getPathname(),
                'name' => $fileName,
                'url' => $fileUrl,
                'size' => $file->getSize(),
                'type' => $fileType,
                'path' => $fileRelativePath,
                'modified' => $file->getMTime(),
                'formatted_size' => $this->formatFileSize($file->getSize()),
                'formatted_date' => $this->formatDate($file->getMTime()),
            ];
        }

        return $items;
    }

    private function getFileType(string $filePath): string
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'];
        $audioExtensions = ['mp3', 'wav', 'ogg', 'm4a'];
        $pdfExtensions = ['pdf'];
        
        if (in_array($extension, $imageExtensions)) return 'image';
        if (in_array($extension, $videoExtensions)) return 'video';
        if (in_array($extension, $audioExtensions)) return 'audio';
        if (in_array($extension, $pdfExtensions)) return 'pdf';
        
        return 'file';
    }

    private function getFileUrl(string $relativePath): string
    {
        // Since your files are outside the public directory, we need to create a route to serve them
        return url('/admin/media/file/' . $relativePath);
    }

    private function formatFileSize(int $bytes): string
    {
        if ($bytes == 0) return '0 Bytes';
        
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }

    private function formatDate(int $timestamp): string
    {
        return date('M j, Y', $timestamp);
    }

    // Fix: Make $folderPath parameter optional with default value
    private function getBreadcrumb(string $folderPath = ''): array
    {
        $breadcrumb = [
            ['id' => '', 'name' => 'Home']
        ];
        
        if (empty($folderPath)) {
            return $breadcrumb;
        }
        
        $parts = explode('/', $folderPath);
        $currentPath = '';
        
        foreach ($parts as $part) {
            $currentPath = $currentPath ? $currentPath . '/' . $part : $part;
            $breadcrumb[] = [
                'id' => $currentPath,
                'name' => $part
            ];
        }
        
        return $breadcrumb;
    }

    public function createFolder(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9_\-\s]+$/',
            'folder_path' => 'nullable|string'
        ]);

        $folderName = $request->name;
        $parentPath = $request->folder_path ?: '';
        
        $fullPath = $this->mediaPath;
        if ($parentPath) {
            $fullPath .= '/' . $parentPath;
        }
        $fullPath .= '/' . $folderName;

        try {
            if (File::exists($fullPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Folder already exists'
                ], 422);
            }

            File::makeDirectory($fullPath, 0755, true);

            return response()->json([
                'success' => true,
                'message' => 'Folder created successfully',
                'folder' => [
                    'name' => $folderName,
                    'path' => $parentPath ? $parentPath . '/' . $folderName : $folderName
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create folder: ' . $e->getMessage()
            ], 500);
        }
    }

    public function upload(Request $request): JsonResponse
{
    $request->validate([
        'files.*' => 'required|file|max:10240', // 10MB max
        'folder_path' => 'nullable|string'
    ]);

    $folderPath = $request->folder_path ?: '';
    $uploadPath = $this->mediaPath;
    if ($folderPath) {
        $uploadPath .= '/' . $folderPath;
    }

    // Ensure upload directory exists
    if (!File::exists($uploadPath)) {
        File::makeDirectory($uploadPath, 0755, true);
    }

    $uploadedFiles = [];
    $errors = [];

    foreach ($request->file('files') as $file) {
        try {
            // Validate file exists and is valid
            if (!$file->isValid()) {
                $errors[] = "File {$file->getClientOriginalName()} is not valid";
                continue;
            }

            $originalName = $file->getClientOriginalName();
            $safeName = $this->generateSafeFileName($originalName);
            
            // Get file info BEFORE moving
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();
            
            // Check if file already exists
            $destinationPath = $uploadPath . '/' . $safeName;
            if (File::exists($destinationPath)) {
                $errors[] = "File {$originalName} already exists";
                continue;
            }

            // Move the file
            $file->move($uploadPath, $safeName);

            // Verify the file was moved successfully
            if (!File::exists($destinationPath)) {
                $errors[] = "Failed to move file {$originalName}";
                continue;
            }

            $fileRelativePath = $folderPath ? $folderPath . '/' . $safeName : $safeName;
            
            $uploadedFiles[] = [
                'name' => $originalName,
                'safe_name' => $safeName,
                'path' => $fileRelativePath,
                'url' => $this->getFileUrl($fileRelativePath),
                'size' => $fileSize,
                'mime_type' => $mimeType,
            ];

        } catch (\Exception $e) {
            $errors[] = "Failed to upload {$file->getClientOriginalName()}: " . $e->getMessage();
        }
    }

    // Return appropriate response based on results
    if (count($uploadedFiles) === 0 && count($errors) > 0) {
        return response()->json([
            'success' => false,
            'message' => 'All files failed to upload',
            'errors' => $errors
        ], 500);
    }

    $response = [
        'success' => true,
        'message' => 'Files uploaded successfully',
        'files' => $uploadedFiles
    ];

    if (count($errors) > 0) {
        $response['warning'] = 'Some files failed to upload';
        $response['errors'] = $errors;
    }

    return response()->json($response);
}

    private function generateSafeFileName(string $filename): string
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        
        $safeName = Str::slug($name);
        $safeName = $safeName ?: 'file';
        
        return $safeName . '-' . time() . '.' . $extension;
    }

    public function deleteFile(Request $request): JsonResponse
    {
        $request->validate([
            'file_path' => 'required|string'
        ]);

        try {
            $filePath = $request->file_path;
            $fullPath = $this->mediaPath . '/' . $filePath;
            
            if (!File::exists($fullPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found'
                ], 404);
            }

            File::delete($fullPath);

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteFolder(Request $request): JsonResponse
    {
        $request->validate([
            'folder_path' => 'required|string'
        ]);

        try {
            $folderPath = $request->folder_path;
            $fullPath = $this->mediaPath . '/' . $folderPath;
            
            if (!File::exists($fullPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Folder not found'
                ], 404);
            }

            // Check if folder is empty
            $files = File::files($fullPath);
            $subfolders = File::directories($fullPath);
            
            if (count($files) > 0 || count($subfolders) > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Folder is not empty. Please delete all contents first.'
                ], 422);
            }

            File::deleteDirectory($fullPath);

            return response()->json([
                'success' => true,
                'message' => 'Folder deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete folder: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteFolderRecursive(Request $request): JsonResponse
    {
        $request->validate([
            'folder_path' => 'required|string'
        ]);

        try {
            $folderPath = $request->folder_path;
            $fullPath = $this->mediaPath . '/' . $folderPath;
            
            if (!File::exists($fullPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Folder not found'
                ], 404);
            }

            File::deleteDirectory($fullPath);

            return response()->json([
                'success' => true,
                'message' => 'Folder and all contents deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete folder: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Serve files from the custom directory
     */
    public function serveFile($path = '')
    {
        $fullPath = $this->mediaPath . '/' . $path;
        
        if (!File::exists($fullPath) || !File::isFile($fullPath)) {
            abort(404);
        }

        $file = File::get($fullPath);
        $type = File::mimeType($fullPath);

        return response($file, 200)
            ->header('Content-Type', $type)
            ->header('Content-Disposition', 'inline; filename="' . basename($path) . '"');
    }
}
	

