@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Edit Product: {{ $product->name }}</h2>
                <a href="{{ route('products.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Products
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <!-- Basic Information -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category *</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div class="col-md-6">
                        <!-- Pricing -->
                        <div class="mb-3">
                            <label for="price" class="form-label">Selling Price *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" 
                                       class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="cost_price" class="form-label">Cost Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" 
                                       class="form-control @error('cost_price') is-invalid @enderror" 
                                       id="cost_price" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}">
                                @error('cost_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Inventory -->
                        <div class="mb-3">
                            <label for="stock_quantity" class="form-label">Current Stock *</label>
                            <input type="number" min="0" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                   id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Image Upload -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Product Image</label>
                            @if($product->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$product->image) }}" alt="Current Product Image" class="img-thumbnail" style="max-height: 150px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                                        <label class="form-check-label text-danger" for="remove_image">
                                            Remove current image
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <small class="text-muted">Max 2MB (JPEG, PNG, WEBP)</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Status -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Product Active</label>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="reset" class="btn btn-outline-secondary me-md-2">
                        <i class="fas fa-undo"></i> Reset Changes
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Image preview functionality
    document.getElementById('image').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('image-preview-container');
        if (!previewContainer) {
            const newPreview = document.createElement('div');
            newPreview.id = 'image-preview-container';
            newPreview.className = 'mt-2';
            newPreview.innerHTML = '<small>New image preview:</small><br>' + 
                                 '<img id="image-preview" src="#" alt="Preview" class="img-thumbnail mt-2" style="max-height: 150px; display: none;">';
            this.parentNode.appendChild(newPreview);
        }
        
        const reader = new FileReader();
        reader.onload = function(){
            const preview = document.getElementById('image-preview');
            preview.src = reader.result;
            preview.style.display = 'block';
        };
        if (this.files[0]) {
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endpush
@endsection