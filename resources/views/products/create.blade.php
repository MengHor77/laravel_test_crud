
@extends('layouts.app')

@section('content')
    <h1>{{ isset($product) ? 'Edit' : 'Create' }} Product</h1>
    
    <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}" method="POST">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif
        
        <div class="form-group mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" 
                   value="{{ old('name', $product->name ?? '') }}" required>
        </div>
        
        <div class="form-group mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
        </div>
        
        <div class="form-group mb-3">
            <label>Price</label>
            <input type="number" step="0.01" name="price" class="form-control" 
                   value="{{ old('price', $product->price ?? '') }}" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
 