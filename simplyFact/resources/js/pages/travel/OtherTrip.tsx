import { Head, router, useForm } from "@inertiajs/react";
import { Button, TextField } from "@mui/material";
import { useState } from "react";
import FileUpload from "@/components/FileUpload";
import Header from '@/layouts/Header';

interface OtherTripProps {
    expensesClaim: {id: string},
    modes: string[],
}

export default function OtherTrip({expensesClaim = {id: ''}, modes = ['Train (2nd classe)', 'Transport en commun', 'Avion (2nd classe)', 'Péage, parking, taxis']}: OtherTripProps) {

    const trainForm = useForm({expense_name: 'Train', expense_price: 0})
    const transportForm = useForm({expense_name: 'Transport en commun', expense_price: 0})
    const plainForm = useForm({expense_name: 'Avion', expense_price: 0})
    const tollForm = useForm({expense_name: 'Péage', expense_price: 0})
    const parkingForm = useForm({expense_name: 'Parking', expense_price: 0})
    const taxiForm = useForm({expense_name: 'Taxis', expense_price: 0})

    const totalFinal = 
    (modes.includes('Train (2nd classe)') ? trainForm.data.expense_price : 0) +
    (modes.includes('Transport en commun') ? transportForm.data.expense_price : 0) +
    (modes.includes('Avion (2nd classe)') ? plainForm.data.expense_price : 0) +
    (modes.includes('Péage, parking, taxis') ? tollForm.data.expense_price + parkingForm.data.expense_price + taxiForm.data.expense_price : 0)

    async function handleSubmit(e: { preventDefault: () => void }) {
        e.preventDefault();
       
        if (modes.includes('Train (2nd classe)')) {
            await trainForm.post(`/expenses-claims/${expensesClaim?.id}/other-trips`)
        }

        if (modes.includes('Transport en commun')) { 
            await transportForm.post(`/expenses-claims/${expensesClaim?.id}/other-trips`)
        }

        if (modes.includes('Avion (2nd classe)')) {
            await plainForm.post(`/expenses-claims/${expensesClaim?.id}/other-trips`)
        }

        if (modes.includes('Péage, parking, taxis')) {
            await tollForm.post(`/expenses-claims/${expensesClaim?.id}/other-trips`)
            await parkingForm.post(`/expenses-claims/${expensesClaim?.id}/other-trips`)
            await taxiForm.post(`/expenses-claims/${expensesClaim?.id}/other-trips`)
        }

        router.get(`/expenses-claims/${expensesClaim?.id}/travel`)
    }

    const [hasDocument, setHasDocument] = useState(false)

    return(
        <Header>
            <Head title="Autre trajet"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-4">
                <h1 className="text-xl font-medium text-gray-900 mb-2">Autre trajet</h1>

                <hr className="border-gray-100 mb-8" />

                <div className="flex flex-col gap-4">

                    {modes.includes('Train (2nd classe)') && (
                        <div className="flex flex-col gap-2">
                            <p className="text-md text-gray-500 mb-2">Déplacement en train</p>
                            <TextField
                                label="Montant dépensé"
                                slotProps={{ inputLabel: { shrink: true } }}
                                defaultValue={trainForm.data.expense_price}
                                onChange={(e) => trainForm.setData('expense_price', Number(e.target.value))}
                                fullWidth
                                error={!!trainForm.errors['expense_price']}
                                helperText={trainForm.errors['expense_price']}
                            />
                            <FileUpload expensesClaimId={expensesClaim?.id} />
                            <hr className="border-gray-100 mb-8" />
                        </div>
                    )}

                    {modes.includes('Transport en commun') && (
                        <div className="flex flex-col gap-2">
                            <p className="text-md text-gray-500 mb-2">Déplacement en transport en commun</p>
                            <TextField
                                label="Montant dépensé"
                                slotProps={{ inputLabel: { shrink: true } }}
                                defaultValue={transportForm.data.expense_price}
                                onChange={(e) => transportForm.setData('expense_price', Number(e.target.value))}
                                fullWidth
                                error={!!transportForm.errors['expense_price']}
                                helperText={transportForm.errors['expense_price']}
                            />
                            <FileUpload expensesClaimId={expensesClaim?.id} />
                            <hr className="border-gray-100 mb-8" />
                        </div>
                    )}

                    {modes.includes('Avion (2nd classe)') && (
                        <div className="flex flex-col gap-2">
                            <p className="text-md text-gray-500 mb-2">Déplacement en avion</p>
                            <TextField
                                label="Montant dépensé"
                                slotProps={{ inputLabel: { shrink: true } }}
                                defaultValue={plainForm.data.expense_price}
                                onChange={(e) => plainForm.setData('expense_price', Number(e.target.value))}
                                fullWidth
                                error={!!plainForm.errors['expense_price']}
                                helperText={plainForm.errors['expense_price']}
                            />
                            <FileUpload expensesClaimId={expensesClaim?.id} />
                            <hr className="border-gray-100 mb-8" />
                        </div>
                    )}

                    {modes.includes('Péage, parking, taxis') && (
                        <>
                        <div className="flex flex-col gap-2">
                            <p className="text-md text-gray-500 mb-2">Péage</p>
                            <TextField
                                label="Montant dépensé"
                                slotProps={{ inputLabel: { shrink: true } }}
                                defaultValue={tollForm.data.expense_price}
                                onChange={(e) => tollForm.setData('expense_price', Number(e.target.value))}
                                fullWidth
                                error={!!tollForm.errors['expense_price']}
                                helperText={tollForm.errors['expense_price']}
                            />
                            <FileUpload expensesClaimId={expensesClaim?.id} />
                            <hr className="border-gray-100 mb-8" />
                        </div>
                        <div className="flex flex-col gap-2">
                            <p className="text-md text-gray-500 mb-2">Parking</p>
                            <TextField
                                label="Montant dépensé"
                                slotProps={{ inputLabel: { shrink: true } }}
                                defaultValue={parkingForm.data.expense_price}
                                onChange={(e) => parkingForm.setData('expense_price', Number(e.target.value))}
                                fullWidth
                                error={!!parkingForm.errors['expense_price']}
                                helperText={parkingForm.errors['expense_price']}
                            />
                            <FileUpload expensesClaimId={expensesClaim?.id} />
                            <hr className="border-gray-100 mb-8" />
                        </div>
                        <div className="flex flex-col gap-2">
                            <p className="text-md text-gray-500 mb-2">Taxis</p>
                            <TextField
                                label="Montant dépensé"
                                slotProps={{ inputLabel: { shrink: true } }}
                                defaultValue={taxiForm.data.expense_price}
                                onChange={(e) => taxiForm.setData('expense_price', Number(e.target.value))}
                                fullWidth
                                error={!!taxiForm.errors['expense_price']}
                                helperText={taxiForm.errors['expense_price']}
                            />
                            <FileUpload 
                                expensesClaimId={expensesClaim?.id}
                                onUpload={(hasFile) => setHasDocument(hasFile)}
                            />
                            <hr className="border-gray-100 mb-8" />
                        </div>
                        </>
                    )}

                </div>

                <div className="bg-gray-50 rounded-xl p-4 flex justify-between items-center mb-2">

                    <p className="text-sm text-gray-500">Total à rembourser</p>    
                    <p className="text-2xl font-medium text-gray-900">{totalFinal.toFixed(2)}€</p>

                    </div>

                <Button
                    onClick={handleSubmit}
                    disabled={!hasDocument}
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
            </div>
        </Header>
    )

}