<?php

use App\Livewire\MyOrder;
use App\Http\Controllers\AssignmentPDFController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderPDFController;
use App\Livewire\Home;
use Filament\Infolists\Infolist;
use App\Livewire\ContractOrder;
use Filament\Pages\Auth\Login;
use App\Filament\Resources\ContractResource;
use App\Livewire\OrderSuccessMessage;
use App\Livewire\ViewOrder;

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

Route::get('/', Home::class)->name('home');

Route::get('/orderpdf/{id}', [OrderPDFController::class, 'contractpdf'])->name('order.pdf');

Route::get('/assignmentpdf/{id}', [AssignmentPDFController::class, 'assignmentpdf'])->name('assignment.pdf');

Route::get('/new-order', ContractOrder::class)->name('contract-order');

Route::get('/my-order', MyOrder::class)->name('my-order');

Route::get('/order-sent', OrderSuccessMessage::class)->name('order-message');
