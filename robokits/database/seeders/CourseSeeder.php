<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Group; 

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void 
{ 
$courses = Course::factory(15)->create(); 
$groups = Group::all(); 
foreach ($courses as $course) { 
$course->groups()->attach( 
$groups->random(rand(1,3))->pluck('id') 
); 
} 
} 
}
