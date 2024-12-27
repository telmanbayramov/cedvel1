<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index()
    {
        $roomtype = RoomType::where('status', 1)->get();
        return response()->json($roomtype);
    }

    public function show($id)
    {
        $roomtype = RoomType::where('status', 1)->findOrFail($id);
        return response()->json($roomtype);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $existingRoomType = RoomType::where('name', $validated['name'])->first();
        if ($existingRoomType) {
            if ($existingRoomType->status == 0) {
                $existingRoomType->update(['status' => 1]);
                return response()->json(['message' => 'Bu oda tipi yeniden aktif hale getirildi.', 'data' => $existingRoomType], 200);
            }
            return response()->json(['message' => 'Bu oda tipi zaten mevcut.', 'data' => $existingRoomType], 409);
        }

        $roomtype = RoomType::create(['name' => $validated['name'], 'status' => 1]);
        return response()->json(["message" => "Oda tipi eklendi", "data" => $roomtype], 201);
    }

    public function update(Request $request, $id)
    {
        $roomtype = RoomType::where('status', 1)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $existingRoomType = RoomType::where('name', $validated['name'])
            ->where('status', 1)
            ->where('id', '!=', $id)
            ->first();

        if ($existingRoomType) {
            return response()->json(["message" => 'Aynı isimde bir oda tipi zaten mevcut.'], 409);
        }

        $roomtype->update(['name' => $validated['name']]);
        return response()->json(["message" => "Oda tipi güncellendi", "data" => $roomtype]);
    }

    public function destroy($id)
    {
        $roomtype = RoomType::findOrFail($id);

        if ($roomtype->status == 1) {
            $roomtype->update(['status' => 0]);
            return response()->json(["message" => 'Oda tipi silindi (pasif hale getirildi).']);
        }

        return response()->json(["message" => 'Bu oda tipi zaten pasif durumda.'], 400);
    }
}
