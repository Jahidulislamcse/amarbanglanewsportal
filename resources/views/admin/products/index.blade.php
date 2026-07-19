@extends('layouts.admin')

@section('content')
<div class="content-area ">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Products') }}</h4>
                <div class="btn-area mt-4 mb-4">
                    <a href="{{ route('admin.products.create') }}" class="add-btn">
                        <i class="fas fa-plus"></i>{{ __('Add New Product') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('includes.admin.form-success')
    @include('includes.admin.flash-message')

    <div class="product-area">
        <div class="row">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    <div class="table-responsiv">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Buying Price') }}</th>
                                    <th>{{ __('Selling Price') }}</th>
                                    <th>{{ __('Stock') }}</th>
                                    <th>{{ __('Package') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>
                                        @if($product->primaryImage)
                                            <img src="{{ asset('assets/images/products/' . $product->primaryImage->image_path) }}" width="70" height="70">
                                        @else
                                            <img src="{{ asset('assets/images/noimage.png') }}" width="70" height="70">
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>{{ $product->buying_price }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        {{-- Package badge/buttons --}}
                                        <div class="d-flex gap-1 flex-wrap" style="gap:4px;">
                                            <button type="button"
                                                class="btn btn-xs package-btn {{ $product->package === 'package1' ? 'btn-success' : 'btn-outline-secondary' }}"
                                                data-id="{{ $product->id }}"
                                                data-package="package1"
                                                style="font-size:11px; padding:2px 8px;">
                                                P1
                                            </button>
                                            <button type="button"
                                                class="btn btn-xs package-btn {{ $product->package === 'package2' ? 'btn-primary' : 'btn-outline-secondary' }}"
                                                data-id="{{ $product->id }}"
                                                data-package="package2"
                                                style="font-size:11px; padding:2px 8px;">
                                                P2
                                            </button>
                                            @if($product->package)
                                                <button type="button"
                                                    class="btn btn-xs btn-outline-danger package-btn"
                                                    data-id="{{ $product->id }}"
                                                    data-package=""
                                                    title="Clear package"
                                                    style="font-size:11px; padding:2px 6px;">
                                                    ×
                                                </button>
                                            @endif
                                        </div>
                                        @if($product->package)
                                            <small class="text-muted d-block mt-1" id="pkg-label-{{ $product->id }}">
                                                {{ $product->package === 'package1' ? 'Package 1' : 'Package 2' }}
                                            </small>
                                        @else
                                            <small class="text-muted d-block mt-1" id="pkg-label-{{ $product->id }}">—</small>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                                        <a href="{{ route('admin.products.delete', $product->id) }}" class="btn btn-sm btn-danger">{{ __('Delete') }}</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.package-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const id      = this.dataset.id;
        const pkg     = this.dataset.package;
        const row     = this.closest('tr');
        const allBtns = row.querySelectorAll('.package-btn[data-package]');
        const label   = document.getElementById('pkg-label-' + id);

        fetch('{{ url("admin/products") }}/' + id + '/set-package', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ package: pkg })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Reset all package buttons in this row
                allBtns.forEach(b => {
                    const bPkg = b.dataset.package;
                    b.className = b.className
                        .replace('btn-success', 'btn-outline-secondary')
                        .replace('btn-primary', 'btn-outline-secondary');

                    if (bPkg === 'package1' && data.package === 'package1') {
                        b.className = b.className.replace('btn-outline-secondary', 'btn-success');
                    } else if (bPkg === 'package2' && data.package === 'package2') {
                        b.className = b.className.replace('btn-outline-secondary', 'btn-primary');
                    }
                });

                // Update label
                if (label) {
                    label.textContent = data.package === 'package1' ? 'Package 1'
                                      : data.package === 'package2' ? 'Package 2'
                                      : '—';
                }

                // Show/hide the clear (×) button
                let clearBtn = row.querySelector('.package-btn[data-package=""]');
                if (data.package) {
                    if (!clearBtn) {
                        clearBtn = document.createElement('button');
                        clearBtn.type = 'button';
                        clearBtn.className = 'btn btn-xs btn-outline-danger package-btn';
                        clearBtn.dataset.id = id;
                        clearBtn.dataset.package = '';
                        clearBtn.title = 'Clear package';
                        clearBtn.style.cssText = 'font-size:11px; padding:2px 6px;';
                        clearBtn.textContent = '×';
                        clearBtn.addEventListener('click', arguments.callee);
                        row.querySelector('.d-flex').appendChild(clearBtn);
                    }
                } else {
                    if (clearBtn) clearBtn.remove();
                }
            }
        })
        .catch(() => alert('Error updating package.'));
    });
});
</script>
@endsection
