<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Facades\Redis;
use App\Helpers\GroupHelper;
class GroupController extends Controller
{
    public function index()
    {
        $group = Group::where('status', '1')->get();
        
        // group_type ve group_level ile açıklama ekleniyor
        $group = $group->map(function($item) {
            $item->group_type_label=GroupHelper::getGroupTypeName($item->group_type); // getGroupTypeName fonksiyonu
            $item->group_level_label=GroupHelper::getGroupLevelName($item->group_level); // getGroupLevelName fonksiyonu
            return $item;
        });
        
        return response()->json($group, 201);
    }
    public function show($id)
    {
        $group = Group::where('status', '1')->findOrFail($id);
        
        $group->group_type_label = GroupHelper::getGroupTypeName($group->group_type); 
        $group->group_level_label = GroupHelper::getGroupLevelName($group->group_level); 

        return response()->json($group, 201);
    }

    public function store(Request $request)
    {
        $validated=$request->validate([
            'name' => 'required|string|max:255',
            'student_amount'=>'required|integer|min:1',
            'group_type'=>'required|integer',
            'faculty_id'=>'required|exists:faculties,id',
            'course_id'=>'required|exists:courses,id',
            'speciality_id'=>'required|exists:specialities,id',
            'group_level'=>'required|integer'
        ]);
        $group=Group::create($validated);
        return response()->json($group,201);
    }
    public function update(Request $request,$id)
    {
        $group=Group::findOrFail($id);
        $validated=$request->validate([
            'name'=>'required|string|max:255',
            'student_amount'=>'required|integer|min:1',
            'group_type'=>'required|integer',
            'faculty_id'=>'required|exists:faculties,id',
            'course_id'=>'required|exists:courses,id',
            'specialty_id'=>'required|exists:specialities,id',
            'group_level'=>'required|integer'
        ]);
        $group->update($validated);
        return response()->json($group);
    }
    public function destroy($id)
    {
        $group=Group::findOrFail($id);
        $group->update(['status'=>0]);
        $group->delete();
        return response()->json(['message'=>'silindi']);
    }
}
