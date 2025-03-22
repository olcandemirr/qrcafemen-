<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Score;
use App\Models\Product;
use App\Models\Order;
use App\Models\Table;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class ScoreController extends Controller
{
    /**
     * Display the product rating form.
     *
     * @param  int  $table_id
     * @param  int  $order_id
     * @param  int  $product_id
     * @return \Inertia\Response
     */
    public function create($table_id, $order_id, $product_id)
    {
        $table = Table::findOrFail($table_id);
        $order = Order::findOrFail($order_id);
        $product = Product::findOrFail($product_id);
        
        // Check if the product belongs to the order
        $orderHasProduct = $order->products()->where('product_id', $product_id)->exists();
        if (!$orderHasProduct) {
            return redirect()->route('customer.order.status', ['table_id' => $table_id, 'order_id' => $order_id])
                ->with('error', 'Bu ürün siparişinizde bulunmuyor.');
        }
        
        // Check if the product is already rated
        $existingRating = Score::where([
            'order_id' => $order_id,
            'product_id' => $product_id,
        ])->first();
        
        return Inertia::render('Customer/RateProduct', [
            'table' => $table,
            'order' => $order,
            'product' => $product,
            'existingRating' => $existingRating,
        ]);
    }

    /**
     * Store a new product rating.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $table_id
     * @param  int  $order_id
     * @param  int  $product_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $table_id, $order_id, $product_id)
    {
        $validator = Validator::make($request->all(), [
            'score' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $table = Table::findOrFail($table_id);
        $order = Order::findOrFail($order_id);
        $product = Product::findOrFail($product_id);
        
        // Check if the product belongs to the order
        $orderHasProduct = $order->products()->where('product_id', $product_id)->exists();
        if (!$orderHasProduct) {
            return redirect()->route('customer.order.status', ['table_id' => $table_id, 'order_id' => $order_id])
                ->with('error', 'Bu ürün siparişinizde bulunmuyor.');
        }
        
        // Check if the product is already rated
        $existingRating = Score::where([
            'order_id' => $order_id,
            'product_id' => $product_id,
        ])->first();
        
        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'score' => $request->score,
                'comment' => $request->comment,
            ]);
        } else {
            // Create new rating
            Score::create([
                'order_id' => $order_id,
                'product_id' => $product_id,
                'score' => $request->score,
                'comment' => $request->comment,
            ]);
        }
        
        return redirect()->route('customer.order.status', ['table_id' => $table_id, 'order_id' => $order_id])
            ->with('success', 'Puanlamanız için teşekkür ederiz!');
    }
}
