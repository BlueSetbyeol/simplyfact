import { Head, router } from '@inertiajs/react';
import { Button } from '@mui/material';
import { useState } from 'react';
import Header from '@/layouts/Header';

interface ChoicesProps {
    expensesClaim: { id: number };
}

export default function Choices({ expensesClaim }: ChoicesProps) {
    // Déclaration des états pour chaque choix
    const [hasTravel, setHasTravel] = useState(false);
    const [hasAccomodation, setHasAccomodation] = useState(false);
    const [hasMeal, setHasMeal] = useState(false);
    const [hasOther, setHasOther] = useState(false);
    const [chosenSteps, setChosenSteps] = useState<string[]>([]);

    function submitChoices() {
        router.post(`/expenses-claims/${expensesClaim.id}/flow/choices`, {
            steps: chosenSteps,
            expensesClaimId: expensesClaim.id,
        });
    }

    return (
        <Header>
            <Head title="Choix des étapes"></Head>
            <div className="w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6">
                <h1 className="text-xl font-medium text-gray-900">
                    Choix des étapes
                </h1>
                <p className="mb-6 text-gray-500">
                    Vous voulez déclarer une note de frais pour:
                </p>

                <hr className="mb-6 border-gray-100" />

                <form onSubmit={submitChoices}>
                    <div className="flex flex-col gap-5">
                        {/* Frais de déplacement */}
                        <div className="flex flex-col gap-3">
                            <p className="text-gray-700">
                                Un ou des déplacements ?
                            </p>

                            <div className="flex flex-row gap-2">
                                <Button
                                    variant={
                                        hasTravel ? 'contained' : 'outlined'
                                    }
                                    onClick={() => {
                                        setHasTravel(true);
                                        chosenSteps.push('travel');
                                        setChosenSteps(chosenSteps);
                                    }}
                                    sx={
                                        hasTravel
                                            ? {
                                                  backgroundColor: '#2D6A2D',
                                                  '&:hover': {
                                                      backgroundColor:
                                                          '#1F4F1F',
                                                  },
                                              }
                                            : {
                                                  color: '#2D6A2D',
                                                  borderColor: '#2D6A2D',
                                                  '&:hover': {
                                                      borderColor: '#1F4F1F',
                                                      backgroundColor:
                                                          '#F0F7F0',
                                                  },
                                              }
                                    }
                                >
                                    Oui
                                </Button>
                                <Button
                                    variant={
                                        !hasTravel ? 'contained' : 'outlined'
                                    }
                                    onClick={() => {
                                        setHasTravel(false);
                                        setChosenSteps(
                                            chosenSteps.filter(
                                                (e) => e !== 'travel',
                                            ),
                                        );
                                    }}
                                    sx={
                                        !hasTravel
                                            ? {
                                                  backgroundColor: '#2D6A2D',
                                                  '&:hover': {
                                                      backgroundColor:
                                                          '#1F4F1F',
                                                  },
                                              }
                                            : {
                                                  color: '#2D6A2D',
                                                  borderColor: '#2D6A2D',
                                                  '&:hover': {
                                                      borderColor: '#1F4F1F',
                                                      backgroundColor:
                                                          '#F0F7F0',
                                                  },
                                              }
                                    }
                                >
                                    Non
                                </Button>
                            </div>
                        </div>

                        {/* Frais d'hébergement */}
                        <div className="flex flex-col gap-3">
                            <p className="text-gray-700">
                                Un ou des hébergements ?
                            </p>

                            <div className="flex flex-row gap-2">
                                <Button
                                    variant={
                                        hasAccomodation
                                            ? 'contained'
                                            : 'outlined'
                                    }
                                    onClick={() => {
                                        setHasAccomodation(true);
                                        chosenSteps.push('accommodation');
                                        setChosenSteps(chosenSteps);
                                    }}
                                    sx={
                                        hasAccomodation
                                            ? {
                                                  backgroundColor: '#2D6A2D',
                                                  '&:hover': {
                                                      backgroundColor:
                                                          '#1F4F1F',
                                                  },
                                              }
                                            : {
                                                  color: '#2D6A2D',
                                                  borderColor: '#2D6A2D',
                                                  '&:hover': {
                                                      borderColor: '#1F4F1F',
                                                      backgroundColor:
                                                          '#F0F7F0',
                                                  },
                                              }
                                    }
                                >
                                    Oui
                                </Button>
                                <Button
                                    variant={
                                        !hasAccomodation
                                            ? 'contained'
                                            : 'outlined'
                                    }
                                    onClick={() => {
                                        setHasAccomodation(false);
                                        setChosenSteps(
                                            chosenSteps.filter(
                                                (e) => e !== 'accommodation',
                                            ),
                                        );
                                    }}
                                    sx={
                                        !hasAccomodation
                                            ? {
                                                  backgroundColor: '#2D6A2D',
                                                  '&:hover': {
                                                      backgroundColor:
                                                          '#1F4F1F',
                                                  },
                                              }
                                            : {
                                                  color: '#2D6A2D',
                                                  borderColor: '#2D6A2D',
                                                  '&:hover': {
                                                      borderColor: '#1F4F1F',
                                                      backgroundColor:
                                                          '#F0F7F0',
                                                  },
                                              }
                                    }
                                >
                                    Non
                                </Button>
                            </div>
                        </div>

                        {/* Frais de repas */}
                        <div className="flex flex-col gap-3">
                            <p className="text-gray-700">Un ou des repas ?</p>

                            <div className="flex flex-row gap-2">
                                <Button
                                    variant={hasMeal ? 'contained' : 'outlined'}
                                    onClick={() => {
                                        setHasMeal(true);
                                        chosenSteps.push('meal');
                                        setChosenSteps(chosenSteps);
                                    }}
                                    sx={
                                        hasMeal
                                            ? {
                                                  backgroundColor: '#2D6A2D',
                                                  '&:hover': {
                                                      backgroundColor:
                                                          '#1F4F1F',
                                                  },
                                              }
                                            : {
                                                  color: '#2D6A2D',
                                                  borderColor: '#2D6A2D',
                                                  '&:hover': {
                                                      borderColor: '#1F4F1F',
                                                      backgroundColor:
                                                          '#F0F7F0',
                                                  },
                                              }
                                    }
                                >
                                    Oui
                                </Button>
                                <Button
                                    variant={
                                        !hasMeal ? 'contained' : 'outlined'
                                    }
                                    onClick={() => {
                                        setHasMeal(false);
                                        setChosenSteps(
                                            chosenSteps.filter(
                                                (e) => e !== 'meal',
                                            ),
                                        );
                                    }}
                                    sx={
                                        !hasMeal
                                            ? {
                                                  backgroundColor: '#2D6A2D',
                                                  '&:hover': {
                                                      backgroundColor:
                                                          '#1F4F1F',
                                                  },
                                              }
                                            : {
                                                  color: '#2D6A2D',
                                                  borderColor: '#2D6A2D',
                                                  '&:hover': {
                                                      borderColor: '#1F4F1F',
                                                      backgroundColor:
                                                          '#F0F7F0',
                                                  },
                                              }
                                    }
                                >
                                    Non
                                </Button>
                            </div>
                        </div>

                        {/* Autres frais */}
                        <div className="flex flex-col gap-3">
                            <p className="text-gray-700">D'autres frais ?</p>

                            <div className="flex flex-row gap-2">
                                <Button
                                    variant={
                                        hasOther ? 'contained' : 'outlined'
                                    }
                                    onClick={() => {
                                        setHasOther(true);
                                        chosenSteps.push('other_expense');
                                        setChosenSteps(chosenSteps);
                                    }}
                                    sx={
                                        hasOther
                                            ? {
                                                  backgroundColor: '#2D6A2D',
                                                  '&:hover': {
                                                      backgroundColor:
                                                          '#1F4F1F',
                                                  },
                                              }
                                            : {
                                                  color: '#2D6A2D',
                                                  borderColor: '#2D6A2D',
                                                  '&:hover': {
                                                      borderColor: '#1F4F1F',
                                                      backgroundColor:
                                                          '#F0F7F0',
                                                  },
                                              }
                                    }
                                >
                                    Oui
                                </Button>
                                <Button
                                    variant={
                                        !hasOther ? 'contained' : 'outlined'
                                    }
                                    onClick={() => {
                                        setHasOther(false);
                                        setChosenSteps(
                                            chosenSteps.filter(
                                                (e) => e !== 'other_expense',
                                            ),
                                        );
                                    }}
                                    sx={
                                        !hasOther
                                            ? {
                                                  backgroundColor: '#2D6A2D',
                                                  '&:hover': {
                                                      backgroundColor:
                                                          '#1F4F1F',
                                                  },
                                              }
                                            : {
                                                  color: '#2D6A2D',
                                                  borderColor: '#2D6A2D',
                                                  '&:hover': {
                                                      borderColor: '#1F4F1F',
                                                      backgroundColor:
                                                          '#F0F7F0',
                                                  },
                                              }
                                    }
                                >
                                    Non
                                </Button>
                            </div>
                        </div>
                    </div>

                    <Button
                        type="submit"
                        variant="contained"
                        fullWidth
                        disabled={chosenSteps.length < 1 ? true : false}
                        className="!mt-6"
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
