<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;

class MenuController extends Controller
{
    /**
     * Display the menu for a specific table.
     *
     * @param  int  $table_id
     * @return \Inertia\Response
     */
    public function index($table_id)
    {
        $table = Table::findOrFail($table_id);
        $categories = Category::with('products')->get();
        
        return Inertia::render('Customer/Menu', [
            'table' => $table,
            'categories' => $categories,
        ]);
    }

    /**
     * Display the details of a specific product.
     *
     * @param  int  $table_id
     * @param  int  $product_id
     * @return \Inertia\Response
     */
    public function showProduct($table_id, $product_id)
    {
        $table = Table::findOrFail($table_id);
        $product = Product::with('allergens')->findOrFail($product_id);
        
        return Inertia::render('Customer/ProductDetail', [
            'table' => $table,
            'product' => $product,
        ]);
    }

    /**
     * Display products by category.
     *
     * @param  int  $table_id
     * @param  int  $category_id
     * @return \Inertia\Response
     */
    public function productsByCategory($table_id, $category_id)
    {
        $table = Table::findOrFail($table_id);
        $category = Category::findOrFail($category_id);
        $products = Product::where('category_id', $category_id)->get();
        
        return Inertia::render('Customer/CategoryProducts', [
            'table' => $table,
            'category' => $category,
            'products' => $products,
        ]);
    }
}
