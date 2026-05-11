<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LeadPipelineController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\LendersController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\InterestRateController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DsaPartnerController;
use App\Http\Controllers\EducationModuleController;
use App\Http\Controllers\EducationContentController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\ReelController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CalculatorMediaController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\PodcastController;
/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (AFTER LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /* DASHBOARD */
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    /* LEADS */
    Route::get('/leads', [LeadPipelineController::class, 'index'])
        ->name('leads');
    Route::post('/leads/{id}/stage', [LeadPipelineController::class, 'updateStage']);
    Route::post('/leads', [LeadPipelineController::class, 'store'])
        ->name('leads.store');
    Route::post('/leads/{id}/update', [LeadPipelineController::class, 'update'])
        ->name('leads.update');

    Route::delete('/leads/{id}', [LeadPipelineController::class, 'destroy']);

    /* PROJECTS */
    Route::get('/projects', [ProjectsController::class, 'index'])
        ->name('projects');

    /* LENDERS */
    Route::prefix('community')->name('community.')->group(function () {
        Route::get('/', [CommunityController::class, 'index'])->name('index');
        Route::post('/post', [CommunityController::class, 'storePost'])->name('post.store');
        Route::delete('/post/{id}', [CommunityController::class, 'destroy'])->name('post.destroy');
        Route::post('/comment', [CommunityController::class, 'storeComment'])->name('comment.store');
        Route::delete('/comment/{id}', [CommunityController::class, 'destroyComment'])->name('comment.destroy');
    });

    Route::prefix('podcasts')->group(function () {
        Route::get('/', [PodcastController::class, 'index'])->name('podcasts.index');
        Route::get('/create', [PodcastController::class, 'create'])->name('podcasts.create');
        Route::post('/store', [PodcastController::class, 'store'])->name('podcasts.store');
        Route::get('/edit/{id}', [PodcastController::class, 'edit'])->name('podcasts.edit');
        Route::post('/update/{id}', [PodcastController::class, 'update'])->name('podcasts.update');
        Route::delete('/delete/{id}', [PodcastController::class, 'destroy'])->name('podcasts.destroy');
        Route::get('/show/{id}', [PodcastController::class, 'show'])->name('podcasts.show');
    });

    Route::get('/partners', [DsaPartnerController::class, 'index'])
        ->name('partners.index');

    Route::post('/partners', [DsaPartnerController::class, 'store'])
        ->name('partners.store');

    Route::get('/partners', [DsaPartnerController::class, 'index'])
        ->name('partners.index');

    Route::post('/partners', [DsaPartnerController::class, 'store'])
        ->name('partners.store');

    Route::post('/partners/{id}/update', [DsaPartnerController::class, 'update'])
        ->name('partners.update');


    Route::delete('/partners/{id}', [DsaPartnerController::class, 'destroy'])
        ->name('partners.destroy');



    Route::delete('/partners/{id}', [DsaPartnerController::class, 'destroy'])
        ->name('partners.destroy');


    /* DOCUMENTS */
    Route::get('/documents', [DocumentsController::class, 'index'])
        ->name('documents');

    Route::post('/documents', [DocumentsController::class, 'store'])
        ->name('documents.store');
    Route::post(
        '/documents/{id}/verify',
        [DocumentsController::class, 'verify']
    )->name('documents.verify');

    Route::get('/rates', [InterestRateController::class, 'index'])
        ->name('rates.index');
    Route::prefix('lenders')->group(function () {
        Route::get('/', [LendersController::class, 'index'])->name('lenders');
        Route::post('/', [LendersController::class, 'store'])->name('lenders.store');
        Route::post('{id}/update', [LendersController::class, 'update'])
            ->name('lenders.update');
        Route::delete('{id}', [LendersController::class, 'destroy'])->name('lenders.destroy');
    });

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
    Route::get('/tasks/list', [TaskController::class, 'list']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::post('/tasks/{id}/complete', [TaskController::class, 'complete']);
    Route::get('/leads/list', [LeadPipelineController::class, 'list']);


    // LEAD AUTOFILL SEARCH
    Route::get('/leads/search', [LeadPipelineController::class, 'search']);

    /* Sidebar Pages */
    /* Sidebar Pages */
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns');
    Route::post('/campaigns', [CampaignController::class, 'store'])
        ->name('campaigns.store');
    Route::get('/c/{campaign}', [CampaignController::class, 'redirect'])
        ->name('campaign.redirect');
    Route::get('/analytics', [AnalyticsController::class, 'index'])
        ->name('analytics');
    Route::get('/settings', [SettingsController::class, 'index'])
        ->name('settings');
    Route::post('/settings/profile', [SettingsController::class, 'saveProfile']);
    Route::post('/settings/notifications', [SettingsController::class, 'saveNotifications']);
    Route::post('/settings/security', [SettingsController::class, 'changePassword']);
    Route::post('/settings/organization', [SettingsController::class, 'saveOrganization']);

    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/store', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/edit/{id}', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/update/{id}', [CourseController::class, 'update'])->name('courses.update');
        Route::post('/delete/{id}', [CourseController::class, 'destroy'])->name('courses.delete');
        Route::post('/toggle/{id}', [CourseController::class, 'toggle'])->name('courses.toggle');
    });




    Route::prefix('education-modules')->group(function () {
        Route::get('/', [EducationModuleController::class, 'index'])->name('modules.index');
        Route::get('/create', [EducationModuleController::class, 'create'])->name('modules.create');
        Route::post('/store', [EducationModuleController::class, 'store'])->name('modules.store');
        Route::get('/edit/{id}', [EducationModuleController::class, 'edit'])->name('modules.edit');
        Route::post('/update/{id}', [EducationModuleController::class, 'update'])->name('modules.update');
        Route::get('/delete/{id}', [EducationModuleController::class, 'destroy'])->name('modules.delete');
    });


    Route::prefix('education-contents')->group(function () {
        Route::get('/', [EducationContentController::class, 'index'])->name('contents.index');
        Route::get('/show/{id}', [EducationContentController::class, 'show'])->name('contents.show');
        Route::get('/create', [EducationContentController::class, 'create'])->name('contents.create');
        Route::post('/store', [EducationContentController::class, 'store'])->name('contents.store');
        Route::get('/edit/{id}', [EducationContentController::class, 'edit'])->name('contents.edit');
        Route::post('/update/{id}', [EducationContentController::class, 'update'])->name('contents.update');
        Route::post('/delete/{id}', [EducationContentController::class, 'destroy'])->name('contents.delete');
    });



    Route::prefix('calculators')->group(function () {
        Route::get('/', [CalculatorController::class, 'index'])->name('calculators.index');
        Route::post('/store', [CalculatorController::class, 'store'])->name('calculators.store');
        Route::post('/toggle/{id}', [CalculatorController::class, 'toggle'])->name('calculators.toggle');
        Route::post('/calculators/access/{id}', [CalculatorController::class, 'updateAccess'])->name('calculators.updateAccess');
        Route::post('/calculators/user-type/{id}', [CalculatorController::class, 'updateUserType'])->name('calculators.updateUserType');
    });




    Route::prefix('reels')->group(function () {
        Route::get('/', [ReelController::class, 'index'])->name('reels.index');
        Route::get('/create', [ReelController::class, 'create'])->name('reels.create');
        Route::post('/store', [ReelController::class, 'store'])->name('reels.store');
        Route::get('/edit/{id}', [ReelController::class, 'edit'])->name('reels.edit');
        Route::post('/update/{id}', [ReelController::class, 'update'])->name('reels.update');
        Route::post('/delete/{id}', [ReelController::class, 'delete'])->name('reels.delete');
    });
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::post('/toggle/{id}', [UserController::class, 'toggleStatus'])->name('users.toggle');
    });
    Route::prefix('banners')->group(function () {

        Route::get('/', [BannerController::class, 'index'])->name('banners.index');
        Route::get('/create', [BannerController::class, 'create'])->name('banners.create');
        Route::post('/store', [BannerController::class, 'store'])->name('banners.store');

        Route::get('/edit/{id}', [BannerController::class, 'edit'])->name('banners.edit');
        Route::post('/update/{id}', [BannerController::class, 'update'])->name('banners.update');

        Route::post('/delete/{id}', [BannerController::class, 'destroy'])->name('banners.delete');
        Route::post('/toggle/{id}', [BannerController::class, 'toggle'])->name('banners.toggle');
    });
});



Route::prefix('calculator-media')->group(function () {
    Route::get('/', [CalculatorMediaController::class, 'index'])->name('calculator-media.index');
    Route::get('/create', [CalculatorMediaController::class, 'create'])->name('calculator-media.create');
    Route::post('/store', [CalculatorMediaController::class, 'store'])->name('calculator-media.store');
    Route::get('/edit/{id}', [CalculatorMediaController::class, 'edit'])->name('calculator-media.edit');
    Route::put('/update/{id}', [CalculatorMediaController::class, 'update'])->name('calculator-media.update');
    Route::delete('/delete/{id}', [CalculatorMediaController::class, 'destroy'])->name('calculator-media.delete');
});
/*
|--------------------------------------------------------------------------
| FALLBACK
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return redirect()->route('login');
});
