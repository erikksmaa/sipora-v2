<?php

namespace App\Actions\Manager;

use App\Models\ProgramLogbook;
use App\Models\ProgramLogbookMedia;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class AddProgramLogbookMediaAction
{
    public function execute(User $actor, ProgramLogbook $logbook, UploadedFile $file, ?string $caption): ProgramLogbookMedia
    {
        $path = $file->storeAs('logbooks/'.$logbook->uuid(), Str::uuid().'.'.$file->extension(), 'program_logbook_media');
        try {
            $media = ProgramLogbookMedia::create(['logbook_id' => $logbook->getKey(), 'uploaded_by' => $actor->getKey(), 'file_path' => $path, 'caption' => filled($caption) ? trim($caption) : null]);
            activity()->causedBy($actor)->performedOn($logbook)->event('program_logbook_media_added')->withProperties(['logbook_id' => $logbook->uuid(), 'media_id' => $media->uuid()])->log('Media Logbook ditambahkan');

            return $media;
        } catch (\Throwable $exception) {
            Storage::disk('program_logbook_media')->delete($path);
            throw $exception;
        }
    }
}
