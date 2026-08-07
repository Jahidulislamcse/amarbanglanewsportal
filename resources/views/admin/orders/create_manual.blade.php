@extends('layouts.admin')

@section('styles')
<style>
    .search-results-container {
        position: relative;
    }
    .search-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1000;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        max-height: 250px;
        overflow-y: auto;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        display: none;
    }
    .suggestion-item {
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
        transition: background 0.2s;
    }
    .suggestion-item:last-child {
        border-bottom: none;
    }
    .suggestion-item:hover {
        background: #f8f9fa;
    }
    .selected-user-card {
        background: #e8f4fd;
        border: 1px solid #b3d7ff;
        border-radius: 6px;
        padding: 15px;
        margin-top: 10px;
        display: none;
    }
    .product-row {
        background: #fdfdfd;
        border: 1px solid #eaeaea;
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 15px;
        position: relative;
    }
    .product-row .remove-row-btn {
        position: absolute;
        top: 10px;
        right: 10px;
    }
    .totals-summary {
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 20px;
    }
    .totals-summary table td {
        padding: 6px 0;
    }
</style>
@endsection

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <h4 class="heading">{{ __('Create Manual Order') }}</h4>
    </div>

    @include('includes.admin.form-success')
    @include('includes.admin.flash-message')

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form id="manualOrderForm" action="{{ route('admin.orders.storeManual') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <!-- User Selection Block -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white font-weight-bold">
                        1. Associate Customer
                    </div>
                    <div class="card-body">
                        <div class="form-group search-results-container">
                            <label for="userSearch">Search User (Name, Email, or Phone)</label>
                            <input type="text" id="userSearch" class="form-control" placeholder="Type to search..." autocomplete="off">
                            <div id="userSuggestions" class="search-suggestions"></div>
                        </div>

                        <!-- Hidden User Input -->
                        <input type="hidden" name="user_id" id="userId" value="{{ old('user_id') }}" required>

                        <!-- Selected User Details Card -->
                        <div id="selectedUserCard" class="selected-user-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1 font-weight-bold" id="selectedUserName">-</h5>
                                    <p class="mb-1 text-muted" id="selectedUserEmail">-</p>
                                    <p class="mb-0 text-muted" id="selectedUserPhone">-</p>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm" id="clearUserBtn">Change User</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Block -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center font-weight-bold">
                        <span>2. Add Products</span>
                        <button type="button" class="btn btn-light btn-sm font-weight-bold" id="addProductRowBtn">+ Add Product</button>
                    </div>
                    <div class="card-body" id="productRowsContainer">
                        <!-- Product Rows will be dynamically appended here -->
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Shipping & Delivery Block -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white font-weight-bold">
                        3. Shipping & Delivery
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="phoneNumber">Shipping Phone Number</label>
                            <input type="text" name="phone_number" id="phoneNumber" class="form-control" value="{{ old('phone_number') }}" placeholder="Customer phone number">
                        </div>

                        <div class="form-group">
                            <label for="address">Shipping Address</label>
                            <textarea name="address" id="address" class="form-control" rows="3" placeholder="Enter delivery address" required>{{ old('address') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="deliveryZone">Delivery Zone</label>
                            <select name="delivery_zone" id="deliveryZone" class="form-control" required>
                                <option value="outside" {{ old('delivery_zone') == 'outside' ? 'selected' : '' }}>Outside Dhaka (৳ 120)</option>
                                <option value="inside" {{ old('delivery_zone') == 'inside' ? 'selected' : '' }}>Inside Dhaka (৳ 80)</option>
                                <option value="office" {{ old('delivery_zone') == 'office' ? 'selected' : '' }}>Collect from Office (৳ 0)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Order Settings -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white font-weight-bold">
                        4. Order Info
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="status">Order Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="shipped" {{ old('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ old('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Payment Status</label>
                            <input type="text" class="form-control text-success font-weight-bold" value="PAID (Automatically)" readonly style="background-color: #e8f9ec;">
                            <small class="text-muted">Orders created manually are automatically marked as paid in full.</small>
                        </div>
                    </div>
                </div>

                <!-- Totals & Submit Block -->
                <div class="totals-summary card mb-4">
                    <h5 class="font-weight-bold mb-3 border-bottom pb-2">Order Summary</h5>
                    <table class="w-100 mb-3">
                        <tr>
                            <td>Items Subtotal</td>
                            <td class="text-right font-weight-bold">৳ <span id="summarySubtotal">0.00</span></td>
                        </tr>
                        <tr>
                            <td>Delivery Cost</td>
                            <td class="text-right font-weight-bold">৳ <span id="summaryDelivery">120.00</span></td>
                        </tr>
                        <tr class="border-top">
                            <td class="pt-2 font-weight-bold" style="font-size: 1.1rem;">Grand Total</td>
                            <td class="pt-2 text-right font-weight-bold text-primary" style="font-size: 1.1rem;">৳ <span id="summaryTotal">120.00</span></td>
                        </tr>
                    </table>

                    <button type="submit" class="btn btn-success btn-block btn-lg font-weight-bold" id="submitBtn">
                        Create Order
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let usersData = [];
    let productIndex = 0;
    
    // Active products list passed from controller
    const activeProducts = @json($products);

    // Search User Logic
    $('#userSearch').on('input', function() {
        const query = $(this).val().trim();
        if (query.length < 2) {
            $('#userSuggestions').hide();
            return;
        }

        $.getJSON("{{ route('admin.orders.searchUsers') }}", { q: query }, function(data) {
            usersData = data;
            let html = '';
            if (data.length === 0) {
                html = '<div class="suggestion-item text-muted">No users found</div>';
            } else {
                data.forEach(function(user) {
                    html += `<div class="suggestion-item" data-id="${user.id}">
                        <strong>${user.name}</strong><br>
                        <small class="text-muted">${user.email} | ${user.phone || 'No phone'}</small>
                    </div>`;
                });
            }
            $('#userSuggestions').html(html).show();
        });
    });

    // Close suggestion list on click outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-results-container').length) {
            $('#userSuggestions').hide();
        }
    });

    // Select User Suggestion
    $(document).on('click', '.suggestion-item', function() {
        const id = $(this).data('id');
        if (!id) return;

        const user = usersData.find(u => u.id == id);
        if (user) {
            $('#userId').val(user.id);
            $('#selectedUserName').text(user.name);
            $('#selectedUserEmail').text(user.email);
            $('#selectedUserPhone').text(user.phone || 'No phone');
            
            // Auto fill fields
            if (user.phone) {
                $('#phoneNumber').val(user.phone);
            }
            if (user.address) {
                $('#address').val(user.address);
            }

            $('#selectedUserCard').slideDown();
            $('#userSearch').val('');
            $('#userSuggestions').hide();
            $('.search-results-container').hide();
        }
    });

    // Clear Selected User
    $('#clearUserBtn').on('click', function() {
        $('#userId').val('');
        $('#selectedUserCard').slideUp(function() {
            $('.search-results-container').slideDown();
        });
    });

    // Handle initial old user if exists
    const oldUserId = "{{ old('user_id') }}";
    if (oldUserId) {
        $.getJSON("{{ route('admin.orders.searchUsers') }}", { q: oldUserId }, function(data) {
            if (data.length > 0) {
                const user = data.find(u => u.id == oldUserId);
                if (user) {
                    usersData = [user];
                    $('.suggestion-item[data-id="' + oldUserId + '"]').trigger('click');
                }
            }
        });
    }

    // Dynamic Product Row HTML Template
    function createProductRow(index) {
        let productOptions = '<option value="">-- Select Product --</option>';
        activeProducts.forEach(function(product) {
            productOptions += `<option value="${product.id}" data-price="${product.price}" data-stock="${product.stock}">${product.name} (Stock: ${product.stock})</option>`;
        });

        return `
        <div class="product-row" id="productRow_${index}">
            <button type="button" class="btn btn-danger btn-sm remove-row-btn" data-index="${index}">&times;</button>
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group mb-2 mb-md-0">
                        <label>Product</label>
                        <select name="items[${index}][product_id]" class="form-control product-select" data-index="${index}" required>
                            ${productOptions}
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-2 mb-md-0">
                        <label>Size</label>
                        <input type="text" name="items[${index}][size]" class="form-control" placeholder="e.g. M, L">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-2 mb-md-0">
                        <label>Unit Price</label>
                        <input type="number" step="0.01" min="0" name="items[${index}][price]" class="form-control price-input" data-index="${index}" required>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group mb-2 mb-md-0">
                        <label>Qty</label>
                        <input type="number" min="1" name="items[${index}][qty]" class="form-control qty-input" data-index="${index}" value="1" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-2 mb-md-0">
                        <label>Subtotal</label>
                        <div class="form-control-plaintext font-weight-bold">৳ <span class="row-subtotal" id="subtotal_${index}">0.00</span></div>
                    </div>
                </div>
            </div>
        </div>`;
    }

    // Add row button trigger
    $('#addProductRowBtn').on('click', function() {
        $('#productRowsContainer').append(createProductRow(productIndex));
        productIndex++;
    });

    // Remove row button trigger
    $(document).on('click', '.remove-row-btn', function() {
        const index = $(this).data('index');
        $(`#productRow_${index}`).remove();
        calculateTotals();
    });

    // Product Select Dropdown Changed
    $(document).on('change', '.product-select', function() {
        const index = $(this).data('index');
        const selectedOption = $(this).find('option:selected');
        const price = parseFloat(selectedOption.data('price')) || 0;
        
        $(`#productRow_${index} .price-input`).val(price.toFixed(2));
        calculateRowSubtotal(index);
    });

    // Price or Qty Changed
    $(document).on('input change', '.price-input, .qty-input', function() {
        const index = $(this).data('index');
        calculateRowSubtotal(index);
    });

    // Delivery Zone Changed
    $('#deliveryZone').on('change', function() {
        calculateTotals();
    });

    // Helper functions for calculation
    function calculateRowSubtotal(index) {
        const price = parseFloat($(`#productRow_${index} .price-input`).val()) || 0;
        const qty = parseInt($(`#productRow_${index} .qty-input`).val()) || 1;
        const subtotal = price * qty;
        
        $(`#subtotal_${index}`).text(subtotal.toFixed(2));
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;
        $('.row-subtotal').each(function() {
            subtotal += parseFloat($(this).text()) || 0;
        });

        const zone = $('#deliveryZone').val();
        let delivery = 120;
        if (zone === 'inside') {
            delivery = 80;
        } else if (zone === 'office') {
            delivery = 0;
        }

        const grandTotal = subtotal + delivery;

        $('#summarySubtotal').text(subtotal.toFixed(2));
        $('#summaryDelivery').text(delivery.toFixed(2));
        $('#summaryTotal').text(grandTotal.toFixed(2));
    }

    // Add first row by default
    $('#addProductRowBtn').trigger('click');

    // Prevent submission if no user selected
    $('#manualOrderForm').on('submit', function(e) {
        if (!$('#userId').val()) {
            e.preventDefault();
            alert('Please select a customer first.');
            return false;
        }
        
        if ($('.product-row').length === 0) {
            e.preventDefault();
            alert('Please add at least one product.');
            return false;
        }
        
        let valid = true;
        $('.product-select').each(function() {
            if (!$(this).val()) {
                valid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (!valid) {
            e.preventDefault();
            alert('Please select a product for all added rows.');
            return false;
        }

        $('#submitBtn').prop('disabled', true).text('Creating Order...');
    });
});
</script>
@endsection
