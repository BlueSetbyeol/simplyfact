import { useState } from "react"
import { Button, TextField, styled } from "@mui/material"
import { CloudUploadIcon } from "lucide-react"
import AppLayout from "@/layouts/AppLayout"
import { Head } from "@inertiajs/react"
import { useForm, router } from '@inertiajs/react';

const VisuallyHiddenInput = styled('input')({
    clip: 'rect(0 0 0 0)',
    clipPath: 'inset(50%)',
    height: 1,
    overflow: 'hidden',
    position: 'absolute',
    whiteSpace: 'nowrap',
    width: 1,
});

interface MealFormProps {
    expensesClaim: { id: number };
    meal: {
        id: number;
        number_of_meal: number;
        total_price: number;
        reimbursed_price: number;
    };
}

export default function MealForm({ expensesClaim, meal }: MealFormProps) {

    const { data, setData, post, errors, reset } = useForm({
        number_of_meal: meal ? meal.number_of_meal : 0,
        total_price: meal ? meal.total_price : 0,
        reimbursed_price: 0,
    });

    const totalRefund = Math.min(data.total_price, 25 * data.number_of_meal);

    function completeStep() {
        router.post('flow.complete-step');
    }

    function submitMeal(e: { preventDefault: () => void }) {
        e.preventDefault();
        setData('reimbursed_price', totalRefund);
        post(`/expenses-claims/${expensesClaim.id}/meals`, {
            onSuccess: () => {
                reset()
                completeStep()
            },
        });
    }

    const [proofDocument, setProofDocument] = useState<File[]>([]);

    return (
        <AppLayout>
            <Head title="Repas" />
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6">

                <h1 className="text-xl font-medium text-gray-900">Vos repas</h1>
                <p className="mt-1 mb-6 text-sm text-gray-500">
                    Renseignez vos dépenses de repas effectuées lors de votre déplacement.
                </p>

                <hr className="mb-6 border-gray-100" />

                <form onSubmit={submitMeal}>

                    <div className="flex flex-col gap-5">
                        <TextField
                            label="Nombre de repas"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={data.number_of_meal !== 0 ? data.number_of_meal : ''}
                            onChange={(e) => setData('number_of_meal', Number(e.target.value))}
                            fullWidth
                        />
                        {errors.number_of_meal && <span>{errors.number_of_meal}</span>}

                        <TextField
                            label="Montant total"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={data.total_price !== 0 ? data.total_price : ''}
                            onChange={(e) => setData('total_price', Number(e.target.value))}
                            fullWidth
                        />
                        {errors.total_price && <span>{errors.total_price}</span>}
                    </div>

                    <hr className="my-6 border-gray-100" />

                    <div className="mb-6 flex items-center justify-between rounded-xl bg-gray-50 p-4">
                        <div>
                            <p className="text-sm text-gray-500">Total remboursé</p>
                        </div>
                        <div className="text-right">
                            <p className="text-2xl font-medium text-gray-900">{totalRefund}€</p>
                            <p className="mt-1 text-xs text-gray-400">Plafond : 25 € par repas</p>
                        </div>
                    </div>

                    <div>
                        <Button
                            component="label"
                            variant="outlined"
                            fullWidth
                            startIcon={<CloudUploadIcon />}
                            sx={{
                                color: '#2D6A2D',
                                borderColor: '#2D6A2D',
                                '&:hover': {
                                    borderColor: '#1F4F1F',
                                    backgroundColor: '#F0F7F0',
                                },
                            }}
                        >
                            Document justificatif
                            <VisuallyHiddenInput
                                type="file"
                                onChange={(e) => {
                                    const files = Array.from(e.target.files || []);
                                    setProofDocument((prev) => [...prev, ...files]);
                                }}
                            />
                        </Button>
                        {proofDocument.length > 0 ? (
                            proofDocument.map((file, index) => (
                                <p key={index} className="mt-1 text-sm text-gray-500">
                                    {file.name}
                                </p>
                            ))
                        ) : (
                            <p className="mt-2 text-sm text-gray-500">Aucun document sélectionné</p>
                        )}
                    </div>

                    <Button
                        type="submit"
                        variant="contained"
                        fullWidth
                        className="!mt-5"
                        sx={{
                            backgroundColor: '#2D6A2D',
                            '&:hover': { backgroundColor: '#1F4F1F' },
                        }}
                    >
                        Suivant
                    </Button>

                </form>
            </div>
        </AppLayout>
    );
}