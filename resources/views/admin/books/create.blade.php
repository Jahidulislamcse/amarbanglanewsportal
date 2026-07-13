@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Add New Book</h1>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 rounded">
            <ul class="list-disc list-inside text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label for="title" class="block font-medium mb-1">Book Title</label>
            <input 
                type="text" 
                name="title" 
                id="title" 
                value="{{ old('title') }}" 
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
            >
        </div>

        <div>
            <label for="price" class="block font-medium mb-1">Price (USD)</label>
            <input 
                type="number" 
                name="price" 
                id="price" 
                value="{{ old('price') }}" 
                step="0.01" 
                min="0"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
            >
        </div>

        <div>
            <label for="pdf_file" class="block font-medium mb-1">PDF File</label>
            <input 
                type="file" 
                name="pdf_file" 
                id="pdf_file" 
                accept="application/pdf"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
            >
        </div>

        <div>
            <button 
                type="submit" 
                class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
            >
                Upload Book
            </button>
        </div>
    </form>
</div>
@endsection