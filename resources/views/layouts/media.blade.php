
<!DOCTYPE html><html><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Print | @yield('title', 'Default Title')</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
   
	 <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            padding: 20px;
            line-height: 1.6;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #7f8c8d;
            font-size: 18px;
        }

        .example-form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        .file-input-with-trigger {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .file-input-with-trigger input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #bdc3c7;
            border-radius: 6px;
            font-size: 16px;
            background: #f8f9fa;
        }

        .media-trigger-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
            font-size: 16px;
            white-space: nowrap;
        }

        .media-trigger-btn:hover {
            background: #2980b9;
        }

        .file-preview {
            margin-top: 10px;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            background: #f8f9fa;
            min-height: 60px;
        }

        .preview-image {
            max-width: 200px;
            max-height: 150px;
            border-radius: 4px;
            margin-right: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
        }

        .file-info {
            display: inline-block;
            padding: 8px 12px;
            background: white;
            border-radius: 4px;
            margin-right: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
        }

        .submit-btn {
            padding: 12px 24px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .submit-btn:hover {
            background: #219653;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 2% auto;
            border-radius: 12px;
            width: 95%;
            max-width: 1200px;
            height: 90vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #2c3e50;
            color: white;
        }

        .modal-header h2 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .close-modal {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 5px 10px;
        }

        .modal-body {
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Media Manager Styles */
        .media-manager {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .tabs {
            display: flex;
            background: #2c3e50;
        }

        .tab-btn {
            flex: 1;
            padding: 15px 20px;
            background: none;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .tab-btn:hover {
            background: #34495e;
        }

        .tab-btn.active {
            background: #3498db;
        }

        .tab-content {
            display: none;
            padding: 20px;
            flex: 1;
            overflow-y: auto;
        }

        .tab-content.active {
            display: block;
        }

        .drop-zone {
            border: 3px dashed #bdc3c7;
            border-radius: 12px;
            padding: 60px 20px;
            text-align: center;
            transition: all 0.3s ease;
            background: #f8f9fa;
            margin-bottom: 20px;
        }

        .drop-zone.drag-over {
            border-color: #3498db;
            background: #e3f2fd;
            transform: scale(1.02);
        }

        .drop-zone i {
            font-size: 64px;
            color: #7f8c8d;
            margin-bottom: 20px;
        }

        .drop-zone h3 {
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .drop-zone p {
            color: #7f8c8d;
            margin-bottom: 20px;
        }

        .btn {
            padding: 12px 24px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            background: #2980b9;
        }

        .btn-primary {
            background: #3498db;
        }

        .btn-danger {
            background: #e74c3c;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn-success {
            background: #27ae60;
        }

        .btn-success:hover {
            background: #219653;
        }

        .upload-progress {
            margin: 20px 0;
            display: none;
        }

        .progress-bar {
            width: 100%;
            height: 12px;
            background: #ecf0f1;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(45deg, #2ecc71, #27ae60);
            transition: width 0.3s ease;
            border-radius: 6px;
            width: 0%;
        }

        .progress-text {
            text-align: center;
            color: #7f8c8d;
            font-size: 14px;
        }

        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .media-item {
            border: 2px solid transparent;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .media-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .media-item.selected {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.3);
        }

        .media-thumbnail {
            position: relative;
            height: 160px;
            overflow: hidden;
            background: #f8f9fa;
        }

        .media-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .media-item:hover .media-thumbnail img {
            transform: scale(1.05);
        }

        .media-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            font-size: 48px;
            color: #7f8c8d;
        }

        .media-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .media-item:hover .media-overlay {
            opacity: 1;
        }

        .media-overlay .btn {
            padding: 8px 12px;
            font-size: 14px;
        }

        .media-info {
            padding: 15px;
        }

        .media-name {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
            word-break: break-word;
        }

        .media-meta {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #7f8c8d;
        }

        .library-controls {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-input, .type-filter {
            padding: 10px 15px;
            border: 2px solid #bdc3c7;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            min-width: 200px;
        }

        .search-input:focus, .type-filter:focus {
            outline: none;
            border-color: #3498db;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-top: 30px;
            padding: 20px;
        }

        .pagination button {
            padding: 10px 20px;
            border: 2px solid #3498db;
            background: white;
            color: #3498db;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pagination button:hover:not(:disabled) {
            background: #3498db;
            color: white;
        }

        .pagination button:disabled {
            border-color: #bdc3c7;
            color: #bdc3c7;
            cursor: not-allowed;
        }

        .selected-files {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            display: none;
        }

        .selected-files.show {
            display: block;
        }

        .selected-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .selected-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 15px 0;
        }

        .selected-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            background: white;
            border-radius: 8px;
            border: 2px solid #3498db;
            font-size: 14px;
        }

        .btn-remove {
            background: #e74c3c;
            color: white;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .btn-remove:hover {
            background: #c0392b;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
            font-size: 18px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
        }

        .uploaded-section {
            margin-top: 30px;
        }

        .uploaded-section h3 {
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            padding: 20px;
            border-top: 1px solid #e0e0e0;
        }

        /* Folder Styles */
        .folder-item {
            border: 2px solid transparent;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .folder-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .folder-thumbnail {
            position: relative;
            height: 160px;
            overflow: hidden;
            background: linear-gradient(135deg, #3498db, #2c3e50);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .folder-icon {
            font-size: 64px;
            color: white;
        }

        .folder-info {
            padding: 15px;
        }

        .folder-name {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
            word-break: break-word;
        }

        .folder-meta {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #7f8c8d;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 8px;
            flex-wrap: wrap;
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #3498db;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .breadcrumb-item:hover {
            color: #2980b9;
        }

        .breadcrumb-separator {
            color: #7f8c8d;
        }

        .folder-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .folder-form {
            display: none;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .folder-form input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #bdc3c7;
            border-radius: 6px;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .folder-form input:focus {
            outline: none;
            border-color: #3498db;
        }

        .folder-form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        /* Notifications */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            background: #2ecc71;
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateX(400px);
            transition: transform 0.3s ease;
            z-index: 1000;
            max-width: 300px;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.error {
            background: #e74c3c;
        }

        @media (max-width: 768px) {
            .media-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 15px;
            }

            .library-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .search-input, .type-filter {
                width: 100%;
                min-width: auto;
            }

            .pagination {
                flex-direction: column;
                gap: 10px;
            }

            .tabs {
                flex-direction: column;
            }

            .file-input-with-trigger {
                flex-direction: column;
                align-items: stretch;
            }

            .media-trigger-btn {
                justify-content: center;
            }

            .modal-content {
                width: 98%;
                height: 95vh;
                margin: 1% auto;
            }
        }
    </style>
</head>
<body class="print-body">

 <div class="container">
 <div class="example-form">
	<h2>Example Form</h2>
	<p style="margin-bottom: 20px; color: #7f8c8d;">Click the media buttons to open the media manager and select files.</p>
	
	<form id="exampleForm">
	
		<div class="form-group">
			<label for="featuredImage">Featured Image</label>
			<div class="file-input-with-trigger">
				<input type="text" id="featuredImage" name="featured_image" readonly 
					   placeholder="No file selected">
				<button type="button" class="media-trigger-btn" data-target="featuredImage">
					<i class="fas fa-image"></i> Select Image
				</button>
			</div>
			<div class="file-preview" id="featuredImagePreview">No preview</div>
		</div>

		<div class="form-group">
			<label for="galleryImages">Gallery Images (Multiple Selection)</label>
			<div class="file-input-with-trigger">
				<input type="text" id="galleryImages" name="gallery_images" readonly 
					   placeholder="No files selected">
				<button type="button" class="media-trigger-btn" data-target="galleryImages" data-multiple="true">
					<i class="fas fa-images"></i> Select Multiple
				</button>
			</div>
			<div class="file-preview" id="galleryImagesPreview">No preview</div>
		</div>

		<button type="submit" class="submit-btn">
			<i class="fas fa-paper-plane"></i> Submit Form
		</button>
	</form>
</div>
</div>
<!-- Media Manager Modal -->
    <div id="mediaManagerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-images"></i> Media Library</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="media-manager">
                    <div class="tabs">
                        <button class="tab-btn active" data-tab="upload">Upload Files</button>
                        <button class="tab-btn" data-tab="library">Media Library</button>
                    </div>

                    <!-- Upload Tab -->
                    <div id="upload-tab" class="tab-content active">
                        <div class="drop-zone" id="dropZone">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <h3>Drag & Drop Files Here</h3>
                            <p>Files will be uploaded to: <span id="currentUploadPath">/media</span></p>
                            <p>Maximum file size: 10MB</p>
                            <button class="btn btn-primary" id="selectFilesBtn">
                                <i class="fas fa-folder-open"></i> Select Files
                            </button>
                            <input type="file" id="fileInput" multiple style="display: none;" 
                                   accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.txt">
                        </div>

                        <div class="upload-progress" id="uploadProgress">
                            <div class="progress-bar">
                                <div class="progress-fill" id="progressFill"></div>
                            </div>
                            <div class="progress-text" id="progressText">0%</div>
                        </div>
                    </div>

                    <!-- Library Tab -->
                    <div id="library-tab" class="tab-content">
                        <div class="breadcrumb" id="breadcrumb">
                            <div class="breadcrumb-item" data-path="">
                                <i class="fas fa-home"></i> Home
                            </div>
                        </div>

                        <div class="folder-actions">
                            <button class="btn btn-success" id="createFolderBtn">
                                <i class="fas fa-folder-plus"></i> Create Folder
                            </button>
                            <button class="btn btn-danger" id="deleteSelectedBtn" style="display: none;">
                                <i class="fas fa-trash"></i> Delete Selected
                            </button>
                        </div>

                        <div class="folder-form" id="folderForm">
                            <input type="text" id="folderNameInput" placeholder="Enter folder name">
                            <div class="folder-form-actions">
                                <button class="btn" id="cancelFolderBtn">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                                <button class="btn btn-primary" id="saveFolderBtn">
                                    <i class="fas fa-save"></i> Create Folder
                                </button>
                            </div>
                        </div>

                        <div class="library-controls">
                            <input type="text" id="searchInput" placeholder="Search files..." class="search-input">
                            <select id="typeFilter" class="type-filter">
                                <option value="">All File Types</option>
                                <option value="image">Images</option>
                                <option value="video">Videos</option>
                                <option value="audio">Audio</option>
                                <option value="pdf">PDF</option>
                                <option value="file">Other Files</option>
                            </select>
                        </div>

                        <div id="loadingIndicator" class="loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Loading files...
                        </div>

                        <div id="emptyState" class="empty-state" style="display: none;">
                            <i class="fas fa-folder-open"></i>
                            <h3>No files found</h3>
                            <p>Upload some files to get started</p>
                        </div>

                        <div id="mediaGrid" class="media-grid"></div>
                    </div>

                    <!-- Selected Files -->
                    <div class="selected-files" id="selectedFiles">
                        <div class="selected-header">
                            <h3><i class="fas fa-check-circle"></i> Selected Files (<span id="selectedCount">0</span>)</h3>
                        </div>
                        <div class="selected-list" id="selectedList"></div>
                    </div>
                </div>
            </div>
			
			<div class="modal-actions">
				<button class="btn" id="cancelSelectionBtn" style="background: #95a5a6;">
					<i class="fas fa-times"></i> Cancel
				</button>
				<button class="btn btn-primary" id="insertMediaBtn">
					<i class="fas fa-plus"></i> Insert Selected Media
				</button>
			</div>
          
        </div>
    </div>

    <div class="notification" id="notification"></div>

    <script>
        class MediaManager {
             constructor() {
				if (window.mediaManagerInstance) {
					console.warn('MediaManager already instantiated');
					return window.mediaManagerInstance;
				}
				window.mediaManagerInstance = this;

				this.selectedMedia = new Set();
				this.currentFolderPath = '';
				this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
				this.eventsBound = false;
				
				this.initializeElements();
				this.bindEvents();
				this.loadMediaLibrary();
			}


            initializeElements() {
                this.tabButtons = document.querySelectorAll('.tab-btn');
                this.tabContents = document.querySelectorAll('.tab-content');
                
                this.dropZone = document.getElementById('dropZone');
                this.fileInput = document.getElementById('fileInput');
                this.selectFilesBtn = document.getElementById('selectFilesBtn');
                this.uploadProgress = document.getElementById('uploadProgress');
                this.progressFill = document.getElementById('progressFill');
                this.progressText = document.getElementById('progressText');
                this.currentUploadPath = document.getElementById('currentUploadPath');
                
                this.breadcrumb = document.getElementById('breadcrumb');
                this.createFolderBtn = document.getElementById('createFolderBtn');
                this.folderForm = document.getElementById('folderForm');
                this.folderNameInput = document.getElementById('folderNameInput');
                this.cancelFolderBtn = document.getElementById('cancelFolderBtn');
                this.saveFolderBtn = document.getElementById('saveFolderBtn');
                this.searchInput = document.getElementById('searchInput');
                this.typeFilter = document.getElementById('typeFilter');
                this.mediaGrid = document.getElementById('mediaGrid');
                this.loadingIndicator = document.getElementById('loadingIndicator');
                this.emptyState = document.getElementById('emptyState');
                this.deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
                
                this.selectedFiles = document.getElementById('selectedFiles');
                this.selectedList = document.getElementById('selectedList');
                this.selectedCount = document.getElementById('selectedCount');
                this.insertMediaBtn = document.getElementById('insertMediaBtn');
            }
			
			handleModalClick(e) {
				const target = e.target;
				
				// Handle select files button
				if (target.id === 'selectFilesBtn' || target.closest('#selectFilesBtn')) {
					console.log('Select files clicked via delegation');
					if (this.fileInput) {
						this.fileInput.click();
					}
					return;
				}

				// Handle create folder button
				if (target.id === 'createFolderBtn' || target.closest('#createFolderBtn')) {
					this.showFolderForm();
					return;
				}

				// Handle cancel folder button
				if (target.id === 'cancelFolderBtn' || target.closest('#cancelFolderBtn')) {
					this.hideFolderForm();
					return;
				}

				// Handle save folder button
				if (target.id === 'saveFolderBtn' || target.closest('#saveFolderBtn')) {
					this.createFolder();
					return;
				}

				// Handle delete selected button
				if (target.id === 'deleteSelectedBtn' || target.closest('#deleteSelectedBtn')) {
					this.deleteSelectedMedia();
					return;
				}

				// Handle tab buttons
				if (target.classList.contains('tab-btn')) {
					this.switchTab(target.dataset.tab);
					return;
				}

				// Handle breadcrumb items
				if (target.classList.contains('breadcrumb-item') || target.closest('.breadcrumb-item')) {
					const breadcrumbItem = target.classList.contains('breadcrumb-item') ? target : target.closest('.breadcrumb-item');
					const path = breadcrumbItem.dataset.path || '';
					this.navigateToFolder(path);
					return;
				}

				// Handle folder items
				if (target.classList.contains('folder-item') || target.closest('.folder-item')) {
					const folderItem = target.classList.contains('folder-item') ? target : target.closest('.folder-item');
					const path = folderItem.dataset.path || '';
					this.navigateToFolder(path);
					return;
				}

				// Handle media items (excluding delete buttons)
				if ((target.classList.contains('media-item') || target.closest('.media-item')) && 
					!target.closest('.media-overlay')) {
					const mediaItem = target.classList.contains('media-item') ? target : target.closest('.media-item');
					const path = mediaItem.dataset.path || '';
					// You'll need to get the media object from your data
					this.toggleMediaSelection(path, this.getMediaByPath(path));
					return;
				}

				// Handle delete buttons in media overlay
				if (target.classList.contains('btn-danger') && target.closest('.media-overlay')) {
					const mediaItem = target.closest('.media-item');
					const path = mediaItem?.dataset.path || '';
					if (path) {
						this.deleteMedia(path, false);
					}
					return;
				}
			}

			handleModalChange(e) {
				const target = e.target;
				
				// Handle file input
				if (target.id === 'fileInput') {
					console.log('File input changed via delegation', target.files.length);
					this.handleFileSelect(e);
					return;
				}

				// Handle type filter
				if (target.id === 'typeFilter') {
					this.filterType = target.value;
					this.currentPage = 1;
					this.loadMediaLibrary();
					return;
				}
			}

			handleModalInput(e) {
				const target = e.target;
				
				// Handle search input
				if (target.id === 'searchInput') {
					this.searchTerm = target.value;
					this.currentPage = 1;
					// Use debounce if needed
					clearTimeout(this.searchTimeout);
					this.searchTimeout = setTimeout(() => {
						this.loadMediaLibrary();
					}, 500);
					return;
				}

				// Handle folder name input
				if (target.id === 'folderNameInput') {
					// You can add real-time validation here if needed
					return;
				}
			}

			// Helper method to get media by path (you'll need to implement this based on your data structure)
			getMediaByPath(path) {
				// This depends on how you store your media items
				// You might need to maintain a reference to your current items
				return this.currentItems?.find(item => item.path === path);
			}

			// Add this method to handle media insertion
			insertMedia() {
				if (this.selectedMedia.size === 0) {
					this.showNotification('Please select at least one file', 'error');
					return;
				}

				console.log('Inserting media:', this.selectedMedia);
				
				// Get the file URLs from selected media
				const selectedFiles = Array.from(this.selectedMedia).map(path => {
					// You might need to get the full URL from your data
					const mediaItem = this.getMediaByPath(path);
					return mediaItem ? mediaItem.url : path;
				});

				// Pass selection to modal manager
				if (window.mediaManagerModal) {
					window.mediaManagerModal.handleMediaSelection(selectedFiles);
					
					// Auto-insert if single selection is required
					if (!window.mediaManagerModal.allowMultiple && selectedFiles.length === 1) {
						window.mediaManagerModal.insertSelectedMedia();
					} else {
						// For multiple files, the user needs to click insert in the modal
						// So we don't auto-insert here
						console.log('Multiple files selected, waiting for user to click insert');
					}
				}
				
				// Don't clear selection here - let the modal manager handle it
			}

            bindEvents() {
				if (this.eventsBound) {
					console.warn('Events already bound');
					return;
				}
				
				if (this.insertMediaBtn) {
					console.log('Direct binding insert button');
					this.insertMediaBtn.addEventListener('click', () => {
						console.log('Insert button clicked directly');
						this.insertMedia();
					});
				}

				console.log('Binding MediaManager events...');
				
				// Tab switching
				this.tabButtons.forEach(btn => {
					btn.addEventListener('click', (e) => {
						this.switchTab(e.target.dataset.tab);
					});
				});

				// File selection - use once or check if already bound
				if (this.selectFilesBtn && !this.selectFilesBtn.hasClickListener) {
					this.selectFilesBtn.addEventListener('click', () => {
						console.log('Select files clicked');
						this.fileInput.click();
					});
					this.selectFilesBtn.hasClickListener = true; // Mark as having listener
				}

				if (this.fileInput && !this.fileInput.hasChangeListener) {
					this.fileInput.addEventListener('change', (e) => {
							console.log('File input changed', e.target.files.length);
							this.handleFileSelect(e);
						});
						this.fileInput.hasChangeListener = true;
				}


				// Drag and drop
				this.dropZone.addEventListener('dragover', (e) => this.handleDragOver(e));
				this.dropZone.addEventListener('dragleave', (e) => this.handleDragLeave(e));
				this.dropZone.addEventListener('drop', (e) => this.handleDrop(e));

				// Folder management
				this.createFolderBtn.addEventListener('click', () => this.showFolderForm());
				this.cancelFolderBtn.addEventListener('click', () => this.hideFolderForm());
				this.saveFolderBtn.addEventListener('click', () => this.createFolder());

				// Library controls
				this.searchInput.addEventListener('input', this.debounce(() => {
					this.searchTerm = this.searchInput.value;
					this.currentPage = 1;
					this.loadMediaLibrary();
				}, 500));

				this.typeFilter.addEventListener('change', () => {
					this.filterType = this.typeFilter.value;
					this.currentPage = 1;
					this.loadMediaLibrary();
				});

				this.deleteSelectedBtn.addEventListener('click', () => this.deleteSelectedMedia());
				
				// Prevent file input from triggering multiple times
				this.fileInput.addEventListener('click', (e) => {
					e.stopPropagation();
				});
				
				this.eventsBound = true;
				console.log('MediaManager events bound successfully');
			}

            async loadMediaLibrary() {
                this.showLoading(true);
                this.mediaGrid.innerHTML = '';
                try {
                    const params = new URLSearchParams({
                        folder_path: this.currentFolderPath,
                        search: this.searchInput.value,
                        type: this.typeFilter.value
                    });

                    const response = await fetch(`/admin/get-files?${params}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.displayMediaItems(data.data);
                        this.updateBreadcrumb(data.breadcrumb);
                        this.updateUploadPath();
                        
                        if (data.data.length > 0) {
                            this.showEmptyState(false);
                        } else {
                            this.showEmptyState(true);
                        }
                    }
                } catch (error) {
                    console.error('Failed to load media:', error);
                    this.showNotification('Failed to load media library', 'error');
                    this.showEmptyState(true);
                } finally {
                    this.showLoading(false);
                }
            }

            displayMediaItems(items) {
                this.mediaGrid.innerHTML = '';
                items.forEach(item => {
                    if (item.type === 'folder') {
                        const folderElement = this.createFolderElement(item);
                        this.mediaGrid.appendChild(folderElement);
                    } else {
                        const mediaElement = this.createMediaElement(item);
                        this.mediaGrid.appendChild(mediaElement);
                    }
                });
            }

            createFolderElement(folder) {
                const div = document.createElement('div');
                div.className = 'folder-item';
                div.dataset.id = folder.id;
                div.dataset.path = folder.path;
                
                div.innerHTML = `
                    <div class="folder-thumbnail">
                        <div class="folder-icon">
                            <i class="fas fa-folder"></i>
                        </div>
                    </div>
                    <div class="folder-info">
                        <div class="folder-name">${this.truncateFilename(folder.name)}</div>
                        <div class="folder-meta">
                            <span>${folder.item_count} items</span>
                            <span>${this.formatDate(folder.modified)}</span>
                        </div>
                    </div>
                `;

                div.addEventListener('click', () => {
                    this.navigateToFolder(folder.path);
                });

                return div;
            }

            createMediaElement(media) {
                const div = document.createElement('div');
                div.className = 'media-item';
                div.dataset.id = media.id;
                div.dataset.path = media.path;
                
                const isImage = media.type === 'image';
                const isSelected = this.selectedMedia.has(media.path);

                if (isSelected) {
                    div.classList.add('selected');
                }

                div.innerHTML = `
                    <div class="media-thumbnail">
                        ${isImage ? 
                            `<img src="${media.url}" alt="${media.name}" loading="lazy">` :
                            `<div class="media-icon">
                                <i class="${this.getFileIcon(media.type)}"></i>
                            </div>`
                        }
                        <div class="media-overlay">
                            <button class="btn btn-danger" onclick="mediaManager.deleteMedia('${media.path}', false)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="media-info">
                        <div class="media-name">${this.truncateFilename(media.name)}</div>
                        <div class="media-meta">
                            <span>${media.formatted_size}</span>
                            <span>${media.formatted_date}</span>
                        </div>
                    </div>
                `;

                div.addEventListener('click', (e) => {
                    if (!e.target.closest('.media-overlay')) {
                        this.toggleMediaSelection(media.path, media);
                    }
                });

                return div;
            }

           toggleMediaSelection(mediaPath, media) {
				console.log('Toggling selection for:', mediaPath);
				
				const mediaElement = document.querySelector(`.media-item[data-path="${mediaPath}"]`);
				
				if (this.selectedMedia.has(mediaPath)) {
					// Deselect
					this.selectedMedia.delete(mediaPath);
					if (mediaElement) {
						mediaElement.classList.remove('selected');
					}
					console.log('Deselected:', mediaPath);
				} else {
					// Select
					this.selectedMedia.add(mediaPath);
					if (mediaElement) {
						mediaElement.classList.add('selected');
					}
					console.log('Selected:', mediaPath);
				}

				this.updateSelectedFiles();
				
				// Also update the insert button in the modal
				if (window.mediaManagerModal) {
					const selectedFiles = Array.from(this.selectedMedia).map(path => {
						const item = this.getMediaByPath(path);
						return item ? item.url : path;
					});
					window.mediaManagerModal.handleMediaSelection(selectedFiles);
				}
			}

			updateSelectedFiles() {
				const count = this.selectedMedia.size;
				console.log('Selected files count:', count);

				if (this.selectedCount) {
					this.selectedCount.textContent = count;
				}

				if (count > 0) {
					if (this.selectedFiles) {
						this.selectedFiles.classList.add('show');
					}
					if (this.deleteSelectedBtn) {
						this.deleteSelectedBtn.style.display = 'block';
					}
				} else {
					if (this.selectedFiles) {
						this.selectedFiles.classList.remove('show');
					}
					if (this.deleteSelectedBtn) {
						this.deleteSelectedBtn.style.display = 'none';
					}
				}

				// Update selected list
				if (this.selectedList) {
					this.selectedList.innerHTML = '';
					this.selectedMedia.forEach(path => {
						const fileName = path.split('/').pop();
						const item = document.createElement('div');
						item.className = 'selected-item';
						item.innerHTML = `
							<span>${fileName}</span>
							<button class="btn-remove" onclick="mediaManager.removeSelected('${path}')">×</button>
						`;
						this.selectedList.appendChild(item);
					});
				}
			}

			removeSelected(mediaPath) {
				console.log('Removing selected:', mediaPath);
				this.selectedMedia.delete(mediaPath);
				
				const mediaElement = document.querySelector(`.media-item[data-path="${mediaPath}"]`);
				if (mediaElement) {
					mediaElement.classList.remove('selected');
				}
				
				this.updateSelectedFiles();
				
				// Update modal manager
				if (window.mediaManagerModal) {
					const selectedFiles = Array.from(this.selectedMedia).map(path => {
						const item = this.getMediaByPath(path);
						return item ? item.url : path;
					});
					window.mediaManagerModal.handleMediaSelection(selectedFiles);
				}
			}

            navigateToFolder(folderPath) {
                this.currentFolderPath = folderPath;
                this.selectedMedia.clear();
                this.updateSelectedFiles();
                this.loadMediaLibrary();
            }

           updateBreadcrumb(breadcrumbData) {
				console.log('Breadcrumb data received:', breadcrumbData);
				console.log('Type of breadcrumbData:', typeof breadcrumbData);
				console.log('Is array?', Array.isArray(breadcrumbData));
				
				this.breadcrumb.innerHTML = '';
				
				// Force conversion to array
				const breadcrumbs = Array.isArray(breadcrumbData) ? breadcrumbData : [];
				
				breadcrumbs.forEach((item, index) => {
					const breadcrumbItem = document.createElement('div');
					breadcrumbItem.className = 'breadcrumb-item';
					breadcrumbItem.dataset.path = item.id || '';
					
					const itemName = item.name || 'Unknown';
					if (index === 0) {
						breadcrumbItem.innerHTML = `<i class="fas fa-home"></i> ${itemName}`;
					} else {
						breadcrumbItem.innerHTML = `<i class="fas fa-folder"></i> ${itemName}`;
					}
					
					breadcrumbItem.addEventListener('click', () => {
						this.navigateToFolder(item.id || '');
					});
					
					this.breadcrumb.appendChild(breadcrumbItem);
					
					if (index < breadcrumbs.length - 1) {
						const separator = document.createElement('div');
						separator.className = 'breadcrumb-separator';
						separator.innerHTML = '<i class="fas fa-chevron-right"></i>';
						this.breadcrumb.appendChild(separator);
					}
				});
			}

            updateUploadPath() {
			
			
                const displayPath = this.currentFolderPath ? `/media/${this.currentFolderPath}` : '/media';
                this.currentUploadPath.textContent = displayPath;
				
				//alert(displayPath);
            }

            async createFolder() {
                const folderName = this.folderNameInput.value.trim();
                if (!folderName) {
                    this.showNotification('Please enter a folder name', 'error');
                    return;
                }

                try {
                    const response = await fetch('/admin/folders', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            name: folderName,
                            folder_path: this.currentFolderPath
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.showNotification(`Folder "${folderName}" created successfully`);
                        this.hideFolderForm();
                        this.loadMediaLibrary();
                    } else {
                        this.showNotification(data.message || 'Folder creation failed', 'error');
                    }
                } catch (error) {
                    console.error('Folder creation failed:', error);
                    this.showNotification('Folder creation failed', 'error');
                }
            }
			
			async uploadFiles(files) {
				if (files.length === 0) return;

				try {
					this.showUploadProgress(true);
					
					const formData = new FormData();
					formData.append('folder_path', this.currentFolderPath);
					
					for (let i = 0; i < files.length; i++) {
						formData.append('files[]', files[i]);
					}

					const response = await fetch('/admin/upload', { // Make sure path is correct
						method: 'POST',
						headers: {
							'X-CSRF-TOKEN': this.csrfToken
						},
						body: formData
					});

					const data = await response.json();

					if (data.success) {
						this.showNotification('Files uploaded successfully!');
						this.fileInput.value = ''; // Clear the input
						this.loadMediaLibrary(); // Reload the library
						
						// Don't switch tabs automatically, let user stay where they are
						// this.switchTab('library');
						
					} else {
						const errorMessage = data.errors ? data.errors.join(', ') : data.message;
						throw new Error(errorMessage || 'Upload failed');
					}
				} catch (error) {
					console.error('Upload failed:', error);
					this.showNotification('Upload failed: ' + error.message, 'error');
				} finally {
					this.showUploadProgress(false);
				}
			}

	

            async deleteMedia(path, isFolder = false) {
                if (!confirm(`Are you sure you want to delete this ${isFolder ? 'folder' : 'file'}?`)) return;

                try {
                    const endpoint = isFolder ? '/admin/folders' : '/admin/files';
                    
                    const response = await fetch(endpoint, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            [isFolder ? 'folder_path' : 'file_path']: path
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.showNotification(`${isFolder ? 'Folder' : 'File'} deleted successfully`);
                        this.loadMediaLibrary();
                    } else {
                        this.showNotification(data.message || 'Delete failed', 'error');
                    }
                } catch (error) {
                    console.error('Delete failed:', error);
                    this.showNotification('Delete failed', 'error');
                }
            }

            async deleteSelectedMedia() {
                if (this.selectedMedia.size === 0) return;
                
                if (!confirm(`Are you sure you want to delete ${this.selectedMedia.size} files?`)) return;

                try {
                    for (const path of this.selectedMedia) {
                        const response = await fetch('/admin/files', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                file_path: path
                            })
                        });

                        const data = await response.json();
                        if (!data.success) {
                            throw new Error(data.message || 'Delete failed');
                        }
                    }

                    this.showNotification('Selected files deleted successfully');
                    this.selectedMedia.clear();
                    this.updateSelectedFiles();
                    this.loadMediaLibrary();
                } catch (error) {
                    console.error('Bulk delete failed:', error);
                    this.showNotification('Some files could not be deleted', 'error');
                }
            }

            // Utility methods (same as before)
            switchTab(tabName) {
                this.tabButtons.forEach(btn => {
                    btn.classList.toggle('active', btn.dataset.tab === tabName);
                });

                this.tabContents.forEach(content => {
                    content.classList.toggle('active', content.id === `${tabName}-tab`);
                });
            }

            handleDragOver(e) {
                e.preventDefault();
                this.dropZone.classList.add('drag-over');
            }

            handleDragLeave(e) {
                e.preventDefault();
                if (!this.dropZone.contains(e.relatedTarget)) {
                    this.dropZone.classList.remove('drag-over');
                }
            }

			handleFileSelect(e) {
				console.log('Handling file select', e.target.files.length);
				const files = e.target.files;
				if (files.length > 0) {
					this.uploadFiles(files);
				}
				// Reset the input to allow uploading same file again
				e.target.value = '';
			}

			handleDrop(e) {
				e.preventDefault();
				e.stopPropagation();
				this.dropZone.classList.remove('drag-over');
				const files = e.dataTransfer.files;
				console.log('Files dropped:', files.length);
				if (files.length > 0) {
					this.uploadFiles(files);
				}
			}


            showUploadProgress(show) {
                this.uploadProgress.style.display = show ? 'block' : 'none';
                if (!show) {
                    this.updateProgress(0);
                }
            }

            updateProgress(percent) {
                this.progressFill.style.width = percent + '%';
                this.progressText.textContent = percent + '%';
            }

            showFolderForm() {
                this.folderForm.style.display = 'block';
                this.folderNameInput.focus();
            }

            hideFolderForm() {
                this.folderForm.style.display = 'none';
                this.folderNameInput.value = '';
            }

            showLoading(show) {
                this.loadingIndicator.style.display = show ? 'block' : 'none';
            }

            showEmptyState(show) {
                this.emptyState.style.display = show ? 'block' : 'none';
                this.mediaGrid.style.display = show ? 'none' : 'grid';
            }

            getFileIcon(type) {
                const iconMap = {
                    'image': 'fas fa-file-image',
                    'pdf': 'fas fa-file-pdf',
                    'video': 'fas fa-file-video',
                    'audio': 'fas fa-file-audio'
                };
                return iconMap[type] || 'fas fa-file';
            }

            formatDate(timestamp) {
                return new Date(timestamp * 1000).toLocaleDateString();
            }

            truncateFilename(filename, maxLength = 20) {
                if (filename.length <= maxLength) return filename;
                return filename.substring(0, maxLength) + '...';
            }

            showNotification(message, type = 'success') {
                const notification = document.getElementById('notification');
                notification.textContent = message;
                notification.className = `notification ${type}`;
                notification.classList.add('show');

                setTimeout(() => {
                    notification.classList.remove('show');
                }, 3000);
            }

            debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }
        }

        
        class MediaManagerModal {
            constructor() {
                this.modal = document.getElementById('mediaManagerModal');
                this.currentTarget = null;
                this.allowMultiple = false;
                this.selectedMedia = new Set();
                this.mediaManager = null;
                
                this.initializeModal();
                this.bindModalEvents();
            }

            initializeModal() {
                this.modal.addEventListener('click', (e) => {
                    if (e.target === this.modal) {
                        this.closeModal();
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.modal.style.display === 'block') {
                        this.closeModal();
                    }
                });
            }
			handleMediaSelection(selectedFiles) {
				console.log('Modal received selected files:', selectedFiles);
				this.selectedMedia = new Set(selectedFiles);
				this.updateInsertButton();
			}

			updateInsertButton() {
				const insertBtn = document.getElementById('insertMediaBtn');
				if (!insertBtn) {
					console.error('Insert media button not found');
					return;
				}

				const count = this.selectedMedia.size;
				console.log('Updating insert button, selected count:', count);
				
				if (count > 0) {
					insertBtn.innerHTML = `<i class="fas fa-plus"></i> Insert ${count} File${count > 1 ? 's' : ''}`;
					insertBtn.disabled = false;
					insertBtn.style.opacity = '1';
				} else {
					insertBtn.innerHTML = `<i class="fas fa-plus"></i> Insert Media`;
					insertBtn.disabled = true;
					insertBtn.style.opacity = '0.6';
				}
			}

			insertSelectedMedia() {
				console.log('Inserting selected media:', this.selectedMedia);
				
				if (this.selectedMedia.size === 0 || !this.currentTarget) {
					this.showNotification('No files selected or no target specified', 'error');
					return;
				}

				const targetInput = document.getElementById(this.currentTarget);
				const previewContainer = document.getElementById(this.currentTarget + 'Preview');

				if (!targetInput) {
					this.showNotification('Target input not found', 'error');
					return;
				}

				const selectedFiles = Array.from(this.selectedMedia);

				if (this.allowMultiple) {
					// For multiple files - store as JSON array
					targetInput.value = JSON.stringify(selectedFiles);
					this.updateMultiplePreview(selectedFiles, previewContainer);
				} else {
					// For single file - store as string
					const fileUrl = selectedFiles[0];
					targetInput.value = fileUrl;
					this.updateSinglePreview(fileUrl, previewContainer);
				}

				this.showNotification(`Media inserted into ${this.currentTarget}`, 'success');
				this.closeModal();
			}

            bindModalEvents() {
				// Remove any existing event listeners first to prevent duplicates
				//this.removeModalEvents();
				
				// Close button
				document.querySelector('.close-modal').addEventListener('click', () => {
					this.closeModal();
				});

				// Cancel selection button
				document.getElementById('cancelSelectionBtn').addEventListener('click', () => {
					this.closeModal();
				});

				// Insert media button
				document.getElementById('insertMediaBtn').addEventListener('click', () => {
					this.insertSelectedMedia();
				});

				// Media trigger buttons - make sure these are only in the main form, not in modal
				document.querySelectorAll('.media-trigger-btn').forEach(btn => {
					// Check if button is inside modal or main form
					if (!btn.closest('#mediaManagerModal')) {
						btn.addEventListener('click', (e) => {
							const target = e.target.getAttribute('data-target');
							const multiple = e.target.getAttribute('data-multiple') === 'true';
							this.openModal(target, multiple);
						});
					}
				});

				// Form submission - only bind to main form
				const exampleForm = document.getElementById('exampleForm');
				if (exampleForm && !exampleForm.closest('#mediaManagerModal')) {
					exampleForm.addEventListener('submit', (e) => {
						e.preventDefault();
						this.handleFormSubmit(e);
					});
				}
			}

           openModal(target, multiple = false) {
				console.log('Opening modal for target:', target, 'multiple:', multiple);
				
				// Prevent multiple modals
				if (this.modal.style.display === 'block') {
					console.log('Modal already open, skipping');
					return;
				}
				
				this.currentTarget = target;
				this.allowMultiple = multiple;
				this.modal.style.display = 'block';
				document.body.style.overflow = 'hidden';

				if (!this.mediaManager) {
					console.log('Creating new MediaManager');
					this.mediaManager = new MediaManager();
				} else {
					console.log('Reloading media library');
					this.mediaManager.loadMediaLibrary();
				}

				this.selectedMedia.clear();
				this.updateInsertButton();
			}

            closeModal() {
                this.modal.style.display = 'none';
                document.body.style.overflow = 'auto';
                this.currentTarget = null;
                
                if (this.mediaManager) {
                    this.mediaManager.selectedMedia.clear();
                    this.mediaManager.updateSelectedFiles();
                }
            }

            handleMediaSelection(selectedFiles) {
                this.selectedMedia = new Set(selectedFiles);
                this.updateInsertButton();
            }

            updateInsertButton() {
                const insertBtn = document.getElementById('insertMediaBtn');
                const count = this.selectedMedia.size;
                
                if (count > 0) {
                    insertBtn.innerHTML = `<i class="fas fa-plus"></i> Insert ${count} File${count > 1 ? 's' : ''}`;
                    insertBtn.disabled = false;
                } else {
                    insertBtn.innerHTML = `<i class="fas fa-plus"></i> Insert Media`;
                    insertBtn.disabled = true;
                }
            }

            insertSelectedMedia() {
                if (this.selectedMedia.size === 0 || !this.currentTarget) return;

                const targetInput = document.getElementById(this.currentTarget);
                const previewContainer = document.getElementById(this.currentTarget + 'Preview');

                if (this.allowMultiple) {
                    const filesArray = Array.from(this.selectedMedia);
                    targetInput.value = JSON.stringify(filesArray);
                    this.updateMultiplePreview(filesArray, previewContainer);
                } else {
                    const fileUrl = Array.from(this.selectedMedia)[0];
                    targetInput.value = fileUrl;
                    this.updateSinglePreview(fileUrl, previewContainer);
                }

                this.closeModal();
                this.showNotification(`Media inserted into ${this.currentTarget}`, 'success');
            }

            updateSinglePreview(fileUrl, previewContainer) {
                if (fileUrl.match(/\.(jpeg|jpg|gif|png|webp)$/i)) {
                    previewContainer.innerHTML = `
                        <img src="/admin/media/file/${fileUrl}" alt="Preview" class="preview-image">
                        <div class="file-info">${fileUrl.split('/').pop()}</div>
                    `;
                } else {
                    previewContainer.innerHTML = `
                        <div class="file-info">
                            <i class="fas fa-file"></i> ${fileUrl.split('/').pop()}
                        </div>
                    `;
                }
            }

            updateMultiplePreview(fileUrls, previewContainer) {
                if (fileUrls.length === 0) {
                    previewContainer.innerHTML = '<div class="file-info">No files selected</div>';
                    return;
                }

                let previewHTML = '';
                
                fileUrls.forEach(url => {
                    if (url.match(/\.(jpeg|jpg|gif|png|webp)$/i)) {
                        previewHTML += `
                            <img src="/admin/media/file/${url}" alt="Preview" class="preview-image">
                        `;
                    } else {
                        previewHTML += `
                            <div class="file-info">
                                <i class="fas fa-file"></i> ${url.split('/').pop()}
                            </div>
                        `;
                    }
                });
                
                previewContainer.innerHTML = previewHTML;
            }

            showNotification(message, type = 'success') {
                const notification = document.getElementById('notification');
                notification.textContent = message;
                notification.className = `notification ${type}`;
                notification.classList.add('show');

                setTimeout(() => {
                    notification.classList.remove('show');
                }, 3000);
            }

            handleFormSubmit(e) {
                e.preventDefault();
                const formData = new FormData(e.target);
                const data = Object.fromEntries(formData);
                
                if (data.gallery_images) {
                    try {
                        data.gallery_images = JSON.parse(data.gallery_images);
                    } catch (e) {
                        data.gallery_images = [];
                    }
                }
                
                console.log('Form submitted with data:', data);
                this.showNotification('Form submitted successfully!', 'success');
                alert('Form submitted! Check console for form data.');
            }
        }

        // Override to work with file paths
        MediaManager.prototype.insertMedia = function() {
            if (this.selectedMedia.size === 0) return;
            
            const selectedFiles = Array.from(this.selectedMedia);

            if (window.mediaManagerModal) {
                window.mediaManagerModal.handleMediaSelection(selectedFiles);
                
                if (!window.mediaManagerModal.allowMultiple && selectedFiles.length === 1) {
                    window.mediaManagerModal.insertSelectedMedia();
                }
            }
            
            this.selectedMedia.clear();
            this.updateSelectedFiles();
            
            document.querySelectorAll('.media-item.selected').forEach(item => {
                item.classList.remove('selected');
            });
        };

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            window.mediaManager = new MediaManager();
            window.mediaManagerModal = new MediaManagerModal();
        });
    </script>

</body>

</html>

