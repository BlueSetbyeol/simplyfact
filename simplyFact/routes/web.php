<?php

use App\Http\Controllers\ExpensesClaimController;
use App\Http\Controllers\FlowController;
use App\Http\Controllers\MealController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;


//Front : chemin pour afficher React en utilisant Inertia ??
Route::inertia('/', 'home'
// A ajouter si on veut avoir une vérification d'identification avant complétion
//      , ['canRegister' => Features::enabled(Features::registration()),]
)->name('home');
// aussi possible sous les formes : 
//      Route::inertia('/home', 'home');
//      Route::get('/', function(){return [...]})

//Back : chemin "API" => code php Laravel + render des vues des pages statics

Route::post('/flow/start',          [FlowController::class, 'start'])->name('flow.start');
Route::get('/flow/next',            [FlowController::class, 'next'])->name('flow.next');
Route::post('/flow/enter-child',    [FlowController::class, 'enterChild'])->name('flow.enter-child');
Route::post('/flow/return-parent',  [FlowController::class, 'returnToParent'])->name('flow.return-parent');
Route::post('/flow/complete-step',  [FlowController::class, 'completeStep'])->name('flow.complete-step');
Route::get('/flow/done',            [FlowController::class, 'done'])->name('flow.done');

// TODO Attention au nom de la route, probablement à changer en fonction du Front.
Route::resource('/expensesClaim', ExpensesClaimController::class)
->only(['store','edit','update','destroy']);

Route::resource('/meal', MealController::class)
->only(['store','edit','update','destroy']);

//remplace ci-dessous :
//      Route::post('/expenses_claim', [ExpensesClaimController::class, 'store']);
//      {expenses_claim} : route model binding, c'est le front qui donne l'id recherché
//      Route::get('/expenses_claim/{expenses_claim}/edit', [ExpensesClaimController::class, 'edit']);
//      Route::put('/expenses_claim/{expenses_claim}', [ExpensesClaimController::class, 'update']);
//      Route::delete('/expenses_claim/{expenses_claim}', [ExpensesClaimController::class, 'destroy']);

// Route::resource('vehicle', \App\Http\Controllers\VehicleController::class);

require __DIR__.'/settings.php';



//Route pour identification avant d'atteindre ces pages
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::inertia('dashboard', 'dashboard')->name('dashboard');
// });