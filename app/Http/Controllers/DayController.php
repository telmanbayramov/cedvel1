<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Day;
use Illuminate\Support\Facades\Redis;

class DayController extends Controller
{
    public function index()
    {
        $day = Day::where('status', '1')->get();
        return response()->json($day);
    }
    public function show($id)
    {
        $day = Day::where('status', '1')->findOrFail($id);
        return response()->json($day);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        $day = Day::create([
            'name' => $validated['name'],
        ]);
        return response()->json(["message" => 'elave edildi', 'data' => $day]);
    }
    public function update(Request $request, $id)
    {
        $day = Day::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        $day->update(['name' => $validated['name']]);
        return response()->json(["message" => 'deyisildi', 'data' => $day]);
    }
    public function destroy($id)
    {
        $day = Day::where('status', '1')->findOrFail($id);
        if ($day) {
            $day->update(['status' => '0']);
            return response()->json(["message" => 'silindi']);
        }
        return response()->json(["message" => 'movcud deil']);
    }
}
