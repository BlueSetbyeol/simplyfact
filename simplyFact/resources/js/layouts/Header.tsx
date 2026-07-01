import { Link } from '@inertiajs/react';

export default function AppLayout({
    children,
    showback = true,
}: {
    children: React.ReactNode;
    showback?: boolean;
}) {
    return (
        <div className="min-h-screen bg-gray-100">
            <div className="flex items-center justify-between border-b border-gray-200 bg-taupe-950 px-6 py-4">
                {showback && (
                    <button
                        onClick={() => window.history.back()}
                        className="text-sm text-gray-300 transition-colors duration-200 hover:text-gray-100"
                    >
                        ← Retour
                    </button>
                )}
                <Link href="/">
                    <img
                        src="/img/ffs-xs-logo.svg"
                        alt="Logo Fédération Française de Spéléologie"
                    ></img>
                </Link>
            </div>
            <main className="flex items-center justify-center p-4 lg:p-8">
                {children}
            </main>
        </div>
    );
}
