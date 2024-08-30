<?php

use App\Http\Controllers\DomainController;
use Illuminate\Support\Facades\Route;

Route::post('/fetch-domain', [DomainController::class, 'fetchDomain'])->name('fetch.domain');
