<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    * { font-family: 'Inter', sans-serif; }
    body { background: white; color: #1a1a1a; }
    .page-break { page-break-before: always; }
  </style>
</head>
<body class="p-8 max-w-4xl mx-auto text-sm">

  {{-- ─── HEADER ──────────────────────────────────────────────── --}}
  <header class="flex items-start justify-between mb-8 pb-6 border-b-2 border-gray-200">
    <div class="flex items-center gap-4">
      <img src="data:image/jpeg;base64,{{ $logoBase64 }}"
           alt="Logo FFS" class="h-20 w-auto object-contain">
    </div>
    <div class="text-right">
      <h1 class="text-2xl font-bold tracking-tight text-gray-900">NOTE DE FRAIS</h1>
      <p class="text-base font-semibold text-green-700 mt-1">{{ $expensesClaim->action_dates }}</p>
      @if($expensesClaim->commitee_name)
        <p class="text-gray-500 text-xs mt-1">{{ $expensesClaim->commitee_name }}</p>
      @endif
    </div>
  </header>

  {{-- ─── IDENTITÉ ────────────────────────────────────────────── --}}
  <section class="grid grid-cols-2 gap-6 mb-8">
    <div class="bg-gray-50 rounded-xl p-5">
      <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">Bénéficiaire</h2>
      <p class="font-semibold text-base text-gray-900">{{ $user->firstname }} {{ $user->lastname }}</p>
      @if($user->address_street)
        <p class="text-gray-600 mt-1">{{ $user->address_street }}</p>
        <p class="text-gray-600">{{ $user->address_zipcode }} {{ $user->address_city }}</p>
        @if($user->address_country && $user->address_country !== 'France')
          <p class="text-gray-600">{{ $user->address_country }}</p>
        @endif
      @endif
      @if($user->email_address)
        <p class="text-gray-500 text-xs mt-2">{{ $user->email_address }}</p>
      @endif
      @if($user->phone_number)
        <p class="text-gray-500 text-xs">{{ $user->phone_number }}</p>
      @endif
    </div>

    <div class="bg-gray-50 rounded-xl p-5">
      <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">Action</h2>
      <p class="font-semibold text-gray-900">{{ $expensesClaim->action_name }}</p>
      <p class="text-gray-500 text-xs mt-1">Commission : {{ $expensesClaim->commitee_name }}</p>

      @if($vehicle)
        <div class="mt-3 pt-3 border-t border-gray-200">
          <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Véhicule</p>
          <p class="font-medium text-gray-900">
            {{ $vehicle->vehicule_type === 'voiture' ? 'Voiture' : 'Moto' }}
            {{ $vehicle->electrical ? '(electrique)' : '' }}
            - {{ $vehicle->number_plate }}
          </p>
          @if($vehicle->power)
            <p class="text-gray-500 text-xs mt-1">Puissance : {{ $vehicle->power }} CV</p>
          @endif
        </div>
      @endif
    </div>
  </section>

  {{-- ─── DÉPLACEMENTS KILOMÉTRIQUES ─────────────────────────── --}}
  @if($drivenTrips->isNotEmpty())
  <section class="mb-6">
    <div class="flex items-baseline justify-between mb-3">
      <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400">Déplacements kilométriques</h2>
      <span class="text-xs text-gray-400">
        Taux appliqué :
        @if($vehicle?->vehicule_type === 'moto')
          {{ $vehicle->electrical ? '0,168 EUR/km' : '0,14 EUR/km' }} (moto)
        @else
          {{ $vehicle?->electrical ? '0,432 EUR/km' : '0,36 EUR/km' }} (voiture)
        @endif
      </span>
    </div>
    <div class="rounded-xl overflow-hidden border border-gray-200">
      <table class="w-full text-sm">
        <thead class="bg-gray-100 text-gray-600 text-xs uppercase tracking-wide">
          <tr>
            <th class="text-left px-4 py-3">Trajet</th>
            <th class="text-left px-4 py-3">Type</th>
            <th class="text-right px-4 py-3">Km A/R</th>
            <th class="text-right px-4 py-3">Km abandon</th>
            <th class="text-right px-4 py-3">Montant hors abandon</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @foreach($drivenTrips as $trip)
          <tr class="bg-white">
            <td class="px-4 py-3 font-medium text-gray-900">
              {{ $trip->starting_city }} - {{ $trip->ending_city }}
              @if($trip->trip_type)
                <span class="ml-2 text-xs text-gray-400">({{ $trip->trip_type }})</span>
              @endif
            </td>
            <td class="px-4 py-3 text-gray-600">
              {{ $vehicle?->vehicule_type === 'moto' ? 'Moto' : 'Voiture' }}
            </td>
            <td class="px-4 py-3 text-right text-gray-900">
              {{ number_format($trip->total_distance, 0, ',', ' ') }} km
            </td>
            <td class="px-4 py-3 text-right text-gray-500">
              {{ $trip->total_distance_given ? number_format($trip->total_distance_given, 0, ',', ' ').' km' : '-' }}
            </td>
            <td class="px-4 py-3 text-right font-semibold text-gray-900">
              {{ number_format($trip->reimbursed_amount, 2, ',', ' ') }} €
            </td>
          </tr>
          @endforeach
        </tbody>
        <tfoot class="bg-gray-800 text-white text-xs border-t border-gray-200">
          <tr>
            <td colspan="3" class="px-4 py-3 text-right font-bold uppercase tracking-wide">Totaux des kilomètres</td>
            <td class="px-4 py-3 text-right font-bold w-28">
              {{ number_format($computed->totalDistanceGiven, 0, ',', ' ') }} km
            </td>
            <td class="px-4 py-3 text-right font-bold w-28">
              {{ number_format($computed->totalReimbursedKm, 2, ',', ' ') }} €
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </section>
  @endif

  {{-- ─── AUTRES DÉPLACEMENTS ─────────────────────────────────── --}}
  @if($otherTrips->isNotEmpty())
  <section class="mb-6">
    <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">
      Autres déplacements
    </h2>
    <div class="rounded-xl overflow-hidden border border-gray-200">
      <table class="w-full text-sm">
        <thead class="bg-gray-100 text-gray-600 text-xs uppercase tracking-wide">
          <tr>
            <th class="text-left px-4 py-3">Type de transport</th>
            <th class="text-right px-4 py-3">Montant</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @foreach($otherTrips as $trip)
          <tr class="bg-white">
            <td class="px-4 py-3 text-gray-900">{{ $trip->expense_name }}</td>
            <td class="px-4 py-3 text-right font-semibold text-gray-900">
              {{ number_format($trip->expense_price, 2, ',', ' ') }} €
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </section>
  @endif

  {{-- ─── HÉBERGEMENT ─────────────────────────────────────────── --}}
  @if($accommodations->isNotEmpty())
  <section class="mb-6">
    <div class="flex items-baseline justify-between mb-3">
      <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400">Hébergement</h2>
      <span class="text-xs text-gray-400">Plafonds : province 70 EUR/nuit - coeur de ville 90 EUR - Lyon 100 EUR - Paris 150 EUR</span>
    </div>
    <div class="rounded-xl overflow-hidden border border-gray-200">
      <table class="w-full text-sm">
        <thead class="bg-gray-100 text-gray-600 text-xs uppercase tracking-wide">
          <tr>
            <th class="text-left px-4 py-3">Type</th>
            <th class="text-right px-4 py-3">Nuits</th>
            <th class="text-right px-4 py-3">Total dépensé</th>
            <th class="text-right px-4 py-3">Montant retenu</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @foreach($accommodations as $acc)
          <tr class="bg-white">
            <td class="px-4 py-3 text-gray-900">{{ $acc->accomodation_type }}</td>
            <td class="px-4 py-3 text-right text-gray-600">{{ $acc->nb_of_night }}</td>
            <td class="px-4 py-3 text-right text-gray-600">
              {{ number_format($acc->total_price, 2, ',', ' ') }} €
            </td>
            <td class="px-4 py-3 text-right font-semibold text-gray-900">
              {{ number_format($acc->reimbursed_price, 2, ',', ' ') }} €
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </section>
  @endif

  {{-- ─── REPAS ───────────────────────────────────────────────── --}}
  @if($meals->isNotEmpty())
  <section class="mb-6">
    <div class="flex items-baseline justify-between mb-3">
      <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400">Repas</h2>
      <span class="text-xs text-gray-400">Plafond : 25 EUR/repas en moyenne sur le déplacement</span>
    </div>
    <div class="rounded-xl overflow-hidden border border-gray-200">
      <table class="w-full text-sm">
        <thead class="bg-gray-100 text-gray-600 text-xs uppercase tracking-wide">
          <tr>
            <th class="text-left px-4 py-3">Description</th>
            <th class="text-right px-4 py-3">Nb repas</th>
            <th class="text-right px-4 py-3">Total dépensé</th>
            <th class="text-right px-4 py-3">Montant retenu</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @foreach($meals as $meal)
          <tr class="bg-white">
            <td class="px-4 py-3 text-gray-900">Repas</td>
            <td class="px-4 py-3 text-right text-gray-600">{{ $meal->nb_of_meal }}</td>
            <td class="px-4 py-3 text-right text-gray-600">
              {{ number_format($meal->total_price, 2, ',', ' ') }} €
            </td>
            <td class="px-4 py-3 text-right font-semibold text-gray-900">
              {{ number_format($meal->reimbursed_price, 2, ',', ' ') }} €
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </section>
  @endif

  {{-- ─── AUTRES FRAIS ────────────────────────────────────────── --}}
  @if($otherExpenses->isNotEmpty())
  <section class="mb-6">
    <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-3">
      Autres frais
    </h2>
    <div class="rounded-xl overflow-hidden border border-gray-200">
      <table class="w-full text-sm">
        <thead class="bg-gray-100 text-gray-600 text-xs uppercase tracking-wide">
          <tr>
            <th class="text-left px-4 py-3">Désignation</th>
            <th class="text-right px-4 py-3">Montant</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @foreach($otherExpenses as $expense)
          <tr class="bg-white">
            <td class="px-4 py-3 text-gray-900">
              {{ $expense->expense_name }}
              @if($expense->nb_days_of_training)
                <span class="ml-2 text-xs text-gray-400">({{ $expense->nb_days_of_training }} jours x 21,30 EUR)</span>
              @endif
            </td>
            <td class="px-4 py-3 text-right font-semibold text-gray-900">
              {{ number_format($expense->expense_price, 2, ',', ' ') }} €
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </section>
  @endif

  {{-- ─── TOTAL HORS KILOMÈTRES ───────────────────────────────── --}}
  <div class="rounded-xl overflow-hidden border border-gray-200 mb-8">
    <table class="w-full text-sm">
      <tfoot class="bg-gray-800 text-white text-xs">
        <tr>
          <td class="px-4 py-3 text-right font-bold uppercase tracking-wide">Total hors kilomètres</td>
          <td class="px-4 py-3 text-right font-bold w-28">
            {{ number_format($computed->totalWithoutKm, 2, ',', ' ') }} €
          </td>
        </tr>
      </tfoot>
    </table>
  </div>

  {{-- ─── ABANDONS + TOTAL NDF + TOTAL À REMBOURSER ──────────────────────── --}}
  <section class="mt-2">
    <div class="rounded-xl border-2 border-gray-200 overflow-hidden">
      <div class="divide-y divide-gray-100">
        
        @if($computed->totalGiven > 0)
          @if($computed->abandonKm > 0)
          <div class="flex justify-between px-6 py-3 text-sm">
            <span class="text-gray-500">Don FFS - kilomètres abandonnés</span>
            <span class="text-green-700">- {{ number_format($computed->abandonKm, 2, ',', ' ') }} €</span>
          </div>
          @endif

          @if($computed->abandonOthers > 0)
          <div class="flex justify-between px-6 py-3 text-sm">
            <span class="text-gray-500">Don FFS - autres frais abandonnés</span>
            <span class="text-green-700">- {{ number_format($computed->abandonOthers, 2, ',', ' ') }} €</span>
          </div>
          @endif

          <div class="flex justify-between px-6 py-3 text-sm bg-green-50">
            <span class="font-medium text-green-800">
              Total abandon
              <span class="font-normal text-xs text-green-600 ml-1">- réduction d'impôt possible à 66%</span>
            </span>
            <span class="font-semibold text-green-700">- {{ number_format($computed->totalGiven, 2, ',', ' ') }} €</span>
          </div>
        @endif

        <div class="flex justify-between px-6 py-3 text-sm bg-gray-100">
          <span class="font-bold uppercase text-lg tracking-wide text-gray-800">Montant total NDF</span>
          <span class="font-bold text-lg text-gray-900">{{ number_format($computed->totalNDF, 2, ',', ' ') }} €</span>
        </div>

        <div class="flex justify-between px-6 py-4 bg-gray-900">
          <span class="font-bold text-base text-white">Total a rembourser</span>
          <span class="font-bold text-xl text-green-400">
            {{ number_format($expensesClaim->total_reimbursed ?? $computed->netTotal, 2, ',', ' ') }} €
          </span>
        </div>

      </div>
    </div>
  </section>

  {{-- ─── DON / ABANDON INFO ──────────────────────────────────── --}}
  @if($computed->totalGiven > 0)
  <section class="mt-4 bg-green-50 border border-green-200 rounded-xl px-5 py-4 text-xs text-green-800">
    <p class="font-semibold mb-1">Abandon de frais - Don a la FFS</p>
    <p>
      Conformément à l'article 41 de la loi du 6 juillet 2000, vous bénéficiez d'une réduction d'impôt
      de <strong>66%</strong> du montant abandonné (dans la limite de 20% de votre revenu imposable).
      Un recu récapitulatif vous sera remis en fin d'année.
    </p>
    <p class="mt-1">
      Estimation de réduction d'impôt :
      <strong>{{ number_format($computed->taxReduction, 2, ',', ' ') }} €</strong>
    </p>
  </section>
  @endif

  {{-- ─── POUR INFORMATION ───────────────────────────────────── --}}
  <section class="mt-4 border border-2 border-gray-200 rounded-xl px-5 py-4 text-xs text-gray-600">
    <p class="font-semibold mb-2 text-gray-700 uppercase tracking-widest text-xs">Pour information</p>
    <div class="flex justify-between py-1 border-b border-gray-100">
      <span>En cas de règlement total</span>
      <span class="font-medium text-gray-900">{{ number_format($computed->fullSettlementTotal, 2, ',', ' ') }} €</span>
    </div>
    <div class="flex justify-between py-1">
      <span>Estimation réduction d'impôt (hors frais réels) si 100% en abandon</span>
      <span class="font-medium text-gray-900">{{ number_format($computed->fullAbandonTotal, 2, ',', ' ') }} €</span>
    </div>
  </section>

  {{-- ─── NOTE LÉGALE ─────────────────────────────────────────── --}}
  <footer class="mt-8 pt-4 border-t border-gray-200 text-xs text-gray-400 text-center">
    <p>Fédération Française de Spéléologie - 28 rue Delandine, 69002 Lyon - www.ffspeleo.fr</p>
    <p class="mt-1">
      Les notes de frais doivent être présentées dans les 30 jours suivant la dépense
      (15 jours en décembre). Au-delà, les remboursements ne sont plus possibles.
    </p>
    <p class="mt-2 text-gray-300">Générée le {{ now()->format('d/m/Y à H:i') }}</p>
  </footer>

</body>
</html>