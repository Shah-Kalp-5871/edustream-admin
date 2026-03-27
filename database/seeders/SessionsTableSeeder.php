<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sessions')->insert([
            [
                'id' => '6N8aOrjnrpNAEsCOlPafWSbMCNp0UgzgtJBlawTS',
                'user_id' => 1,
                'ip_address' => '2409:4080:9205:fcaf:a002:c9b3:25e4:c8aa',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36',
                'payload' => 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieFlpWURaSUxPeHB4aFVNSDBOMXY5Ym4ySkxmVTlvZ2dPd2RSODFUdCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vZ3VqanVzY2hvbGFyLmluL3VzZXJzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',
                'last_activity' => 1774550286,
            ],
            [
                'id' => 'CtH7dcH2x7oZdbMPx0Joa3d5GnE3BeyEOCmAwyk5',
                'user_id' => null,
                'ip_address' => '2a02:4780:11:c0de::e',
                'user_agent' => 'Go-http-client/2.0',
                'payload' => 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiSDE1Z21ZTGdmbHN1SGdqOXZFblJhUTlHNU9FQktwVnpTaXNzMUlNRSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',
                'last_activity' => 1774582179,
            ],
            [
                'id' => 'iulmHtYkOw1E24W1noyPI8aIFkgpUkLelp3Q6dya',
                'user_id' => 1,
                'ip_address' => '2402:a00:403:12b4:201e:89c7:e126:846d',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',
                'payload' => 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiYlprb21yNGxZcUw4NlZuTWZ1bzN2amtRdFFGdENFZHZZeWdob3BwaCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMxOiJodHRwczovL2d1amp1c2Nob2xhci5pbi9jb250ZW50Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',
                'last_activity' => 1774614641,
            ],
            [
                'id' => 'NmJud3od6rPi8ESBtPHaesmG4LiheIkbW9Td4PNX',
                'user_id' => null,
                'ip_address' => '161.35.128.86',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36',
                'payload' => 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiREZoNTk4TXBWSE84NEVOQk1kMFU0OEZBd1ZPMWdEbHNXMGg4YVZEMyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHBzOi8vd3d3Lmd1amp1c2Nob2xhci5pbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',
                'last_activity' => 1774574809,
            ],
            [
                'id' => 'OUpP15uQSj9gxxPZbKSLweVIaK0kefZmTZc46j2Z',
                'user_id' => 1,
                'ip_address' => '2402:a00:403:12b4:201e:89c7:e126:846d',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36',
                'payload' => 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaHNGanBseGpjN3ROQjF5TTRsYTBSeHVMWUhDT3B0TEd4U2ZoU1RBcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHBzOi8vZ3VqanVzY2hvbGFyLmluL2NvbnRlbnQvY2F0ZWdvcmllcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',
                'last_activity' => 1774614721,
            ],
            [
                'id' => 'OV7bERVwDhHdmJCjVh6hu4GA1TZnTVDgmPpA49iQ',
                'user_id' => null,
                'ip_address' => '35.175.239.101',
                'user_agent' => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.51 Safari/537.36',
                'payload' => 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUktuN2FqWDBxWTJhSWZZMjhXZlg3WDltMjIzcnpneWM2UjlNa0xJdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vZ3VqanVzY2hvbGFyLmluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',
                'last_activity' => 1774612743,
            ],
            [
                'id' => 'rkVkZ2aege9lTC0w5TzO1uqAMgAeYPA3ImUI9yWs',
                'user_id' => null,
                'ip_address' => '35.87.110.155',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36',
                'payload' => 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWlJ5QkEzOFpKWWZoZloxNjNKaTEwaGNzZnBsRzBIQVVlQzJNM2hrcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vZ3VqanVzY2hvbGFyLmluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',
                'last_activity' => 1774591771,
            ],
            [
                'id' => 'UK0xvMuVrOyVm60OheY1SUJRPJG45JtNBO0Bc6DJ',
                'user_id' => null,
                'ip_address' => '209.38.125.40',
                'user_agent' => 'req/v3 (https://github.com/imroc/req)',
                'payload' => 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaUNNVnZDaWVXcVJZMENEd09oaERMUGtxSjJtdmlJTGtoYzlIY3ZMbiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vZ3VqanVzY2hvbGFyLmluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',
                'last_activity' => 1774549666,
            ],
            [
                'id' => 'YUJa7nGhM2LXQfPaxiVtkHl5lhZzeyf5yNF8U9ZS',
                'user_id' => null,
                'ip_address' => '35.175.239.101',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.20 Safari/535.1',
                'payload' => 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTUtkanQwYVJOV3FpejRqT2NJUlJ5RlVHbzZaRVM5SGNJQ2QwdkhOMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vZ3VqanVzY2hvbGFyLmluIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',
                'last_activity' => 1774612744,
            ],
        ]);
    }
}
