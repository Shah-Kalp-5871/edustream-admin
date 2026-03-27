<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jobs')->insert([
            [
                'id' => 1,
                'queue' => 'default',
                'payload' => '{"uuid":"f90629d3-295e-4349-aaf2-c7cb9491567c","displayName":"App\\Jobs\\ConvertVideoToHls","job":"Illuminate\\Queue\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"failOnTimeout":false,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"App\\Jobs\\ConvertVideoToHls","command":"O:26:\"App\\Jobs\\ConvertVideoToHls\":1:{s:5:\"video\";O:45:\"Illuminate\\Contracts\\Database\\ModelIdentifier\":5:{s:5:\"class\";s:16:\"App\\Models\\Video\";s:2:\"id\";i:61;s:9:\"relations\";a:0:{}s:10:\"connection\";s:5:\"mysql\";s:15:\"collectionClass\";N;}}"}}',
                'attempts' => 0,
                'reserved_at' => null,
                'available_at' => 1774075865,
                'created_at' => 1774075865,
            ],
        ]);
    }
}
