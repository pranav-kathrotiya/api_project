<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ProviderStaffController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CompanyConroller;
use App\Http\Controllers\DetailsController;
use App\Http\Controllers\DiseasesController;
use App\Http\Controllers\Frontend\HomesController;
use App\Http\Controllers\InsuranceProductController;
use App\Http\Controllers\PreEgibilityCheckController;
use App\Http\Controllers\ProviderPriceController;
use App\Http\Controllers\UserController;
use App\Models\Insurance;
use App\Models\InsuranceProduct;
use App\Models\Patients;
use App\Models\ProviderPrice;
use Faker\Provider\ar_EG\Company;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Frontend\ServiceController;

use App\Http\Controllers\Frontend\CheckEligibilityController;
use App\Http\Controllers\frontend\ProfileController;
use Symfony\Component\HttpKernel\Profiler\Profile;


// use App\Http\Controllers\Frontend\HomeController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
