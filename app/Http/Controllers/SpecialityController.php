<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialityController extends Controller
{
    public function index()
    {
        $specialties = Specialty::where('status', '1')
            ->with('faculty')
            ->get();

        return response()->json($specialties, 200);
    }

    public function show($id)
    {
        // İlgili specialty ve ilişkili grupları getirme
        $specialty = Specialty::where('status', '1')
            ->with('faculty')->with('groups') // ilişkili grupları da dahil et
            ->findOrFail($id);
    
        return response()->json($specialty);
    }
    
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id',
        ]);
        $speciality = Specialty::create([   
            'name' => $request->name,
            'faculty_id' => $request->faculty_id,
        ]);
        return response()->json($speciality, 201);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id',
        ]);

        $speciality = Specialty::findOrFail($id);

        if ($speciality->status === 0) {
            return response()->json(['error' => 'ixtisası yeniləmək mümkün deyil.'], 400);
        }

        $speciality->update([
            'name' => $request->name,
            'faculty_id' => $request->faculty_id,
        ]);

        return response()->json($speciality, 200);
    }
    public function delete($id)
    {
        $speciality = Specialty::where('status', '1')->findOrFail($id);

        if ($speciality) {
            $speciality->update(['status' => 0]);
        }

        return response()->json(['message' => 'İxtisas uğurla silindi.'], 200);
    }
}
