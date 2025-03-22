<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Table;
use App\Models\Product;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display the current cart for the table.
     *
     * @param  int  $table_id
     * @return \Inertia\Response
     */
    public function cart($table_id)
    {
        $table = Table::findOrFail($table_id);
        
        return Inertia::render('Customer/Cart', [
            'table' => $table,
            'cartItems' => session()->get("cart_{$table_id}", []),
        ]);
    }

    /**
     * Add a product to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $table_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToCart(Request $request, $table_id)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $product = Product::findOrFail($request->product_id);
        $cartKey = "cart_{$table_id}";
        $cart = session()->get($cartKey, []);

        // Check if the product is already in the cart
        $existingItem = false;
        foreach ($cart as $key => $item) {
            if ($item['product_id'] == $request->product_id && 
                ($request->notes ?? '') == ($item['notes'] ?? '')) {
                $cart[$key]['quantity'] += $request->quantity;
                $existingItem = true;
                break;
            }
        }

        // If the product is not in the cart, add it
        if (!$existingItem) {
            $cart[] = [
                'product_id' => $request->product_id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity,
                'notes' => $request->notes,
            ];
        }

        session()->put($cartKey, $cart);

        return redirect()->route('customer.cart', ['table_id' => $table_id])
            ->with('success', 'Ürün sepete eklendi!');
    }

    /**
     * Update cart item quantity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $table_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateCartItem(Request $request, $table_id)
    {
        $validator = Validator::make($request->all(), [
            'index' => 'required|integer|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $cartKey = "cart_{$table_id}";
        $cart = session()->get($cartKey, []);

        if (isset($cart[$request->index])) {
            $cart[$request->index]['quantity'] = $request->quantity;
            session()->put($cartKey, $cart);
        }

        return redirect()->route('customer.cart', ['table_id' => $table_id])
            ->with('success', 'Sepet güncellendi!');
    }

    /**
     * Remove an item from the cart.
     *
     * @param  int  $table_id
     * @param  int  $index
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeCartItem($table_id, $index)
    {
        $cartKey = "cart_{$table_id}";
        $cart = session()->get($cartKey, []);

        if (isset($cart[$index])) {
            array_splice($cart, $index, 1);
            session()->put($cartKey, $cart);
        }

        return redirect()->route('customer.cart', ['table_id' => $table_id])
            ->with('success', 'Ürün sepetten kaldırıldı!');
    }

    /**
     * Place the order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $table_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function placeOrder(Request $request, $table_id)
    {
        $cartKey = "cart_{$table_id}";
        $cart = session()->get($cartKey, []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Sepetiniz boş!');
        }

        $table = Table::findOrFail($table_id);
        
        // Create the order
        DB::beginTransaction();
        
        try {
            $order = Order::create([
                'table_id' => $table_id,
                'status' => 'pending',
                'total_amount' => collect($cart)->sum(function ($item) {
                    return $item['price'] * $item['quantity'];
                }),
                'notes' => $request->notes,
            ]);
            
            // Add order items
            foreach ($cart as $item) {
                $order->products()->attach($item['product_id'], [
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }
            
            // Clear the cart
            session()->forget($cartKey);
            
            DB::commit();
            
            return redirect()->route('customer.order.status', ['table_id' => $table_id, 'order_id' => $order->id])
                ->with('success', 'Siparişiniz başarıyla oluşturuldu!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Sipariş oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Display the order status.
     *
     * @param  int  $table_id
     * @param  int  $order_id
     * @return \Inertia\Response
     */
    public function orderStatus($table_id, $order_id)
    {
        $table = Table::findOrFail($table_id);
        $order = Order::with('products')->findOrFail($order_id);
        
        return Inertia::render('Customer/OrderStatus', [
            'table' => $table,
            'order' => $order,
        ]);
    }

    /**
     * Display the table's order history.
     *
     * @param  int  $table_id
     * @return \Inertia\Response
     */
    public function orderHistory($table_id)
    {
        $table = Table::findOrFail($table_id);
        $orders = Order::where('table_id', $table_id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return Inertia::render('Customer/OrderHistory', [
            'table' => $table,
            'orders' => $orders,
        ]);
    }
}
