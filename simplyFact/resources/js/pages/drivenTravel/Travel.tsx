import { Head, router } from '@inertiajs/react';
import { Button } from '@mui/material';
import Header from '@/layouts/Header';

interface TravelProps {
    expensesClaimId: string;
    drivenTrips: {
        id: number;
        starting_city: string;
        starting_zip_code: number;
        ending_city: string;
        ending_zip_code: string;
        trip_type?: string;
        total_distance: number;
        total_price: number;
        total_distance_given?: number;
        total_price_given?: number;
        description: string;
    }[];
}

export default function Travel({ expensesClaimId, drivenTrips }: TravelProps) {
    const totalReimbursed = drivenTrips.reduce(
        (sum, trip) => sum + trip.total_price,
        0,
    );

    function handleClickAddTravel(e: { preventDefault: () => void }) {
        e.preventDefault();
        router.get(`/expenses-claims/${expensesClaimId}/vehicles/create`);
    }

    function completeStep(e: { preventDefault: () => void }) {
        e.preventDefault();
        router.post(`/expenses-claims/${expensesClaimId}/flow/complete-step`);
    }

    return (
        <Header>
            <Head title="Vos déplacements"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
                <div className="flex flex-row items-center justify-between">
                    <h1 className="text-xl font-medium text-gray-900">
                        Vos trajets
                    </h1>
                    <Button
                        variant="contained"
                        className="mt-5!"
                        sx={{
                            backgroundColor: '#2D6A2D',
                            '&:hover': { backgroundColor: '#1F4F1F' },
                        }}
                        onClick={handleClickAddTravel}
                    >
                        +
                    </Button>
                </div>

                {drivenTrips.length > 0 ? (
                    <div className="flex flex-row">
                        {drivenTrips.map((drivenTrip) => (
                            <div
                                key={drivenTrip.id}
                                className="mt-3 w-full rounded-xl bg-gray-50 p-4"
                            >
                                <p className="text-sm font-medium">
                                    De {drivenTrip.starting_city} à{' '}
                                    {drivenTrip.ending_city}
                                </p>

                                <p className="text-xs text-gray-500">
                                    {drivenTrip.total_price}€ payés -{' '}
                                    {drivenTrip.total_price_given}€ abandonnés
                                </p>

                                <p className="text-xs text-gray-500">
                                    {drivenTrip.description}
                                </p>
                            </div>
                        ))}
                    </div>
                ) : (
                    <p className="mt-4 text-sm text-gray-400">
                        Aucun trajet pour le moment
                    </p>
                )}

                <div className="mt-6 mb-6 flex items-center justify-between rounded-xl bg-gray-50 p-4">
                    <p className="text-sm text-gray-500">Total à rembourser</p>
                    <p className="text-2xl font-medium text-gray-900">
                        {totalReimbursed}€
                    </p>
                </div>

                <Button
                    variant="contained"
                    fullWidth
                    className="mt-5!"
                    sx={{
                        backgroundColor: '#2D6A2D',
                        '&:hover': { backgroundColor: '#1F4F1F' },
                    }}
                    onClick={completeStep}
                >
                    Suivant
                </Button>
            </div>
        </Header>
    );
}
