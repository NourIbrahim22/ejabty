<?php

namespace App\Services;

use App\Models\Student;

class HallAssignmentService
{
    public function assignMixedHallsFromDB($hallCount, $capacity)
    {
        // 1️⃣ Fetch all students
        $students = Student::all();

        // 2️⃣ Split students by studentPackage
        $grade9 = [];
        $scientific = [];
        $arts = [];

        foreach ($students as $student) {
            switch ($student->studentPackage) {
                case 'grade9':
                    $grade9[] = $student;
                    break;

                case 'grade12_sci':
                    $scientific[] = $student;
                    break;

                case 'grade12_arts':
                    $arts[] = $student;
                    break;
            }
        }

        // 3️⃣ Shuffle for randomness
        shuffle($grade9);
        shuffle($scientific);
        shuffle($arts);

        // 4️⃣ Initialize halls
        $halls = [];
        for ($i = 1; $i <= $hallCount; $i++) {
            $halls[$i] = [];
        }

        $unassigned = [];

        // 5️⃣ Prepare groups for round-robin
        $groups = [&$grade9, &$scientific, &$arts];
        $hallIndex = 1;

        // 6️⃣ Assign students
        while (!empty($grade9) || !empty($scientific) || !empty($arts)) {

            foreach ($groups as &$group) {

                if (!empty($group)) {

                    // Move to next hall if current is full
                    while ($hallIndex <= $hallCount &&
                           count($halls[$hallIndex]) >= $capacity) {
                        $hallIndex++;
                    }

                    // If no halls left → overflow
                    if ($hallIndex > $hallCount) {
                        $unassigned = array_merge(
                            $unassigned,
                            $grade9,
                            $scientific,
                            $arts
                        );
                        return [$halls, $unassigned];
                    }

                    // Assign student
                    $student = array_shift($group);
                    $halls[$hallIndex][] = $student;
                }
            }
        }

        return [$halls, $unassigned];
    }
}