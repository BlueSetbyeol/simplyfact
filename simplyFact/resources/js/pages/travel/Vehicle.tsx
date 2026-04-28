import { Head, router, useForm } from "@inertiajs/react";
import { Button, TextField } from "@mui/material";
import FileUpload from "@/components/FileUpload";
import Header from '@/layouts/Header';

interface VehicleProps {
    expensesClaim: {id: string};
    vehicle?: {
        id: string,
        vehicule_type: string,
        electrical: boolean,
        power: string,
        price_given: number,
        number_plate: string,
        legal_document?: string,
    },
    modes: string[]
}

export default function Vehicle({expensesClaim = {id: ''}, vehicle, modes = []} : VehicleProps) {

    const { data, setData, post, errors, reset } = useForm({
        vehicule_type: modes.includes('Voiture') ? 'Voiture' : 'Moto',
        electrical: vehicle ? vehicle.electrical : false,
        power: vehicle ? vehicle.power : '',
        number_plate: vehicle ? vehicle.number_plate : '',
        legal_document: vehicle ? vehicle.legal_document : ''
    });

    function handleSubmit(e: {preventDefault: () => void}) {
        e.preventDefault();
        post(`/expenses-claims/${expensesClaim?.id}/vehicles`, {
            onSuccess: () => {
                reset();
                router.get(`/expenses-claims/${expensesClaim?.id}/driven-trips`, {modes})
            }
        })
    }
    
    return(
        <Header>
            <Head title="Votre véhicule"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
                <h1 className="text-xl font-medium text-gray-900 mb-2">Identification du véhicule</h1>
                <hr className="border-gray-100 mb-4" />

                <form className="flex flex-col gap-4" onSubmit={handleSubmit}>

                    <TextField
                        label="Plaque d'immatriculation"
                        slotProps={{ inputLabel: { shrink: true } }}
                        defaultValue={data.number_plate}
                        onChange={(e) => setData('number_plate', e.target.value)}
                        fullWidth
                        size="small"
                        error={!!errors['number_plate']}
                        helperText={errors['number_plate']}
                    />

                    <div>
                        <p className="text-sm text-gray-500 mb-2">Carte grise</p>
                        <FileUpload expensesClaimId={expensesClaim?.id} />
                    </div>

                    <div>
                        <p className="text-sm text-gray-500 mb-2">Véhicule électrique ?</p>
                        <div className="flex flex-row gap-2">
                            <Button
                                variant={data.electrical ? 'contained' : 'outlined'}
                                onClick={() => setData('electrical', true)}
                                sx={data.electrical ? {
                                    backgroundColor: '#2D6A2D',
                                    '&:hover': { backgroundColor: '#1F4F1F' },
                                } : {
                                    color: '#2D6A2D',
                                    borderColor: '#2D6A2D',
                                    '&:hover': { borderColor: '#1F4F1F', backgroundColor: '#F0F7F0' },
                                }}
                            >
                                Oui
                            </Button>
                            <Button
                                variant={!data.electrical ? 'contained' : 'outlined'}
                                onClick={() => setData('electrical', false)}
                                sx={!data.electrical ? {
                                    backgroundColor: '#2D6A2D',
                                    '&:hover': { backgroundColor: '#1F4F1F' },
                                } : {
                                    color: '#2D6A2D',
                                    borderColor: '#2D6A2D',
                                    '&:hover': { borderColor: '#1F4F1F', backgroundColor: '#F0F7F0' },
                                }}
                            >
                                Non
                            </Button>
                        </div>
                    </div>

                    <TextField
                        label="Puissance du véhicule"
                        slotProps={{ inputLabel: { shrink: true } }}
                        defaultValue={data.power}
                        onChange={(e) => setData('power', e.target.value)}
                        fullWidth
                        size="small"
                        error={!!errors['power']}
                        helperText={errors['power']}
                    />

                    <Button
                        type="submit"
                        variant="contained"
                        fullWidth
                        className="!mt-2"
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