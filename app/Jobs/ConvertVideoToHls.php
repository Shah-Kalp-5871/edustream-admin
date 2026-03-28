<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Video\X264;
use App\Models\Video;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ConvertVideoToHls implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;

    /**
     * Create a new job instance.
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $rawFilePath = $this->video->getRawOriginal('file_path');
        if (!$rawFilePath) {
            Log::error("ConvertVideoToHls: Video {$this->video->id} has no file path.");
            $this->video->update(['processing_status' => 'failed']);
            return;
        }

        // Clean the path: remove leading slashes and common prefixes that break disk resolution
        $cleanPath = ltrim($rawFilePath, '/');
        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
        }

        try {
            $this->video->update(['processing_status' => 'processing']);

            // Create HLS format (720p optimization)
            // -preset: veryfast (faster encoding)
            // -crf: 23 (balance between quality and size)
            // -vf: scale=1280:-2 (downscale to 720p width, maintain aspect ratio, ensures even height)
            $lowBitrateFormat = (new X264('aac'))
                ->setKiloBitrate(1000)
                ->setAdditionalParameters([
                    '-preset', 'veryfast',
                    '-crf', '23',
                    '-vf', 'scale=1280:-2',
                ]);

            // Path to save HLS files: videos/hls/{video_id}
            $hlsFolderName = 'videos/hls/' . $this->video->id;
            $hlsPlaylistPath = $hlsFolderName . '/playlist.m3u8';

            FFMpeg::fromDisk('private')
                ->open($cleanPath)
                ->exportForHLS()
                ->addFormat($lowBitrateFormat)
                ->toDisk('private')
                ->save($hlsPlaylistPath);

            // Update video record
            $this->video->update([
                'hls_path' => $hlsPlaylistPath,
                'processing_status' => 'completed',
            ]);

            // Optional: delete raw video after successful conversion to save space
            // Storage::disk('private')->delete($this->video->file_path);

            Log::info("ConvertVideoToHls: Successfully converted video {$this->video->id} to HLS.");
        } catch (\Exception $e) {
            Log::error("ConvertVideoToHls Failed for Video {$this->video->id}: " . $e->getMessage());
            $this->video->update(['processing_status' => 'failed']);
            // Re-throw if you want it to retry (based on queue config)
            // throw $e;
        }
    }
}
