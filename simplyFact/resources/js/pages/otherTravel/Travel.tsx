import { Head, router } from '@inertiajs/react';
import { Button } from '@mui/material';
import Header from '@/layouts/Header';

interface TravelProps {
    expensesClaim: {id: string},
    drivenTrips: {
        id: number,
        starting_city: string,
        starting_zip: number,
        ending_city: string,
        ending_zip_code: string,
        trip_type?: string,
        total_distance: number,
        total_price: number,
        total_distance_given?: number,
        total_price_given?: number
    }[],
    otherTrips: {
        id: number,
        expense_name: string,
        expense_price: number,
    }[]
}

export default function Travel({expensesClaim = {id: ''}, drivenTrips = [], otherTrips = []}: TravelProps) {

    const trips = drivenTrips.length + otherTrips.length

    const totalDrivenReimbursed = drivenTrips.reduce((sum, trip) => sum + trip.total_price, 0);

    const totalOtherReimbursed = otherTrips.reduce((sum, trip) => sum + trip.expense_price, 0)

    const totalReimbursed = totalDrivenReimbursed + totalOtherReimbursed

    function handleClickAddTravel() {
        router.get(`/expenses-claim/${expensesClaim.id}/travel-mode`)
    }

    function completeStep() {
        router.post('/flow/complete-step')
    }

    return (<Header>
        <Head title="Vos déplacements"></Head>
        <div className='w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6'>
            <div className='flex flex-row justify-between items-center'>
                <h1 className="text-xl font-medium text-gray-900">Vos trajets</h1>
                <Button 
                        variant="contained"
                        className="mt-5!"
                        sx={{
                            backgroundColor: '#2D6A2D',
                            '&:hover': { backgroundColor: '#1F4F1F' },
                        }}
                        onClick={handleClickAddTravel}>
                            +
                </Button>
            </div>

            {trips > 0 ? (
                <div className='flex flex-row'>
                    <h3>Trajets conduits</h3>
                    {drivenTrips.map((drivenTrip) => (
                        <p className="text-sm font-medium" key={drivenTrip.id}>De {drivenTrip.starting_city} à {drivenTrip.ending_city}</p>
                    ))}
                    <h3>Autres trajets</h3>
                    {otherTrips.map((otherTrip) => (
                        <p className='text-sm font-medium' key={otherTrip.id}>{otherTrip.expense_name}</p>
                    ))}
                </div>
            ) : (
                <p className='text-sm text-gray-400 mt-4'>Aucun trajet pour le moment</p>
            )}

            <div className="bg-gray-50 rounded-xl p-4 flex justify-between items-center mt-6 mb-6">
                <p className="text-sm text-gray-500">Total des trajets à rembourser</p>
                <p className="text-2xl font-medium text-gray-900">{totalReimbursed}€</p>
            </div>

            <Button
                variant="contained"
                fullWidth
                className="mt-5!"
                sx={{
                    backgroundColor: '#2D6A2D',
                    '&:hover': { backgroundColor: '#1F4F1F' },
                }}
                onClick={completeStep}
            >
                Suivant
            </Button>

            

        </div>
    </Header>)
    

}