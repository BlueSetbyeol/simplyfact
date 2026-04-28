import { Head, router } from '@inertiajs/react';
import { Button } from '@mui/material';
import Header from '@/layouts/Header';

interface AccommodationProps {
    expensesClaimId: string;
    accommodations: {
        id: string;
        accommodation_type: string;
        nb_of_night: number;
        total_price: number;
        reimbursed_price: number;
    }[];
}

export default function Accommodation({
    expensesClaimId,
    accommodations,
}: AccommodationProps) {
    function handleAdd(e: { preventDefault: () => void }) {
        e.preventDefault();
        router.get(`/expenses-claims/${expensesClaimId}/accommodations/create`);
    }

    const totalReimbursed = accommodations.reduce(
        (sum, accommodation) => sum + accommodation.reimbursed_price,
        0,
    );

    function completeStep(e: { preventDefault: () => void }) {
        e.preventDefault();
        router.post(`/expenses-claims/${expensesClaimId}/flow/complete-step`);
    }

    return (
        <Header>
            <Head title="Déplacements"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
<<<<<<< HEAD
                <div className="flex flex-row justify-between items-center">
                    <h1 className="text-xl font-medium text-gray-900">Vos nuits en hébergements</h1>
                    <Button 
                            variant="contained"
                            className="mt-5!"
                            sx={{
                                backgroundColor: '#2D6A2D',
                                '&:hover': { backgroundColor: '#1F4F1F' },
                            }}
                            onClick={handleClick}>
                                +
                    </Button>
                </div>

                {accommodations.length === 0 ? (
                    <p className="text-sm text-gray-400 mt-4">Aucun hébergement ajouté</p>
                ) : (
                    accommodations.map((accomodation) => (
                        <div key={accomodation.id} className="bg-gray-50 rounded-xl p-4 mt-3">
                            <p className="text-sm font-medium">{accomodation.accomodation_type}</p>
                            <p className="text-xs text-gray-500">{accomodation.nb_of_night} nuit(s) - {accomodation.total_price}€ payés - {accomodation.reimbursed_price}€ remboursés</p>
                        </div>
                    ))
                )}

                <div className="bg-gray-50 rounded-xl p-4 flex justify-between items-center mt-6 mb-6">
                    <p className="text-sm text-gray-500">Total des hébergements à rembourser</p>
                    <p className="text-2xl font-medium text-gray-900">{totalReimbursed}€</p>
                </div>

                <Button
=======
                <div className="flex flex-row items-center justify-between">
                    <h1 className="text-xl font-medium text-gray-900">
                        Vos nuits en hébergements
                    </h1>
                    <Button
>>>>>>> dev
                        variant="contained"
                        className="mt-5!"
                        sx={{
                            backgroundColor: '#2D6A2D',
                            '&:hover': { backgroundColor: '#1F4F1F' },
                        }}
                        onClick={handleAdd}
                    >
                        +
                    </Button>
                </div>

                {accommodations.length === 0 ? (
                    <p className="mt-4 text-sm text-gray-400">
                        Aucun hébergement ajouté
                    </p>
                ) : (
                    accommodations.map((accommodation) => (
                        <div
                            key={accommodation.id}
                            className="mt-3 rounded-xl bg-gray-50 p-4"
                        >
                            <p className="text-sm font-medium">
                                {accommodation.accommodation_type}
                            </p>
                            <p className="text-xs text-gray-500">
                                {accommodation.nb_of_night} nuit(s) -{' '}
                                {accommodation.total_price}€ payés -{' '}
                                {accommodation.reimbursed_price}€ remboursés
                            </p>
                        </div>
                    ))
                )}

                <div className="mt-6 mb-6 flex items-center justify-between rounded-xl bg-gray-50 p-4">
                    <p className="text-sm text-gray-500">
                        Total des hébergements à rembourser
                    </p>
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
