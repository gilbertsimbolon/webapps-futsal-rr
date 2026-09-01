<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Field;
use App\Services\JadwalDinamisService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    /**
     * Halaman Publik Utama (Landing Page)
     */
    public function index()
    {
        // Ambil cabang venue aktif
        $branches = Branch::where('status', 'active')
            ->withCount(['fields' => function ($q) {
                $q->where('status', 'available');
            }])
            ->get();

        // Ambil lapangan pilihan unggulan
        $fields = Field::with('branch')
            ->where('status', 'available')
            ->latest()
            ->take(6)
            ->get();

        // Tipe lapangan unik yang tersedia
        $fieldTypes = Field::where('status', 'available')
            ->whereNotNull('field_type')
            ->distinct()
            ->pluck('field_type')
            ->toArray();

        // Rentang harga
        $minPrice = Field::where('status', 'available')->min('price_per_hour') ?? 50000;
        $maxPrice = Field::where('status', 'available')->max('price_per_hour') ?? 300000;

        return view('frontend.landing', compact(
            'branches',
            'fields',
            'fieldTypes',
            'minPrice',
            'maxPrice'
        ));
    }

    /**
     * Halaman Publik Daftar & Pencarian Lapangan Lengkap
     */
    public function fields(Request $request)
    {
        $branches = Branch::where('status', 'active')->get();

        $fieldTypes = Field::where('status', 'available')
            ->whereNotNull('field_type')
            ->distinct()
            ->pluck('field_type')
            ->toArray();

        $query = Field::with('branch')->where('status', 'available');

        // Filter Pencarian Teks
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('field_name', 'like', "%{$search}%")
                    ->orWhereHas('branch', function ($bq) use ($search) {
                        $bq->where('branch_name', 'like', "%{$search}%")
                            ->orWhere('address', 'like', "%{$search}%");
                    });
            });
        }

        // Filter Cabang
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter Tipe Lapangan
        if ($request->filled('field_type')) {
            $query->where('field_type', $request->field_type);
        }

        // Filter Harga Maksimal
        if ($request->filled('max_price') && is_numeric($request->max_price)) {
            $query->where('price_per_hour', '<=', $request->max_price);
        }

        // Urutan
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price_per_hour', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price_per_hour', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('field_name', 'asc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $fields = $query->paginate(9)->withQueryString();

        return view('frontend.lapangan_index', compact(
            'fields',
            'branches',
            'fieldTypes'
        ));
    }

    /**
     * Halaman Publik Detail Lapangan & Ketersediaan Jadwal
     */
    public function fieldDetail(Field $field)
    {
        $field->load(['branch.user', 'schedules' => function ($q) {
            $q->where('status', 'active');
        }]);

        // Lapangan terkait di cabang yang sama
        $relatedFields = Field::with('branch')
            ->where('branch_id', $field->branch_id)
            ->where('id', '!=', $field->id)
            ->where('status', 'available')
            ->take(3)
            ->get();

        // Slot waktu awal untuk hari ini
        $today = Carbon::today()->toDateString();
        $initialSlots = JadwalDinamisService::generateSlotsForDate($field->id, $today);

        return view('frontend.lapangan_detail', compact(
            'field',
            'initialSlots',
            'today',
            'relatedFields'
        ));
    }

    /**
     * Endpoint AJAX Publik untuk mengambil ketersediaan slot waktu
     */
    public function getFieldSlots(Request $request, Field $field)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $slots = JadwalDinamisService::generateSlotsForDate($field->id, $request->date);

        return response()->json([
            'status' => 'success',
            'date'   => $request->date,
            'data'   => $slots,
        ]);
    }
}

