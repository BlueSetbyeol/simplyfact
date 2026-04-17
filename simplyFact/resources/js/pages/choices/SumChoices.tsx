import Header from "@/layouts/Header";
import { Head, router } from "@inertiajs/react";

interface SumChoicesProps {
    steps:  string[];
    expensesClaimId: number;
}

const labels: Record<string, string> = {
    travel: 'Déplacements',
    accomodation: 'Hébergements',
    meal: 'Repas',
    other_expense: 'Autre frais',
}

function startFlow() {
    router.post('/flow/start', {
        steps,
        expensesClaimId
    })
}


export default function SumChoices({ steps }: SumChoicesProps){

    return(
        <Header>
            <Head title="Résumé des choix"></Head>
            <div className="bg-white rounded-2xl border border-gray-200 p-6 w-full max-w-xl">
                <h1 className="text-xl font-medium text-gray-900 mb-6">Vous allez faire une note de frais pour:</h1>

                <div className= "bg-gray-50 rounded-xl px-4 py-2 flex justify-between items-center mb-2">
                
                {steps.map((step, index) => (
                    <p key={index}>{labels[step]}</p>
                ))}

                </div>



            </div>

        </Header>
    )
    
}