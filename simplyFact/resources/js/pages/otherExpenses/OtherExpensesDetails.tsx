import { Head, useForm } from '@inertiajs/react';
import { Button, TextField } from '@mui/material';
import { useState } from 'react';
import FileUpload from '@/components/FileUpload';
import Header from '@/layouts/Header';

interface OtherExpensesDetailsProps {
    expensesClaimId: string;
    otherExpense: {
        id: string;
        expense_name: string;
        total_price: number;
    };
}

export default function OtherExpensesDetails({
    expensesClaimId,
    otherExpense,
}: OtherExpensesDetailsProps) {
    const { data, setData, post, errors, reset } = useForm('CreateExpenses', {
        expense_name: otherExpense?.expense_name || '',
        total_price: otherExpense?.total_price || 0,
    });

    const [reimbursedPrice, setReimbursedPrice] = useState<number>(0);

    function handleSubmit(e: { preventDefault: () => void }) {
        e.preventDefault();
        post(`/expenses-claims/${expensesClaimId}/other-expenses`, {
            onSuccess: () => {
                reset();
            },
        });
    }

    const [hasDocument, setHasDocument] = useState(false);

    return (
        <Header>
            <Head title="Ajout déplacement"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-4">
                <div className="flex flex-col gap-4">
                    <h1 className="text-xl font-medium text-gray-900">
                        Ajout d'un autre frais
                    </h1>
                    <form onSubmit={handleSubmit}>
                        <div className="flex flex-col gap-5">
                            <TextField
                                label="Sujet de la dépense"
                                slotProps={{ inputLabel: { shrink: true } }}
                                type="text"
                                defaultValue={
                                    data.expense_name !== ''
                                        ? data.expense_name
                                        : ''
                                }
                                onChange={(e) => {
                                    setData('expense_name', e.target.value);
                                }}
                                fullWidth
                                error={!!errors['expense_name']}
                                helperText={errors['expense_name']}
                            />
                            {errors.expense_name && (
                                <span>{errors.expense_name}</span>
                            )}

                            <TextField
                                label="Montant total dépensé"
                                slotProps={{
                                    inputLabel: { shrink: true },
                                    htmlInput: {
                                        step: 0.01,
                                        min: 0,
                                    },
                                }}
                                type="number"
                                defaultValue={
                                    data.total_price !== 0
                                        ? data.total_price
                                        : ''
                                }
                                onChange={(e) => {
                                    setData(
                                        'total_price',
                                        Number(e.target.value),
                                    );
                                    setReimbursedPrice(Number(e.target.value));
                                }}
                                fullWidth
                                error={!!errors['total_price']}
                                helperText={errors['total_price']}
                            />
                            {errors.total_price && (
                                <span>{errors.total_price}</span>
                            )}
                        </div>
                        <div className="mt-6 mb-6 flex items-center justify-between rounded-xl bg-gray-50 p-4">
                            <p className="text-sm text-gray-500">
                                Total à rembourser
                            </p>
                            <p className="text-2xl font-medium text-gray-900">
                                {reimbursedPrice}€
                            </p>
                        </div>

                        <FileUpload
                            expensesClaimId={expensesClaimId}
                            onUpload={(hasFile) => setHasDocument(hasFile)}
                        />

                        <Button
                            type="submit"
                            disabled={!hasDocument}
                            variant="contained"
                            fullWidth
                            className="mt-5!"
                            sx={{
                                backgroundColor: '#2D6A2D',
                                '&:hover': { backgroundColor: '#1F4F1F' },
                            }}
                        >
                            Ajouter la dépense
                        </Button>
                    </form>
                </div>
            </div>
        </Header>
    );
}
