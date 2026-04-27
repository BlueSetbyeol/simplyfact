import { Link } from "@inertiajs/react";

export default function AppLayout({ children, showback =true }: { children: React.ReactNode, showback?: boolean }) {
    return (
        <div className="min-h-screen bg-gray-100">
            <div className="flex items-center justify-between px-6 py-4 bg-taupe-950 border-b border-gray-200">
                {showback && (
                    <button onClick={() => window.history.back()} className="text-sm text-gray-300 hover:text-gray-100 transition-colors duration-200">
                        ← Retour
                    </button>
                )}
                <Link href="/">
                    <img src="/img/ffs-xs-logo.svg" alt="Logo Fédération Française de Spéléologie"></img>
                </Link>  
            </div>
            <main className="flex items-center justify-center p-8"> 
                {children}
            </main>
        </div>
    )
}