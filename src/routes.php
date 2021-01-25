<?php

use Bigmom\Excel\Http\Controllers\ExportController;
use Bigmom\Excel\Http\Controllers\ImportController;
use Bigmom\Excel\Http\Middleware\EnsureUserIsAdmin;
use Bigmom\Excel\Http\Middleware\EnsureUserIsExportAuthorized;
use Bigmom\Excel\Http\Middleware\EnsureUserIsImportAuthorized;
use Bigmom\Auth\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::prefix('bigmom/excel')->name('bigmom-excel.')->middleware(['web', Authenticate::class])->group(function () {
    Route::prefix('import')->name('import.')->middleware([EnsureUserIsImportAuthorized::class])->group(function () {
        Route::get('/', [ImportController::class, 'getIndex'])->name('getIndex');
        Route::post('/post', [ImportController::class, 'postImport'])->name('postImport');
        Route::post('/postConfirmImport', [ImportController::class, 'postConfirmImport'])->name('postConfirmImport');
    });
    Route::prefix('export')->name('export.')->middleware([EnsureUserIsExportAuthorized::class])->group(function () {
        Route::get('/', [ExportController::class, 'getIndex'])->name('getIndex');
        Route::get('/admin', [ExportController::class, 'getAdminIndex'])->middleware([EnsureUserIsAdmin::class])->name('getAdminIndex');
        Route::get('/download', [ExportController::class, 'download'])->name('download');
    });
});
