<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Day;
use Illuminate\Support\Facades\Redis;

class DayController extends Controller
{
    public function index()
    {
        $day=Day::where('status','1')->get();
        return response()->json($day); 
    }
    public function show($id)
    {
        $day=Day::where('status','1')->findOrFail($id);
        return response()->json($day);
    }
    public function store(Request $request)
    {
        $validated=$request->validate([
            'name'=>'required|string|max:255'
        ]);
        $existingDay=Day::where('name',$validated['name'])->first();
        if($existingDay)
        {
            if($existingDay->status==0)
            {
                $existingDay->update(['status'=>'1']);
                return response()->json(["message"=>'movcud edildi']);
            }
            return response()->json(["message"=>'movud']);
        }
        $day=Day::create([
            'name'=>$validated['name'],
        ]);
        return response()->json(["message"=>'elave edildi','data'=>$day]);
    }
    public function update(Request $request,$id)
    {
        $day=Day::findOrFail($id);
        $validated=$request->validate([
            'name'=>'required|string|max:255'
        ]);
        $existingDay = Day::where('name', $validated['name'])
        ->where('status', 1)
        ->where('id', '!=', $id)
        ->first();

    if ($existingDay) {
        return response()->json(["message" => 'Aynı isimde bir gun zaten mevcut.'], 409);
    }

        $day->update(['name'=>$validated['name']]);
        return response()->json(["message"=>'deyisildi','data'=>$day]);
    }
    public function destroy($id)
    {
        $day=Day::findOrFail($id);
        if($day->status==1)
        {
            $day->update(['status'=>'0']);
            return response()->json(["message"=>'silindi']);
        }
        return response()->json(["message"=>'movcud deil']);    
    }
}
