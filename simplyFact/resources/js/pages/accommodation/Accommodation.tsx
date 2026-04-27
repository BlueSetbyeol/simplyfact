import { Head, useForm } from "@inertiajs/react";
import Header from '@/layouts/Header';
import { Button } from "@mui/material";

interface AccomodationPropos {
    expensesClaim: {id: string};
    accommodation: {
        accomodation_type: string;
        nb_of_night: number;
        total_price: number;
        reimbursed_price: number;
    }[];
} 

export default function Accomodation({ expensesClaim, accommodation}: AccomodationPropos) {

    /* const totalRefund = accommodation.reduce((sum, item) => sum + item.reimbursed_price, 0)
    et dans le JSX :
    {accommodation.length === 0 && (
        <p className="text-sm text-gray-400 mt-4">Aucun hébergement ajouté</p>
    )} */

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
                            }}>+
                    </Button>
                </div>

                <Button
                        type="submit"
                        variant="contained"
                        fullWidth
                        className="mt-5!"
                        sx={{
                            backgroundColor: '#2D6A2D',
                            '&:hover': { backgroundColor: '#1F4F1F' },
                        }}
                    >
                        Suivant
                </Button>

                {accommodation.map((accommodation, index) => (
                    <div key={index} className="bg-gray-50 rounded-xl p-4 mt-3">
                        <p className="text-sm font-medium">{accommodation.accomodation_type}</p>
                        <p className="text-xs text-gray-500">{accommodation.nb_of_night} nuit(s) — {accommodation.reimbursed_price}€</p>
                    </div>
                ))}

                

            </div>

        </Header>

    )

}