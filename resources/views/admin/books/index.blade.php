@extends('layouts.admin')

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <h4 class="heading">Books</h4>
            <button class="add-btn mt-3 mb-3" data-bs-toggle="modal" data-bs-target="#addBookModal">
                Add New Book
            </button>
        </div>

        @include('includes.admin.form-success')
        @include('includes.admin.flash-message')
        
        <div class="mb-3">
            <div class="d-inline-block me-2">
                <button class="btn btn-warning px-3" type="button" data-bs-toggle="collapse" data-bs-target="#pendingPurchases" aria-expanded="false" aria-controls="pendingPurchases">
                    Pending ({{ $pendingPurchases->count() }})
                </button>
            </div>
        
            <div class="d-inline-block">
                <button class="btn btn-success px-3" type="button" data-bs-toggle="collapse" data-bs-target="#approvedPurchases" aria-expanded="false" aria-controls="approvedPurchases">
                    Approved ({{ $approvedPurchases->count() }})
                </button>
            </div>
        </div>

        <div class="accordion" id="purchasesAccordion">
        
            {{-- Pending Purchases Table --}}
            <div class="accordion-item">
                <div id="pendingPurchases" class="accordion-collapse collapse" data-bs-parent="#purchasesAccordion">
                    <div class="accordion-body p-0">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Book</th>
                                    <th>Phone</th>
                                    <th>Operator</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingPurchases as $purchase)
                                    <tr>
                                        <td>{{ $purchase->user->name }}</td>
                                        <td>{{ $purchase->book->title }}</td>
                                        <td>{{ $purchase->phone_number }}</td>
                                        <td>{{ $purchase->operator }}</td>
                                        <td><span class="badge bg-warning">Pending</span></td>
                                        <td>
                                            <form action="{{ route('admin.books.purchase.approve', $purchase->id) }}" method="POST" style="display:inline-block">
                                                @csrf
                                                <button class="btn btn-success btn-sm px-2">Approve</button>
                                            </form>
        
                                            <form action="{{ route('admin.books.purchase.reject', $purchase->id) }}" method="POST" style="display:inline-block">
                                                @csrf
                                                <button class="btn btn-danger btn-sm px-2">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        
            {{-- Approved Purchases Table --}}
            <div class="accordion-item">
                <div id="approvedPurchases" class="accordion-collapse collapse" data-bs-parent="#purchasesAccordion">
                    <div class="accordion-body p-0">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Book</th>
                                    <th>Phone</th>
                                    <th>Operator</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($approvedPurchases as $purchase)
                                    <tr>
                                        <td>{{ $purchase->user->name }}</td>
                                        <td>{{ $purchase->book->title }}</td>
                                        <td>{{ $purchase->phone_number }}</td>
                                        <td>{{ $purchase->operator }}</td>
                                        <td><span class="badge bg-success">Approved</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        
        </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Cover</th> {{-- New --}}
                <th>Title</th>
                <th>PDF</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($books as $book)
                <tr>
                    {{-- Cover --}}
                    <td>
                        @if($book->cover)
                            <img src="{{ asset('assets/images/books/'.$book->cover) }}" 
                                 alt="Cover" style="height:60px; object-fit:cover;">
                        @else
                            <span>No Cover</span>
                        @endif
                    </td>
    
                    <td>{{ $book->title }}</td>
    
                    <td>
                        @if ($book->pdf_file)
                            <button class="btn btn-info btn-sm" type="button"
                                onclick="openPDF('{{ asset('assets/pdfs/books/' . $book->pdf_file) }}')">
                                View PDF
                            </button>
                        @endif
                    </td>
    
                    <td>{{ $book->price }}</td>
    
                    <td>
                        <span class="badge {{ $book->status ? 'bg-success' : 'bg-danger' }}">
                            {{ $book->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
    
                    <td>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#editBookModal{{ $book->id }}">
                            Edit
                        </button>
    
                        <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST"
                            style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
    
                        {{-- EDIT MODAL --}}
                        <div class="modal fade" id="editBookModal{{ $book->id }}">
                            <div class="modal-dialog modal-lg">
                                <form action="{{ route('admin.books.update', $book->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
    
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5>Edit Book</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
    
                                        <div class="modal-body">
                                            <label>Title</label>
                                            <input type="text" name="title" value="{{ $book->title }}"
                                                class="form-control mb-2" required>
                                                
                                             <label>Cover Image</label>
                                            <input type="file" name="cover" class="form-control mb-2" onchange="previewImage(event,'editCoverPreview{{ $book->id }}')">
                                            @if($book->cover)
                                                <img id="editCoverPreview{{ $book->id }}" src="{{ asset('assets/images/books/'.$book->cover) }}" 
                                                     alt="Cover Preview" class="img-fluid mb-2" style="max-height:150px;">
                                            @else
                                                <img id="editCoverPreview{{ $book->id }}" src="" style="display:none; max-height:150px;">
                                            @endif
    
                                            <label>File</label>
                                            <input type="file" name="pdf_file" class="form-control mb-2"
                                                onchange="previewPDF(event,'editFrame{{ $book->id }}')">
    
                                            <iframe id="editFrame{{ $book->id }}"
                                                src="{{ asset('assets/pdfs/books/' . $book->pdf_file) }}" width="100%"
                                                height="300"></iframe>
    
                                            <label>Price</label>
                                            <input type="number" name="price" value="{{ $book->price }}"
                                                class="form-control mb-2" required>
    
                                            <select name="status" class="form-control">
                                                <option value="1" {{ $book->status ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ !$book->status ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
    
                                        <div class="modal-footer">
                                            <button class="btn btn-success" type="submit">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
    </div>

    {{-- ADD MODAL --}}
    <div class="modal fade" id="addBookModal">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Add Book</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control mb-2" required>
                        
                        <label>Cover Image</label>
                        <input type="file" name="cover" class="form-control mb-2" onchange="previewImage(event,'addCoverPreview')">
                        <img id="addCoverPreview" src="" alt="Cover Preview" class="img-fluid mb-2" style="display:none; max-height:150px;">

                        <label>Input file</label>
                        <input type="file" name="pdf_file" class="form-control mb-2"
                            onchange="previewPDF(event,'addFrame')" required>

                        <iframe id="addFrame" width="100%" height="300" style="display:none;"></iframe>
                        
                        <label>Price</label>
                        <input type="number" name="price" class="form-control mb-2" required>

                        <select name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="pdfViewerModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body" style="height:80vh;">
                    <iframe id="pdfViewerFrame" width="100%" height="100%"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openPDF(url) {
            document.getElementById('pdfViewerFrame').src = url;
            new bootstrap.Modal(document.getElementById('pdfViewerModal')).show();
        }

        function previewPDF(event, frameId) {
            const file = event.target.files[0];
            if (file) {
                const url = URL.createObjectURL(file);
                const frame = document.getElementById(frameId);
                frame.src = url;
                frame.style.display = 'block';
            }
        }
        
        function previewImage(event, imgId) {
            const file = event.target.files[0];
            if(file) {
                const url = URL.createObjectURL(file);
                const img = document.getElementById(imgId);
                img.src = url;
                img.style.display = 'block';
            }
        }
    </script>
@endsection
