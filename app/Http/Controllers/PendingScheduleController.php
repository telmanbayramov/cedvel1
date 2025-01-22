<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;

class PendingScheduleController extends Controller
{
  public function pendingSchedules()
  {
      $pendingSchedules = Schedule::where('confirm_status', 0)->get();

      return response()->json([
          'message' => 'Pending schedules retrieved successfully.',
          'data' => $pendingSchedules,
      ]);
  }
  public function approve($id)
  {
      if (!is_numeric($id)) {
          return response()->json([
              'message' => 'Invalid schedule ID provided.',
          ], 400);
      }
  
      $schedule = Schedule::findOrFail($id);

      $schedule->update(['confirm_status' => 1]);
      return response()->json([
          'message' => 'Schedule approved successfully.',
          'data' => $schedule,
      ]);
  }
  
}
