<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

final class AnalyticsPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('analytics')
            ->path('analytics')
            ->login()
            ->authGuard('web')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Analytics/Widgets'), for: 'App\\Filament\\Analytics\\Widgets')
            ->widgets([
                // will be registered later
            ])
            ->discoverPages(in: app_path('Filament/Analytics/Pages'), for: 'App\\Filament\\Analytics\\Pages')
            ->pages([
                \App\Filament\Analytics\Pages\AnalyticsDashboard::class,
            ])
            ->canAccess(function (): bool {
                $user = auth()->user();
                return $user !== null && in_array($user->role, ['admin', 'member'], true);
            });
    }
}
