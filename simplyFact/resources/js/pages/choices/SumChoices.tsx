import { Head, useForm } from '@inertiajs/react';
import { Button } from '@mui/material';
import Header from '@/layouts/Header';

interface SumChoicesProps {
    steps: string[];
    expensesClaim: { id: number };
}

export default function SumChoices({ steps, expensesClaim }: SumChoicesProps) {
    const labels: Record<string, string> = {
        travel: 'Déplacements',
        accommodation: 'Hébergements',
        meal: 'Repas',
        other_expenses: 'Autre frais',
    };

    const { post, processing } = useForm();

    function startFlow() {
        post(`/expenses-claims/${expensesClaim.id}/flow/start`);
    }

    return (
        <Header>
            <Head title="Résumé des choix"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
                <h1 className="mb-6 text-xl font-medium text-gray-900">
                    Vous allez faire une note de frais pour:
                </h1>

                <div className="mb-2 flex flex-col rounded-xl bg-gray-50 px-4 py-2">
                    {steps.map((step, index) => (
                        <p className="mb-1 text-gray-500" key={index}>
                            {labels[step]}
                        </p>
                    ))}
                </div>

                <Button
                    onClick={startFlow}
                    disabled={processing}
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
            </div>
        </Header>
    );
}
