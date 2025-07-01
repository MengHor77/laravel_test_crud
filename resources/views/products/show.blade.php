@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h2 class="h5 mb-0">
                <i class="fas fa-box me-2"></i>Product Details: {{ $product->name }}
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('products.edit', $product->id) }}" 
                   class="btn btn-light btn-sm px-3 py-2 rounded-3 transition-all"
                   title="Edit Product"
                   data-bs-toggle="tooltip"
                   data-bs-placement="top">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline m-0">
                    @csrf @method('DELETE')
                    <button type="submit" 
                            class="btn btn-danger btn-sm px-3 py-2 rounded-3 transition-all"
                            title="Delete Product"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            onclick="return confirm('Are you sure you want to delete this product?')">
                        <i class="fas fa-trash-alt me-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        
        <div class="card-body p-4">
            @if($product->image)
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <img src="{{ asset('storage/'.$product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="img-fluid rounded shadow-sm" 
                         style="max-height: 300px;">
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Basic Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <th>Name:</th>
                                        <td>{{ $product->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Description:</th>
                                        <td>{!! $product->description ?? '<span class="text-muted">N/A</span>' !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Category:</th>
                                        <td>
                                            @if($product->category)
                                                <span class="badge bg-info">{{ $product->category->name }}</span>
                                            @else
                                                <span class="text-muted">Uncategorized</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-warehouse me-2"></i>Inventory & Pricing
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="30%">Price:</th>
                                        <td>{{ $product->formatted_price }}</td>
                                    </tr>
                                    <tr>
                                        <th>Cost Price:</th>
                                        <td>{{ $product->cost_price ? '$'.number_format($product->cost_price, 2) : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Stock Quantity:</th>
                                        <td>
                                            <span class="{{ $product->stock_quantity < 10 ? 'text-danger fw-bold' : 'text-success' }}">
                                                {{ $product->stock_quantity }}
                                            </span>
                                            <small class="text-muted ms-2">({{ $product->stock_status }})</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status:</th>
                                        <td>
                                            <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Profit Margin:</th>
                                        <td>
                                            @if($product->cost_price)
                                                @php
                                                    $margin = (($product->price - $product->cost_price) / $product->cost_price) * 100;
                                                @endphp
                                                <span class="{{ $margin >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($margin, 2) }}%
                                                </span>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Additional Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Created At:</strong> {{ $product->created_at->format('M d, Y \a\t H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Updated At:</strong> {{ $product->updated_at->format('M d, Y \a\t H:i') }}</p>
                        </div>
                        @if($product->trashed())
                        <div class="col-12">
                            <div class="alert alert-warning mt-3 mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                This product was deleted on {{ $product->deleted_at->format('M d, Y \a\t H:i') }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer bg-light py-3">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('products.index') }}" 
                   class="btn btn-outline-secondary px-4 py-2 rounded-3 transition-all">
                    <i class="fas fa-arrow-left me-2"></i> Back to Products
                </a>
                <div class="text-muted small">
                    Product ID: {{ $product->id }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .transition-all {
        transition: all 0.3s ease-in-out;
    }
    
    .btn {
        border: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 3px rgba(0,0,0,0.1);
    }
    
    .table-borderless th {
        font-weight: 600;
        color: #495057;
        width: 30%;
    }
    
    .table-borderless td {
        vertical-align: middle;
    }
    
    .card {
        border-radius: 0.5rem;
    }
    
    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
    
    form.d-inline {
        display: inline-flex !important;
        align-items: center;
        margin: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush