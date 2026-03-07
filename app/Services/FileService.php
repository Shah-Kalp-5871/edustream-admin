<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    /**
     * Upload a file with hierarchical structure.
     * Path: courses/{course_id}/subjects/{subject_id}/{type}/{filename}
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $type (notes, videos, qa_papers, thumbnails)
     * @param int|null $courseId
     * @param int|null $subjectId
     * @return string Final file path
     */
    public function upload($file, $type, $courseId = null, $subjectId = null)
    {
        $basePath = 'public';
        
        if ($courseId) {
            $basePath .= "/courses/{$courseId}";
        }
        
        if ($subjectId) {
            $basePath .= "/subjects/{$subjectId}";
        }
        
        $basePath .= "/{$type}";
        
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        return $file->storeAs($basePath, $filename);
    }

    /**
     * Delete a file from storage.
     *
     * @param string $path
     * @return bool
     */
    public function delete($path)
    {
        if ($path && Storage::exists($path)) {
            return Storage::delete($path);
        }
        return false;
    }

    /**
     * Get the public URL for a file.
     *
     * @param string $path
     * @return string
     */
    public function getUrl($path)
    {
        if (!$path) return '';
        return Storage::url($path);
    }
}
