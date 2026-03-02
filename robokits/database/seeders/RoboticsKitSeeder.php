<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoboticsKit;

class RoboticsKitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoboticsKit::create([ 
            'name' => 'StarterKit', 
            'description' => 'A starter kit for learning robotics'
        ]);
        RoboticsKit::create([ 
            'name' => 'Educational Robotics Kit', 
            'description' => 'An advanced kit for robotics enthusiasts'
        ]);
        RoboticsKit::create([ 
            'name' => 'Kit5', 
            'description' => 'A professional kit for robotics experts'
        ]);
    }
}
