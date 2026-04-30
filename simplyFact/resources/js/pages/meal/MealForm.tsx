import { Head } from '@inertiajs/react';
import { useForm } from '@inertiajs/react';
import { Button, TextField } from '@mui/material';
import { useState } from 'react';
import FileUpload from '@/components/FileUpload';
import Header from '@/layouts/Header';

interface MealFormProps {
    expensesClaimId: string;
    meal: {
        id: number;
        number_of_meal: number;
        total_price: number;
    } | null;
}

export default function MealForm({ expensesClaimId, meal }: MealFormProps) {
    const { data, setData, post, errors, reset } = useForm('CreateMeal', {
        number_of_meal: meal?.number_of_meal || 0,
        total_price: meal?.total_price || 0,
    });

    const totalRefund = Math.min(data.total_price, 25 * data.number_of_meal);

    function submitMeal(e: { preventDefault: () => void }) {
        e.preventDefault();
        post(`/expenses-claims/${expensesClaimId}/meals`, {
            onSuccess: () => {
                reset();
            },
        });
    }
 
    const [hasDocument, setHasDocument] = useState(false)

    return (
        <Header>
            <Head title="Repas" />
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-4">
                <h1 className="text-xl font-medium text-gray-900">Vos repas</h1>
                <p className="mt-1 mb-6 text-sm text-gray-500">
                    Renseignez vos dépenses de repas effectuées lors de votre
                    déplacement.
                </p>

                <hr className="mb-6 border-gray-100" />

                <form onSubmit={submitMeal}>
                    <div className="flex flex-col gap-5">
                        <TextField
                            label="Nombre de repas"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={
                                data.number_of_meal !== 0
                                    ? data.number_of_meal
                                    : ''
                            }
                            onChange={(e) =>
                                setData(
                                    'number_of_meal',
                                    Number(e.target.value),
                                )
                            }
                            fullWidth
                            error={!!errors['number_of_meal']}
                            helperText={errors['number_of_meal']}
                        />
                        {errors.number_of_meal && (
                            <span>{errors.number_of_meal}</span>
                        )}

                        <TextField
                            label="Montant total"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={
                                data.total_price !== 0 ? data.total_price : ''
                            }
                            onChange={(e) =>
                                setData('total_price', Number(e.target.value))
                            }
                            fullWidth
                            error={!!errors['total_price']}
                            helperText={errors['total_price']}
                        />
                        {errors.total_price && (
                            <span>{errors.total_price}</span>
                        )}
                    </div>

                    <hr className="my-6 border-gray-100" />

                    <div className="mb-6 flex items-center justify-between rounded-xl bg-gray-50 p-4">
                        <div>
                            <p className="text-sm text-gray-500">
                                Total remboursé
                            </p>
                            <p className="text-xs text-gray-400 mt-1">{data.number_of_meal} repas x 25€ = {data.number_of_meal * 25}€ max</p>
                            <p className='text-xs text-gray-400'>Montant dépensé: {data.total_price}</p>
                        </div>
                        <div className="text-right">
                            <p className="text-2xl font-medium text-gray-900">
                                {totalRefund}€
                            </p>
                            <p className="mt-1 text-xs text-gray-400">
                                Plafond : 25 € par repas
                            </p>
                        </div>
                    </div>

                    <FileUpload 
                        expensesClaimId={expensesClaimId}
                        onUpload={(hasFiles) => setHasDocument(hasFiles)}
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
                        Suivant
                    </Button>
                </form>
            </div>
        </Header>
    );
}
