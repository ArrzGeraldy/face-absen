<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\OfficeSettingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Route::post('/employee', [EmployeeController::class, 'store'])->name('employe.store');


// // jabatan
// Route::get('/admin/jabatan', [JabatanController::class, 'index'])->name("jabatan.index");
// Route::get('/admin/jabatan/create', [JabatanController::class, 'create'])->name("jabatan.create");
// Route::post('/admin/jabatan', [JabatanController::class, 'store'])->name("jabatan.store");
// Route::get('/admin/jabatan/edit/{id}', [JabatanController::class, 'edit'])->name("jabatan.edit");
// Route::put('/admin/jabatan/{id}', [JabatanController::class, 'update'])->name("jabatan.update");
// Route::delete('/admin/jabatan/{id}', [JabatanController::class, 'destroy'])->name("jabatan.destroy");

// // karyawan
// Route::get('/admin/karyawan', [KaryawanController::class, 'index'])->name("karyawan.index");
// Route::get('/admin/karyawan/create', [KaryawanController::class, 'create'])->name("karyawan.create");
// Route::post('/admin/karyawan', [KaryawanController::class, 'store'])->name("karyawan.store");
// Route::get('/admin/karyawan/edit/{id}', [KaryawanController::class, 'edit'])->name("karyawan.edit");
// Route::put('/admin/karyawan/update/{id}', [KaryawanController::class, 'update'])->name("karyawan.update");
// Route::delete('/admin/karyawan/{id}', [KaryawanController::class, 'destroy'])->name("karyawan.destroy");
// Route::get('/admin/karyawan/{user}/change-password', [KaryawanController::class, 'showChangePasswordForm'])
//     ->name('karyawan.change_password');
// Route::post('/admin/karyawan/{user}/change-password', [KaryawanController::class, 'updatePassword'])
//     ->name('karyawan.update_password');


Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');


    // JABATAN
    Route::get('/admin/jabatan', [JabatanController::class, 'index'])->name("jabatan.index");
    Route::get('/admin/jabatan/create', [JabatanController::class, 'create'])->name("jabatan.create");
    Route::post('/admin/jabatan', [JabatanController::class, 'store'])->name("jabatan.store");
    Route::get('/admin/jabatan/edit/{id}', [JabatanController::class, 'edit'])->name("jabatan.edit");
    Route::put('/admin/jabatan/{id}', [JabatanController::class, 'update'])->name("jabatan.update");
    Route::delete('/admin/jabatan/{id}', [JabatanController::class, 'destroy'])->name("jabatan.destroy");

    // KARYAWAN
    Route::get('/admin/karyawan', [KaryawanController::class, 'index'])->name("karyawan.index");
    Route::get('/admin/karyawan/create', [KaryawanController::class, 'create'])->name("karyawan.create");
    Route::post('/admin/karyawan', [KaryawanController::class, 'store'])->name("karyawan.store");
    Route::get('/admin/karyawan/edit/{id}', [KaryawanController::class, 'edit'])->name("karyawan.edit");
    Route::put('/admin/karyawan/update/{id}', [KaryawanController::class, 'update'])->name("karyawan.update");
    Route::delete('/admin/karyawan/{id}', [KaryawanController::class, 'destroy'])->name("karyawan.destroy");

    // CHANGE PASSWORD KARYAWAN
    Route::get('/admin/karyawan/{user}/change-password', [KaryawanController::class, 'showChangePasswordForm'])
        ->name('karyawan.change_password');
    Route::post('/admin/karyawan/{user}/change-password', [KaryawanController::class, 'updatePassword'])
        ->name('karyawan.update_password');

    // OFFICE 
    Route::get('/admin/office-setting', [OfficeSettingController::class, 'index'])->name("office-setting.index");
    Route::post('/admin/office-setting', [OfficeSettingController::class, 'storeOrUpdate'])->name("office-setting.save");

    // ABSEN
    Route::get('/admin/absen',[AbsenController::class,'index'])->name('absen.index');
    Route::get('/admin/absen/edit/{id}',[AbsenController::class,'edit'])->name('absen.edit');
    Route::put('/admin/absen/{absen}',[AbsenController::class,'update'])->name('absen.update');
    Route::delete('/admin/absen/{id}',[AbsenController::class,'destroy'])->name('absen.destroy');

    
    // Route untuk ADMIN (dengan parameter karyawan_id)
    Route::get('/admin/riwayat-absen/{karyawan}', [AbsenController::class, 'show'])
        ->name('absen.calendar.show');


});




// // offce 
// Route::get('/admin/office-setting', [OfficeSettingController::class, 'index'])->name("office-setting.index");
// Route::post('/admin/office-setting', [OfficeSettingController::class, 'storeOrUpdate'])->name("office-setting.save");

// absen
// Route::get('/admin/absen',[AbsenController::class,'index'])->name('absen.index');
// Route::get('/admin/absen/edit/{id}',[AbsenController::class,'edit'])->name('absen.edit');
// Route::put('/admin/absen/{absen}',[AbsenController::class,'update'])->name('absen.update');
// Route::delete('/admin/absen/{id}',[AbsenController::class,'destroy'])->name('absen.destroy');
Route::get('/absen',[AbsenController::class,'absenKaryawan'])->name('absen');
Route::get('/izin',[AbsenController::class,'izin'])->name('absen.izin');
Route::post('/izin',[AbsenController::class,'storeIzin'])->name('absen.store_izin');
Route::middleware(['auth'])->group(function () {
    Route::post('/absen', [AbsenController::class, 'storeOrUpdate']);
});

// Route untuk USER biasa (tanpa parameter)
Route::get('/riwayat-absen', [AbsenController::class, 'showMine'])
    ->name('absen.calendar.mine');

// // Route untuk ADMIN (dengan parameter karyawan_id)
// Route::get('/admin/riwayat-absen/{karyawan}', [AbsenController::class, 'show'])
//     ->name('absen.calendar.show');



Route::middleware('auth')->group(function () {
    Route::get('/',[AbsenController::class,'absenKaryawan'])->name('absen');
    Route::get('/izin',[AbsenController::class,'izin'])->name('absen.izin');
    Route::post('/izin',[AbsenController::class,'storeIzin'])->name('absen.store_izin');
    Route::middleware(['auth'])->group(function () {
        Route::post('/absen', [AbsenController::class, 'storeOrUpdate']);
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
