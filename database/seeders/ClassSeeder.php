<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassModel;
use App\Models\Choreography;
use App\Models\User;
use App\Models\Video; // ✅ Make sure you import Video

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        // Get a user who has the instructor role
        $instructor = User::whereHas('roles', function ($query) {
            $query->where('name', 'instructor');
        })->inRandomOrder()->first();

        if (!$instructor) {
            $this->command->error('No instructor found. Please seed users and assign instructor roles first.');
            return;
        }

        // 🟣 Create Choreo + Class + Video
        $this->createClassWithVideo(
            $instructor->id,
            'Zoo – NCT x AESPA',
            'K-Pop',
            'Intermediate',
            'https://youtu.be/_HgdSvQSzmc?si=CVdltxf7RB6Q5PWj',
            '2025-07-05',
            '18:00',
            '20:00'
        );

        $this->createClassWithVideo(
            $instructor->id,
            'Bloodline – Ariana Grande',
            'Jazzfunk',
            'Intermediate',
            'https://www.youtube.com/watch?v=Ht3Mgh1DZ8c',
            '2025-07-06',
            '19:00',
            '21:00'
        );

        $this->createClassWithVideo(
            $instructor->id,
            'Sluther – Kendrick Lamar',
            'Hip Hop',
            'Intermediate',
            'https://youtu.be/Dme9rfbg244?si=9g9uh1jGkOfHDK_v',
            '2025-07-07',
            '18:00',
            '20:00'
        );
    }

    private function createClassWithVideo($instructorId, $title, $style, $difficulty, $videoUrl, $date, $start, $end)
    {
        $choreo = Choreography::create([
            'style' => $style,
            'difficulty' => $difficulty,
        ]);

        $class = ClassModel::create([
            'title' => $title,
            'instructor_id' => $instructorId,
            'choreography_id' => $choreo->id,
            'location' => 'Ivy Studio, Jl. Bengawan No.6, Darmo, Surabaya',
            'date' => $date,
            'start_time' => $start,
            'end_time' => $end,
            'max_slot' => 12,
        ]);

        Video::create([
            'class_id' => $class->id,
            'uploader_id' => $instructorId,
            'video_url' => $videoUrl,
            'upload_date' => now(),
        ]);
    }
}
