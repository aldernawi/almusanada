<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\FormFieldController;
use App\Http\Controllers\FormSubmissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MedicalAuditingController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmployeeController as AdminEmployeeController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\RegulationController as AdminRegulationController;
use App\Http\Controllers\Admin\ReviewerController as AdminReviewerController;
use App\Http\Controllers\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Customer\TransactionController as CustomerTransactionController;
use App\Http\Controllers\Query\AuthController as QueryAuthController;
use App\Http\Controllers\Query\DashboardController as QueryDashboardController;
use App\Http\Controllers\Query\TransactionController as QueryTransactionController;
use App\Http\Controllers\Reviewer\DashboardController as ReviewerDashboardController;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Route;

// Website Landing Page
Route::get('/', function () {
    $profile = CompanyProfile::first();
    if (!$profile) {
        $profile = new CompanyProfile([
            'company_name' => 'الشركة المساندة',
            'hero_title' => 'مرحباً بك في موقعنا حيث يبدأ الإبداع',
            'hero_description' => 'نقدم لك حلولاً مبتكرة...',
            'primary_color' => '#007bff',
            'secondary_color' => '#0056b3',
        ]);
    }

    $regulations = \App\Models\Regulation::orderBy('sort_order')->get();
    $services = \App\Models\Service::orderBy('sort_order')->get();
    $employees = \App\Models\Employee::all();

    return view('landing', compact('profile', 'regulations', 'services', 'employees'));
})->name('home');

// Public Employee Pages
Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::get('/employee/{id}', [EmployeeController::class, 'show'])->name('employee.show');

// Admin Auth Routes
Route::get('/secure-admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/secure-admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/secure-admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes (Website CMS)
Route::middleware(['auth', 'admin'])->prefix('secure-admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [AdminDashboardController::class, 'update'])->name('dashboard.update');

    Route::get('/employees', [AdminEmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [AdminEmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [AdminEmployeeController::class, 'store'])->name('employees.store');
    Route::delete('/employees/{id}', [AdminEmployeeController::class, 'destroy'])->name('employees.destroy');

    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [AdminTransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [AdminTransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{id}/edit', [AdminTransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{id}', [AdminTransactionController::class, 'update'])->name('transactions.update');
    Route::patch('/transactions/update-status', [AdminTransactionController::class, 'updateStatus'])->name('transactions.update.status');
    Route::delete('/transactions/{id}', [AdminTransactionController::class, 'destroy'])->name('transactions.destroy');

    Route::resource('services', AdminServiceController::class);
    Route::resource('regulations', AdminRegulationController::class);

    Route::get('/customers', [AdminTransactionController::class, 'customers'])->name('customers.index');
    Route::get('/customers/create', [AdminTransactionController::class, 'createCustomer'])->name('customers.create');
    Route::post('/customers', [AdminTransactionController::class, 'storeCustomer'])->name('customers.store');
    Route::get('/customers/{id}/edit', [AdminTransactionController::class, 'editCustomer'])->name('customers.edit');
    Route::put('/customers/{id}', [AdminTransactionController::class, 'updateCustomer'])->name('customers.update');
    Route::delete('/customers/{id}', [AdminTransactionController::class, 'destroyCustomer'])->name('customers.destroy');

    Route::get('/reviewers', [AdminReviewerController::class, 'index'])->name('reviewers.index');
    Route::get('/reviewers/create', [AdminReviewerController::class, 'create'])->name('reviewers.create');
    Route::post('/reviewers', [AdminReviewerController::class, 'store'])->name('reviewers.store');
    Route::get('/reviewers/{id}/edit', [AdminReviewerController::class, 'edit'])->name('reviewers.edit');
    Route::put('/reviewers/{id}', [AdminReviewerController::class, 'update'])->name('reviewers.update');
    Route::delete('/reviewers/{id}', [AdminReviewerController::class, 'destroy'])->name('reviewers.destroy');
});

// Customer Portal Auth Routes
Route::get('/portal/login', [CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
Route::post('/portal/login', [CustomerAuthController::class, 'login'])->name('customer.login.post');
Route::post('/portal/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

// Protected Customer Portal Routes
Route::middleware(['auth', 'customer'])->prefix('portal')->name('customer.')->group(function () {
    Route::get('/search', [CustomerTransactionController::class, 'search'])->name('search');
    Route::post('/search', [CustomerTransactionController::class, 'result'])->name('search.result');
});

// Reviewer Portal Routes (Website)
Route::middleware(['auth', 'reviewer'])->prefix('reviewer')->name('reviewer.')->group(function () {
    Route::get('/dashboard', [ReviewerDashboardController::class, 'index'])->name('dashboard');
});

// Query Portal Auth Routes
Route::get('/query/login', [QueryAuthController::class, 'showLoginForm'])->name('query.login');
Route::post('/query/login', [QueryAuthController::class, 'login'])->name('query.login.post');
Route::post('/query/logout', [QueryAuthController::class, 'logout'])->name('query.logout');

// Protected Query User Routes
Route::middleware(['auth', 'query_user'])->prefix('query')->name('query.')->group(function () {
    Route::get('/dashboard', [QueryDashboardController::class, 'index'])->name('dashboard');
    Route::get('/transactions', [QueryTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}', [QueryTransactionController::class, 'show'])->name('transactions.show');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin.area'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('admin.area')->group(function () {
    // Forms Routes
    Route::resource('forms', FormController::class);
    Route::post('/forms/{form}/duplicate', [FormController::class, 'duplicate'])->name('forms.duplicate');
    Route::get('/forms/{form}/share', [FormController::class, 'share'])->name('forms.share');
    Route::post('/forms/{form}/share/email', [FormController::class, 'shareEmail'])->name('forms.share.email');
    Route::post('/forms/{form}/favorite', [FormController::class, 'toggleFavorite'])->name('forms.favorite');
    Route::post('/forms/{form}/archive', [FormController::class, 'toggleArchive'])->name('forms.archive');
    Route::post('/forms/{id}/restore', [FormController::class, 'restore'])->name('forms.restore');
    Route::delete('/forms/{id}/force-delete', [FormController::class, 'forceDelete'])->name('forms.force-delete');

    // Folders Routes
    Route::post('/folders', [FolderController::class, 'store'])->name('folders.store');
    Route::delete('/folders/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');
    Route::post('/folders/add-form', [FolderController::class, 'addForm'])->name('folders.add-form');
    Route::post('/folders/remove-form', [FolderController::class, 'removeForm'])->name('folders.remove-form');

    // API Keys Routes
    Route::get('/account/api-keys', [ApiKeyController::class, 'index'])->name('api-keys.index');
    Route::post('/account/api-keys', [ApiKeyController::class, 'store'])->name('api-keys.store');
    Route::delete('/account/api-keys/{apiKey}', [ApiKeyController::class, 'destroy'])->name('api-keys.destroy');

    // Account Usage & Settings Routes
    Route::get('/account/usage', [AccountController::class, 'usage'])->name('account.usage');
    Route::get('/account/settings', [AccountController::class, 'settings'])->name('account.settings');

    // API endpoints (X-API-Key middleware authorized)
    Route::middleware('api.key')->group(function () {
        Route::get('/api/v1/forms', [ApiController::class, 'forms'])->name('api.forms');
        Route::get('/api/v1/forms/{form}/details', [ApiController::class, 'formDetails'])->name('api.form.details');
        Route::get('/api/v1/forms/{form}/submissions', [ApiController::class, 'submissions'])->name('api.submissions');
    });

    // Form Fields Routes
    Route::post('/forms/{form}/fields', [FormFieldController::class, 'store'])->name('forms.fields.store');
    Route::put('/forms/{form}/fields/{field}', [FormFieldController::class, 'update'])->name('forms.fields.update');
    Route::delete('/forms/{form}/fields/{field}', [FormFieldController::class, 'destroy'])->name('forms.fields.destroy');
    Route::post('/forms/{form}/fields/reorder', [FormFieldController::class, 'reorder'])->name('forms.fields.reorder');

    // Form Submissions Routes
    Route::get('/forms/{form}/submissions', [FormSubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/forms/{form}/submissions/{submission}', [FormSubmissionController::class, 'show'])->name('submissions.show');
    Route::delete('/forms/{form}/submissions/{submission}', [FormSubmissionController::class, 'destroy'])->name('submissions.destroy');
    Route::get('/forms/{form}/submissions/{submission}/attachments/{data}', [FormSubmissionController::class, 'attachment'])->name('submissions.attachments.show');
Route::get('/forms/{form}/submissions/export/{format}', [FormSubmissionController::class, 'export'])->name('submissions.export');

    // Admin Users Routes
    Route::resource('users', UserController::class);

    // Reviewer assignment is an administrative function
    Route::get('/reviewer/assignment', [ReviewerController::class, 'assignmentIndex'])->name('reviewer.assignment');
    Route::post('/reviewer/forms/{form}/assign', [ReviewerController::class, 'assignReviewers'])->name('reviewer.assign');
    });

    // Medical Auditing Routes
    Route::prefix('medical-auditing')->group(function () {
        Route::get('/', [MedicalAuditingController::class, 'index'])->name('medical-auditing.index');
        Route::get('/{submission}/data', [MedicalAuditingController::class, 'data'])->name('medical-auditing.data');
        Route::post('/{submission}/audit', [MedicalAuditingController::class, 'audit'])->name('medical-auditing.audit');
        Route::put('/{submission}/notes', [MedicalAuditingController::class, 'updateNotes'])->name('medical-auditing.notes');
        Route::get('/{submission}/attachments/{data}', [MedicalAuditingController::class, 'attachment'])->name('medical-auditing.attachments.show');
        Route::get('/{submission}/attachments/{data}/inline', [MedicalAuditingController::class, 'attachmentInline'])->name('medical-auditing.attachments.inline');
        Route::get('/export/{format}', [MedicalAuditingController::class, 'export'])->name('medical-auditing.export');
    });
});

// Public Form Routes
Route::get('/f/{slug}', [FormController::class, 'publicShow'])
    ->middleware('throttle:public-forms')
    ->name('forms.public');
Route::post('/f/{form:slug}', [FormSubmissionController::class, 'store'])
    ->middleware('throttle:public-submissions')
    ->name('forms.submit');

require __DIR__.'/auth.php';
