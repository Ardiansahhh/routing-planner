<?php

use App\Http\Controllers\BARANG\BarangController;
use App\Http\Controllers\CABANG\BranchController;
use App\Http\Controllers\EMPLOYEE\EmployeeController;
use App\Http\Controllers\HO\HOController;
use App\Http\Controllers\IT\ITController;
use App\Http\Controllers\LOGIN\LoginController;
use App\Http\Controllers\RAYON\CountRayonController;
use App\Http\Controllers\VEHICLE\VehicleController;
use App\Http\Controllers\WH\AreaController;
use App\Http\Controllers\WH\RayonController;
use App\Http\Controllers\WH\WHController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// ROUTE LOGIN
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/proses-login', [LoginController::class, 'store'])->name('proses-login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => ['CekLogin:1']], function () {
        Route::get('/IT', [ITController::class, 'index'])->name('IT');

        //Route for Access Apps
        Route::get('/access', [ITController::class, 'index']);
        Route::get('/input-access', [ITController::class, 'input']);
        Route::post('/store-access', [ITController::class, 'store']);

        //Route for Setting Branch
        Route::get('/cabang', [BranchController::class, 'index']);
        Route::get('/input-sto/{FC_BRANCH}', [BranchController::class, 'input_sto']);
        Route::get('/satelite/{FC_BRANCH}', [BranchController::class, 'satelite']);
        Route::get('/setup-gt/{CODE_STOF}/{FC_BRANCH}', [BranchController::class, 'setup_gt']);
        Route::get('/setup-mt/{CODE_STOF}/{FC_BRANCH}', [BranchController::class, 'setup_mt']);
        Route::post('/set-all', [BranchController::class, 'set_all']);
        Route::post('/set-gt', [BranchController::class, 'set_gt']);
        Route::post('/set-mt', [BranchController::class, 'set_mt']);
        Route::post('/setting-dc', [BranchController::class, 'setting_dc']);
        Route::post('/store', [BranchController::class, 'store']);

        //Route for reset
        Route::get('/reset',  [WHController::class, 'reset']);
    });

    Route::group(['middleware' => ['CekLogin:2']], function () {
        // Route::get('/WH', [CountRayonController::class, 'index'])->name('WH');
        Route::get('/WH', [WHController::class, 'index'])->name('WH');
        //Route for create routing planner
        Route::post('/create',  [WHController::class, 'store']);
        Route::get('/routing-list',  [WHController::class, 'list']);
        Route::get('/pilih/{routing}',  [WHController::class, 'pilih']);
        Route::post('/range-tanggal',  [WHController::class, 'rangetanggal']);
        Route::post('/checkbox',  [WHController::class, 'checkbox']);
        Route::get('/detail-barang/{routing}',  [WHController::class, 'detail_barang']);
        Route::get('/detail-toko-routing/{routing}',  [WHController::class, 'detail_toko_routing']);
        Route::get('/reset',  [WHController::class, 'reset']); // Jika sudah tidak dipakai, hapus route 
        Route::post('/load-monthly',  [WHController::class, 'load_monthly']);
        Route::post('/confirm',  [WHController::class, 'confirm']);
        Route::post('/delete-toko',  [WHController::class, 'delete_toko']);
        Route::post('/filter_by',  [WHController::class, 'filter_by']);
        Route::post('/filter_byy',  [WHController::class, 'filter_byy']);
        Route::post('/detail-kelurahan',  [WHController::class, 'detail_kelurahan']);
        Route::post('/detail-rayon',  [WHController::class, 'detail_rayon']);
        Route::post('/pilih-kelurahan',  [WHController::class, 'pilih_kelurahan']);
        Route::post('/pilih-rayon',  [WHController::class, 'pilih_rayon']);
        Route::get('/cetak-toko/{routing}', [WHController::class, 'cetak_toko']);
        Route::get('/cetak-barang/{routing}', [WHController::class, 'cetak_barang']);
        Route::post('/recount', [WHController::class, 'recount']);

        // Route for filter kode
        Route::post('/filter_kode', [WHController::class, 'filter_kode']);

        //Route for Employee
        Route::get('/employee', [EmployeeController::class, 'index']);
        Route::get('/input-loader', [EmployeeController::class, 'input'])->name('input-loader');
        Route::post('/store-employee', [EmployeeController::class, 'store']);

        //Route For Vehicle
        Route::get('/vehicle', [VehicleController::class, 'index']);
        Route::get('/input-vehicle', [VehicleController::class, 'input'])->name('input-vehicle');
        Route::post('/store_vehicle', [VehicleController::class, 'store']);

        //Route for Rayon
        Route::get('/rayon', [RayonController::class, 'index']);
        Route::get('/input-rayon', [RayonController::class, 'input'])->name('input-rayon');
        Route::get('/detail-toko-rayon/{rayon}', [RayonController::class, 'detailRayon']);
        Route::get('/setting-rayon/{kode_rayon}', [RayonController::class, 'setting']);
        Route::post('/store-rayon', [RayonController::class, 'store']);
        Route::post('/checkbox-rayon', [RayonController::class, 'checkbox_rayon']);
        Route::post('/hapus-toko-rayon', [RayonController::class, 'hapus_toko_rayon']);
        Route::post('/load-rayon', [RayonController::class, 'load_rayon']);
        Route::post('/pilih-toko-rayon', [RayonController::class, 'pilih_toko_rayon']);
        Route::post('/hold-rayon', [RayonController::class, 'hold_rayon']);
        Route::post('/setting-shipto', [RayonController::class, 'setting_shipto']);
        Route::post('/search-customer', [RayonController::class, 'search_customer']);

        //Route for Area
        Route::get('/area', [AreaController::class, 'index']);
        Route::get('/input-area', [AreaController::class, 'input'])->name('input-area');
        Route::post('/store-area', [AreaController::class, 'store']);
        Route::post('/store-area-dc', [AreaController::class, 'store_dc']);

        //Route for CountRayon 
        Route::get('/count', [CountRayonController::class, 'index']);
        Route::get('/refresh', [CountRayonController::class, 'refresh']);
        Route::post('/kubikasi-rayon', [CountRayonController::class, 'kubikasi_rayon']);
    });

    Route::group(['middleware' => ['CekLogin:3']], function () {
        Route::get('/HO', [HOController::class, 'index'])->name('HO');

        // Route for Barang
        Route::get('/barang', [BarangController::class, 'index']);
        Route::get('/input', [BarangController::class, 'input']);
        Route::get('/check-kubikasi-empty', [BarangController::class, 'check_kubikasi']);
        Route::post('/store-barang', [BarangController::class, 'store']);
        Route::post('/store-empty', [BarangController::class, 'store_empty']);

        // Route for Routing
        Route::get('/routing', [HOController::class, 'routing']);
        Route::get('/check', [HOController::class, 'check_barang']);
        Route::post('/input-kubikasi', [HOController::class, 'input_kubikasi']);
    });
});
// ROUTE VEHICLE
