import { useForm, router, Head } from '@inertiajs/react';
import Header from "@/layouts/Header"
import { Button, TextField } from "@mui/material"
import { DatePicker } from '@mui/x-date-pickers/DatePicker'
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider'
import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs'
import dayjs from 'dayjs'

interface InformationsProps {
    // Définir les propriétés nécessaires pour le composant Informations
    expensesClaim?: { id: number };
    informations?: {
        commission: string;
        object: string;
        dateStart: string;
        dateEnd: string;
    };
}

export default function Informations({ expensesClaim, informations }: InformationsProps) {

    const { data, setData, post, errors, reset } = useForm({
        commission: informations ? informations.commission : "",
        object: informations ? informations.object : "",
        dateStart: informations ? informations.dateStart : "",
        dateEnd: informations ? informations.dateEnd : "",
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
                            defaultValue={data.commission !== "" ? data.commission : ''}
                            onChange={(e) => setData('commission', e.target.value)}
                            fullWidth
                            size= "small"
                        />
                        {errors.commission && <span>{errors.commission}</span>}

                        <TextField
                            label="Objet de l'action"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={data.object !== "" ? data.object : ''}
                            onChange={(e) => setData('object', e.target.value)}
                            fullWidth
                            size= "small"
                        />
                        {errors.object && <span>{errors.object}</span>}

                        <div className="flex flex-row gap-2 mb-3">

                            <LocalizationProvider dateAdapter={AdapterDayjs}>

                                <DatePicker
                                    label="Date de début"
                                    value={data.dateStart ? dayjs(data.dateStart) : null}
                                    onChange={(newValue) => setData('dateStart', newValue ? newValue.format('YYYY-MM-DD') : '')}
                                    slotProps={{ textField: { fullWidth: true, size: 'small' } }}
                                />
                                {errors.dateStart && <span>{errors.dateStart}</span>}

                                <DatePicker
                                    label="Date de fin"
                                    value={data.dateEnd ? dayjs(data.dateEnd) : null}
                                    onChange={(newValue) => setData('dateEnd', newValue ? newValue.format('YYYY-MM-DD') : '')}
                                    slotProps={{ textField: { fullWidth: true, size: 'small' } }}
                                />
                                {errors.dateEnd && <span>{errors.dateEnd}</span>}

                            </LocalizationProvider>

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
                        
                    </div>
                
                </form>
            
            </div>

        </Header>
    )
}