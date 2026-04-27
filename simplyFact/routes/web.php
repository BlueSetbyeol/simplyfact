<?php

use App\Http\Controllers\ExpensesClaimController;
use App\Http\Controllers\FlowController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\ProofController;
use App\Http\Controllers\UserController;
use App\Models\ExpensesClaim;
use App\Services\ExpenseClaimPdfService;
use App\Services\PdfGenerator;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


// Chemins temporaires pour dev
Route::inertia('informations', 'user/Informations')->name('informations');
Route::inertia('choices', 'choices/Choices')->name('choices');
Route::get('/pathway', function (Request $request) {
    return Inertia::render('choices/SumChoices', [
        'steps' => $request->input('steps', []),
        'expensesClaimId' => $request->input('expensesClaimId'),
    ]);
})->name('pathway');
Route::inertia('accommodation', 'accommodation/Accommodation')->name('accomodation');
Route::inertia('accommodation-details', 'accommodation/AccommodationDetails')->name('accomodation-details');

// Front : chemin pour afficher React en utilisant Inertia ??
Route::inertia('/', 'home')->name('home');
// A ajouter si on veut avoir une vérification d'identification avant complétion
//      , ['canRegister' => Features::enabled(Features::registration()),]

// Route pour le développement
Route::inertia('/end', 'end/End') -> name('end');


Route::resource('users', UserController::class);
// Route pour identification avant d'atteindre ces pages
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::inertia('dashboard', 'dashboard')->name('dashboard');
// });

Route::resource('expenses-claims', ExpensesClaimController::class);

Route::resource('expenses-claims.meals', MealController::class);
// Route::resource('vehicle', \App\Http\Controllers\VehicleController::class);

// Flow (parcours de l'utilisateur)
Route::prefix('expenses-claims/{expensesClaim}/flow')
    ->name('expenses-claims.flow.')
    ->controller(FlowController::class)
    ->group(function () {
        Route::get('/choices', 'choices')->name('choices');
        Route::post('/choices', 'saveChoices')->name('choices.save');
        Route::get('/summary', 'summary')->name('summary');
        Route::post('/start', 'start')->name('start');
        Route::get('/next', 'next')->name('next');
        Route::post('/enter-child', 'enterChild')->name('enter-child');
        Route::post('/return-parent', 'returnParent')->name('return-parent');
        Route::post('/complete-step', 'completeStep')->name('complete-step');
        Route::get('/checkingClaims', 'checkingClaims')->name('checkingClaims');
        Route::get('/done', 'done')->name('done');
    });

// Route::get('/expenses-claims', [ExpensesClaimController::class, 'index'])->name('expensesClaim.index');
// Route::post('/expenses-claims', [ExpensesClaimController::class, 'store'])->name('expensesClaim.store');
// Route::get('/expenses-claims/{expensesClaim}/edit', [ExpensesClaimController::class, 'edit'])->name('expensesClaim.edit');
// Route::put('/expenses-claims/{expensesClaim}', [ExpensesClaimController::class, 'update'])->name('expensesClaim.update');
// Route::delete('/expenses-claims/{expensesClaim}', [ExpensesClaimController::class, 'destroy'])->name('expensesClaim.destroy');

// création du pdf avant envoi
Route::get('/pdf-preview', function () {
    $service = new ExpenseClaimPdfService(
        new PdfGenerator
    );

    return $service->previewFake();
})->name('pdf.preview');

// upload de documents justificatif
Route::post(
    '/expenses-claims/{expensesClaim}/proofs',
    [ProofController::class, 'store']
)->name('expenses-claims.proofs.store');

// suppression des documents justificatif
Route::delete(
    '/expenses-claims/{expensesClaim}/proofs',
    [ProofController::class, 'destroy']
)->name('expenses-claims.proofs.destroy');

require __DIR__.'/settings.php';
