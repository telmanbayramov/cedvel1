<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hour;
use Illuminate\Support\Facades\Redis;

class HourController extends Controller
{
    public function index()
    {
        $hour = Hour::where('status', '1')->orderBy('id')->get();
        return response()->json($hour);
    }

    public function show($id)
    {
        $hour = Hour::where('status', '1')->findOrFail($id);
        return response()->json($hour);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        $existingHour = Hour::where('name', $validated['name'])->first();

        $hour = Hour::create([
            'name' => $validated['name']
        ]);
        return response()->json(['message' => 'elave edildi', 'data' => $hour]);
    }
    public function update(Request $request, $id)
    {
        $hour = Hour::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $hour->update([
            'name' => $validated['name'],
        ]);
        return response()->json(['message' => 'deyisdirildi', 'data' => $hour]);
    }
    public function destroy($id)
    {
        $hour = hour::findOrFail($id);
        if ($hour->status == 1) {
            $hour->update(['status' => '0']);
            return response()->json(['message' => 'silindi']);
        }
        return response()->json(['message' => 'silinib']);
    }
}
