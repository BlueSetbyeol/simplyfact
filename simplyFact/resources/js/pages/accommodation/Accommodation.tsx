import { Head, router } from "@inertiajs/react";
import { Button } from "@mui/material";
import Header from '@/layouts/Header';

interface AccomodationProps {
    expensesClaim: {id: string};
    accommodations: {
        id: number;
        accomodation_type: string;
        nb_of_night: number;
        total_price: number;
        reimbursed_price: number;
    }[];
} 

export default function Accomodation({ expensesClaim = {id: ''}, accommodations = []}: AccomodationProps) {

    const totalReimbursed = accommodations.reduce((sum, accomodation) => sum + accomodation.reimbursed_price, 0)

    function handleClick() {
        router.get(`/expenses-claims/${expensesClaim.id}/accommodation-details`)
    }

    function completeStep() {
        router.post('/flow/complete-step')
    }

    return(
        <Header>
            <Head title="Déplacements"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
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

    )

}