import { Head, useForm } from '@inertiajs/react';
import { Button, MenuItem, TextField } from '@mui/material';
import { useState } from 'react';
import FileUpload from '@/components/FileUpload';
import Header from '@/layouts/Header';

interface AccommodationDetailsProps {
    expensesClaimId: string;
    accommodation: {
        id: string;
        accommodation_type: string;
        nb_of_night: number;
        total_price: number;
    };
}

export default function AccommodationDetails({
    expensesClaimId,
    accommodation,
}: AccommodationDetailsProps) {
    const { data, setData, post, errors, reset } = useForm(
        'CreateAccomodation',
        {
            accommodation_type: 'Hôtel province hors coeur de ville',
            nb_of_night: accommodation?.nb_of_night || 0,
            total_price: accommodation?.total_price || 0,
        },
    );

    const ceilings: Record<string, number> = {
        'Hôtel province hors coeur de ville': 70,
        'Hôtel province coeur de ville': 90,
        'Hôtel Lyon': 100,
        'Hôtel Paris': 150,
    };

    const ceiling = ceilings[data.accommodation_type] ?? 0;

    const totalRefund = Math.min(data.total_price, ceiling * data.nb_of_night);

    function handleSubmit(e: { preventDefault: () => void }) {
        e.preventDefault();
        post(`/expenses-claims/${expensesClaimId}/accommodations`, {
            onSuccess: () => {
                reset();
            },
        });
    }

    const [hasDocument, setHasDocument] = useState(false);

    return (
        <Header>
            <Head title="Ajout déplacement"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
                <div className="flex flex-col gap-4">
                    <h1 className="text-xl font-medium text-gray-900">
                        Ajout d'un hébergement
                    </h1>
                    <form onSubmit={handleSubmit}>
                        <div className="flex flex-col gap-5">
                            <TextField
                                label="Type de logement"
                                slotProps={{ inputLabel: { shrink: true } }}
                                required
                                select
                                defaultValue={data.accommodation_type || ''}
                                onChange={(e) =>
                                    setData(
                                        'accommodation_type',
                                        e.target.value,
                                    )
                                }
                                fullWidth
                                error={!!errors['accommodation_type']}
                                helperText={errors['accommodation_type']}
                            >
                                <MenuItem value="Hôtel province hors coeur de ville">
                                    Hôtel province hors cœur de ville
                                </MenuItem>
                                <MenuItem value="Hôtel province coeur de ville">
                                    Hôtel province cœur de ville
                                </MenuItem>
                                <MenuItem value="Hôtel Lyon">
                                    Hôtel Lyon
                                </MenuItem>
                                <MenuItem value="Hôtel Paris">
                                    Hôtel Paris
                                </MenuItem>
                            </TextField>
                            {errors.accommodation_type && (
                                <span>{errors.accommodation_type}</span>
                            )}

                            <TextField
                                label="Nombre de nuits"
                                slotProps={{
                                    inputLabel: { shrink: true },
                                    htmlInput: { step: 0, min: 1 },
                                }}
                                type="number"
                                defaultValue={
                                    data.nb_of_night !== 0
                                        ? data.nb_of_night
                                        : ''
                                }
                                onChange={(e) =>
                                    setData(
                                        'nb_of_night',
                                        Number(e.target.value),
                                    )
                                }
                                fullWidth
                                error={!!errors['nb_of_night']}
                                helperText={errors['nb_of_night']}
                            />
                            {errors.nb_of_night && (
                                <span>{errors.nb_of_night}</span>
                            )}

                            <TextField
                                label="Montant total dépensé"
                                slotProps={{
                                    inputLabel: { shrink: true },
                                    htmlInput: { step: 0.01, min: 0 },
                                }}
                                type="number"
                                defaultValue={
                                    data.total_price !== 0
                                        ? data.total_price
                                        : ''
                                }
                                onChange={(e) => {
                                    setData(
                                        'total_price',
                                        Number(e.target.value),
                                    );
                                }}
                                fullWidth
                                error={!!errors['total_price']}
                                helperText={errors['total_price']}
                            />
                            {errors.total_price && (
                                <span>{errors.total_price}</span>
                            )}
                        </div>
                        <div className="mt-6 mb-6 flex items-center justify-between rounded-xl bg-gray-50 p-4">
                            <div>
                                <p className="text-sm text-gray-500">
                                    Total à rembourser
                                </p>
                                <p className="mt-1 text-xs text-gray-400">
                                    Plafond de {ceiling}€ par nuit, soit :{' '}
                                    {data.nb_of_night} nuits * {ceiling}€
                                </p>
                            </div>
                            <p className="text-2xl font-medium text-gray-900">
                                {totalRefund}€
                            </p>
                        </div>

                        <FileUpload
                            expensesClaimId={expensesClaimId}
                            onUpload={(hasFiles) => setHasDocument(hasFiles)}
                        />

                        <Button
                            type="submit"
                            disabled={!hasDocument}
                            variant="contained"
                            fullWidth
                            className="mt-5!"
                            sx={{
                                backgroundColor: '#2D6A2D',
                                '&:hover': { backgroundColor: '#1F4F1F' },
                            }}
                        >
                            Ajouter l'hébergement
                        </Button>
                    </form>
                </div>
            </div>
        </Header>
    );
}
