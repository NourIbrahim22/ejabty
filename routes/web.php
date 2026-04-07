<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

use App\Services\HallAssignmentService;
use App\Models\Student;
use App\Http\Controllers\ScheduleController;


Route::get('/assign-exam-halls', function(Request $request, HallAssignmentService $service) {

    // 1️⃣ Get number of halls and capacity from query parameters (default values if not provided)
    $hallCount = (int) $request->query('halls', 3);    // default 3 halls
    $capacity  = (int) $request->query('capacity', 6); // default 6 students per hall

    // 2️⃣ Reset all examHall to null before a new assignment
    Student::query()->update(['examHall' => null]);

    // 3️⃣ Run the hall assignment algorithm
    [$halls, $unassigned] = $service->assignMixedHallsFromDB($hallCount, $capacity);

    // 4️⃣ Assign hall to students that fit
    foreach ($halls as $hallId => $students) {
        foreach ($students as $student) {
            $student->examHall = $hallId;
            $student->save();
        }
    }

    // 5️⃣ Ensure unassigned students have examHall = null
    foreach ($unassigned as $student) {
        $student->examHall = null;
        $student->save();
    }

    // 6️⃣ Prepare simplified JSON output with only required fields
    $simplifiedHalls = [];
    foreach ($halls as $hallId => $students) {
        $simplifiedHalls[$hallId] = array_map(function($student) {
            return [
                'id' => $student->id,
                'firstName' => $student->firstName,
                'fatherName' => $student->fatherName,
                'lastName' => $student->lastName,
                'examHall' => $student->examHall,
            ];
        }, $students);
    }

    $simplifiedUnassigned = array_map(function($student) {
        return [
            'id' => $student->id,
            'firstName' => $student->firstName,
            'fatherName' => $student->fatherName,
            'lastName' => $student->lastName,
            'examHall' => $student->examHall,
        ];
    }, $unassigned);

    // 7️⃣ Return JSON
    return response()->json([
        'assigned_halls' => $simplifiedHalls,
        'unassigned_students' => $simplifiedUnassigned
    ]);
});

Route::get('/halls', function () {
    return view('halls');
});

Route::get('/schedule', [ScheduleController::class, 'getSchedule']);

Route::post('/schedule/generate',[ScheduleController::class,'generateAutomatic'])->name('schedules.generate');