<?php

use App\Http\Controllers\ExpensesClaimController;
use App\Http\Controllers\FlowController;
use App\Http\Controllers\MealController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

<<<<<<< HEAD
Route::inertia('meal', 'meal/MealForm')->name('meal');
Route::inertia('who-first', 'who/WhoFirst')->name('who-first');
=======
>>>>>>> dev

//Front : chemin pour afficher React en utilisant Inertia ??
Route::inertia('/', 'home')->name('home');
// A ajouter si on veut avoir une vérification d'identification avant complétion
//      , ['canRegister' => Features::enabled(Features::registration()),]

Route::resource('expenses-claims', ExpensesClaimController::class);

// Nested resources
Route::resource('expenses-claims.meals', MealController::class);

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
    
// Route::post('/flow/start',          [FlowController::class, 'start'])->name('flow.start');
// Route::get('/flow/next',            [FlowController::class, 'next'])->name('flow.next');
// Route::post('/flow/enter-child',    [FlowController::class, 'enterChild'])->name('flow.enter-child');
// Route::post('/flow/return-parent',  [FlowController::class, 'returnToParent'])->name('flow.return-parent');
// Route::post('/flow/complete-step',  [FlowController::class, 'completeStep'])->name('flow.complete-step');
// Route::get('/flow/done',            [FlowController::class, 'done'])->name('flow.done');

// // Route::inertia('meal', 'meal/MealForm')->name('meal'); ==> taken into MealController.php
// Route::get('/expenses-claims/{expensesClaim}/meal',           [MealController::class, 'index'])->name('meal.index');
// Route::post('/expenses-claims/{expensesClaim}/meal',          [MealController::class, 'store'])->name('meal.store');
// Route::get('/expenses-claims/{expensesClaim}/meal/{meal}/edit',    [MealController::class, 'edit'])->name('meal.edit');
// Route::put('/expenses-claims/{expensesClaim}/meal/{meal}',         [MealController::class, 'update'])->name('meal.update');
// Route::delete('/expenses-claims/{expensesClaim}/meal/{meal}',      [MealController::class, 'destroy'])->name('meal.destroy');


// Route::resource('vehicle', \App\Http\Controllers\VehicleController::class);

require __DIR__.'/settings.php';

//Route pour identification avant d'atteindre ces pages
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::inertia('dashboard', 'dashboard')->name('dashboard');
// });