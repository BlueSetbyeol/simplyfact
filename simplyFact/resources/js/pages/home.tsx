import {
    Head,
    // Link, usePage
} from '@inertiajs/react';
// import { dashboard, login, register } from '@/routes';

export default function Home() {
    // {
    //     canRegister = true,
    // }: {
    //     canRegister?: boolean;
    // }
    // const { auth } = usePage().props;

    return (
        <>
            <Head title="Home"></Head>
            <div className="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]">
                <h1 className="text-5xl text-red-700">Hello Laravel</h1>
                <div className="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0">
                    <main className="flex w-full max-w-[335px] flex-col-reverse lg:max-w-4xl lg:flex-row"></main>
                </div>
                <div className="hidden h-14.5 lg:block"></div>
            </div>
        </>
    );
}
