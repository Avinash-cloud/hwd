<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['category', 'batch'])->latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::all();
        $batches = Batch::where('status', 'ready')->get();

        return view('admin.products.create', compact('categories', 'batches'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'batch_id' => 'nullable|exists:batches,id',
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'member_price' => 'nullable|numeric|min:0',
            'is_gangajal' => 'nullable|boolean',
            'volume_ml' => 'nullable|integer',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100',
            'image' => 'nullable|string|max:500',
            'purity_details' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']).'-'.Str::random(4);
        $validated['is_gangajal'] = $request->boolean('is_gangajal');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::all();
        $batches = Batch::all();

        return view('admin.products.edit', compact('product', 'categories', 'batches'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'batch_id' => 'nullable|exists:batches,id',
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'member_price' => 'nullable|numeric|min:0',
            'is_gangajal' => 'nullable|boolean',
            'volume_ml' => 'nullable|integer',
            'stock' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100',
            'image' => 'nullable|string|max:500',
            'purity_details' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['is_gangajal'] = $request->boolean('is_gangajal');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }
}
