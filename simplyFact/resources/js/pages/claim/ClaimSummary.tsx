import { useForm, Head } from '@inertiajs/react';
import { Button, TextField, Card, Checkbox } from '@mui/material';
import { useMemo, useState } from 'react';
import Header from '@/layouts/Header';

interface ClaimSummaryProps {
    expensesClaim?: {
        id: number;
        committee_name: string;
        action_name: string;
        action_dates: string;
        total_reimbursed: number;
        total_given?: number;
        travels: {
            id: number;
            total_price: number;
            reimbursed_price: number;
        }[];
        accommodations: {
            id: number;
            total_price: number;
            reimbursed_price: number;
        }[];
        meals: {
            id: number;
            number_of_meal: number;
            total_price: number;
            reimbursed_price: number;
        }[];
        other_expenses: {
            id: number;
            total_price: number;
            reimbursed_price: number;
        }[];
    } | null;
}

export default function ClaimSummary({ expensesClaim }: ClaimSummaryProps) {
    const { data, setData, post, errors, reset } = useForm({
        id: expensesClaim?.id,
        committee_name: expensesClaim?.committee_name,
        action_name: expensesClaim?.action_name,
        action_dates: expensesClaim?.action_dates,
        total_reimbursed: expensesClaim?.total_reimbursed || 0,
        total_given: expensesClaim?.total_given || 0,
        travels: expensesClaim?.travels,
        accommodations: expensesClaim?.accommodations,
        meals: expensesClaim?.meals,
        other_expenses: expensesClaim?.other_expenses,
    });

    const totalSpend = useMemo(() => {
        let total = 0;
        expensesClaim?.travels?.forEach((t) => (total += t.reimbursed_price));
        expensesClaim?.accommodations?.forEach(
            (a) => (total += a.reimbursed_price),
        );
        expensesClaim?.meals?.forEach((m) => (total += m.reimbursed_price));
        expensesClaim?.other_expenses?.forEach(
            (e) => (total += e.reimbursed_price),
        );

        return total;
    }, [expensesClaim]);

    const [willGive, setWillGive] = useState<boolean>(false);
    const [informationConfirmed, setInformationConfirmed] =
        useState<boolean>(false);

    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setInformationConfirmed(event.target.checked);
    };

    const totalReimbursed =
        data.total_given !== 0 ? totalSpend - data.total_given : totalSpend;

    function endFlow() {
        setData('total_reimbursed', totalReimbursed);
        post(`/expenses-claims/${expensesClaim?.id}/flow/done`, {
            onSuccess: () => {
                reset();
            },
        });
    }

    return (
        <Header>
            <Head title="Résumé des choix"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
                <h1 className="mb-6 text-xl font-medium text-gray-900">
                    Votre votre note de frais:
                </h1>

                <section className="my-4 flex w-full flex-col items-center gap-2 rounded-xl border border-gray-200 p-4">
                    <h2>Information concernant la note de frais :</h2>
                    <div className="my-4 flex w-full flex-col items-start gap-2">
                        <p>Commission : {expensesClaim?.committee_name}</p>
                        <p>Nom de l'action : {expensesClaim?.action_name}</p>
                        <p>
                            Les dates de l'action :{' '}
                            {expensesClaim?.action_dates}
                        </p>
                    </div>
                </section>

                <section className="my-4 flex w-full flex-col items-center gap-2">
                    <h2>Toutes les dépenses effectuées</h2>
                    {expensesClaim?.travels && (
                        <div className="mb-2 flex w-full flex-col gap-2 rounded-xl bg-gray-50 px-4 py-4">
                            <h3>Les Trajets</h3>
                            {expensesClaim?.travels.map((travel, index) => (
                                <Card key={index} className="mb-1 p-2">
                                    <p>To be determined : {travel.id}</p>
                                    {/* <p className="mb-1 text-gray-500">{labels[step]}</p> */}
                                </Card>
                            ))}
                        </div>
                    )}
                    {expensesClaim?.accommodations && (
                        <div className="mb-2 flex w-full flex-col gap-2 rounded-xl bg-gray-50 px-4 py-4">
                            <h3>Les Hébergements</h3>
                            {expensesClaim?.accommodations.map(
                                (lodge, index) => (
                                    <Card key={index} className="mb-1 p-2">
                                        <p>To be determined : {lodge.id}</p>
                                        {/* <p className="mb-1 text-gray-500">{labels[step]}</p> */}
                                    </Card>
                                ),
                            )}
                        </div>
                    )}
                    {expensesClaim?.meals && (
                        <div className="mb-2 flex w-full flex-col gap-2 rounded-xl bg-gray-50 px-4 py-4">
                            <h3>Les Repas</h3>
                            {expensesClaim?.meals.map((meal, index) => (
                                <Card key={index} className="mb-1 p-2">
                                    <p className="mb-1 text-gray-500">
                                        Nombre de repas : {meal.number_of_meal}
                                    </p>
                                    <p className="mb-1 text-gray-500">
                                        Total remboursé :{' '}
                                        {meal.reimbursed_price}
                                    </p>
                                </Card>
                            ))}
                        </div>
                    )}{' '}
                    {expensesClaim?.other_expenses && (
                        <div className="mb-2 flex w-full flex-col gap-2 rounded-xl bg-gray-50 px-4 py-4">
                            <h3>Les autres Frais</h3>
                            {expensesClaim?.other_expenses.map(
                                (lodge, index) => (
                                    <Card key={index} className="mb-1 p-2">
                                        <p>To be determined : {lodge.id}</p>
                                        {/* <p className="mb-1 text-gray-500">{labels[step]}</p> */}
                                    </Card>
                                ),
                            )}
                        </div>
                    )}
                </section>

                <section className="my-4 flex w-full flex-col items-center gap-2 rounded-xl border border-gray-200 p-4">
                    <h2>Voulez vous abandonner certain frais ?</h2>
                    <div className="flex w-full flex-row justify-center gap-2">
                        <Button
                            variant={willGive ? 'contained' : 'outlined'}
                            onClick={() => {
                                setWillGive(true);
                            }}
                            sx={
                                willGive
                                    ? {
                                          backgroundColor: '#2D6A2D',
                                          '&:hover': {
                                              backgroundColor: '#1F4F1F',
                                          },
                                      }
                                    : {
                                          color: '#2D6A2D',
                                          borderColor: '#2D6A2D',
                                          '&:hover': {
                                              borderColor: '#1F4F1F',
                                              backgroundColor: '#F0F7F0',
                                          },
                                      }
                            }
                        >
                            Oui
                        </Button>
                        <Button
                            variant={!willGive ? 'contained' : 'outlined'}
                            onClick={() => {
                                setWillGive(false);
                            }}
                            sx={
                                !willGive
                                    ? {
                                          backgroundColor: '#2D6A2D',
                                          '&:hover': {
                                              backgroundColor: '#1F4F1F',
                                          },
                                      }
                                    : {
                                          color: '#2D6A2D',
                                          borderColor: '#2D6A2D',
                                          '&:hover': {
                                              borderColor: '#1F4F1F',
                                              backgroundColor: '#F0F7F0',
                                          },
                                      }
                            }
                        >
                            Non
                        </Button>
                    </div>
                    {willGive ? (
                        <form
                            onSubmit={endFlow}
                            className="flex w-full flex-col items-center justify-between"
                        >
                            <div className="my-2 flex w-[70%] flex-col items-center justify-between gap-5">
                                <TextField
                                    label="Somme abandonnée"
                                    slotProps={{ inputLabel: { shrink: true } }}
                                    defaultValue={
                                        data.total_given !== 0
                                            ? data.total_given
                                            : ''
                                    }
                                    onChange={(e) =>
                                        setData(
                                            'total_given',
                                            Number(e.target.value),
                                        )
                                    }
                                    fullWidth
                                    error={!!errors['total_given']}
                                    helperText={errors['total_given']}
                                />
                                {errors.total_given && (
                                    <span>{errors.total_given}</span>
                                )}
                            </div>
                            <div className="flex w-[70%] items-center justify-between gap-2 rounded-xl bg-gray-50 p-4">
                                <div>
                                    <p className="text-sm text-gray-500">
                                        Total remboursé
                                    </p>
                                </div>
                                <div className="text-right">
                                    <p className="text-2xl font-medium text-gray-900">
                                        {totalReimbursed}€
                                    </p>
                                </div>
                            </div>

                            <section className="flex w-[70%] items-center justify-between py-3">
                                <Checkbox
                                    checked={informationConfirmed}
                                    onChange={handleChange}
                                    required
                                />
                                <p>
                                    Je confirme que toutes les informations
                                    données sont exacte et que j'ai fournis tous
                                    les documents de justificatif.
                                </p>
                            </section>

                            <Button
                                type="submit"
                                disabled={informationConfirmed ? false : true}
                                variant="contained"
                                sx={{
                                    backgroundColor: '#2D6A2D',
                                    '&:hover': { backgroundColor: '#1F4F1F' },
                                    width: '70%',
                                }}
                            >
                                Valider la note de frais
                            </Button>
                        </form>
                    ) : (
                        <div className="m-4 mb-6 flex w-full flex-col items-center justify-between rounded-xl p-4">
                            <div className="flex w-[70%] items-center justify-between gap-2 rounded-xl bg-gray-50 p-4">
                                <div>
                                    <p className="text-sm text-gray-500">
                                        Total remboursé
                                    </p>
                                </div>
                                <div className="text-right">
                                    <p className="text-2xl font-medium text-gray-900">
                                        {totalReimbursed}€
                                    </p>
                                </div>
                            </div>

                            <section className="flex w-[70%] items-center justify-between py-3">
                                <Checkbox
                                    checked={informationConfirmed}
                                    onChange={handleChange}
                                    required
                                />
                                <p>
                                    Je confirme que toutes les informations
                                    données sont exacte et que j'ai fournis tous
                                    les documents de justificatif.
                                </p>
                            </section>

                            <Button
                                onClick={endFlow}
                                disabled={informationConfirmed ? false : true}
                                variant="contained"
                                sx={{
                                    backgroundColor: '#2D6A2D',
                                    '&:hover': { backgroundColor: '#1F4F1F' },
                                    width: '70%',
                                }}
                            >
                                Valider la note de frais
                            </Button>
                        </div>
                    )}
                </section>
            </div>
        </Header>
    );
}
