<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        $this->forceViteManifestInProduction();

        Gate::define('kelola-buku', function (User $user): bool {
            return $user->hasAnyRole(['admin', 'petugas']);
        });
    }

    /**
     * Make sure production deployments (e.g. Vercel) always resolve assets from
     * the compiled manifest instead of a leftover Vite dev-server marker file.
     *
     * A stale "public/hot" file makes @vite emit http://localhost:5173/... URLs
     * which are unreachable for visitors, resulting in an unstyled page.
     */
    private function forceViteManifestInProduction(): void
    {
        if ($this->app->isProduction()) {
            Vite::useHotFile(storage_path('framework/vite.hot'));
        }
    }
}
