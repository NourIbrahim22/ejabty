<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function generateAutomatic(Request $request)
{
    $request->validate([
        'halls' => 'required|integer|min:1',
        'type' => 'required|in:exam,course',
    ]);

    $hallsCount = $request->halls;
    $type = $request->type;

    $courses = Course::all();

    if ($courses->isEmpty()) {
        return response()->json(['error' => 'No courses found in the database'], 404);
    }

    $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday'];
    $times = ['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00'];

    $scheduleEntries = [];
    $hallIndex = 0;
    $timeIndex = 0;
    $dayIndex = 0;

    foreach ($courses as $course) {
        $scheduleEntries[] = [
            'course_id' => $course->id,
            'hall_id' => $hallIndex + 1,
            'day' => $days[$dayIndex],
            'time' => $times[$timeIndex],
            'type' => $type,
            'created_at' => now(),
            'updated_at' => now()
        ];

        $hallIndex++;
        if ($hallIndex >= $hallsCount) {
            $hallIndex = 0;
            $timeIndex++;
            if ($timeIndex >= count($times)) {
                $timeIndex = 0;
                $dayIndex++;
                if ($dayIndex >= count($days)) {
                    return response()->json(['error' => 'Not enough slots for all courses'], 400);
                }
            }
        }
    }

    // Insert into database
    Schedule::insert($scheduleEntries);

    // Return the generated schedule in JSON so Postman sees it
    return response()->json([
        'success' => true,
        'message' => 'Schedule generated successfully!',
        'schedule' => $scheduleEntries
    ]); 
}

public function getSchedule(Request $request)
{
    $type = $request->query('type'); // optional filter

    if ($type && !in_array($type, ['exam', 'course'])) {
    return response()->json([
        'error' => 'Invalid type. Use exam or course only.'
    ], 400);

    $query = Schedule::with('course');

    if ($type) {
        $query->where('type', $type);
    }

    $schedule = $query->orderBy('day')
                      ->orderBy('time')
                      ->orderBy('hall_id')
                      ->get();

    return response()->json([
        'success' => true,
        'selected_type'=>$type ?? 'all',
        'count' => $schedule->count(),
        'data' => $schedule
    ]);
    
}
}

}