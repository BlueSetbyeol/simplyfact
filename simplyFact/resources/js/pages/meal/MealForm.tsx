import { useForm, router } from '@inertiajs/react';
import { Button, TextField, styled } from '@mui/material';
import { CloudUploadIcon } from 'lucide-react';
import { useState } from 'react';

{
    /* Composant input caché visuellement pour le téléchargement de fichiers. */
}
const VisuallyHiddenInput = styled('input')({
    clip: 'rect(0 0 0 0)',
    clipPath: 'inset(50%)',
    height: 1,
    overflow: 'hidden',
    position: 'absolute',
    whiteSpace: 'nowrap',
    width: 1,
});

export default function MealForm() {
    const { data, setData, post, errors, reset } = useForm({
        number_of_meal: 0,
        total_price: 0,
        reimbursed_price: 0,
    });
    const totalRefund = Math.min(data.total_price, 25 * data.number_of_meal);

    function submitMeal(e: { preventDefault: () => void }) {
        e.preventDefault();
        setData('reimbursed_price', totalRefund);
        // POSTs to meal.store → saves → redirects to flow.return-parent → back here
        post('/expenses-claims/${expensesClaim.id}/meals', {
            onSuccess: () => reset(),
        });
    }

    function completeStep() {
        // POSTs to flow.complete-step → marks meal done → redirects to flow.next
        router.post('flow.complete-step');
    }

    /*Cette constante est utilisée pour stocker le document de preuve.*/
    const [proofDocument, setProofDocument] = useState<File[]>([]);

    return (
        <div className="flex min-h-screen items-center justify-center bg-gray-100 p-8">
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
                <h1 className="text-xl font-medium text-gray-900">Vos repas</h1>
                <p className="mt-1 mb-6 text-sm text-gray-500">
                    Renseignez vos dépenses de repas effectuées lors de votre
                    déplacement.
                </p>

                <hr className="mb-6 border-gray-100" />
                <form onSubmit={submitMeal}>
                    <div className="flex flex-col gap-5">
                        {/* Ces deux champs permettent à l'utilisateur de saisir le nombre de repas et le montant total dépensé. */}
                        <TextField
                            label="Nombre de repas"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={''}
                            onChange={(e) =>
                                setData(
                                    'number_of_meal',
                                    Number(e.target.value),
                                )
                            }
                            fullWidth
                        />
                        {errors.number_of_meal && (
                            <span>{errors.number_of_meal}</span>
                        )}
                        <TextField
                            label="Montant total"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={''}
                            onChange={(e) =>
                                setData('total_price', Number(e.target.value))
                            }
                            fullWidth
                        />
                        {errors.total_price && (
                            <span>{errors.total_price}</span>
                        )}
                    </div>

                    <hr className="my-6 border-gray-100" />

                    <div className="mb-6 flex items-center justify-between rounded-xl bg-gray-50 p-4">
                        {/* Cette section affiche le montant total remboursé, qui est calculé automatiquement en
                        fonction du montant total saisi par l'utilisateur.*/}
                        <div>
                            <p className="text-sm text-gray-500">
                                Total remboursé
                            </p>
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
                </form>
                <div>
                    <Button
                        component="label"
                        role={undefined}
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
                        {/* Ce champ de saisie est caché visuellement, mais il est accessible via le bouton "Document justificatif". Lorsque l'utilisateur clique sur ce bouton, il peut sélectionner un fichier à télécharger. Le fichier sélectionné est ensuite stocké dans l'état `proofDocument`. */}
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
                            <p
                                key={index}
                                className="mt-1 text-sm text-gray-500"
                            >
                                {file.name}
                            </p>
                        ))
                    ) : (
                        <p className="mt-2 text-sm text-gray-500">
                            Aucun document sélectionné
                        </p>
                    )}
                </div>

                <Button
                    variant="contained"
                    fullWidth
                    className="!mt-5"
                    sx={{
                        backgroundColor: '#2D6A2D',
                        '&:hover': { backgroundColor: '#1F4F1F' },
                    }}
                    onClick={completeStep}
                >
                    Suivant
                </Button>
            </div>
        </div>
    );
}
