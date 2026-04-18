import { Head, useForm } from '@inertiajs/react';
import { Button } from '@mui/material';
import Header from '@/layouts/Header';

interface ClaimSummaryProps {
    expensesClaim?: {
        id: number;
        total_reimbursed: number;
        claimed_items: [
            travel: { id: number }[],
            accommodation: { id: number }[],
            meal: {
                id: number;
                number_of_meal: number;
                total_price: number;
                reimbursed_price: number;
            },
            other_expense: { id: number }[],
        ];
    } | null;
}
export default function ClaimSummary({ expensesClaim }: ClaimSummaryProps) {
    const labels: Record<string, string> = {
        travel: 'Déplacements',
        accommodation: 'Hébergements',
        meal: 'Repas',
        other_expense: 'Autre frais',
    };

    const expensesClaimed = [];

    function summaryOfAllClaim() {
        expensesClaim?.claimed_items.forEach((claim) => {
            expensesClaimed.push(claim);
        });
    }

    summaryOfAllClaim();

    const { post, processing } = useForm();

    function startFlow() {
        post(`/expenses-claims/${expensesClaim?.id}/flow/start`);
    }

    return (
        <Header>
            <Head title="Résumé des choix"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
                <h1 className="mb-6 text-xl font-medium text-gray-900">
                    Vous venez de déclarer les frais suivant:
                </h1>

                <div className="mb-2 flex flex-col rounded-xl bg-gray-50 px-4 py-2">
                    {expensesClaim?.claimed_items.map((item, index) => (
                        <article key={index}>
                            <p className="mb-1 text-gray-500">{labels[step]}</p>
                        </article>
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
