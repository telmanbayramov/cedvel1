<?php

namespace App\Http\Controllers;

use App\Models\WeekType;
use Illuminate\Http\Request;

class WeekTypeController extends Controller
{
    public function index()
    {
        $weektype=WeekType::where('status','1')->get();
        return response()->json($weektype);
    }
    public function show($id)
    {
        $weektype=WeekType::where('status','1')->findOrFail($id);
        return response()->json($weektype);
    }
    public function store(Request $request)
    {
        $validated=$request->validate([
            'name'=>'required|string|max:255'
        ]);
        $weektype=WeekType::create([
            'name'=>$validated['name']
        ]);
        return response()->json([
            'message'=>'elave edildi',
            'data'=>$weektype
        ]);
    }
    public function update(Request $request,$id)
    {
        $weektype=WeekType::findOrFail($id);
        $validated=$request->validate([
            'name'=>'required|string|max:255'
        ]);
        $weektype->update(['name'=>$validated['name']]);
        return response()->json(['message'=>'deyisdirildi','data'=>$weektype]);
    }
    public function destroy($id)
    {
        $weektype = WeekType::where('status', '1')->findOrFail($id);
        $weektype->update(['status' => '0']);
        return response()->json([
            'message' => 'semestr silindi',
        ]);
    }
}
