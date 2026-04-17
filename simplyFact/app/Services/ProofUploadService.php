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

        $file->storeAs($directory, $filename, 'local');

        return [
            'path' => $directory.'/'.$filename,
            'originalName' => $file->getClientOriginalName(),
        ];
    }

    /**
     * Delete a specific proof file.
     */
    /**
     * Delete a specific proof file.
     * Returns false if the file does not exist.
     */
    public function delete(string $path): bool
    {
        if (! Storage::disk('local')->exists($path)) {
            return false;
        }

        Storage::disk('local')->delete($path);

        return true;
    }

    /**
     * Delete all proof files for a given expenses claim.
     * Called after PDF generation + email sending.
     */
    public function deleteAll(int|string $expensesClaimId): void
    {
        Storage::disk('local')->deleteDirectory('uploads/'.$expensesClaimId);
    }

    /**
     * Return absolute paths of all proof files for a given expenses claim.
     * Used by PdfGenerator for merging.
     */
    public function getAbsolutePaths(int|string $expensesClaimId): array
    {
        $directory = 'uploads/'.$expensesClaimId;

        $files = Storage::disk('local')->files($directory);

        return array_map(
            fn (string $path) => Storage::disk('local')->path($path),
            $files
        );
    }
}
