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

    /** Génère le PDF d'une note de frais depuis la BDD.*/
    public function generateAndSend(string $expenseClaimId): void
    {
        ini_set('memory_limit', '512M');

        $expensesClaim = ExpensesClaim::with([
            'user',
            'drivenTrips.vehicle',
            'otherTrips',
            'accommodations',
            'meals',
            'otherExpenses',
            'trainingExpenses',
        ])->findOrFail($expenseClaimId);

        $computed = $this->computeAmounts($expensesClaim);

        // Preuves s3 signed URLs pour merger dans le PDF
        $proofService = new ProofUploadService;
        $proofs = $proofService->getSignedUrls($expenseClaimId);

        if (empty($proofs)) {
            throw new \RuntimeException('Impossible de générer le PDF : aucun justificatif fourni.');
        }

        $pdfContent = $this->pdfGenerator
            ->view('pdf.expense-claim-pdf', [
                'logoBase64' => base64_encode(file_get_contents(public_path('images/logo-ffs.jpg'))),
                'user' => $expensesClaim->user,
                'expensesClaim' => $expensesClaim,
                'drivenTrips' => $expensesClaim->drivenTrips,
                'otherTrips' => $expensesClaim->otherTrips,
                'accommodations' => $expensesClaim->accommodations,
                'meal' => $expensesClaim->meals,
                'trainingExpense' => $expensesClaim->trainingExpenses,
                'otherExpenses' => $expensesClaim->otherExpenses,
                'computed' => $computed,
            ])
            ->urls($proofs)
            ->getDocument();

        Mail::to(config('mail.to_accountant'))
            ->send(new ExpenseClaimMail($pdfContent));
    }

    /**
     * Calcul des montants à rembourser pour chaque type de dépense.
     */
    private function computeAmounts(object $expensesClaim): object
    {
        $drivenTrips = $expensesClaim->drivenTrips ?? collect();
        $otherTrips = $expensesClaim->otherTrips ?? collect();
        $accommodations = $expensesClaim->accommodations ?? collect();
        $meal = $expensesClaim->meals ?? null;
        $trainingExpense = $expensesClaim->trainingExpenses ?? null;
        $otherExpenses = $expensesClaim->otherExpenses ?? collect();

        $totalReimbursedKm = $drivenTrips->sum('reimbursed_price');
        $abandonKm = $drivenTrips->sum('total_price_given');
        $totalDistanceGiven = $drivenTrips->sum('total_distance_given');

        $totalOtherTrips = $otherTrips->sum('reimbursed_price');
        $totalAccommodation = $accommodations->sum('reimbursed_price');
        $totalMeals = $meal?->reimbursed_price ?? 0;
        $totalTraining = $trainingExpense?->reimbursed_price ?? 0;
        $totalOtherExpenses = $otherExpenses->sum('reimbursed_price');

        $totalWithoutKm = $totalOtherTrips + $totalAccommodation + $totalMeals + $totalTraining + $totalOtherExpenses;

        $abandonOthers = (float) ($expensesClaim->total_given ?? 0); // ($expensesClaim->total_given = total without kms given)
        $totalGiven = $abandonKm + $abandonOthers;

        $totalNDF = $totalReimbursedKm + $abandonKm + $totalWithoutKm;
        $netTotal = $totalNDF - $totalGiven;

        $taxReduction = round($totalGiven * 0.66, 2);

        $fullSettlementTotal = $drivenTrips->sum('total_price') + $totalWithoutKm;
        $fullAbandonTotal = round(($abandonKm + $totalWithoutKm) * 0.66, 2);

        return (object) [
            'drivenTrips' => $drivenTrips,
            'totalReimbursedKm' => $totalReimbursedKm,
            'totalDistanceGiven' => $totalDistanceGiven,
            'abandonKm' => $abandonKm,
            'abandonOthers' => $abandonOthers,
            'totalOtherTrips' => $totalOtherTrips,
            'totalAccommodation' => $totalAccommodation,
            'totalMeals' => $totalMeals,
            'totalTraining' => $totalTraining,
            'totalOtherExpenses' => $totalOtherExpenses,
            'totalWithoutKm' => $totalWithoutKm,
            'totalGiven' => $totalGiven,
            'totalNDF' => $totalNDF,
            'netTotal' => $netTotal,
            'taxReduction' => $taxReduction,
            'fullSettlementTotal' => $fullSettlementTotal,
            'fullAbandonTotal' => $fullAbandonTotal,
        ];
    }

    // /**
    //  * Preview avec données fictives pour tester le rendu PDF.
    //  */
    public function previewFake(): Response
    {
        return $this->buildFakePdf()->inlineResponse('preview-note-de-frais.pdf');
    }

    // /**
    //  * Envoie le PDF par email avec des données fictives.
    //  */
    public function sendFakeByEmail(string $toEmail): void
    {
        $pdfContent = $this->buildFakePdf()->getDocument();

        Mail::to($toEmail)->send(new ExpenseClaimMail($pdfContent));
    }

    // /**
    //  * Prépare le PdfGenerator avec les données fictives.
    //  * Partagé entre previewFake() et sendFakeByEmail().
    //  */
    private function buildFakePdf(): PdfGenerator
    {
        // Use fake data for now
        $fakeExpensesClaim = (object) [
            'action_name' => 'Stage fédéral spéléologie',
            'action_dates' => '15-17 mars 2026',
            'committee_name' => 'Commission Formation',
            'total_given' => 100.00,
            'total_reimbursed' => null,
            'drivenTrips' => collect([
                (object) [
                    'starting_city' => 'Lyon',
                    'ending_city' => 'Grenoble',
                    'trip_type' => 'voiture',
                    'total_distance' => 220,
                    'total_distance_given' => 50,
                    'total_price' => 61.20,
                    'total_price_given' => 33.25,
                    'reimbursed_price' => 27.95,
                    'vehicle' => (object) [
                        'vehicle_type' => 'voiture',
                        'electrical' => false,
                        'number_plate' => 'AB-123-CD',
                        'power' => '6CV',
                        'price_given' => 0.665,
                    ],
                ],
            ]),
            'otherTrips' => collect([
                (object) ['expense_name' => 'Péages autoroute', 'reimbursed_price' => 12.40],
                (object) ['expense_name' => 'Train Lyon - Paris', 'reimbursed_price' => 67.00],
            ]),
            'accommodations' => collect([
                (object) [
                    'accommodation_type' => 'Hôtel province hors cœur de ville',
                    'nb_of_night' => 2,
                    'total_price' => 160.00,
                    'reimbursed_price' => 140.00,
                ],
            ]),
            'meals' => (object) [
                'number_of_meal' => 3,
                'total_price' => 78.00,
                'reimbursed_price' => 75.00,
            ],
            'trainingExpenses' => (object) [
                'nb_days_of_training' => 3,
                'reimbursed_price' => 63.90,
            ],
            'otherExpenses' => collect([
                (object) ['expense_name' => 'Fournitures de bureau', 'reimbursed_price' => 14.50, 'nb_days_of_training' => null],
                (object) ['expense_name' => 'Timbres', 'reimbursed_price' => 3.20, 'nb_days_of_training' => null],
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
                'drivenTrips' => $computed->drivenTrips,
                'otherTrips' => $fakeExpensesClaim->otherTrips,
                'accommodations' => $fakeExpensesClaim->accommodations,
                'meal' => $fakeExpensesClaim->meals,
                'trainingExpense' => $fakeExpensesClaim->trainingExpenses,
                'otherExpenses' => $fakeExpensesClaim->otherExpenses,
                'computed' => $computed,
            ])
            ->urls($this->fakeJustificatifs());
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
