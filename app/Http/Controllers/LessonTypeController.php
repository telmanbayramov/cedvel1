<?php

namespace App\Http\Controllers;

use App\Models\LessonType;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\LessThan;

class LessonTypeController extends Controller
{
    public function index()
    {
        $lesson=LessonType::where('status','1')->get();
        return response()->json($lesson);
    }
    public function show($id)
    {
        $lesson=LessonType::where('status','1')->findOrFail($id);
        return response()->json($lesson);
    }
    public function store(Request $request)
    {
        $validate=$request->validate([
            'name'=>'required|string|max:255'
        ]);
     
        $lesson=LessonType::create(['name'=>$validate['name']]);
        return response()->json(['data'=>$lesson],201);
    }
    public function update(Request $request,$id)
    {
        $lesson=LessonType::where('status','1')->findOrFail($id);
        $validate=$request->validate([
            'name'=>'required|string|max:255'
        ]);
    
        $lesson->update($validate);
        return response()->json(['message'=>'fennin tipinin adi deyisdirildi','data'=>$lesson]);
    }
    public function destroy($id)
    {
        $lesson=LessonType::where('status','1')->findOrFail($id);
        $lesson->update(['status'=>'0']);
        return response()->json(['message'=>'fenn tipi silindi']);
    }
}
