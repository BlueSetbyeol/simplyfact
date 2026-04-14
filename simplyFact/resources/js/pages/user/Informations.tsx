import { useForm, router, Head } from '@inertiajs/react';
import Header from "@/layouts/Header"
import { Button, TextField } from "@mui/material"

interface InformationsProps {
    // Définir les propriétés nécessaires pour le composant Informations
    expensesClaim?: { id: number };
    informations?: {
        commitee_name: string;
        action_name: string;
        action_dates: string;
    };
}

export default function Informations({ expensesClaim, informations }: InformationsProps) {

    const { data, setData, post, errors, reset } = useForm({
        commitee_name: informations ? informations.commitee_name : "",
        action_name: informations ? informations.action_name : "",
        action_dates: informations ? informations.action_dates : "",
    });

    function completeStep() {
        router.post('flow.complete-step');
    }

    function submitInformations(e: { preventDefault: () => void }) {
        e.preventDefault();
        post(`/expenses-claims/${expensesClaim?.id}/informations`, {
            onSuccess: () => {
                reset()
                completeStep()
            },
        });
    }

    return(
        <Header>

            <Head title="Informations complémentaires"></Head>
            <div className="bg-white rounded-2xl border border-gray-200 p-6 w-full max-w-xl">
                
                <h1 className="text-xl font-medium text-gray-900 mb-6">Informations complémentaires</h1>

                <hr className="mb-6 border-gray-100" />

                <form onSubmit={submitInformations}>

                    <div className="flex flex-col gap-5">

                        <TextField
                            label="Commission"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={data.commitee_name !== "" ? data.commitee_name : ''}
                            onChange={(e) => setData('commitee_name', e.target.value)}
                            fullWidth
                            size= "small"
                        />
                        {errors.commitee_name && <span>{errors.commitee_name}</span>}

                        <TextField
                            label="Objet de l'action"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={data.action_name !== "" ? data.action_name : ''}
                            onChange={(e) => setData('action_name', e.target.value)}
                            fullWidth
                            size= "small"
                        />
                        {errors.action_name && <span>{errors.action_name}</span>}

                        <TextField
                            label="Date de début"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={data.action_dates !== ""? data.action_dates : ''}
                            onChange={(e) => setData('action_dates', e.target.value)}
                            fullWidth
                            size= "small"
                        />
                        {errors.action_dates && <span>{errors.action_dates}</span>}


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

        </Header>
    )
}