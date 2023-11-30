<?php

use App\Http\Controllers\AssignmentPDFController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Livewire\Home;
use Filament\Infolists\Infolist;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', Home::class);

Route::get('/contractpdf/{id}', [PDFController::class, 'contractpdf'])->name('contract.pdf');

Route::get('/assignmentpdf/{id}', [AssignmentPDFController::class, 'assignmentpdf'])->name('assignment.pdf');

// Route::get('/contract-order', ContractOrder::class);
