
@extends('layouts.app')

@section('content')
    <h1>Product Details</h1>
    <div class="card">
        <div class="card-body">
            <h5>{{ $product->name }}</h5>
            <p>{{ $product->description }}</p>
            <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
@endsection
 