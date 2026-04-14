import AppLayout from "@/layouts/AppLayout";
import { Head, router, useForm } from "@inertiajs/react";
import { Button, Link, TextField } from "@mui/material";

interface UserProps {
    user: {
        firstname: string;
        surname: string;
        adress: string;
        postalCode: string;
        city: string;
        email: string;
        phone: string;
    };
}

export default function User({ user }: UserProps) {

    const { data, setData, post, errors, reset } = useForm({
        firstname: user? user.firstname : "",
        surname: user? user.surname : "",
        adress: user? user.adress : "",
        postalCode: user? user.postalCode : "",
        city: user? user.city : "",
        email: user? user.email : "",
        phone: user? user.phone : "",
    });

    function completeStep() {
            router.post('/expenses-claims');
    }

    function submitUser(e: { preventDefault: () => void }) {
        e.preventDefault();
        post('/users', {
            onSuccess: () => {
                reset(),
                completeStep()  // appelé seulement si /users a répondu OK
            }
        });
    }

    return(

        <AppLayout>
            <Head title="Informations personnelles"></Head>
            
            <div className="bg-white rounded-2xl border border-gray-200 p-6 w-full max-w-xl">
                
                <div className= "bg-gray-50 rounded-xl px-4 py-2 flex justify-between items-center mb-2">
                    <p className= "text-xs text-gray-400 mt-1">Vous avez déjà un compte ?</p>
                    <Link href="/login">
                        <Button 
                            component="label"
                            variant="outlined"
                            sx={{
                                    color: '#2D6A2D',
                                    borderColor: '#2D6A2D',
                                    '&:hover': {
                                        borderColor: '#1F4F1F',
                                        backgroundColor: '#F0F7F0'
                                    }
                                }}
                            >Se connecter
                        </Button>
                    </Link>
                </div>
                
                <div className="flex flex-row gap-2 items-center justify-center mb-6">
                    <hr className="border-gray-100  w-full"></hr>
                    <p className= "text-xs text-gray-400 mt-1 text-nowrap">Ou continuer sans compte</p>
                    <hr className="border-gray-100  w-full"></hr>
                </div>

                <h1 className="text-xl font-medium text-gray-900">
                    Vos informations
                </h1>
                <p className="text-sm text-gray-500 mt-1 mb-4">
                    Ces informations sont nécessaires pour établir votre note de frais.
                </p>

                <hr className="border-gray-100  w-full"></hr>

                <form onSubmit={submitUser} className="mt-6">

                    <div className="flex flex-row gap-2 mb-3">
                        <TextField
                            label="Nom"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={data.surname !== "" ? data.surname : ''}
                            onChange={(e) => setData('surname', e.target.value)}
                            fullWidth
                            size="small" />
                        <TextField 
                            label="Prénom" 
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={data.firstname !== "" ? data.firstname : ''}
                            onChange={(e) => setData('firstname', e.target.value)}
                            fullWidth
                            size="small" />
                    </div>

                    <div className="flex flex-row gap-2 mb-3">
                        <TextField 
                            label="Adresse"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={data.adress !== "" ? data.adress : ''}
                            onChange={(e) => setData('adress', e.target.value)}
                            fullWidth
                            size="small" />
                    </div>

                    <div className="flex flex-row gap-2 mb-3">
                        <TextField 
                            label="Code postal"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={data.postalCode !== "" ? data.postalCode : ''}
                            onChange={(e) => setData('postalCode', e.target.value)}
                            fullWidth
                            size="small" />
                        <TextField 
                            label="Ville"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={data.city !== "" ? data.city : ''}
                            onChange={(e) => setData('city', e.target.value)}
                            fullWidth
                            size="small" />
                    </div>

                    <div className="flex flex-row gap-2 mb-3">
                        <TextField 
                            label="Email"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={data.email !== "" ? data.email : ''}
                            onChange={(e) => setData('email', e.target.value)}
                            fullWidth
                            size="small" />
                    </div>

                    <div className="flex flex-row gap-2 mb-3">
                        <TextField 
                            label="Téléphone"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={data.phone !== "" ? data.phone : ''}
                            onChange={(e) => setData('phone', e.target.value)}
                            fullWidth
                            size="small" />
                    </div>

                    <Button 
                        type="submit"
                        variant="contained" 
                        fullWidth 
                        className="!mt-5" 
                        sx={{ backgroundColor: '#2D6A2D', '&:hover': { backgroundColor: '#1F4F1F'}}}
                        >
                        Suivant
                    </Button>

                </form>
                
            </div>
        </AppLayout>

    )
}