<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\CustomLogin;
use App\Filament\Resources\GA\GaAssetResource\Widgets\GaAssetDoughnutWidget;
use App\Filament\Resources\GA\GaAssetResource\Widgets\GaAssetTableWidget;
use App\Filament\Resources\GA\GaAssetResource\Widgets\GaAssetWidget;
use App\Filament\Resources\ITD\ITAssetResource\Widgets\ITAssetDoughnutWidget;
use App\Filament\Resources\ITD\ITAssetResource\Widgets\ITAssetTableWidget;
use App\Filament\Resources\ITD\ITAssetResource\Widgets\ITAssetWidget;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('')
            ->spa()
            ->login(CustomLogin::class)
            ->loginRouteSlug('login')
            ->profile()
            ->defaultThemeMode(ThemeMode::Light)
            ->maxContentWidth(Width::Full)
            ->brandName('MJG Asset Management')
            ->brandLogo(asset('assets/images/LOGO-MEDQUEST-HD.png'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('assets/images/Medquest-Favicon.png'))
            ->databaseTransactions()
            ->userMenuItems([
                'profile' => MenuItem::make()->label('Edit Profile'),
            ])
            ->colors([
                'primary' => Color::Blue,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(' ITD'),
                NavigationGroup::make()
                    ->label(' General Affairs'),
                NavigationGroup::make()
                    ->label('User Management'),
            ])
            ->plugins([
                //
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                ITAssetWidget::class,
                ITAssetDoughnutWidget::class,
                ITAssetTableWidget::class,
                GaAssetWidget::class,
                GaAssetDoughnutWidget::class,
                GaAssetTableWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
