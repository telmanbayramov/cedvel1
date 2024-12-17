<?php

namespace App\Http\Controllers;

use App\Models\LessonType;
use Illuminate\Http\Request;

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
        $existingLesson=LessonType::where('name',$validate['name'])->first();
        if($existingLesson){
            $existingLesson->update(['status'=='1']);
            return response()->json(['message'=>'Mövcud fəaliyyətsiz ders yenidən işə salınıb','data'=>$existingLesson],201);
        }
        $lesson=LessonType::create(['name'=>$validate['name']]);
        return response()->json(['data'=>$lesson],201);
    }
}
