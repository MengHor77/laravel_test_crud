<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,webp|max:2048'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')
                       ->with('success', 'Product created successfully');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'remove_image' => 'boolean'
        ]);

        // Handle image removal
        if ($request->input('remove_image') && $product->image) {
            Storage::delete('public/'.$product->image);
            $validated['image'] = null;
        }

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::delete('public/'.$product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.index')
                       ->with('success', 'Product updated successfully');
    }

      public function destroy(Product $product)
    {
        // Update is_active to false before deletion
        $product->update(['is_active' => false]);
        
        // Soft delete the product
        $product->delete();
        
        return redirect()->route('products.index')
                       ->with('success', 'Product deactivated and moved to trash');
    }
    public function export()
    {
        // Export logic here
        return response()->streamDownload(function() {
            // Your export content generation
        }, 'products-export.csv');
    }

    public function lowStock()
    {
        $products = Product::where('stock_quantity', '<', 10)->get();
        return view('products.low-stock', compact('products'));
    }

    public function trashed()
    {
        $products = Product::onlyTrashed()->get();
        return view('products.trashed', compact('products'));
    }

       public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        
        // Restore and set is_active to true
        $product->restore();
        $product->update(['is_active' => true]);
        
        return redirect()->route('products.index')
                       ->with('success', 'Product restored and activated');
    }

      public function forceDelete($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        
        // Ensure is_active is false before permanent deletion
        $product->update(['is_active' => false]);
        
        // Delete associated image
        if ($product->image) {
            Storage::delete('public/'.$product->image);
        }
        
        $product->forceDelete();
        
        return redirect()->route('products.trashed')
                       ->with('success', 'Product permanently deleted');
    }
}