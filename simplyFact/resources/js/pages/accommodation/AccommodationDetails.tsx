import { Head, router, useForm } from '@inertiajs/react';
import { Button, FormControl, InputLabel, MenuItem, Select, TextField } from '@mui/material';
import FileUpload from '@/components/FileUpload';
import Header from '@/layouts/Header';

interface AccomodationDetailsProps {
    expensesClaim: {id: string};
}

export default function AccomodationDetails({ expensesClaim }: AccomodationDetailsProps) {

    const { data, setData, post, errors, reset } = useForm({
            accommodation_type: 'Hôtel province hors coeur de ville',
            nb_of_night: 0,
            total_price: 0,
            reimbursed_price: 0,
    });

    const ceilings: Record<string, number> = {
        "Hôtel province hors coeur de ville": 70,
        "Hôtel province coeur de ville": 90,
        "Hôtel Lyon": 100,
        "Hôtel Paris": 150,
    }

    const ceiling = ceilings[data.accommodation_type] ?? 0

    const totalRefund = Math.min(data.total_price, ceiling * data.nb_of_night)

    function handleSubmit(e: {preventDefault : () => void}) {
        e.preventDefault();
        setData('reimbursed_price', totalRefund);
        post(`/expenses-claims/${expensesClaim?.id}/accomodation-details`, {
            onSuccess: () => {
                reset();
                router.get(`/expenses-claims/${expensesClaim?.id}/accomodation`)
            },
        });
    }

    return(
        <Header>
            <Head title="Ajout déplacement"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
                <div className="flex flex-col gap-4">
                    <h1 className="text-xl font-medium text-gray-900">Ajout d'un hébergement</h1>
                    <form onSubmit={handleSubmit}>
                        <div className="flex flex-col gap-5">
                           <FormControl fullWidth>
                                <InputLabel shrink>Type de logement</InputLabel>
                                <Select
                                    value={data.accommodation_type}
                                    onChange={(e) => setData('accommodation_type', e.target.value)}
                                    label="Type de logement"
                                >
                                    <MenuItem value="Hôtel province hors coeur de ville">Hôtel province hors cœur de ville</MenuItem>
                                    <MenuItem value="Hôtel province coeur de ville">Hôtel province cœur de ville</MenuItem>
                                    <MenuItem value="Hôtel Lyon">Hôtel Lyon</MenuItem>
                                    <MenuItem value="Hôtel Paris">Hôtel Paris</MenuItem>

                                </Select>
                            </FormControl>
                            <TextField
                                label="Nombre de nuits"
                                slotProps={{ inputLabel: { shrink: true } }}
                                defaultValue={data.nb_of_night}
                                onChange={(e) => {
                                    setData('nb_of_night', Number(e.target.value))
                                }}
                                fullWidth
                                error={!!errors['nb_of_night']}
                                helperText={errors['nb_of_night']}
                            />
                            <TextField
                                label="Montant total dépensé"
                                slotProps={{ inputLabel: { shrink: true } }}
                                defaultValue={data.total_price}
                                onChange={(e) => {
                                    setData('total_price', Number(e.target.value))
                                }}
                                fullWidth
                                error={!!errors['total_price']}
                                helperText={errors['total_price']}
                            />
                        </div>
                        <div className="bg-gray-50 rounded-xl p-4 flex justify-between items-center mt-6 mb-6">
                            <div>
                                <p className="text-sm text-gray-500">Total à rembourser</p>
                                <p className="text-xs text-gray-400 mt-1">Plafond: {ceiling}€ par nuit</p>
                            </div>
                            <p className="text-2xl font-medium text-gray-900">{totalRefund}€</p>
                        </div>

                        <FileUpload expensesClaimId={expensesClaim?.id} />

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
                            Ajouter l'hébergement
                        </Button>
                    </form>
                </div>
            </div>

        </Header>
    )
}
