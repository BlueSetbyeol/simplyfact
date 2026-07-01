<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProofUploadService
{
    /**
     * Store the file under uploads/{expensesClaimId}/
     * Returns an array with the stored path and the original filename.
     */
    public function store(UploadedFile $file, int|string $expensesClaimId): array
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid().'.'.$extension;
        $directory = 'uploads/'.$expensesClaimId;

        $file->storeAs($directory, $filename, 's3');

        return [
            'path' => $directory.'/'.$filename,
            'originalName' => $file->getClientOriginalName(),
        ];
    }

    /**
     * Delete a specific proof file.
     * Returns false if the file does not exist.
     */
    public function delete(string $path): bool
    {
        if (! Storage::disk('s3')->exists($path)) {
            return false;
        }

        Storage::disk('s3')->delete($path);

        return true;
    }

    /**
     * Delete all proof files for a given expenses claim.
     * Called after PDF generation + email sending.
     */
    public function deleteAll(int|string $expensesClaimId): void
    {
        Storage::disk('s3')->deleteDirectory('uploads/'.$expensesClaimId);
    }

    /**
     * Return absolute paths of all proof files for a given expenses claim.
     * Used by PdfGenerator for merging.
     */
    public function getSignedUrls(int|string $expensesClaimId): array
    {
        $directory = 'uploads/'.$expensesClaimId;

        $files = Storage::disk('s3')->files($directory);

        return array_map(
            fn (string $path) => Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(100)),
            $files
        );
    }
}
