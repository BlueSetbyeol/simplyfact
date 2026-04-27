import { Head, useForm } from '@inertiajs/react';
import { Button, Link, TextField } from '@mui/material';
import Header from '@/layouts/Header';

interface UserProps {
    user: {
        firstname: string;
        lastname: string;
        address_street: string;
        address_zipcode: string;
        address_city: string;
        address_country: string;
        email_address: string;
        phone_number: string;
    };
}

export default function User({ user }: UserProps) {
    const { data, setData, post, reset, errors } = useForm({
        firstname: user ? user.firstname : '',
        lastname: user ? user.lastname : '',
        address_street: user ? user.address_street : '',
        address_zipcode: user ? user.address_zipcode : '',
        address_city: user ? user.address_city : '',
        address_country: 'France',
        email_address: user ? user.email_address : '',
        phone_number: user ? user.phone_number : '',
    });

    function submitUser(e: { preventDefault: () => void }) {
        e.preventDefault();
        post('/users', {
            onSuccess: () => {
                reset();
            },
        });
    }

    return (
        <Header>
            <Head title="Informations personnelles"></Head>

            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
                <div className="mb-2 flex items-center justify-between rounded-xl bg-gray-50 px-4 py-2">
                    <p className="mt-1 text-xs text-gray-400">
                        Vous avez déjà un compte ?
                    </p>
                    <Link href="/login">
                        <Button
                            component="label"
                            variant="outlined"
                            sx={{
                                color: '#2D6A2D',
                                borderColor: '#2D6A2D',
                                '&:hover': {
                                    borderColor: '#1F4F1F',
                                    backgroundColor: '#F0F7F0',
                                },
                            }}
                        >
                            Se connecter
                        </Button>
                    </Link>
                </div>

                <div className="mb-6 flex flex-row items-center justify-center gap-2">
                    <hr className="w-full border-gray-100"></hr>
                    <p className="mt-1 text-xs text-nowrap text-gray-400">
                        Ou continuer sans compte
                    </p>
                    <hr className="w-full border-gray-100"></hr>
                </div>

                <h1 className="text-xl font-medium text-gray-900">
                    Vos informations
                </h1>
                <p className="mt-1 mb-4 text-sm text-gray-500">
                    Ces informations sont nécessaires pour établir votre note de
                    frais.
                </p>

                <hr className="w-full border-gray-100"></hr>

                <form onSubmit={submitUser} className="mt-6">
                    <div className="mb-3 flex flex-row gap-2">
                        <TextField
                            label="Nom"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={
                                data.lastname !== '' ? data.lastname : ''
                            }
                            onChange={(e) =>
                                setData('lastname', e.target.value)
                            }
                            fullWidth
                            size="small"
                            error={!!errors['lastname']}
                            helperText={errors['lastname']}
                        />
                        <TextField
                            label="Prénom"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={
                                data.firstname !== '' ? data.firstname : ''
                            }
                            onChange={(e) =>
                                setData('firstname', e.target.value)
                            }
                            fullWidth
                            error={!!errors['firstname']}
                            helperText={errors['firstname']}
                        />
                    </div>

                    <div className="mb-3 flex flex-row gap-2">
                        <TextField
                            label="address_streete"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={
                                data.address_street !== ''
                                    ? data.address_street
                                    : ''
                            }
                            onChange={(e) =>
                                setData('address_street', e.target.value)
                            }
                            fullWidth
                            error={!!errors['address_street']}
                            helperText={errors['address_street']}
                        />
                    </div>

                    <div className="mb-3 flex flex-row gap-2">
                        <TextField
                            label="Code postal"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={
                                data.address_zipcode !== ''
                                    ? data.address_zipcode
                                    : ''
                            }
                            onChange={(e) =>
                                setData('address_zipcode', e.target.value)
                            }
                            fullWidth
                            error={!!errors['address_zipcode']}
                            helperText={errors['address_zipcode']}
                        />
                        <TextField
                            label="Ville"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={
                                data.address_city !== ''
                                    ? data.address_city
                                    : ''
                            }
                            onChange={(e) =>
                                setData('address_city', e.target.value)
                            }
                            fullWidth
                            error={!!errors['address_city']}
                            helperText={errors['address_city']}
                        />
                    </div>

                    <div className="mb-3 flex flex-row gap-2">
                        <TextField
                            label="email_address"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={
                                data.email_address !== ''
                                    ? data.email_address
                                    : ''
                            }
                            onChange={(e) =>
                                setData('email_address', e.target.value)
                            }
                            fullWidth
                            error={!!errors['email_address']}
                            helperText={errors['email_address']}
                        />
                    </div>

                    <div className="mb-3 flex flex-row gap-2">
                        <TextField
                            label="Téléphone_number"
                            slotProps={{ inputLabel: { shrink: true } }}
                            defaultValue={
                                data.phone_number !== ''
                                    ? data.phone_number
                                    : ''
                            }
                            onChange={(e) =>
                                setData('phone_number', e.target.value)
                            }
                            fullWidth
                            error={!!errors['phone_number']}
                            helperText={errors['phone_number']}
                        />
                    </div>

                    <Button
                        type="submit"
                        variant="contained"
                        fullWidth
                        className="mt-5!"
                        sx={{
                            backgroundColor: '#2D6A2D',
                            '&:hover': { backgroundColor: '#1F4F1F' },
                        }}
                    >
                        Suivant
                    </Button>
                </form>
            </div>
        </Header>
    );
}
