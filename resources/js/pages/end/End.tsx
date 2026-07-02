import { Head, Link } from '@inertiajs/react';
import { Button } from '@mui/material';
import { CheckCircle } from 'lucide-react';
import Header from '@/layouts/Header';

export default function End () {
    return(
        <Header>
            <Head title="Confirmation"></Head>
            <div className="w-full max-w-xl rounded-2xl items-center border border-gray-200 bg-white p-4">
                <CheckCircle className="text-green-600 w-8 h-8 flex-shrink-0 mb-1" />
                <h1 className="text-xl font-medium text-gray-900">
                    Merci d'avoir envoyé votre note de frais
                </h1>
                <p className="mt-1 mb-6 text-sm text-gray-500">
                    Fédération Française de Spéléologie
                </p>
                <img
                    src="/img/speleo_Philippe_Crochet-SP23-1570.jpg"
                    alt="Image de la fédération"
                    className="mb-6 rounded-lg"
                />
                <Link href="/">
                    <Button
                            variant="contained"
                            fullWidth
                            className="!mt-5"
                            sx={{
                                backgroundColor: '#2D6A2D',
                                '&:hover': { backgroundColor: '#1F4F1F' },
                            }}
                        >
                            Faire une autre note de frais
                    </Button>
                </Link>  
            </div>
        </Header>
    )
}