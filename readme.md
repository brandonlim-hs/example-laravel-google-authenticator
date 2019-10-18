# Laravel 6 + Google Authenticator 2FA

Example Laravel project with Google Authenticator 2FA

### Dependencies

- **[laravel/ui](https://github.com/laravel/ui)** to install the frontend scaffolding
- **[pragmarx/google2fa-laravel](https://github.com/antonioribeiro/google2fa-laravel)** to enable Google 2FA secret and QR code

### Keys files

- `app/Http/Controllers/Auth/Google2FAController.php`
- `app/Http/Controllers/Auth/LoginController.php`
- `database/migrations/2019_10_18_001813_add_google2fa_secret_to_users_table.php`
- `resources/views/auth/google2fa/activate.blade.php`
- `resources/views/auth/google2fa/deactivate.blade.php`
- `resources/views/auth/google2fa/login.blade.php`
- `routes/web.php`

### Steps to set up

#### Set up Google Authenticator 2FA

1. Create [Laravel 6 project](https://laravel.com/docs/6.x/installation#installing-laravel)
    ```sh
    laravel new my-project
    cd my-project
    ```

2. Install the [Laravel frontend scaffolding](https://laravel.com/docs/6.x/authentication#included-routing)
    ```sh
    composer require laravel/ui --dev
    php artisan ui vue --auth
    npm install & npm run dev
    ```

3. Install pragmarx/google2fa-laravel to enable 2FA
    ```sh
    composer require pragmarx/google2fa-laravel --dev
    ```

4. Publish the config file if custom settings are required (optional)
    ```sh
    php artisan vendor:publish --provider="PragmaRX\Google2FALaravel\ServiceProvider"
    ```

5. Create database migration to store 2FA secret
    ```sh
    php artisan make:migration add_google2fa_secret_to_users_table
    ```

    Edit migration file
    ```diff
    Schema::table('users', function (Blueprint $table) {
    -   //
    +   $table->string('google2fa_secret')->nullable()->after('password');
    });
    ```
    
    ```diff
    Schema::table('users', function (Blueprint $table) {
    -   //
    +   $table->dropColumn('google2fa_secret');
    });
    ```

#### Allow user to activate 2FA

1. Edit `routes/web.php`
    ```diff
    + Route::get('/2fa/activate', [Google2FAController::class, 'activate2FA'])->name('2fa.activate');
    + Route::post('/2fa/activate', [Google2FAController::class, 'assign2FA']);
    ```

2. Activation flow. See `app/Http/Controllers/Auth/Google2FAController.php`
    1. Generate secret key
    2. Save to session temporarily
    3. Generate QR code
    4. Show QR code and secret to user
    5. User install Google 2FA app on mobile device (if not installed)
    6. User scan QR code (or, manually add secret)
    7. User input 2FA code to verify
    8. If 2FA code is not verified, show error and repeat step iv
    9. If 2FA code is verified, save 2FA secret to user - activation is complete

3. Add view to activate 2FA. See `resources/views/auth/google2fa/activate.blade.php`

#### Allow user to deactivate 2FA

1. Edit `routes/web.php`
    ```diff
    + Route::get('/2fa/deactivate', [Google2FAController::class, 'deactivate2FA'])->name('2fa.deactivate');
    ```

2. Delete 2FA secret from user. See `app/Http/Controllers/Auth/Google2FAController.php`

3. Add view to deactivate 2FA. See `resources/views/auth/google2fa/deactivate.blade.php`

#### Allow user to verify 2FA

1. Edit `routes/web.php`
    ```diff
    + Route::get('/2fa/login', [Google2FAController::class, 'login2FA'])->name('2fa.login');
    + Route::post('/2fa/login', [Google2FAController::class, 'verify2FA']);
    ```

2. Redirect user to 2FA form after login with password. See `app/Http/Controllers/Auth/LoginController.php`

3. Verify 2FA code, and login user if successful. See `app/Http/Controllers/Auth/Google2FAController.php`

4. Add views to login with 2FA. See `resources/views/auth/google2fa/login.blade.php`