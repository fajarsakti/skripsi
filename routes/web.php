<?php

use App\Livewire\MyOrder;
use App\Http\Controllers\AssignmentPDFController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Livewire\Home;
use Filament\Infolists\Infolist;
use App\Livewire\ContractOrder;
use Filament\Pages\Auth\Login;

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

Route::get('/', Home::class)->name('home');

Route::get('/contractpdf/{id}', [PDFController::class, 'contractpdf'])->name('contract.pdf');

Route::get('/assignmentpdf/{id}', [AssignmentPDFController::class, 'assignmentpdf'])->name('assignment.pdf');

Route::get('/contract-order', ContractOrder::class)->name('contract-order');

Route::get('/my-order', MyOrder::class)->name('my-order');

// Route::get('/login', Login::class)->name('login');

// Route::post('/submit-form', 'FormController@submit')->middleware('auth');
