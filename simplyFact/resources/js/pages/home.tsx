import { Head, Link } from '@inertiajs/react';
import { Button } from '@mui/material';
import Header from '@/layouts/Header';

export default function Home() {
    // {canRegister = true,}: {canRegister?: boolean;}
    // const { auth } = usePage().props;

    return (
        <Header showback={false}>
            <Head title="Accueil"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-4">
                <h1 className="text-xl font-medium text-gray-900">
                    Déclaration de notes de frais
                </h1>
                <p className="mt-1 mb-6 text-sm text-gray-500">
                    Fédération Française de Spéléologie
                </p>
                <img
                    src="/img/speleo_Philippe_Crochet-SP23-1570.jpg"
                    alt="Image de la fédération"
                    className="mb-6 rounded-lg"
                />

                <Link href={'/users'}>
                    <Button
                        variant="contained"
                        fullWidth
                        className="!mt-5"
                        sx={{
                            backgroundColor: '#2D6A2D',
                            '&:hover': { backgroundColor: '#1F4F1F' },
                        }}
                    >
                        Faire une note de frais
                    </Button>
                </Link>
            </div>
        </Header>
    );
}
