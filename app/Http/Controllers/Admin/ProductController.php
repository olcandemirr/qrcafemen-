<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Allergen;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with(['category', 'allergens'])
            ->latest()
            ->get();

        return Inertia::render('Admin/Products/Index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Products/Create', [
            'categories' => Category::all(),
            'allergens' => Allergen::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'allergen_ids' => 'nullable|array',
            'allergen_ids.*' => 'exists:allergens,id',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated);

        if (isset($validated['allergen_ids'])) {
            $product->allergens()->attach($validated['allergen_ids']);
        }

        return redirect()->route('admin.products.index')
            ->with('message', 'Ürün başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $product->load('allergens');
        
        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'categories' => Category::all(),
            'allergens' => Allergen::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'allergen_ids' => 'nullable|array',
            'allergen_ids.*' => 'exists:allergens,id',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        if (isset($validated['allergen_ids'])) {
            $product->allergens()->sync($validated['allergen_ids']);
        }

        return redirect()->route('admin.products.index')
            ->with('message', 'Ürün başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->orders()->exists()) {
            return back()->with('error', 'Bu ürüne ait siparişler var. Ürünü silemezsiniz.');
        }

        // Ürün resmini sil
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Alerjen ilişkilerini sil
        $product->allergens()->detach();
        
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('message', 'Ürün başarıyla silindi.');
    }
}
