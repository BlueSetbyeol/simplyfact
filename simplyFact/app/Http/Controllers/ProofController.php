<?php

namespace App\Http\Controllers;

use App\Models\ExpensesClaim;
use App\Services\ProofUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProofController extends Controller
{
    public function __construct(
        private readonly ProofUploadService $uploadService
    ) {}

    /**
     * POST /expenses-claims/{expensesClaim}/proofs
     * Upload a proof document.
     */
    public function store(Request $request, ExpensesClaim $expensesClaim): JsonResponse
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240', // 10 MB
            ],
        ], [
            'file.required' => 'Please select a file.',
            'file.mimes' => 'Only JPG, PNG and PDF files are accepted.',
            'file.max' => 'File size must not exceed 10 MB.',
        ]);

        $result = $this->uploadService->store(
            $request->file('file'),
            $expensesClaim->id
        );

        return response()->json([
            'path' => $result['path'],
            'originalName' => $result['originalName'],
        ], 201);
    }

    /**
     * DELETE /expenses-claims/{expensesClaim}/proofs
     * Delete a specific proof file.
     */
    public function destroy(Request $request, ExpensesClaim $expensesClaim): JsonResponse
    {
        $request->validate([
            'path' => ['required', 'string'],
        ]);

        $path = $request->input('path');

        // Security: ensure the path belongs to this expenses claim
        $expectedPrefix = 'uploads/'.$expensesClaim->id.'/';

        if (! str_starts_with($path, $expectedPrefix)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $deleted = $this->uploadService->delete($path);

        if (! $deleted) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->json(['success' => true]);
    }
}
