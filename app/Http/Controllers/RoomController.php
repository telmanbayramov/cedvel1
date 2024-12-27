<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Validation\Rules\Exists;
use App\Models\Corps;
use App\Models\RoomType;
use App\Models\Department;

class RoomController extends Controller
{   
    public function index()
    {
        // Sadece aktif odaları getir, ilişkili department, room_type ve corps isimlerini de yükle
        $rooms = Room::where('status', '1')
                     ->with(['department', 'roomType', 'corps']) // İlişkili verileri yükle
                     ->get();
    
        // Odaların bilgilerini döndür
        return response()->json($rooms);
    }
    
    public function show($id)
    {
        // Sadece aktif oda ve ilişkili department, room_type ve corps isimlerini yükle
        $room = Room::where('status', '1')
                    ->with(['department', 'roomType', 'corps']) // İlişkili verileri yükle
                    ->findOrFail($id);
    
        // Odanın bilgilerini döndür
        return response()->json($room);
    }
    
    public function store(Request $request)
    {
        // Veriyi doğrulama
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'room_capacity' => 'required|integer',
            'department_name' => 'required|string', // Frontend'den name olarak gelecek
            'room_type_name' => 'required|string',  // Frontend'den name olarak gelecek
            'corps_name' => 'required|string',      // Frontend'den name olarak gelecek
        ]);
    
        // 'department_name' ile department ID'sini al
        $department = Department::where('name', $validated['department_name'])->first();
        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }
    
        // 'room_type_name' ile room type ID'sini al
        $roomType = RoomType::where('name', $validated['room_type_name'])->first();
        if (!$roomType) {
            return response()->json(['message' => 'Room Type not found'], 404);
        }
    
        // 'corps_name' ile corps ID'sini al
        $corps = Corps::where('name', $validated['corps_name'])->first();
        if (!$corps) {
            return response()->json(['message' => 'Corps not found'], 404);
        }
    
        // Aynı isme sahip odanın var olup olmadığını kontrol et
        $existingRoom = Room::where('name', $validated['name'])->first();
        if ($existingRoom) {
            if ($existingRoom->status == 0) {
                $existingRoom->update(['status' => '1']);
                return response()->json(['message' => 'aktif oldu'], 209);
            }
            return response()->json(['message' => 'mevcut']);
        }
    
        // Yeni oda oluştur
        $room = Room::create([
            'name' => $validated['name'],
            'room_capacity' => $validated['room_capacity'],
            'department_id' => $department->id,  // department ID'sini al
            'room_type_id' => $roomType->id,     // room type ID'sini al
            'corps_id' => $corps->id,            // corps ID'sini al
        ]);
    
        // Yeni oluşturulan odanın bilgilerini döndür
        return response()->json(['message' => 'elave edildi', 'data' => $room]);
    }
    
    public function update(Request $request, $id)
    {
        $room = Room::where('status', '1')->findOrFail($id);
        
        // Gelen istek için validasyon
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'room_capacity' => 'required|integer',
            'department_name' => 'required|string|exists:departments,name',
            'room_type_name' => 'required|string|exists:room_types,name',
            'corps_name' => 'required|string|exists:corps,name'
        ]);
    
        // İlişkili isimlerden id değerlerini al
        $departmentId = Department::where('name', $validated['department_name'])->first()->id;
        $roomTypeId = RoomType::where('name', $validated['room_type_name'])->first()->id;
        $corpsId = Corps::where('name', $validated['corps_name'])->first()->id;
    
        // Aynı isimde başka bir oda var mı kontrol et
        $existingRoom = Room::where('name', $validated['name'])
            ->where('status', '1')
            ->where('id', '!=', $id)
            ->first();
    
        if ($existingRoom) {
            return response()->json(['message' => 'Mevcut oda ismi bulunmaktadır'], 400);
        }
    
        // Odayı güncelle
        $room->update([
            'name' => $validated['name'],
            'room_capacity' => $validated['room_capacity'],
            'department_id' => $departmentId,
            'room_type_id' => $roomTypeId,
            'corps_id' => $corpsId
        ]);
    
        return response()->json(['message' => 'Oda başarıyla güncellendi', 'data' => $room]);
    }
    
    public function destroy($id)
    {
        $room=Room::findOrFail($id);
        if($room->status==1)
        {
            $room->update(['status'=>'0']);
            return response()->json(['message'=>'silindi']);
        }
        return response()->json(['message'=>'silinib']);
    }
}
