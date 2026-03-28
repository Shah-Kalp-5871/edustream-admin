# Video Chunking & Server Setup Guide

To ensure video uploads are processed correctly into HLS chunks for streaming, you need to configure your server with FFmpeg and the Laravel scheduler/queue.

## 1. Video Chunking Setup (FFmpeg)

The application uses **HLS (HTTP Live Streaming)** for video playback. This converts your uploaded MP4 files into small `.ts` chunks and a `.m3u8` playlist.

### Requirements:
- **FFmpeg & FFprobe**: Must be installed on your server.
  - Ubuntu/Debian: `sudo apt update && sudo apt install ffmpeg`
  - Windows: Download from FFmpeg.org and add to PATH.
- **Environment Variables**: Ensure these are set in your [.env](file:///c:/laravel-projects/edustream-admin/.env) (already present in your current [.env](file:///c:/laravel-projects/edustream-admin/.env)):
  ```env
  FFMPEG_BINARIES=/usr/bin/ffmpeg
  FFPROBE_BINARIES=/usr/bin/ffprobe
  QUEUE_CONNECTION=database
  ```

### How it works:
- When you upload a video, the [ConvertVideoToHls](file:///c:/laravel-projects/edustream-admin/app/Jobs/ConvertVideoToHls.php#16-93) job is dispatched.
- It uses the `private` disk (`storage/app/private`) to store raw and processed videos.
- The output will be in `storage/app/private/videos/hls/{video_id}/`.

---

## 2. Cron Jobs & Process Management

You need to run two types of processes on your server:

### A. The Laravel Scheduler (Cron Job)
This runs every minute to handle any scheduled tasks (like cleanup or periodic checks).
Add this to your server's crontab (`crontab -e`):
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```
*Note: Replace `/path-to-your-project` with the actual absolute path to your project root.*

### B. The Queue Worker (Crucial!)
Because video conversion takes time, it runs in the background. You **MUST** have a queue worker running to process the videos.

**Manual Run (for testing):**
```bash
php artisan queue:work --timeout=3600
```

**Production Recommendation:**
Use **Supervisor** to keep this process running in the background.
Example Supervisor config (`/etc/supervisor/conf.d/edustream-worker.conf`):
```ini
[program:edustream-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path-to-your-project/artisan queue:work --sleep=3 --tries=3 --max-time=3600 --timeout=3600
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/path-to-your-project/storage/logs/worker.log
```

---

## 3. Verification
1. Upload a video via the Admin dashboard.
2. Check the `processing_status` in the `videos` table (or dashboard).
3. If it stays "pending", ensure your queue worker is running: `php artisan queue:work`.
4. Successful conversion will generate a folder in `storage/app/private/videos/hls/`.
