<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Field;
use Illuminate\Http\Request;

class SewaLapanganController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::where('status', 'active')->get();

        $query = Field::with('branch')->where('status', 'active');

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('field_type')) {
            $query->where('field_type', $request->field_type);
        }

        $fields = $query->latest()->get();

        return view('customer.sewa.index', compact('fields', 'branches'));
    }

    public function create(Field $field)
    {
        $field->load('branch');
        return view('customer.sewa.create', compact('field'));
    }
}