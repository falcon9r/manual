<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LogoutController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\TopicController;

use App\Http\Controllers\ExportController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\StyleController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\WelcomeController;
use App\Models\Topic;
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

Route::get('/', [PublicController::class, 'index'])->name('public');

Route::post('/logout', [LogoutController::class, 'store'])->name('logout');

Route::middleware('guest')->group(function (){
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function (){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [DashboardController::class, 'addChapter'])->name('addChapter');
    Route::post('/dashboard/swap', [DashboardController::class, 'swapChapters'])->name('swapChapter');
    Route::post('/dashboard/toggle-theme/', [DashboardController::class, 'toggleTheme'])->name('toggleTheme');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/save/', [ProfileController::class, 'saveData'])->name('saveProfile');

    Route::get('/chapter/{chapter:id}/', [TopicController::class, 'index'])->name('topics');
    Route::post('/chapter/{chapter:id}/', [TopicController::class, 'addTopic'])->name('addTopic');
    Route::post('/chapter/delete/{chapter:id}/', [TopicController::class, 'removeChapter'])->name('removeChapter');
    Route::post('/chapter/rename/{chapter:id}/', [TopicController::class, 'renameChapter'])->name('renameChapter');

    Route::get('/templates', [TemplateController::class, 'index'])->name('templates');
    Route::post('/templates', [TemplateController::class, 'addTemp'])->name('addTemp');
    Route::get('/styling', [StyleController::class, 'index'])->name('styling');
    Route::post('/styling/save/', [StyleController::class, 'saveData'])->name('savestyling');
    Route::get('/welcome/editor', [WelcomeController::class, 'index'])->name('welcome');
    Route::post('/welcome/editor/save/', [WelcomeController::class, 'save'])->name('saveWelcome');

    Route::get('/download', [ExportController::class, 'index'])->name('download');

    Route::get('/topic/editor/{topic:id}', [EditorController::class, 'index'])->name('editor');
    Route::post('/topic/delete/{topic:id}', [EditorController::class, 'removeTopic'])->name('removeTopic');
    Route::post('/topic/order-change/{topic:id}', [EditorController::class , 'orderChange'])->name('orderChange');
    Route::patch('/topic/renaming/{topic:id}', [EditorController::class, 'renameTopic'])->name('renameTopic');
    Route::post('/topic/publish/{topic:id}', [EditorController::class, 'publishTopic'])->name('publishTopic');
    Route::post('/topic/editor/{topic:id}', [EditorController::class, 'saveTopic'])->name('saveTopic');

    Route::get('/template/editor/{template:id}', [EditorController::class, 'template'])->name('template/editor');
    Route::post('/template/delete/{template:id}', [EditorController::class, 'removeTemp'])->name('removeTemp');
    Route::post('/template/publish/{template:id}', [EditorController::class, 'publishTemp'])->name('publishTemp');
    Route::post('/template/editor/{template:id}', [EditorController::class, 'saveTemp'])->name('saveTemp');

    Route::any('/ckfinder/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')
        ->name('ckfinder_connector');

    Route::any('/ckfinder/browser', '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')
        ->name('ckfinder_browser');

    Route::any('/ckfinder/examples/{example?}', 'CKSource\CKFinderBridge\Controller\CKFinderController@examplesAction')
        ->name('ckfinder_examples');

    Route::post('ckfinder/image-upload' , [\App\Http\Controllers\CKFinder\ImageUploadController::class , 'upload'])->name('ckfinderImageUpload');
});
