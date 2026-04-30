import { Head } from '@inertiajs/react';
import { useForm } from '@inertiajs/react';
import { Button, TextField } from '@mui/material';
import Header from '@/layouts/Header';

interface TrainingExpenseProps {
    expensesClaimId: string;
    trainingExpense: {
        id: number;
        nb_days_of_training: number;
    } | null;
}

export default function TrainingExpense({
    expensesClaimId,
    trainingExpense,
}: TrainingExpenseProps) {
    const { data, setData, post, errors, reset } = useForm({
        nb_days_of_training: trainingExpense?.nb_days_of_training || 0,
    });

    const price_per_day = 21.3;
    const max_reimbursed = 149.1;

    const totalRefund = Math.min(
        data.nb_days_of_training * price_per_day,
        max_reimbursed,
    ).toFixed(2);

    function submitMeal(e: { preventDefault: () => void }) {
        e.preventDefault();
        post(`/expenses-claims/${expensesClaimId}/training-expenses`, {
            onSuccess: () => {
                reset();
            },
        });
    }

    return (
        <Header>
            <Head title="Repas" />
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-4">
                <h1 className="text-xl font-medium text-gray-900">
                    Votre stage
                </h1>
                <p className="mt-1 mb-6 text-sm text-gray-500">
                    Renseignez la durée (en jour) de votre stage
                </p>

                <hr className="mb-6 border-gray-100" />

                <form onSubmit={submitMeal}>
                    <div className="flex flex-col gap-5">
                        <TextField
                            label="Nombre de jour"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={
                                data.nb_days_of_training !== 0
                                    ? data.nb_days_of_training
                                    : ''
                            }
                            onChange={(e) =>
                                setData(
                                    'nb_days_of_training',
                                    Number(e.target.value),
                                )
                            }
                            fullWidth
                            error={!!errors['nb_days_of_training']}
                            helperText={errors['nb_days_of_training']}
                        />
                        {errors.nb_days_of_training && (
                            <span>{errors.nb_days_of_training}</span>
                        )}
                    </div>
                    <hr className="my-6 border-gray-100" />
                    <div className="mb-6 flex items-center justify-between rounded-xl bg-gray-50 p-4">
                        <div>
                            <p className="text-sm text-gray-500">
                                Total de la compensation
                            </p>
                            {/* <p className="mt-1 text-xs text-gray-400">
                                {data.nb_days_of_training} * {price_per_day}
                            </p> */}
                        </div>
                        <div className="text-right">
                            <p className="text-2xl font-medium text-gray-900">
                                {totalRefund}€
                            </p>
                            <p className="mt-1 text-xs text-gray-400">
                                Plafond : 149,10 € par stage
                            </p>
                        </div>
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
                </form>
            </div>
        </Header>
    );
}
