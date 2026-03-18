<?php

declare(strict_types=1);

use App\Http\Controllers\Api\TodoApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('todos')->group(function (): void {
    Route::get('/', [TodoApiController::class, 'index']);
    Route::post('/', [TodoApiController::class, 'store']);
    Route::match(['put', 'patch'], '/{todo}', [TodoApiController::class, 'update']);
    Route::delete('/{todo}', [TodoApiController::class, 'destroy']);
});
