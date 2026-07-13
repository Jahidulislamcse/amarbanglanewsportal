<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookPurchase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::orderBy('created_at', 'asc')->get();
    
        $pendingPurchases = BookPurchase::where('status', 'pending')->with('book', 'user')->get();
        $approvedPurchases = BookPurchase::where('status', 'approved')->with('book', 'user')->get();
    
        return view('admin.books.index', compact('books', 'pendingPurchases', 'approvedPurchases'));
    }

    // Store a new book
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'pdf_file' => 'required|mimes:pdf',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // new
            'price' => 'required|numeric',
            'status' => 'required|boolean',
        ]);
    
        // PDF upload
        $pdfName = time() . '_' . $request->file('pdf_file')->getClientOriginalName();
        $request->file('pdf_file')->move(public_path('assets/pdfs/books/'), $pdfName);
    
        // Cover upload
        $coverName = null;
        if ($request->hasFile('cover')) {
            $coverName = time() . '_' . $request->file('cover')->getClientOriginalName();
            $request->file('cover')->move(public_path('assets/images/books/'), $coverName);
        }
    
        Book::create([
            'title' => $request->title,
            'pdf_file' => $pdfName,
            'cover' => $coverName,
            'price' => $request->price,
            'status' => $request->status,
        ]);
    
        return back()->with('success', 'Book added successfully');
    }

    // Update an existing book
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'pdf_file' => 'nullable|mimes:pdf',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // new
            'price' => 'required|numeric',
            'status' => 'required|boolean',
        ]);
    
        // Update PDF
        if ($request->hasFile('pdf_file')) {
            $oldPdf = public_path('assets/pdfs/books/' . $book->pdf_file);
            if (file_exists($oldPdf)) unlink($oldPdf);
    
            $pdfName = time() . '_' . $request->file('pdf_file')->getClientOriginalName();
            $request->file('pdf_file')->move(public_path('assets/pdfs/books/'), $pdfName);
            $book->pdf_file = $pdfName;
        }
    
        // Update Cover
        if ($request->hasFile('cover')) {
            $oldCover = public_path('assets/images/books/' . $book->cover);
            if ($book->cover && file_exists($oldCover)) unlink($oldCover);
    
            $coverName = time() . '_' . $request->file('cover')->getClientOriginalName();
            $request->file('cover')->move(public_path('assets/images/books/'), $coverName);
            $book->cover = $coverName;
        }
    
        $book->title = $request->title;
        $book->price = $request->price;
        $book->status = $request->status;
        $book->save();
    
        return back()->with('success', 'Book updated successfully');
    }
    public function viewPdf($file)
    {
        $path = public_path('assets/pdfs/books/' . $file);
    
        if (!file_exists($path)) {
            abort(404);
        }
    
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $file . '"'
        ]);
    }
    
    public function purchase(Request $request, $bookId)
    {
        $request->validate([
            'phone_number' => 'required|string|max:255',
            'operator' => 'required|string|in:Bkash,Nagad,Rocket',
        ]);
    
        $user = Auth::user();
        $book = Book::findOrFail($bookId);

        \App\Models\BookPurchase::create([
            'user_id' => $user->id,
            'book_id' => $bookId,
            'phone_number' => $request->phone_number,
            'operator' => $request->operator,
            'amount' => $book->price,
        ]);
    
        return back()->with('success', 'Purchase recorded successfully. Please contact support for verification.');
    }
    
    public function approvePurchase($purchaseId)
    {
        $purchase = \App\Models\BookPurchase::findOrFail($purchaseId);
        $purchase->status = 'approved';
        $purchase->save();
    
        return back()->with('success', 'Purchase approved successfully');
    }
    
    public function rejectPurchase($purchaseId)
    {
        $purchase = \App\Models\BookPurchase::findOrFail($purchaseId);
        $purchase->status = 'rejected';
        $purchase->save();
    
        return back()->with('success', 'Purchase rejected successfully');
    }

    // Delete a book
    public function destroy(Book $book)
    {
        $path = public_path('assets/pdfs/books/' . $book->pdf_file);
        if (file_exists($path)) {
            unlink($path);
        }
    
        $book->delete();
    
        return back()->with('success', 'Book deleted successfully');
    }
}