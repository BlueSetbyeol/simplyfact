import { useRef, useState } from 'react';
import * as proofs from '@/routes/expenses-claims/proofs';

// ─── Types ────────────────────────────────────────────────────────────────────

type UploadedFile = {
    path: string;
    originalName: string;
};

type FileUploadProps = {
    expensesClaimId: number;
    accept?: string;
    label?: string;
};

// ─── CSRF token helper ────────────────────────────────────────────────────────

function getCsrfToken(): string {
    return (
        (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)
            ?.content ?? ''
    );
}

// ─── Component ────────────────────────────────────────────────────────────────

export default function FileUpload({
    expensesClaimId,
    accept = '.jpg,.jpeg,.png,.pdf',
    label = 'Add a proof document',
}: FileUploadProps) {
    const inputRef = useRef<HTMLInputElement>(null);
    const [uploadedFiles, setUploadedFiles] = useState<UploadedFile[]>([]);
    const [uploading, setUploading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    // ── Upload ────────────────────────────────────────────────────────────────

    async function handleFileChange(e: React.ChangeEvent<HTMLInputElement>) {
        const file = e.target.files?.[0];

        if (!file) {
            return;
        }

        setUploading(true);
        setError(null);

        const formData = new FormData();
        formData.append('file', file);

        try {
            const response = await fetch(proofs.store(expensesClaimId).url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': getCsrfToken() },
                body: formData,
            });

            const data = await response.json();

            if (!response.ok) {
                const message =
                    data?.errors?.file?.[0] ??
                    data?.message ??
                    'Upload failed. Please try again.';
                setError(message);

                return;
            }

            setUploadedFiles((prev) => [
                ...prev,
                {
                    path: data.path,
                    originalName: data.originalName,
                },
            ]);
        } catch {
            setError('Upload failed. Please try again.');
        } finally {
            setUploading(false);

            if (inputRef.current) {
                inputRef.current.value = '';
            }
        }
    }

    // ── Delete ────────────────────────────────────────────────────────────────

    async function handleDelete(path: string) {
        try {
            const response = await fetch(proofs.destroy(expensesClaimId).url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
                body: JSON.stringify({ path }),
            });

            if (!response.ok) {
                setError('Failed to delete file. Please try again.');

                return;
            }

            setUploadedFiles((prev) => prev.filter((f) => f.path !== path));
        } catch {
            setError('Failed to delete file. Please try again.');
        }
    }

    // ── Render ────────────────────────────────────────────────────────────────

    return (
        <div className="flex flex-col gap-3">
            <button
                type="button"
                onClick={() => inputRef.current?.click()}
                disabled={uploading}
                className="flex w-full items-center justify-center gap-2 rounded-lg border border-dashed border-gray-300 px-4 py-3 text-sm text-gray-600 transition hover:border-green-600 hover:text-green-700 disabled:cursor-not-allowed disabled:opacity-50"
            >
                {uploading ? 'Uploading...' : label}
            </button>

            <input
                ref={inputRef}
                type="file"
                accept={accept}
                className="hidden"
                onChange={handleFileChange}
            />

            {error && <p className="text-xs text-red-500">{error}</p>}

            {uploadedFiles.length > 0 ? (
                <ul className="flex flex-col gap-2">
                    {uploadedFiles.map((file) => (
                        <li
                            key={file.path}
                            className="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2 text-sm"
                        >
                            <span className="truncate text-gray-700">
                                {file.originalName}
                            </span>
                            <button
                                type="button"
                                onClick={() => handleDelete(file.path)}
                                className="ml-3 shrink-0 text-xs text-red-500 hover:text-red-700"
                            >
                                Remove
                            </button>
                        </li>
                    ))}
                </ul>
            ) : (
                !uploading && (
                    <p className="text-xs text-gray-400">
                        No document selected
                    </p>
                )
            )}
        </div>
    );
}
