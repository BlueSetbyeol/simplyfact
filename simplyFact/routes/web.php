<?php

use App\Http\Controllers\ExpensesClaimController;
use App\Http\Controllers\FlowController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\ProofController;
use App\Http\Controllers\UserController;
use App\Services\ExpenseClaimPdfService;
use App\Services\PdfGenerator;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;

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

// Front : chemin pour afficher React en utilisant Inertia ??
Route::inertia('/', 'home')->name('home');
// A ajouter si on veut avoir une vérification d'identification avant complétion
//      , ['canRegister' => Features::enabled(Features::registration()),]

Route::resource('users', UserController::class);

Route::resource('expenses-claims', ExpensesClaimController::class);

// Nested resources
// Route::resource('expenses-claims.meals', MealController::class);

Route::resource('meals', MealController::class);

// Flow (wizard)
Route::prefix('expenses-claims/{expensesClaim}/flow')
    ->name('expenses-claims.flow.')
    ->controller(FlowController::class)
    ->group(function () {
        Route::post('/start', 'start')->name('start');
        Route::get('/next', 'next')->name('next');
        Route::post('/enter-child', 'enterChild')->name('enter-child');
        Route::post('/return-parent', 'returnParent')->name('return-parent');
        Route::post('/complete-step', 'completeStep')->name('complete-step');
        Route::get('/done', 'done')->name('done');
    });

// Route::get('/expenses-claims', [ExpensesClaimController::class, 'index'])->name('expensesClaim.index');
// Route::post('/expenses-claims', [ExpensesClaimController::class, 'store'])->name('expensesClaim.store');
// // {expenses_claim} : route model binding, c'est le front qui donne l'id recherché
// Route::get('/expenses-claims/{expensesClaim}/edit', [ExpensesClaimController::class, 'edit'])->name('expensesClaim.edit');
// Route::put('/expenses-claims/{expensesClaim}', [ExpensesClaimController::class, 'update'])->name('expensesClaim.update');
// Route::delete('/expenses-claims/{expensesClaim}', [ExpensesClaimController::class, 'destroy'])->name('expensesClaim.destroy');

// Route::resource('vehicle', \App\Http\Controllers\VehicleController::class);

Route::get('/pdf-preview', function () {
    $service = new ExpenseClaimPdfService(
        new PdfGenerator
    );

    return $service->previewFake();
})->name('pdf.preview');

Route::post(
    '/expenses-claims/{expensesClaim}/proofs',
    [ProofController::class, 'store']
)->name('expenses-claims.proofs.store');

Route::delete(
    '/expenses-claims/{expensesClaim}/proofs',
    [ProofController::class, 'destroy']
)->name('expenses-claims.proofs.destroy');

require __DIR__.'/settings.php';

// Route pour identification avant d'atteindre ces pages
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::inertia('dashboard', 'dashboard')->name('dashboard');
// });
