import { Head, useForm } from '@inertiajs/react';
import { Button, MenuItem, TextField } from '@mui/material';
import { useState } from 'react';
import FileUpload from '@/components/FileUpload';
import Header from '@/layouts/Header';

interface OtherTripProps {
    expensesClaimId: string;
    otherTrip: {
        id: number;
        expense_name: string;
        total_price: number;
    };
}

export default function OtherTrip({
    expensesClaimId,
    otherTrip,
}: OtherTripProps) {
    const { data, setData, post, errors } = useForm('CreateOtherTravel', {
        expense_name: otherTrip?.expense_name || '',
        total_price: otherTrip?.total_price || 0,
    });

    const modes = [
        'Train (2nd classe)',
        'Transport en commun',
        'Avion (2nd classe)',
        'Péage, parking, taxis',
    ];

    async function handleSubmit(e: { preventDefault: () => void }) {
        e.preventDefault();
        post(`/expenses-claims/${expensesClaimId}/other-travels`);
    }

    const [hasDocument, setHasDocument] = useState(false);

    return (
        <Header>
            <Head title="Autre trajet"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
                <h1 className="mb-2 text-xl font-medium text-gray-900">
                    Autre trajet
                </h1>

                <hr className="my-4 border-gray-100" />
                <TextField
                    label="Type de transport"
                    slotProps={{ inputLabel: { shrink: true } }}
                    required
                    select
                    defaultValue={data.expense_name || ''}
                    onChange={(e) => setData('expense_name', e.target.value)}
                    fullWidth
                    error={!!errors['expense_name']}
                    helperText={errors['expense_name']}
                >
                    {modes.map((transport, index) => (
                        <MenuItem key={index} value={transport}>
                            {transport}
                        </MenuItem>
                    ))}
                </TextField>
                <hr className="mt-4 mb-8 border-gray-100" />

                <div className="mb-4 flex flex-col gap-4">
                    <div className="flex flex-col gap-2">
                        <p className="text-md mb-2 text-gray-500">
                            Déplacement en train
                        </p>
                        <TextField
                            label="Montant dépensé"
                            slotProps={{
                                inputLabel: { shrink: true },
                                htmlInput: {
                                    step: 0.01,
                                    min: 0,
                                },
                            }}
                            type="number"
                            defaultValue={data.total_price || ''}
                            onChange={(e) =>
                                setData('total_price', Number(e.target.value))
                            }
                            fullWidth
                            error={!!errors['total_price']}
                            helperText={errors['total_price']}
                        />
                    </div>
                </div>

                <div className="mb-2 flex items-center justify-between rounded-xl bg-gray-50 p-4">
                    <p className="text-sm text-gray-500">Total à rembourser</p>
                    <p className="text-2xl font-medium text-gray-900">
                        {data.total_price.toFixed(2)}€
                    </p>
                </div>

                <FileUpload
                    expensesClaimId={expensesClaimId}
                    onUpload={(hasFiles) => setHasDocument(hasFiles)}
                />

                <Button
                    onClick={handleSubmit}
                    variant="contained"
                    disabled={!hasDocument}
                    fullWidth
                    className="!mt-2"
                    sx={{
                        backgroundColor: '#2D6A2D',
                        '&:hover': { backgroundColor: '#1F4F1F' },
                    }}
                >
                    Suivant
                </Button>
            </div>
        </Header>
    );
}
