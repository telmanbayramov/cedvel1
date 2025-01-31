<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Corps;

class CorpsController extends Controller
{
    public function index()
    {
        $corps = Corps::where('status', '1')->get();
        return response()->json($corps);
    }
    public function show($id)
    {
        $corps = Corps::where('status', '1')->findOrFail($id);
        return response()->json($corps);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        $corps = Corps::create(['name' => $validated['name']]);
        return response()->json(['message' => 'elave edildi', $corps]);
    }
    public function update(Request $request, $id)
    {
        $corps = Corps::where('status', '1')->findOrFail($id);
        $validated = $request->validate([
            "name" => 'required|string|max:255'
        ]);
        $corps->update($validated);
        return response()->json(['message' => 'deyisdirildi', $corps]);
    }
    public function destroy($id)
    {
        $corps = Corps::where('status', '1')->findOrFail($id);
        if ($corps) {
            $corps->update(['status' => '0']);
            return response()->json(['message' => 'silindi']);
        }
        return response()->json(['message' => 'silinib']);
    }
}
