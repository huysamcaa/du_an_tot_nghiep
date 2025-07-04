<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewMultimediaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('review_multimedia')->insert([
            [
                'review_id' => 1,
                'file' => 'uploads/reviews/demo.jpg',
                'file_type' => 'image',
                'mime_type' => 'image/jpeg',
            ],
            [
                'review_id' => 1,
                'file' => 'uploads/reviews/demo2.jpg',
                'file_type' => 'image',
                'mime_type' => 'image/jpeg',
            ],
            [
                'review_id' => 2,
                'file' => 'uploads/reviews/demo_video.mp4',
                'file_type' => 'video',
                'mime_type' => 'video/mp4',
            ],
        ]);
    }
}
