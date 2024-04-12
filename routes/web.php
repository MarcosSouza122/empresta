<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginInternController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CursoHabilitacaoController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\VisitanteController;
use App\Http\Controllers\EmprestimoController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Mail\MaterialDevolvido;
use App\Mail\MaterialEmprestado;
use App\Models\Emprestimo;

Route::get('/home', function () {
    return redirect()->route('emprestimos.index');
})->name('home');

Route::get('/', [LoginInternController::class, 'showLoginForm'])->name('show_login_intern');
Route::post('/', [LoginInternController::class, 'login'])->name('login_intern');

Route::get('emprestimos/relatorio', [EmprestimoController::class,'relatorio'])->name('emprestimos.relatorio');
Route::get('emprestimos/usp', [EmprestimoController::class,'usp'])->name('emprestimos.usp');
Route::get('emprestimos/visitante', [EmprestimoController::class,'visitante'])->name('emprestimos.visitante');
Route::get('emprestimos/devolucao', [EmprestimoController::class,'devolucao'])->name('emprestimos.devolucao');
Route::post('emprestimos/devolver', [EmprestimoController::class,'devolver'])->name('emprestimos.devolver');

Route::get('categorias/barcode', [CategoriaController::class,'barcode'])->name('categorias.barcode');
Route::post('categorias/barcodes', [CategoriaController::class,'barcodes'])->name('categorias.barcodes');
Route::resource('categorias', CategoriaController::class);
Route::get('materials/create/{categoria?}', [MaterialController::class, 'create'])->name('materials.create');
Route::resource('materials', MaterialController::class)->except('create');
Route::resource('visitantes', VisitanteController::class);
Route::resource('emprestimos', EmprestimoController::class);
Route::resource('users', UserController::class);

Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');

Route::resource('cursos_hab', CursoHabilitacaoController::class)->parameters(['cursos_hab' => 'curso']);

Route::get('mail', function(){
    $emprestimo = Emprestimo::find(13);

    return new MaterialEmprestado($emprestimo);
});