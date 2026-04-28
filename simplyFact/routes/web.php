<?php

use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\ExpensesClaimController;
use App\Http\Controllers\FlowController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\OtherExpenseController;
use App\Http\Controllers\ProofController;
use App\Http\Controllers\UserController;
use App\Services\ExpenseClaimPdfService;
use App\Services\PdfGenerator;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Chemins temporaires pour dev
Route::inertia('travel', 'travel/Travel')->name('travel');
Route::inertia('travel-mode', 'travel/TravelMode')->name('travel-mode');
Route::inertia('travel-vehicle', 'travel/Vehicle')->name('travel-vehicle');
Route::inertia('travel-driven-trip', 'travel/DrivenTrip')->name('travel-driven-trip');

// Front : chemin pour afficher React en utilisant Inertia ??
Route::inertia('/', 'home')->name('home');
// A ajouter si on veut avoir une vérification d'identification avant complétion
//      , ['canRegister' => Features::enabled(Features::registration()),]

Route::resource('users', UserController::class);
// Route pour identification avant d'atteindre ces pages
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::inertia('dashboard', 'dashboard')->name('dashboard');
// });

Route::resource('expenses-claims', ExpensesClaimController::class);

Route::resource('expenses-claims.accommodations', AccommodationController::class);
Route::resource('expenses-claims.meals', MealController::class);
Route::resource('expenses-claims.other-expenses', OtherExpenseController::class);

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
        Route::get('/checkingClaims', 'checkingClaims')->name('checking-claims');
        Route::get('/done', 'done')->name('done');
    });

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

// Dev only - preview du PDF dans le browser
Route::get('/pdf-preview', function () {
    $service = new ExpenseClaimPdfService(
        new PdfGenerator
    );

    return $service->previewFake();
})->name('pdf.preview');

// Dev only - test envoi email PDF fictif
Route::get('/pdf-send-email', function () {
    $service = new ExpenseClaimPdfService(
        new PdfGenerator
    );

    $service->sendFakeByEmail('john.doe@email.com');

    return 'Email envoyé - voir http://localhost:8025';
})->name('pdf.send-email');

require __DIR__.'/settings.php';
