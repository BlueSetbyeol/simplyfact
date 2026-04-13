import AppLayout from "@/layouts/AppLayout";
import { Head } from "@inertiajs/react";
import { Button, TextField } from "@mui/material";
import { useState } from "react";

export default function WhoFirst() {
 const [firstname, setFirstname] = useState("")
 const [surname, setSurname] = useState("")
 const [adress, setAdress] = useState("")
 const [postalCode, setPostalCode] = useState("")
 const [city, setCity] = useState("")
 const [email, setEmail] = useState("")
 const [phone, setPhone] = useState("")

    return(

        <AppLayout>
            <Head title="Informations personnelles"></Head>
            
            <div className="bg-white rounded-2xl border border-gray-200 p-6 w-full max-w-xl">
                
                <div className= "bg-gray-50 rounded-xl px-4 py-2 flex justify-between items-center mb-2">
                    <p className= "text-xs text-gray-400 mt-1">Vous avez déjà un compte ?</p>
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
                        >Se connecter</Button>
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

                <form className="mt-6">

                    <div className="flex flex-row gap-2 mb-3">
                        <TextField
                        label="Nom"
                        slotProps={{ inputLabel: { shrink: true } }}
                        value={surname}
                        onChange={(e) => setSurname(e.target.value)}
                        fullWidth
                        size="small" />
                        <TextField 
                        label="Prénom" 
                        slotProps={{ inputLabel: { shrink: true } }}
                        value={firstname}
                        onChange={(e) => setFirstname(e.target.value)}
                        fullWidth
                        size="small" />
                    </div>

                    <div className="flex flex-row gap-2 mb-3">
                        <TextField 
                        label="Adresse"
                        slotProps={{ inputLabel: { shrink: true } }}
                        value={adress}
                        onChange={(e) => setAdress(e.target.value)}
                        fullWidth
                        size="small" />
                    </div>

                    <div className="flex flex-row gap-2 mb-3">
                        <TextField 
                        label="Code postal"
                        slotProps={{ inputLabel: { shrink: true } }}
                        value={postalCode}
                        onChange={(e) => setPostalCode(e.target.value)}
                        fullWidth
                        size="small" />
                        <TextField 
                        label="Ville"
                        slotProps={{ inputLabel: { shrink: true } }}
                        value={city}
                        onChange={(e) => setCity(e.target.value)}
                        fullWidth
                        size="small" />
                    </div>

                    <div className="flex flex-row gap-2 mb-3">
                        <TextField 
                        label="Email"
                        slotProps={{ inputLabel: { shrink: true } }}
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        fullWidth
                        size="small" />
                    </div>

                    <div className="flex flex-row gap-2 mb-3">
                        <TextField 
                        label="Téléphone"
                        slotProps={{ inputLabel: { shrink: true } }}
                        value={phone}
                        onChange={(e) => setPhone(e.target.value)}
                        fullWidth
                        size="small" />
                    </div>

                    <Button variant="contained" fullWidth className="!mt-5" sx={{ backgroundColor: '#2D6A2D', '&:hover': { backgroundColor: '#1F4F1F'}}}>Suivant</Button>

                </form>
                
            </div>
        </AppLayout>

    )
}