<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tables = Table::withCount(['orders', 'scores'])
            ->latest()
            ->get();

        return Inertia::render('Admin/Tables/Index', [
            'tables' => $tables
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Tables/Create');
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
        ]);

        // Benzersiz bir QR kod oluştur
        $qrCode = $this->generateUniqueQrCode();
        $validated['qr_code'] = $qrCode;

        Table::create($validated);

        return redirect()->route('admin.tables.index')
            ->with('message', 'Masa başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Table $table)
    {
        $qrCodeUrl = url("/t/{$table->id}");
        $qrCodeImage = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($qrCodeUrl);

        return Inertia::render('Admin/Tables/Show', [
            'table' => $table,
            'qrCodeImage' => $qrCodeImage,
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Table $table)
    {
        return Inertia::render('Admin/Tables/Edit', [
            'table' => $table
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Table $table)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $table->update($validated);

        return redirect()->route('admin.tables.index')
            ->with('message', 'Masa başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Table $table)
    {
        // Siparişleri kontrol et
        if ($table->orders()->count() > 0) {
            return back()->with('error', 'Bu masaya ait siparişler mevcut. Masa silinemez.');
        }

        $table->delete();

        return redirect()->route('admin.tables.index')
            ->with('message', 'Masa başarıyla silindi.');
    }

    /**
     * Generate QR code for table.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generateQr(Table $table)
    {
        $qrCodeUrl = url("/t/{$table->id}");
        $qrCodeImage = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($qrCodeUrl);

        return response($qrCodeImage)->header('Content-Type', 'image/svg+xml');
    }

    /**
     * Generate a unique QR code
     *
     * @return string
     */
    private function generateUniqueQrCode()
    {
        $qrCode = Str::random(10);
        
        // Benzersiz olduğundan emin ol
        while (Table::where('qr_code', $qrCode)->exists()) {
            $qrCode = Str::random(10);
        }
        
        return $qrCode;
    }
}
