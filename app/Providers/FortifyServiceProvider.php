<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use MongoDB\Client as MongoDBClient;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
        
        // Usar email como campo de autenticación
        Fortify::authenticateUsing(function ($request) {
            $user = \App\Models\User::where('email', $request->email)->first();

            if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                return $user;
            }
        });
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(function (Request $request) {
            return view('auth.login');
        });

        Fortify::resetPasswordView(function (Request $request) {
            return view('auth.reset-password', [
                'request' => $request,
            ]);
        });

        Fortify::requestPasswordResetLinkView(function (Request $request) {
            return view('auth.forgot-password');
        });

        // Desactivar vistas de Inertia que ya no existen
        Fortify::verifyEmailView(fn () => view('auth.verify-email'));
        
        Fortify::registerView(function () {
            // Obtener las preguntas secretas desde MongoDB
            try {
                $uri = env('MONGODB_URI');
                // Deshabilitar TLS si está en la URI
                if (strpos($uri, 'ssl=true') !== false) {
                    $uri = str_replace('ssl=true', 'ssl=false', $uri);
                }
                if (strpos($uri, 'tls=true') !== false) {
                    $uri = str_replace('tls=true', 'tls=false', $uri);
                }
                $uri .= (strpos($uri, '?') === false ? '?' : '&') . 'ssl=false';
                
                $client = new \MongoDB\Client($uri);
                $database = $client->selectDatabase('equipo');
                $collection = $database->selectCollection('recuperar-password');
                
                $cursor = $collection->find();
                $preguntas = [];
                foreach ($cursor as $document) {
                    $preguntas[] = iterator_to_array($document);
                }
                
                return view('auth.register', [
                    'preguntas' => $preguntas
                ]);
            } catch (\Exception $e) {
                return view('auth.register', [
                    'preguntas' => []
                ]);
            }
        });
        
        // NO desactivar todas las rutas de Fortify - necesitamos el login
        
        Fortify::twoFactorChallengeView(fn () => view('auth.two-factor'));
        
        Fortify::confirmPasswordView(fn () => view('auth.confirm-password'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
