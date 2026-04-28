import { Head, router } from "@inertiajs/react";
import { Button, Checkbox, FormControlLabel } from "@mui/material";
import { useState } from "react";
import Header from '@/layouts/Header';

interface TravelModeProps {
    expensesClaim: {id: string},
}

export default function TravelMode({expensesClaim = {id: ''}}: TravelModeProps) {

    const [selectedModes, setSelectedModes] = useState<string[]>([])

    const modes = [
        'Voiture',
        'Moto', 
        'Train (2nd classe)',
        'Transport en commun',
        'Avion (2nd classe)',
        'Péage, parking, taxis'
    ]

    function toggleMode(mode: string) {
        setSelectedModes(prev =>
            prev.includes(mode)
            ? prev.filter(m => m !== mode)
            : [...prev, mode]
        )
    }

    function handleSubmit() {
        if(selectedModes.includes('Voiture') || selectedModes.includes('Moto')) {
            router.get(`/expenses-claims/${expensesClaim?.id}/driven-trip`, {
                modes: selectedModes
            })
        } else {
             router.get(`/expenses-claims/${expensesClaim?.id}/other-trip`, { modes: selectedModes })
        }
    }

    return(
        <Header>
            <Head title="Vos moyens de transport"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
                <h1 className="text-xl font-medium text-gray-900 mb-5">Ajout d'un trajet</h1>
                <div className="flex flex-col gap-1">
                    <p className="text-sm text-gray-400">Plusieurs choix possibles</p>
                    {modes.map((mode) => (
                        <FormControlLabel
                            key={mode}
                            control={
                                <Checkbox
                                    checked={selectedModes.includes(mode)}
                                    onChange={() => toggleMode(mode)}
                                    sx={{ color: '#2D6A2D', '&.Mui-checked': { color: '#2D6A2D' } }}
                                />
                            }
                            label={mode}
                            slotProps={{ typography: { className: "text-sm text-gray-700" } }}
                        />
                    ))}
                </div>
                <Button
                    variant="contained"
                    fullWidth
                    className="mt-5!"
                    sx={{
                        backgroundColor: '#2D6A2D',
                        '&:hover': { backgroundColor: '#1F4F1F' },
                    }}
                    onClick={handleSubmit}
                    disabled={selectedModes.length === 0}
                >
                    Suivant
                </Button>
            </div>
        </Header>
    )
}