<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\ExpenseClaimMail;
use App\Models\ExpensesClaim;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class ExpenseClaimPdfService
{
    public function __construct(
        private readonly PdfGenerator $pdfGenerator
    ) {}

    /**
     * Génère le PDF d'une note de frais depuis la BDD.
     * À implémenter quand les Models seront disponibles.
     */
    public function generateAndSend(string $expenseClaimId): void
    {
        $expensesClaim = ExpensesClaim::with([
            'user',
            // 'vehicle',
            // 'drivenTrips',
            // 'otherTrips',
            // 'accommodations',
            'meals',
            // 'otherExpenses',
        ])->findOrFail($expenseClaimId);

        dd($expensesClaim->toArray());

        $computed = $this->computeAmounts($expensesClaim);

        $pdfContent = $this->pdfGenerator
            ->view('pdf.expense-claim-pdf', [
                'logoBase64' => base64_encode(file_get_contents(public_path('images/logo-ffs.jpg'))),
                'user' => $expensesClaim->user,
                'expensesClaim' => $expensesClaim,
                'vehicle' => null, // TODO: $expensesClaim->vehicle
                'drivenTrips' => $computed->drivenTrips,
                'otherTrips' => collect(), // TODO: $expensesClaim->otherTrips
                'accommodations' => collect(), // TODO: $expensesClaim->accommodations
                'meals' => $expensesClaim->meals,
                'otherExpenses' => collect(), // TODO: $expensesClaim->otherExpenses
                'computed' => $computed,
            ])->getDocument();

        Mail::to(config('mail.to_accountant'))
            ->send(new ExpenseClaimMail($pdfContent));
    }

    /**
     * Preview avec données fictives pour tester le rendu PDF.
     */
    public function previewFake(): Response
    {
        return $this->buildFakePdf()->inlineResponse('preview-note-de-frais.pdf');
    }

    /**
     * Envoie le PDF par email avec des données fictives.
     */
    public function sendFakeByEmail(string $toEmail): void
    {
        $pdfContent = $this->buildFakePdf()->getDocument();

        Mail::to($toEmail)->send(new ExpenseClaimMail($pdfContent));
    }

    /**
     * Prépare le PdfGenerator avec les données fictives.
     * Partagé entre previewFake() et sendFakeByEmail().
     */
    private function buildFakePdf(): PdfGenerator
    {
        // Use fake data for now
        $fakeExpensesClaim = (object) [
            'action_name' => 'Stage fédéral spéléologie',
            'action_dates' => '15-17 mars 2026',
            'committee_name' => 'Commission Formation',
            'total_given' => 133.25,
            'total_reimbursed' => null,
            // Fake relations
            'vehicle' => (object) [
                'vehicule_type' => 'voiture',
                'electrical' => false,
                'number_plate' => 'AB-123-CD',
                'power' => '6',
            ],
            'drivenTrips' => collect([
                (object) [
                    'starting_city' => 'Lyon',
                    'ending_city' => 'Grenoble',
                    'trip_type' => 'Aller-retour',
                    'total_distance' => 220,
                    'total_distance_given' => 50,
                    'total_price_given' => 33.25,
                ],
            ]),
            'otherTrips' => collect([
                (object) ['expense_name' => 'Péages autoroute', 'expense_price' => 12.40],
                (object) ['expense_name' => 'Train Lyon - Paris', 'expense_price' => 67.00],
            ]),
            'accommodations' => collect([
                (object) [
                    'accomodation_type' => 'Hôtel province hors cœur de ville',
                    'nb_of_night' => 2,
                    'total_price' => 160.00,
                    'reimbursed_price' => 140.00,
                ],
            ]),
            'meals' => collect([
                (object) [
                    'nb_of_meal' => 3,
                    'total_price' => 78.00,
                    'reimbursed_price' => 75.00,
                ],
            ]),
            'otherExpenses' => collect([
                (object) ['expense_name' => 'Fournitures de bureau', 'expense_price' => 14.50, 'nb_days_of_training' => null],
                (object) ['expense_name' => 'Timbres', 'expense_price' => 3.20, 'nb_days_of_training' => null],
                (object) ['expense_name' => 'Stage fédéral - participation frais matériels', 'expense_price' => 63.90, 'nb_days_of_training' => 3],
            ]),
        ];

        $fakeUser = (object) [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'address_street' => '6 impasse Gord',
            'address_zipcode' => '69004',
            'address_city' => 'Lyon',
            'address_country' => 'France',
            'email_address' => 'john.doe@email.com',
            'phone_number' => '06 12 34 56 78',
        ];

        $computed = $this->computeAmounts($fakeExpensesClaim);

        return $this->pdfGenerator
            ->view('pdf.expense-claim-pdf', [
                'logoBase64' => base64_encode(file_get_contents(public_path('images/logo-ffs.jpg'))),
                'user' => $fakeUser,
                'expensesClaim' => $fakeExpensesClaim,
                'vehicle' => $fakeExpensesClaim->vehicle,
                'drivenTrips' => $computed->drivenTrips,
                'otherTrips' => $fakeExpensesClaim->otherTrips,
                'accommodations' => $fakeExpensesClaim->accommodations,
                'meals' => $fakeExpensesClaim->meals,
                'otherExpenses' => $fakeExpensesClaim->otherExpenses,
                'computed' => $computed,
            ])
            ->merge($this->fakeJustificatifs());
    }

    /**
     * Calcul des montants à rembourser pour chaque type de dépense.
     */
    private function computeAmounts(object $expensesClaim): object
    {
        $vehicle = $expensesClaim->vehicle ?? null;
        $drivenTrips = $expensesClaim->drivenTrips ?? collect();
        $otherTrips = $expensesClaim->otherTrips ?? collect();
        $accommodations = $expensesClaim->accommodations ?? collect();
        $meals = $expensesClaim->meals ?? collect();
        $otherExpenses = $expensesClaim->otherExpenses ?? collect();

        $rate = 0;
        $rateUrssaf = 0;

        if ($vehicle) {
            // Taux FFS (remboursement effectif)
            $rate = $vehicle->vehicule_type === 'moto'
                ? ($vehicle->electrical ? 0.168 : 0.14)
                : ($vehicle->electrical ? 0.432 : 0.36);

            // Barèmes URSSAF 2025
            $urssafVoiture = [
                '3' => ['standard' => 0.529, 'electrique' => 0.635],
                '4' => ['standard' => 0.606, 'electrique' => 0.727],
                '5' => ['standard' => 0.636, 'electrique' => 0.763],
                '6' => ['standard' => 0.665, 'electrique' => 0.798],
                '7' => ['standard' => 0.697, 'electrique' => 0.836],
            ];

            $urssafMoto = [
                '1' => ['standard' => 0.395, 'electrique' => 0.474],
                '2' => ['standard' => 0.395, 'electrique' => 0.474],
                '3' => ['standard' => 0.468, 'electrique' => 0.562],
                '4' => ['standard' => 0.468, 'electrique' => 0.562],
                '5' => ['standard' => 0.468, 'electrique' => 0.562],
                '6' => ['standard' => 0.606, 'electrique' => 0.727],
            ];

            $isMoto = $vehicle->vehicule_type === 'moto';
            $power = min((int) $vehicle->power, $isMoto ? 6 : 7);
            $typeKey = $vehicle->electrical ? 'electrique' : 'standard';
            $baremeUrssaf = $isMoto ? $urssafMoto : $urssafVoiture;
            $rateUrssaf = $baremeUrssaf[(string) $power][$typeKey];

            if (! $rateUrssaf) {
                throw new \RuntimeException("Taux URSSAF introuvable pour puissance {$vehicle->power} ({$typeKey})");
            }
        }

        // Déplacements
        $drivenTripsComputed = $drivenTrips->map(fn ($t) => (object) array_merge(
            (array) $t,
            ['reimbursed_amount' => ($t->total_distance - ($t->total_distance_given ?? 0)) * $rate]
        ));

        $totalReimbursedKm = $drivenTripsComputed->sum('reimbursed_amount');
        $abandonKm = $drivenTrips->sum('total_price_given');
        $totalDistanceGiven = $drivenTrips->sum('total_distance_given');

        // Totaux autres catégories
        $totalOtherTrips = $otherTrips->sum('expense_price');
        $totalAccommodation = $accommodations->sum('reimbursed_price');
        $totalMeals = $meals->sum('reimbursed_price');
        $totalOtherExpenses = $otherExpenses->sum('expense_price');
        $totalWithoutKm = $totalOtherTrips + $totalAccommodation + $totalMeals + $totalOtherExpenses;

        // Abandons
        $totalGiven = $expensesClaim->total_given ?? 0;
        $abandonOthers = $totalGiven - $abandonKm;

        // Total NDF
        $totalNDF = $totalReimbursedKm + $abandonKm + $totalWithoutKm;

        // Total à rembourser
        $netTotal = $totalNDF - $totalGiven;

        // Section "Pour information"
        $fullSettlementTotal = ($drivenTrips->sum('total_distance') * $rate) + $totalWithoutKm;
        $totalKmUrssaf = $drivenTrips->sum('total_distance') * $rateUrssaf;
        $fullAbandonTotal = ($totalKmUrssaf + $totalWithoutKm) * 0.66;

        // Réduction d'impôt sur l'abandon actuel
        $taxReduction = $totalGiven * 0.66;

        return (object) [
            'rate' => $rate,
            'drivenTrips' => $drivenTripsComputed,
            'totalReimbursedKm' => $totalReimbursedKm,
            'totalDistanceGiven' => $totalDistanceGiven,
            'abandonKm' => $abandonKm,
            'totalOtherTrips' => $totalOtherTrips,
            'totalAccommodation' => $totalAccommodation,
            'totalMeals' => $totalMeals,
            'totalOtherExpenses' => $totalOtherExpenses,
            'totalWithoutKm' => $totalWithoutKm,
            'totalGiven' => $totalGiven,
            'abandonOthers' => $abandonOthers,
            'totalNDF' => $totalNDF,
            'netTotal' => $netTotal,
            'taxReduction' => $taxReduction,
            'fullSettlementTotal' => $fullSettlementTotal,
            'fullAbandonTotal' => $fullAbandonTotal,
        ];
    }

    /**
     * URLs publiques de PDFs pour tester la fusion des justificatifs.
     */
    private function fakeJustificatifs(): array
    {
        return [
            'https://pour-un-reveil-ecologique.org/documents/54/10_key_points_IPCC_1_2_and_3.pdf',
        ];
    }
}
