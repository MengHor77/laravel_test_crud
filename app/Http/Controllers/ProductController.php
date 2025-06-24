<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;  // This was missing
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(): View
    {
        return view('products.index', [
            'products' => Product::latest()->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new product
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:999999.99'
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
                        ->with('success', 'Product created successfully');
    }

    /**
     * Display the specified product
     */
    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the product
     */
    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:999999.99'
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
                        ->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')
                        ->with('success', 'Product deleted successfully');
    }

   

}