import { Head, router } from '@inertiajs/react';
import { Button } from '@mui/material';
import Header from '@/layouts/Header';

interface TravelProps {
    expensesClaimId: string;
    otherTrips: {
        id: number;
        expense_name: string;
        total_price: number;
    }[];
}

export default function Travel({ expensesClaimId, otherTrips }: TravelProps) {
    const totalReimbursed = otherTrips.reduce(
        (sum, trip) => sum + trip.total_price,
        0,
    );

    function handleClickAddTravel(e: { preventDefault: () => void }) {
        e.preventDefault();
        router.get(`/expenses-claims/${expensesClaimId}/other-travels/create`);
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
                        Vos trajets non conduits
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

                {otherTrips.length > 0 ? (
                    otherTrips.map((otherTrip) => (
                        <div
                            key={otherTrip.id}
                            className="mt-3 w-full rounded-xl bg-gray-50 p-4"
                        >
                            <p className="text-sm font-medium">
                                {otherTrip.expense_name}
                            </p>

                            <p className="text-xs text-gray-500">
                                {otherTrip.total_price}€ payés
                            </p>
                        </div>
                    ))
                ) : (
                    <p className="mt-4 text-sm text-gray-400">
                        Aucun trajet pour le moment
                    </p>
                )}

                <div className="mt-6 mb-6 flex items-center justify-between rounded-xl bg-gray-50 p-4">
                    <p className="text-sm text-gray-500">
                        Total des trajets à rembourser
                    </p>
                    <p className="text-2xl font-medium text-gray-900">
                        {totalReimbursed.toFixed(2)}€
                    </p>
                </div>

                <Button
                    variant="contained"
                    fullWidth
                    className="mt-5!"
                    disabled={otherTrips.length <= 0}
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
