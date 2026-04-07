<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = ['Math','English','Physics','Chemistry','History','Biology','Geography','Religion','French'];

        foreach($courses as $c){
            Course::create(['name'=>$c]);
        }
    }
}
