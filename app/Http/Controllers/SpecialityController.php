<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialityController extends Controller
{
    public function index()
    {
        $specialty = Specialty::where('status', '1')->get();
        return response()->json($specialty, 200);
    }
    public function show($id)
    {
        $specialty = Specialty::where('status', '1')->findOrFail($id);
        return response()->json($specialty, 200);
    }
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id',
        ]);

        $existingSpeciality = Specialty::where('name', $request->name)
            ->where('status', 0)
            ->first();
        if ($existingSpeciality) {
            $existingSpeciality->update(['status' => 1]);
            return response()->json($existingSpeciality, 200);
        }
        $speciality = Specialty::create([
            'name' => $request->name,
            'faculty_id' => $request->faculty_id,
            'status' => 1,
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
        $speciality = Specialty::findOrFail($id);

        if ($speciality->status === 1) {
            $speciality->update(['status' => 0]);
        }

        return response()->json(['message' => 'İxtisas uğurla silindi.'], 200);
    }
}
