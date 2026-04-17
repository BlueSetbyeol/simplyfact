import Header from "@/layouts/Header";
import { Head, router } from "@inertiajs/react";
import { Button } from "@mui/material";

interface SumChoicesProps {
    steps:  string[];
    expensesClaimId: number;
}

export default function SumChoices(
    { steps, expensesClaimId }: SumChoicesProps){

    const labels: Record<string, string> = {
        travel: 'Déplacements',
        accommodation: 'Hébergements',
        meal: 'Repas',
        other_expense: 'Autre frais',
    }

    function startFlow() {
        router.post('/flow/start', {
            steps,
            expensesClaimId
        })
    }

    return(
        <Header>
            <Head title="Résumé des choix"></Head>
            <div className="bg-white rounded-2xl border border-gray-200 p-6 w-full max-w-xl">
                <h1 className="text-xl font-medium text-gray-900 mb-6">Vous allez faire une note de frais pour:</h1>

                <div className= "bg-gray-50 rounded-xl px-4 py-2 flex flex-col mb-2">
                    {steps.map((step, index) => (
                        <p className="text-gray-500 mb-1" key={index}>{labels[step]}</p>
                    ))}
                </div>

                <form onSubmit={startFlow}>
                    <Button 
                        type="submit"
                        variant="contained"
                        fullWidth
                        className="!mt-6"
                        sx={{
                            backgroundColor: '#2D6A2D',
                            '&:hover': { backgroundColor: '#1F4F1F' },
                        }}
                    >
                        Commencer
                    </Button>
                </form>



            </div>

        </Header>
    )
    
}