import { Head, useForm } from '@inertiajs/react';
import { Button, MenuItem, TextField, Tooltip } from '@mui/material';
import { Info } from 'lucide-react';
import { useState } from 'react';
import FileUpload from '@/components/FileUpload';
import Header from '@/layouts/Header';

interface VehicleProps {
    expensesClaimId: string;
    vehicle?: {
        id: string;
        vehicle_type: string;
        electrical: boolean;
        power: string;
        price_given: number;
        number_plate: string;
    };
    modes: string[];
}

export default function Vehicle({ expensesClaimId, vehicle }: VehicleProps) {
    const { data, setData, post, errors, reset, transform } = useForm(
        'CreateVehicle',
        {
            vehicle_type: vehicle?.vehicle_type || 'voiture',
            electrical: vehicle?.electrical || false,
            power: vehicle?.power || '',
            number_plate: vehicle?.number_plate || '',
            price_given: vehicle?.price_given || '',
        },
    );

    const carRates: Record<string, number> = {
        '3CV et moins': 0.529,
        '4CV': 0.606,
        '5CV': 0.636,
        '6CV': 0.665,
        '7CV et plus': 0.697,
    };

    const bikeRates: Record<string, number> = {
        '1 et 2CV': 0.395,
        '3, 4 et 5CV': 0.468,
        '6CV et plus': 0.606,
    };

    const electricalCarRates: Record<string, number> = {
        '3CV et moins': 0.635,
        '4CV': 0.727,
        '5CV': 0.763,
        '6CV': 0.798,
        '7CV et plus': 0.836,
    };

    const electricalBikeRates: Record<string, number> = {
        '1 et 2CV': 0.474,
        '3, 4 et 5CV': 0.562,
        '6CV et plus': 0.727,
    };

    const getPriceGiven = () => {
        if (data.vehicle_type === 'voiture') {
            return data.electrical
                ? (electricalCarRates[data.power] ?? 0)
                : (carRates[data.power] ?? 0);
        } else {
            return data.electrical
                ? (electricalBikeRates[data.power] ?? 0)
                : (bikeRates[data.power] ?? 0);
        }
    };

    transform((data) => ({
        ...data,
        price_given: getPriceGiven(),
    }));

    function handleSubmit(e: { preventDefault: () => void }) {
        e.preventDefault();
        post(`/expenses-claims/${expensesClaimId}/vehicles`, {
            onSuccess: () => {
                reset();
            },
        });
    }

    const [hasDocument, setHasDocument] = useState(false);

    return (
        <Header>
            <Head title="Votre véhicule"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
                <h1 className="mb-2 text-xl font-medium text-gray-900">
                    Identification du véhicule
                </h1>
                <hr className="mb-4 border-gray-100" />

                <form className="flex flex-col gap-4" onSubmit={handleSubmit}>
                    <TextField
                        label="Type de véhicule"
                        slotProps={{ inputLabel: { shrink: true } }}
                        required
                        select
                        defaultValue={data.vehicle_type || ''}
                        onChange={(e) =>
                            setData('vehicle_type', e.target.value)
                        }
                        fullWidth
                        error={!!errors['vehicle_type']}
                        helperText={errors['vehicle_type']}
                    >
                        <MenuItem value="voiture">voiture</MenuItem>
                        <MenuItem value="moto">moto</MenuItem>
                    </TextField>
                    {errors.vehicle_type && <span>{errors.vehicle_type}</span>}

                    <TextField
                        label="Plaque d'immatriculation"
                        slotProps={{ inputLabel: { shrink: true } }}
                        type="text"
                        defaultValue={data.number_plate}
                        onChange={(e) =>
                            setData('number_plate', e.target.value)
                        }
                        fullWidth
                        error={!!errors['number_plate']}
                        helperText={errors['number_plate']}
                    />
                    {errors.number_plate && <span>{errors.number_plate}</span>}

                    <div>
                        <p className="mb-2 text-sm text-gray-500">
                            Carte grise
                        </p>
                        <FileUpload
                            expensesClaimId={expensesClaimId}
                            onUpload={(hasFiles) => setHasDocument(hasFiles)}
                        />
                    </div>

                    <div>
                        <p className="mb-2 text-sm text-gray-500">
                            Véhicule électrique ?
                        </p>
                        <div className="flex flex-row gap-2">
                            <Button
                                variant={
                                    data.electrical ? 'contained' : 'outlined'
                                }
                                onClick={() => setData('electrical', true)}
                                sx={
                                    data.electrical
                                        ? {
                                              backgroundColor: '#2D6A2D',
                                              '&:hover': {
                                                  backgroundColor: '#1F4F1F',
                                              },
                                          }
                                        : {
                                              color: '#2D6A2D',
                                              borderColor: '#2D6A2D',
                                              '&:hover': {
                                                  borderColor: '#1F4F1F',
                                                  backgroundColor: '#F0F7F0',
                                              },
                                          }
                                }
                            >
                                Oui
                            </Button>
                            <Button
                                variant={
                                    !data.electrical ? 'contained' : 'outlined'
                                }
                                onClick={() => setData('electrical', false)}
                                sx={
                                    !data.electrical
                                        ? {
                                              backgroundColor: '#2D6A2D',
                                              '&:hover': {
                                                  backgroundColor: '#1F4F1F',
                                              },
                                          }
                                        : {
                                              color: '#2D6A2D',
                                              borderColor: '#2D6A2D',
                                              '&:hover': {
                                                  borderColor: '#1F4F1F',
                                                  backgroundColor: '#F0F7F0',
                                              },
                                          }
                                }
                            >
                                Non
                            </Button>
                        </div>
                    </div>

                    <div className="flex flex-col items-end gap-4 rounded-xl bg-gray-50 p-4">
                        <Tooltip
                            title="Le coefficient de calcul de la valeur kilométrique se fait en fonction de la puissance de votre véhicule."
                            arrow
                        >
                            <Info className="h-4 w-4 cursor-pointer text-gray-400" />
                        </Tooltip>
                        {data.vehicle_type === 'voiture' ? (
                            <TextField
                                label="Puissance de la voiture"
                                slotProps={{ inputLabel: { shrink: true } }}
                                required
                                select
                                defaultValue={data.power || ''}
                                onChange={(e) =>
                                    setData('power', e.target.value)
                                }
                                fullWidth
                                error={!!errors['power']}
                                helperText={errors['power']}
                            >
                                <MenuItem value="3CV et moins">
                                    3CV et moins
                                </MenuItem>
                                <MenuItem value="4CV">4CV</MenuItem>
                                <MenuItem value="5CV">5CV</MenuItem>
                                <MenuItem value="6CV">6CV</MenuItem>
                                <MenuItem value="7CV et plus">
                                    7CV et plus
                                </MenuItem>
                            </TextField>
                        ) : (
                            <TextField
                                label="Puissance de la moto"
                                slotProps={{ inputLabel: { shrink: true } }}
                                required
                                select
                                defaultValue={data.power || ''}
                                onChange={(e) =>
                                    setData('power', e.target.value)
                                }
                                fullWidth
                                error={!!errors['power']}
                                helperText={errors['power']}
                            >
                                <MenuItem value="1 et 2CV">1 et 2CV</MenuItem>
                                <MenuItem value="3, 4 et 5CV">
                                    3, 4 et 5CV
                                </MenuItem>
                                <MenuItem value="6CV et plus">
                                    6CV et plus
                                </MenuItem>
                            </TextField>
                        )}
                        {errors.power && <span>{errors.power}</span>}
                    </div>

                    <div className="mt-6 mb-6 flex items-center justify-between rounded-xl bg-gray-50 p-4">
                        <div>
                            <p className="text-sm text-gray-500">
                                Barême d'abandon de frais
                            </p>
                            <p className="mt-1 text-xs text-gray-400">
                                {getPriceGiven()} € par Km
                            </p>
                        </div>
                    </div>

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
