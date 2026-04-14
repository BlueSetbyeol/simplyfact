import AppLayout from '@/layouts/AppLayout';
import {
    Head,
    // Link, usePage
} from '@inertiajs/react';
import { Button } from "@mui/material"
// import { dashboard, login, register } from '@/routes';

export default function Home() {
    // {
    //     canRegister = true,
    // }: {
    //     canRegister?: boolean;
    // }
    // const { auth } = usePage().props;

    return (
        <AppLayout showback={false}>
            <Head title="Accueil"></Head>
            <div className="bg-white rounded-2xl border border-gray-200 p-6 w-full max-w-xl">

                <h1 className="text-xl font-medium text-gray-900">Déclaration de notes de frais</h1>
                <p className="text-sm text-gray-500 mt-1 mb-6">Fédération Française de Spéléologie</p>
                <img src="/img/speleo_Philippe_Crochet-SP23-1570.jpg" alt="Image de la fédération" className="mb-6 rounded-lg"></img>

        
                <Button variant="contained" fullWidth className="!mt-5" sx={{ backgroundColor: '#2D6A2D', '&:hover': { backgroundColor: '#1F4F1F'}}}>Faire une note de frais</Button>

            </div>
        </AppLayout>
    );
}
