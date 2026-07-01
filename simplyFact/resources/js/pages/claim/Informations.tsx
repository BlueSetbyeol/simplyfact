import { useForm, Head } from '@inertiajs/react';
import { Button, TextField } from '@mui/material';
import Header from '@/layouts/Header';

interface InformationsProps {
    expensesClaim?: {
        committee_name: string;
        action_name: string;
        action_dates: string;
    } | null;
}

export default function Informations({ expensesClaim }: InformationsProps) {
    const { data, setData, post, errors, reset } = useForm('CreateClaim', {
        committee_name: expensesClaim?.committee_name || '',
        action_name: expensesClaim?.action_name || '',
        action_dates: expensesClaim?.action_dates || '',
    });

    function submitInformations(e: { preventDefault: () => void }) {
        e.preventDefault();
        post(`/expenses-claims`, {
            onSuccess: () => {
                reset();
            },
        });
    }

    return (
        <Header>
            <Head title="Informations complémentaires"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-4">
                <h1 className="mb-4 text-xl font-medium text-gray-900">
                    Informations complémentaires
                </h1>

                <hr className="mb-6 border-gray-100" />

                <form onSubmit={submitInformations}>
                    <div className="flex flex-col gap-3">
                        <TextField
                            label="Commission"
                            slotProps={{ inputLabel: { shrink: true } }}
                            type="text"
                            defaultValue={
                                data.committee_name !== ''
                                    ? data.committee_name
                                    : ''
                            }
                            onChange={(e) =>
                                setData('committee_name', e.target.value)
                            }
                            fullWidth
                            error={!!errors['committee_name']}
                            helperText={errors['committee_name']}
                        />
                        {errors.committee_name && (
                            <span>{errors.committee_name}</span>
                        )}

                        <TextField
                            label="Objet de l'action"
                            slotProps={{ inputLabel: { shrink: true } }}
                            type="text"
                            defaultValue={
                                data.action_name !== '' ? data.action_name : ''
                            }
                            onChange={(e) =>
                                setData('action_name', e.target.value)
                            }
                            fullWidth
                            error={!!errors['action_name']}
                            helperText={errors['action_name']}
                        />
                        {errors.action_name && (
                            <span>{errors.action_name}</span>
                        )}

                        <TextField
                            label="Dates de l'action"
                            slotProps={{ inputLabel: { shrink: true } }}
                            type="text"
                            defaultValue={
                                data.action_dates !== ''
                                    ? data.action_dates
                                    : ''
                            }
                            onChange={(e) =>
                                setData('action_dates', e.target.value)
                            }
                            fullWidth
                            error={!!errors['action_dates']}
                            helperText={errors['action_dates']}
                        />
                        {errors.action_dates && (
                            <span>{errors.action_dates}</span>
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
        </Header>
    );
}
