<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GamificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\MyCasaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StudyController;
use Illuminate\Support\Facades\Route;

// =============================================================================
// Rotas públicas — apenas para visitantes não autenticados (guest)
// =============================================================================
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Cadastro
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register/visitante', [RegisterController::class, 'storeDevoto'])->name('register.visitante');
    Route::post('/register/casa',   [RegisterController::class, 'storeCasa'])->name('register.casa');
    Route::post('/register/loja',   [RegisterController::class, 'storeLoja'])->name('register.loja');

    // Recuperação de senha
    Route::get('/forgot-password',  [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'send'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset');
    Route::post('/reset-password',        [ResetPasswordController::class, 'reset'])->name('password.update');

    // OAuth (Google / Facebook)
    Route::get('/auth/google',            [SocialAuthController::class, 'redirectGoogle'])->name('auth.google');
    Route::get('/auth/google/callback',   [SocialAuthController::class, 'handleGoogle']);
    Route::get('/auth/facebook',          [SocialAuthController::class, 'redirectFacebook'])->name('auth.facebook');
    Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebook']);
});

// =============================================================================
// Logout (requer autenticação)
// =============================================================================
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// =============================================================================
// Área autenticada
// =============================================================================
Route::middleware('auth')->group(function () {

    // Home
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Casas / Templos
    Route::get('/houses',              [HouseController::class, 'index'])->name('houses');
    Route::get('/houses/{id}',         [HouseController::class, 'show'])->name('houses.show');
    Route::get('/houses/{id}/edit',    [HouseController::class, 'edit'])->name('houses.edit');
    Route::post('/houses/{id}/update', [HouseController::class, 'update'])->name('houses.update');
    Route::post('/houses/{id}/join', [HouseController::class, 'join'])->name('houses.join');
    Route::post('/houses/{id}/cancel-request', [HouseController::class, 'cancelRequest'])->name('houses.cancel-request');

    // Mapa
    Route::get('/map', [MapController::class, 'index'])->name('map');

    // Perfil
    Route::get('/profile',                   [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit',              [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',                   [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password',   [ProfileController::class, 'changePasswordForm'])->name('profile.change-password');
    Route::put('/profile/change-password',   [ProfileController::class, 'changePassword'])->name('profile.change-password.update');

    // Carteirinha digital
    Route::get('/card', [CardController::class, 'index'])->name('card');

    // Configurações
    Route::get('/settings',  [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings',  [SettingsController::class, 'update'])->name('settings.update');

    // Notificações
    Route::get('/notifications',               [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read',    [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::put('/notifications/read-all',      [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Estudos — IMPORTANTE: rotas específicas devem vir ANTES do wildcard {id}
    Route::get('/studies/publicos',       [StudyController::class, 'publicIndex'])->name('studies.public');
    Route::get('/studies',                [StudyController::class, 'index'])->name('studies');
    Route::get('/studies/create',         [StudyController::class, 'create'])->name('studies.create')->middleware('role:dirigente,assistente,admin');
    Route::post('/studies',               [StudyController::class, 'store'])->name('studies.store')->middleware('role:dirigente,assistente,admin');
    Route::get('/studies/{id}/edit',      [StudyController::class, 'edit'])->name('studies.edit')->middleware('role:dirigente,assistente,admin');
    Route::post('/studies/{id}/update',   [StudyController::class, 'update'])->name('studies.update')->middleware('role:dirigente,assistente,admin');
    Route::post('/studies/{id}/complete', [StudyController::class, 'complete'])->name('studies.complete');
    Route::get('/studies/{id}',           [StudyController::class, 'show'])->name('studies.show');

    // Eventos
    Route::get('/events',                        [EventController::class, 'index'])->name('events');
    Route::get('/events/{id}',                   [EventController::class, 'show'])->name('events.show');
    Route::get('/my-list',                       [EventController::class, 'myList'])->name('events.my-list');
    Route::post('/events/{id}/subscribe',        [EventController::class, 'subscribe'])->name('events.subscribe');
    Route::delete('/events/{id}/subscribe',      [EventController::class, 'unsubscribe'])->name('events.unsubscribe');
    Route::post('/events/{id}/intent',           [EventController::class, 'setIntent'])->name('events.intent');
    Route::post('/events/{id}/checkin',          [EventController::class, 'selfCheckin'])->name('events.checkin.self');

    // Loja / E-commerce
    Route::get('/shop',               [ShopController::class, 'index'])->name('shop');
    Route::get('/shop/products/{id}', [ShopController::class, 'show'])->name('shop.products.show');
    Route::get('/cart',               [ShopController::class, 'cart'])->name('cart');
    Route::post('/cart/add',          [ShopController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/{id}',       [ShopController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/checkout',           [ShopController::class, 'checkout'])->name('checkout');
    Route::post('/checkout',          [ShopController::class, 'placeOrder'])->name('checkout.store');

    // Pedidos
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');

    // Gamificação
    Route::get('/dashboard',    [GamificationController::class, 'dashboard'])->name('dashboard');
    Route::get('/achievements', [GamificationController::class, 'achievements'])->name('achievements');
    Route::get('/ranking',      [GamificationController::class, 'ranking'])->name('ranking');

    // Alerta de emergência
    Route::post('/emergency', [EmergencyController::class, 'trigger'])->name('emergency');

    // -------------------------------------------------------------------------
    // Área da Casa — visitante vê tela de filiação, demais perfis veem o painel
    // -------------------------------------------------------------------------
    Route::get('/my-house', [MyCasaController::class, 'index'])->name('my-house');
    Route::post('/my-house/suggestions', [MyCasaController::class, 'storeSuggestion'])->name('my-house.suggestions.store');

    // Tarefas — assistente, dirigente, admin podem criar/gerir
    Route::middleware('role:assistente,dirigente,admin')->group(function () {
        Route::get('/checkin',                         [EventController::class, 'checkin'])->name('checkin');
        Route::post('/my-house/tasks',                 [MyCasaController::class, 'storeTask'])->name('my-house.tasks.store');
        Route::post('/my-house/studies',               [MyCasaController::class, 'storeStudy'])->name('my-house.studies.store');
        Route::post('/my-house/studies/{id}/update',   [MyCasaController::class, 'updateStudy'])->name('my-house.studies.update');
        Route::post('/my-house/tasks/{id}/status',     [MyCasaController::class, 'updateTaskStatus'])->name('my-house.tasks.status');
        Route::post('/my-house/tasks/{id}/approve',    [MyCasaController::class, 'approveTask'])->name('my-house.tasks.approve');
        Route::post('/my-house/tasks/{id}/reject',     [MyCasaController::class, 'rejectTask'])->name('my-house.tasks.reject');
        Route::post('/my-house/tasks/{id}/assign',     [MyCasaController::class, 'assignTask'])->name('my-house.tasks.assign');
        Route::post('/my-house/tasks/randomize',       [MyCasaController::class, 'randomizeTasks'])->name('my-house.tasks.randomize');
    });

    // Eventos e finanças — dirigente e admin apenas
    Route::middleware('role:dirigente,admin')->group(function () {
        Route::post('/my-house/events',               [MyCasaController::class, 'storeEvent'])->name('my-house.events.store');
        Route::post('/my-house/events/{id}/cancel',   [MyCasaController::class, 'cancelEvent'])->name('my-house.events.cancel');
        Route::post('/my-house/events/{id}/update',   [MyCasaController::class, 'updateEvent'])->name('my-house.events.update');
        Route::post('/my-house/tasks/{id}/update',    [MyCasaController::class, 'updateTask'])->name('my-house.tasks.update');
        Route::post('/my-house/finances',             [MyCasaController::class, 'storeFinance'])->name('my-house.finances.store');
        Route::post('/my-house/finances/{id}/update', [MyCasaController::class, 'updateFinance'])->name('my-house.finances.update');
        Route::post('/my-house/members/{id}/approve',              [MyCasaController::class, 'approveMember'])->name('my-house.members.approve');
        Route::post('/my-house/members/{id}/reject',               [MyCasaController::class, 'rejectMember'])->name('my-house.members.reject');
        Route::post('/my-house/members/{id}/role',                 [MyCasaController::class, 'updateMemberRole'])->name('my-house.members.role');
        Route::post('/my-house/finances/{financeId}/members/{userId}/toggle', [MyCasaController::class, 'toggleMemberPayment'])->name('my-house.finances.member.toggle');
        Route::post('/my-house/notify',                            [MyCasaController::class, 'sendHouseNotification'])->name('my-house.notify');
    });

    // -------------------------------------------------------------------------
    // Área Seller — Loja / Loja Master / Admin
    // -------------------------------------------------------------------------
    Route::middleware('role:loja,loja_master,admin')->group(function () {
        Route::get('/seller',                   [SellerController::class, 'dashboard'])->name('seller');
        Route::get('/wholesale',                [SellerController::class, 'wholesale'])->name('wholesale');
        Route::get('/seller/products/create',   [SellerController::class, 'createProduct'])->name('seller.products.create');
        Route::post('/seller/products',         [SellerController::class, 'storeProduct'])->name('seller.products.store');
    });

    // (Estudos — criação e edição movidas para antes do wildcard /studies/{id})

    // -------------------------------------------------------------------------
    // Painel Admin — admin e moderador
    // -------------------------------------------------------------------------
    Route::middleware('role:admin,moderador')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/',                            [AdminController::class, 'index'])->name('index');
        Route::post('/houses/{id}/approve',        [AdminController::class, 'approveHouse'])->name('houses.approve');
        Route::post('/houses/{id}/reject',         [AdminController::class, 'rejectHouse'])->name('houses.reject');
        Route::post('/users/{id}/role',            [AdminController::class, 'updateUserRole'])->name('users.role');
        Route::post('/houses/{houseId}/transfer/{userId}/approve', [AdminController::class, 'approveTransfer'])->name('transfer.approve');
        Route::post('/houses/{houseId}/transfer/{userId}/reject',  [AdminController::class, 'rejectTransfer'])->name('transfer.reject');
    });
});
