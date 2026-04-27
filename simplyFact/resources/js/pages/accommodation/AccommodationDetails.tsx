/* const { setData, data, post, errors, reset } = useForm({
        accomodation_type: '',
        nb_of_night: 0,
        total_price: 0,
        reimbursed_price: 0,
    })

    const ceilings: Record<string, number> = {
        "Hôtel province hors coeur de ville": 70,
        "Hôtel province coeur de ville": 90,
        "Hôtel Lyon": 100,
        "Hôtel Paris": 150,
    }

    const ceiling = ceilings[data.accomodation_type] ?? 0

    const totalRefund= Math.min(data.total_price, ceiling * data.nb_of_night) */