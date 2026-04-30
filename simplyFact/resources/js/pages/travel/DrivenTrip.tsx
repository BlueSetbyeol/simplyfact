import { Head, router, useForm } from "@inertiajs/react"
import { Button, Checkbox, FormControlLabel, TextField, Tooltip } from "@mui/material";
import { Info } from "lucide-react";
import Header from '@/layouts/Header';

interface DrivenTripsProps {
    expensesClaim: {id: string},
    drivenTrip?: {
        id: string,
        starting_city: string,
        starting_zip_code: number,
        ending_city: string,
        ending_zip_code: number,
        trip_type?: string,
        total_distance: number,
        total_price: number,
        total_distance_given?: number,
        total_price_given?: number,
        description?: string,
    },
    modes: string[],
}

export default function DrivenTrips({expensesClaim = {id: ''}, drivenTrip, modes = []}: DrivenTripsProps) {

    const {data, setData, post, errors, reset} = useForm({
        starting_city: drivenTrip ? drivenTrip.starting_city : '',
        starting_zip_code: drivenTrip ? Number(drivenTrip.starting_zip_code) : 0,
        ending_city: drivenTrip ? drivenTrip.ending_city : '',
        ending_zip_code: drivenTrip ? Number(drivenTrip.ending_zip_code) : 0,
        trip_type: drivenTrip ? drivenTrip.trip_type : '',
        total_distance: drivenTrip ? drivenTrip.total_distance : 0,
        total_price: 0,
        total_distance_given: 0,
        total_price_given: 0,
        description: drivenTrip ? drivenTrip.description : '',
    })

    const rates: Record<string, number> = {
        'Voiture': 0.36,
        'Moto': 0.14,
        'Covoiturage': 0.40,
        'Stage': 0.40,
    }

    const vehicleType = modes.includes('Voiture') ? 'Voiture' : 'Moto'

    const rate = (data.trip_type === 'Covoiturage' || data.trip_type === 'Stage') 
        ? 0.40 
        : rates[vehicleType]

    const totalPrice = rate * data.total_distance

    const totalAbandonned = rate * data.total_distance_given

    const totalFinal = totalPrice - totalAbandonned

    function handleSubmit(e: {preventDefault: () => void}) {
        e.preventDefault();
        post(`/expenses-claims/${expensesClaim?.id}/driven-trips`, {
            onBefore: () => {
                setData({
                    ...data,
                    total_price: totalFinal,
                    total_price_given: totalAbandonned,
                })
            },
            onSuccess: () => {
                reset();
                // Si l'utilisateur a choisi d'autres trajets
                const hasOtherModes = modes.some(mode => ['Train (2nd classe)', 'Transport en commun', 'Avion (2nd classe)', 'Péage, parking, taxis'].includes(mode))

                if (modes.includes('Moto') && vehicleType === 'Voiture') {
                    router.get(`/expenses-claim/${expensesClaim?.id}/driven-trip`, {
                        modes,
                        vehicleType: 'moto'
                    })
                } else if (hasOtherModes) {
                    router.get(`/expenses-claim/${expensesClaim?.id}/other-trips`, {modes})
                } else {
                    router.get(`/expenses-claim/${expensesClaim?.id}/travel`)
                }
            }
        })
    }

    return(
        <Header>
            <Head title="Trajet conduit"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-4">

                <h1 className="text-xl font-medium text-gray-900 mb-2">Trajet conduit</h1>

                <hr className="border-gray-100 mb-8" />

                <form className="flex flex-col gap-4" onSubmit={handleSubmit}>
                
                    <div className="flex flex-row gap-2">

                        <TextField
                            label="Ville de départ"
                            required
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={data.starting_city}
                            onChange={(e) => setData('starting_city', e.target.value)}
                            fullWidth
                            error={!!errors['starting_city']}
                            helperText={errors['starting_city']}
                        />

                        <TextField
                            label="Code postal"
                            required
                            type="text"
                            slotProps={{ 
                                inputLabel: { shrink: true },
                                htmlInput: { maxLength: 5, minLength: 5}
                             }}
                            defaultValue={data.starting_zip_code}
                            onChange={(e) => setData('starting_zip_code', Number(e.target.value))}
                            error={!!errors['starting_zip_code']}
                            helperText={errors['starting_zip_code']}
                        />

                    </div>

                    <div className="flex flex-row gap-2">

                        <TextField
                            label="Ville d'arrivée"
                            required
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={data.ending_city}
                            onChange={(e) => setData('ending_city', e.target.value)}
                            fullWidth
                            error={!!errors['ending_city']}
                            helperText={errors['ending_city']}
                        />

                        <TextField
                            label="Code postal"
                            required
                            type="text"
                            slotProps={{ 
                                inputLabel: { shrink: true },
                                htmlInput: { maxLength: 5, minLength: 5}
                             }}
                            defaultValue={data.ending_zip_code}
                            onChange={(e) => setData('ending_zip_code', Number(e.target.value))}
                            error={!!errors['ending_zip_code']}
                            helperText={errors['ending_zip_code']}
                        />

                    </div>

                    <TextField
                            label="Description"
                            multiline
                            rows={3}
                            type="text"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={data.description}
                            onChange={(e) => setData('description', e.target.value)}
                    />

                    <div className="flex flex-col">

                        {modes.includes('Voiture') && (
                            <FormControlLabel
                                control={
                                    <Checkbox
                                        checked={data.trip_type === 'Covoiturage'}
                                        onChange={() => setData('trip_type',
                                            data.trip_type === 'Covoiturage' ? '' : 'Covoiturage'
                                        )}
                                        sx={{ color: '#2D6A2D', '&.Mui-checked': { color: '#2D6A2D' } }}
                                    />
                                }
                                label="Covoiturage, remorque, salarié"
                                slotProps={{ typography: { className: "text-sm text-gray-700" } }}
                            />
                        )}

                        <FormControlLabel
                            control={
                                <Checkbox
                                    checked={data.trip_type === 'Stage'}
                                    onChange={() => setData('trip_type',
                                        data.trip_type === 'Stage' ? '' : 'Stage'
                                    )}
                                    sx={{ color: '#2D6A2D', '&.Mui-checked': { color: '#2D6A2D' } }}
                                />
                            }
                            label="Stage fédéral"
                            slotProps={{ typography: { className: "text-sm text-gray-700" } }}
                        />

                    </div>

                    <TextField
                            label="Total des km parcourus"
                            required
                            type="text"
                            slotProps={{ 
                                inputLabel: { shrink: true },
                                htmlInput: { maxLength: 5, minLength: 5}
                             }}
                            defaultValue={data.total_distance}
                            onChange={(e) => setData('total_distance', Number(e.target.value))}
                            error={!!errors['total_distance']}
                            helperText={errors['total_distance']}
                    />

                    <div className="flex flex-col gap-4 rounded-xl bg-gray-50 p-4">

                        <div className="flex items-baseline gap-2">
                             <p className="text-sm text-gray-500 font-medium mb-4">OPTIONNEL: Déclaration de km en abandon</p>
                            <Tooltip
                                title="Les km en abandon seront pris en compte en tant que dons à l'association pour les impôts."
                                arrow
                            >
                                <Info className="w-4 h-4 text-gray-400 cursor-pointer"/>
                            </Tooltip>
                        </div>
                        <TextField
                            label="Total des km abandonnés"
                            type="text"
                            slotProps={{ 
                                inputLabel: { shrink: true },
                                htmlInput: { maxLength: 5, minLength: 5}
                             }}
                            defaultValue={data.total_distance_given}
                            onChange={(e) => setData('total_distance_given', Number(e.target.value))}
                            error={!!errors['total_distance_given']}
                            helperText={errors['total_distance_given']}
                        />

                    </div>

                    <hr className="border-gray-100 mb-4" />

                    <div className="bg-gray-50 rounded-xl p-4 flex justify-between items-center">

                        <div>
                            <p className="text-sm text-gray-500">Total à rembourser</p>
                            {data.total_distance_given > 0 && (
                                <p className="text-xs text-gray-400 mt-1">
                                    Dont {totalAbandonned.toFixed(2)}€ en abandon
                                </p>
                            )}
                        </div>

                        <p className="text-2xl font-medium text-gray-900">{totalFinal.toFixed(2)}€</p>

                    </div>

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