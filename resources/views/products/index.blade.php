@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Products</h2>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th class="text-end" style="width: 180px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->formatted_price }}</td>
                            <td class="{{ $product->stock_quantity < 10 ? 'text-danger fw-bold' : '' }}">
                                {{ $product->stock_quantity }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $product->is_active ? 'success' : 'secondary' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('products.show', $product->id) }}" 
                                       class="btn btn-sm btn-info text-nowrap">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    
                                    <a href="{{ route('products.edit', $product->id) }}" 
                                       class="btn btn-sm btn-primary text-nowrap">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger text-nowrap"
                                                onclick="return confirm('Delete this product?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .text-nowrap {
        white-space: nowrap;
    }
    
    .gap-2 {
        gap: 0.5rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    form.d-inline {
        display: inline-block;
        margin: 0;
    }
</style>
@endpush