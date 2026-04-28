import { Button, styled } from '@mui/material';
import { CloudUploadIcon } from 'lucide-react';
import { useRef, useState } from 'react';
import * as proofs from '@/routes/expenses-claims/proofs';

type UploadedFile = {
    path: string;
    originalName: string;
};

type FileUploadProps = {
    expensesClaimId: string;
    accept?: string;
    label?: string;
    maxSizeMb?: number;
    onUpload?: (hasFile: boolean) => void;
};

const VisuallyHiddenInput = styled('input')({
    clip: 'rect(0 0 0 0)',
    clipPath: 'inset(50%)',
    height: 1,
    overflow: 'hidden',
    position: 'absolute',
    whiteSpace: 'nowrap',
    width: 1,
});

function getCsrfToken(): string {
    return (
        (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)
            ?.content ?? ''
    );
}

export default function FileUpload({
    expensesClaimId,
    accept = '.jpg,.jpeg,.png,.pdf',
    label = 'Ajouter des justificatifs',
    maxSizeMb = 20,
    onUpload,
}: FileUploadProps) {
    const inputRef = useRef<HTMLInputElement>(null);
    const [uploadedFiles, setUploadedFiles] = useState<UploadedFile[]>([]);
    const [uploading, setUploading] = useState(false);
    const [errors, setErrors] = useState<string[]>([]);

    async function handleFileChange(e: React.ChangeEvent<HTMLInputElement>) {
        const files = Array.from(e.target.files || []);

        if (files.length === 0) {
            return;
        }

        setUploading(true);
        setErrors([]);

        const newErrors: string[] = [];
        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'application/pdf',
            'image/webp',
        ];
        const maxSizeBytes = maxSizeMb * 1024 * 1024;

        for (const file of files) {
            // Vérification type côté front
            if (!allowedTypes.includes(file.type)) {
                newErrors.push(
                    `${file.name} — Seuls les fichiers JPG, PNG et PDF sont acceptés.`,
                );
                continue;
            }

            // Vérification taille côté front
            if (file.size > maxSizeBytes) {
                newErrors.push(
                    `${file.name} — Le fichier dépasse la taille maximale de ${maxSizeMb} Mo.`,
                );
                continue;
            }

            const formData = new FormData();
            formData.append('file', file);

            try {
                console.log('URL:', proofs.store(expensesClaimId));
                const response = await fetch(
                    proofs.store(expensesClaimId).url,
                    {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': getCsrfToken() },
                        body: formData,
                    },
                );

                const data = await response.json();

                if (!response.ok) {
                    const message =
                        data?.errors?.file?.[0] ??
                        data?.message ??
                        "Échec de l'envoi.";
                    newErrors.push(`${file.name} — ${message}`);
                    continue;
                }

                setUploadedFiles((prev) => {
                    const newFiles: UploadedFile[] = [...prev, { path: data.path, originalName: data.originalName }]
                    onUpload?.(newFiles.length > 0)

                    return newFiles
                })
            } catch (err) {
                console.log('catch error:', err);
                newErrors.push(
                    `${file.name} — Échec de l'envoi. Veuillez réessayer.`,
                );
            }
        }

        setErrors(newErrors);
        setUploading(false);

        if (inputRef.current) {
            inputRef.current.value = '';
        }
    }

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
                setErrors(['Échec de la suppression. Veuillez réessayer.']);

                return;
            }

            setUploadedFiles((prev) => prev.filter((f) => f.path !== path));
        } catch {
            setErrors(['Échec de la suppression. Veuillez réessayer.']);
        }
    }

    return (
        <div className="flex flex-col gap-3">
            <Button
                component="label"
                role={undefined}
                variant="outlined"
                fullWidth
                disabled={uploading}
                startIcon={<CloudUploadIcon />}
                sx={{
                    color: '#2D6A2D',
                    borderColor: '#2D6A2D',
                    '&:hover': {
                        borderColor: '#1F4F1F',
                        backgroundColor: '#F0F7F0',
                    },
                }}
            >
                {uploading ? 'Envoi en cours...' : label}
                <VisuallyHiddenInput
                    ref={inputRef}
                    type="file"
                    accept={accept}
                    multiple
                    onChange={handleFileChange}
                />
            </Button>

            {errors.length > 0 && (
                <ul className="flex flex-col gap-1">
                    {errors.map((err, i) => (
                        <li key={i} className="text-xs text-red-500">
                            {err}
                        </li>
                    ))}
                </ul>
            )}

            {uploadedFiles.length > 0
                ? uploadedFiles.map((file) => (
                      <div
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
                              Supprimer
                          </button>
                      </div>
                  ))
                : !uploading && (
                      <p className="text-sm text-gray-500">
                          Aucun document sélectionné
                      </p>
                  )}
        </div>
    );
}
