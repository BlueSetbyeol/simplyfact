import { Head, router } from '@inertiajs/react';
import { Button } from '@mui/material';
import Header from '@/layouts/Header';

interface OtherExpensesProps {
    expensesClaimId: string;
    otherExpenses: {
        id: string;
        expense_name: string;
        total_price: number;
        reimbursed_price: number;
    }[];
}

export default function OtherExpenses({
    expensesClaimId,
    otherExpenses,
}: OtherExpensesProps) {
    function handleAdd(e: { preventDefault: () => void }) {
        e.preventDefault();
        router.get(`/expenses-claims/${expensesClaimId}/other-expenses/create`);
    }

    const totalReimbursed = otherExpenses.reduce(
        (sum, otherExpense) => sum + otherExpense.total_price,
        0,
    );

    function completeStep(e: { preventDefault: () => void }) {
        e.preventDefault();
        router.post(`/expenses-claims/${expensesClaimId}/flow/complete-step`);
    }

    return (
        <Header>
            <Head title="Déplacements"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-4">
                <div className="flex flex-row items-center justify-between">
                    <h1 className="text-xl font-medium text-gray-900">
                        Autres Frais
                    </h1>
                    <Button
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

                {otherExpenses.length === 0 ? (
                    <p className="mt-4 text-sm text-gray-400">
                        Aucun frais ajouté
                    </p>
                ) : (
                    otherExpenses.map((otherExpense) => (
                        <div
                            key={otherExpense.id}
                            className="mt-3 rounded-xl bg-gray-50 p-4"
                        >
                            <p className="text-sm font-medium">
                                {otherExpense.expense_name}
                            </p>
                            <p className="text-xs text-gray-500">
                                {otherExpense.total_price}€ payés
                            </p>
                        </div>
                    ))
                )}

                <div className="mt-6 mb-6 flex items-center justify-between rounded-xl bg-gray-50 p-4">
                    <p className="text-sm text-gray-500">
                        Total des frais à rembourser
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
