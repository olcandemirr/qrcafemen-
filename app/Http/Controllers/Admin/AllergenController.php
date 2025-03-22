<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allergen;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AllergenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allergens = Allergen::withCount('products')
            ->latest()
            ->get();

        return Inertia::render('Admin/Allergens/Index', [
            'allergens' => $allergens
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Allergens/Create');
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
            'name' => 'required|string|max:255|unique:allergens',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50'
        ]);

        Allergen::create($validated);

        return redirect()->route('admin.allergens.index')
            ->with('message', 'Alerjen başarıyla oluşturuldu.');
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
    public function edit(Allergen $allergen)
    {
        return Inertia::render('Admin/Allergens/Edit', [
            'allergen' => $allergen
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Allergen $allergen)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:allergens,name,' . $allergen->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50'
        ]);

        $allergen->update($validated);

        return redirect()->route('admin.allergens.index')
            ->with('message', 'Alerjen başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Allergen $allergen)
    {
        if ($allergen->products()->exists()) {
            return back()->with('error', 'Bu alerjene sahip ürünler var. Önce ürünlerden alerjeni kaldırmalısınız.');
        }

        $allergen->delete();

        return redirect()->route('admin.allergens.index')
            ->with('message', 'Alerjen başarıyla silindi.');
    }
}
