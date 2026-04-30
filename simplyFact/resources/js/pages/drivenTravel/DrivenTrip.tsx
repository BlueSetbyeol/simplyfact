import { Head, useForm } from '@inertiajs/react';
import { Button, MenuItem, TextField, Tooltip } from '@mui/material';
import { Info } from 'lucide-react';
import { useState } from 'react';
import FileUpload from '@/components/FileUpload';
import Header from '@/layouts/Header';

interface DrivenTripsProps {
    expensesClaimId: string;
    drivenTrip?: {
        id: number;
        starting_city: string;
        starting_zip_code: number;
        ending_city: string;
        ending_zip_code: string;
        trip_type?: string;
        total_distance: number;
        total_distance_given?: number;
        description: string;
    };
    vehicle: {
        id: string;
        vehicle_type: string;
        price_given: number;
    };
}

export default function DrivenTrips({
    expensesClaimId,
    drivenTrip,
    vehicle,
}: DrivenTripsProps) {
    const { data, setData, post, errors, reset } = useForm(
        'CreateDrivenTravel',
        {
            starting_city: drivenTrip?.starting_city || '',
            starting_zip_code: drivenTrip?.starting_zip_code || 0,
            ending_city: drivenTrip?.ending_city || '',
            ending_zip_code: drivenTrip?.ending_zip_code || 0,
            trip_type: drivenTrip?.trip_type || '',
            total_distance: drivenTrip?.total_distance || 0,
            total_distance_given: drivenTrip?.total_distance_given || 0,
            description: drivenTrip?.description || '',
        },
    );

    const TransportMode = [
        `${vehicle.vehicle_type}`,
        'Covoiturage, remorque, salarié...',
        'Déplacements pendant stage fédéral',
    ];

    const rate =
        data.trip_type === 'Covoiturage, remorque, salarié...' ||
        data.trip_type === 'Déplacements pendant stage fédéral'
            ? 0.4
            : data.trip_type === 'voiture'
              ? 0.36
              : 0.14;

    const totalPrice = Number(
        (
            rate *
            (data.total_distance - data.total_distance_given < 0
                ? 0
                : data.total_distance - data.total_distance_given)
        ).toFixed(2),
    );

    const totalAbandonned = Number(
        (vehicle.price_given * data.total_distance_given).toFixed(2),
    );

    function handleSubmit(e: { preventDefault: () => void }) {
        e.preventDefault();
        post(`/expenses-claims/${expensesClaimId}/driven-travels`, {
            onSuccess: () => {
                reset();
            },
        });
    }

    const [hasDocument, setHasDocument] = useState(false);

    return (
        <Header>
            <Head title="Trajet conduit"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
                <h1 className="mb-2 text-xl font-medium text-gray-900">
                    Trajet conduit
                </h1>

                <hr className="mb-8 border-gray-100" />

                <form className="flex flex-col gap-4" onSubmit={handleSubmit}>
                    <div className="flex flex-row gap-2">
                        <TextField
                            label="Ville de départ"
                            slotProps={{ inputLabel: { shrink: true } }}
                            type="text"
                            required
                            defaultValue={
                                data.starting_city !== ''
                                    ? data.starting_city
                                    : ''
                            }
                            onChange={(e) =>
                                setData('starting_city', e.target.value)
                            }
                            fullWidth
                            error={!!errors['starting_city']}
                            helperText={errors['starting_city']}
                        />
                        {errors.starting_city && (
                            <span>{errors.starting_city}</span>
                        )}

                        <TextField
                            label="Code postal"
                            slotProps={{
                                inputLabel: { shrink: true },
                                htmlInput: {
                                    step: 0,
                                    min: 0,
                                    maxLength: 6,
                                    minLength: 5,
                                },
                            }}
                            required
                            type="number"
                            defaultValue={
                                data.starting_zip_code !== 0
                                    ? data.starting_zip_code
                                    : ''
                            }
                            onChange={(e) =>
                                setData(
                                    'starting_zip_code',
                                    Number(e.target.value),
                                )
                            }
                            fullWidth
                            error={!!errors['starting_zip_code']}
                            helperText={errors['starting_zip_code']}
                        />
                        {errors.starting_zip_code && (
                            <span>{errors.starting_zip_code}</span>
                        )}
                    </div>

                    <div className="flex flex-row gap-2">
                        <TextField
                            label="Ville d'arrivée"
                            slotProps={{ inputLabel: { shrink: true } }}
                            type="text"
                            required
                            defaultValue={
                                data.ending_city !== '' ? data.ending_city : ''
                            }
                            onChange={(e) =>
                                setData('ending_city', e.target.value)
                            }
                            fullWidth
                            error={!!errors['ending_city']}
                            helperText={errors['ending_city']}
                        />
                        {errors.ending_city && (
                            <span>{errors.ending_city}</span>
                        )}

                        <TextField
                            label="Code postal"
                            slotProps={{
                                inputLabel: { shrink: true },
                                htmlInput: {
                                    step: 0,
                                    min: 0,
                                    maxLength: 6,
                                    minLength: 5,
                                },
                            }}
                            required
                            type="number"
                            defaultValue={
                                data.ending_zip_code !== 0
                                    ? data.ending_zip_code
                                    : ''
                            }
                            onChange={(e) =>
                                setData(
                                    'ending_zip_code',
                                    Number(e.target.value),
                                )
                            }
                            fullWidth
                            error={!!errors['ending_zip_code']}
                            helperText={errors['ending_zip_code']}
                        />
                        {errors.ending_zip_code && (
                            <span>{errors.ending_zip_code}</span>
                        )}
                    </div>

                    <div className="flex flex-col items-end gap-4 rounded-xl bg-gray-50 p-4">
                        <Tooltip
                            title="Détails des déplacements si plusieurs destinations et dans le cas d'un covoiturage : Noms des passagers."
                            arrow
                        >
                            <Info className="h-4 w-4 cursor-pointer text-gray-400" />
                        </Tooltip>

                        <TextField
                            label="Description"
                            multiline
                            rows={3}
                            type="text"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={
                                data.description !== '' ? data.description : ''
                            }
                            onChange={(e) =>
                                setData('description', e.target.value)
                            }
                            fullWidth
                        />
                        {errors.description && (
                            <span>{errors.description}</span>
                        )}
                    </div>

                    <TextField
                        label="Type de véhicule"
                        slotProps={{ inputLabel: { shrink: true } }}
                        required
                        select
                        defaultValue={data.trip_type || ''}
                        onChange={(e) => setData('trip_type', e.target.value)}
                        fullWidth
                        error={!!errors['trip_type']}
                        helperText={errors['trip_type']}
                    >
                        {TransportMode.map((transport, index) => (
                            <MenuItem key={index} value={transport}>
                                {transport}
                            </MenuItem>
                        ))}
                    </TextField>
                    {errors.trip_type && <span>{errors.trip_type}</span>}

                    <TextField
                        label="Total des km parcourus"
                        required
                        type="number"
                        slotProps={{
                            inputLabel: { shrink: true },
                            htmlInput: {
                                step: 0.01,
                                min: 1,
                                maxLength: 15,
                                minLength: 1,
                            },
                        }}
                        defaultValue={
                            data.total_distance !== 0 ? data.total_distance : ''
                        }
                        onChange={(e) =>
                            setData('total_distance', Number(e.target.value))
                        }
                        fullWidth
                        error={!!errors['total_distance']}
                        helperText={errors['total_distance']}
                    />
                    {errors.total_distance && (
                        <span>{errors.total_distance}</span>
                    )}

                    <div className="flex flex-col gap-4 rounded-xl bg-gray-50 p-4">
                        <div className="flex items-baseline justify-between gap-2">
                            <p className="mb-4 text-sm font-medium text-gray-500">
                                OPTIONNEL: Déclaration de km en abandon
                            </p>
                            <Tooltip
                                title="Les km en abandon seront pris en compte en tant que dons à l'association pour les impôts."
                                arrow
                            >
                                <Info className="h-4 w-4 cursor-pointer text-gray-400" />
                            </Tooltip>
                        </div>
                        <TextField
                            label="Total des km abandonnés"
                            slotProps={{
                                inputLabel: { shrink: true },
                                htmlInput: {
                                    step: 0.01,
                                    min: 0,
                                },
                            }}
                            type="number"
                            defaultValue={
                                data.total_distance_given !== 0
                                    ? data.total_distance_given
                                    : ''
                            }
                            onChange={(e) =>
                                setData(
                                    'total_distance_given',
                                    Number(e.target.value),
                                )
                            }
                            fullWidth
                            error={
                                !!errors['total_distance_given'] ||
                                data.total_distance < data.total_distance_given
                            }
                            helperText={
                                errors['total_distance_given'] ||
                                data.total_distance < data.total_distance_given
                                    ? `La distance ne peut pas dépasser ${data.total_distance} km`
                                    : undefined
                            }
                        />
                        {errors.total_distance_given && (
                            <span>{errors.total_distance_given}</span>
                        )}

                        {data.total_distance_given !== 0 && (
                            <p className="text-xs text-[#2D6A2D]">
                                Donc km à rembourser : ({data.total_distance} -{' '}
                                {data.total_distance_given}) ={' '}
                                {data.total_distance -
                                    data.total_distance_given}{' '}
                                km
                            </p>
                        )}
                    </div>

                    <hr className="mb-4 border-gray-100" />

                    <div className="flex flex-col items-center justify-around gap-2 rounded-xl bg-gray-50 p-4">
                        <div className="flex w-full items-center justify-between">
                            <div className="flex flex-col">
                                <p className="text-sm text-gray-500">
                                    Total à rembourser :
                                </p>
                                <p className="mt-1 text-xs text-gray-400">
                                    Taux de {rate}€ par km, soit :{' '}
                                    {data.total_distance -
                                        data.total_distance_given}{' '}
                                    km * {rate}€
                                </p>
                            </div>
                            <p className="text-xl font-medium text-gray-900">
                                {totalPrice.toFixed(2)}€
                            </p>
                        </div>

                        {data.total_distance_given > 0 && (
                            <div className="flex w-full items-center justify-between">
                                <div className="flex flex-col">
                                    <p className="text-sm text-gray-500">
                                        Total abandon :
                                    </p>
                                    <p className="mt-1 text-xs text-gray-400">
                                        Taux de {vehicle.price_given}€ par km,
                                        soit : {data.total_distance_given} km *{' '}
                                        {vehicle.price_given}€
                                    </p>
                                </div>
                                <p className="text-xl font-medium text-gray-900">
                                    {totalAbandonned.toFixed(2)}€
                                </p>
                            </div>
                        )}
                    </div>

                    <FileUpload
                        expensesClaimId={expensesClaimId}
                        onUpload={(hasFiles) => setHasDocument(hasFiles)}
                    />

                    <Button
                        type="submit"
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
                </form>
            </div>
        </Header>
    );
}
