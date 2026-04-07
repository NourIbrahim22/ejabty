<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function generateAutomatic(Request $request) {
        $request->validate([
            'halls'=>'required|integer|min:1',
            'type'=>'required|in:exam,course',
        ]);

        $hallsCount = $request->halls;
        $type = $request->type;

        $courses = Course::all();

        $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday'];
        $times = ['8:00','9:00','10:00','11:00','12:00','13:00','14:00','15:00'];

        $scheduleEntries = [];
        $hallIndex = 0;
        $timeIndex = 0;
        $dayIndex = 0;

        foreach($courses as $course) {
            $scheduleEntries[] = [
                'course_id'=>$course->id,
                'hall_id'=>$hallIndex+1,
                'day'=>$days[$dayIndex],
                'time'=>$times[$timeIndex],
                'type'=>$type,
                'created_at'=>now(),
                'updated_at'=>now()
            ];

            $hallIndex++;
            if($hallIndex>=$hallsCount) {
                $hallIndex = 0;
                $timeIndex++;
                if($timeIndex>=count($times)) {
                    $timeIndex = 0;
                    $dayIndex++;
                    if($dayIndex>=count($days)) {
                        return back()->with('error','Not enough slots for all courses');
                    }
                }

            }
        }
        Schedule::insert($scheduleEntries);
        return back()->with('Schedule Generated');
    }

}
