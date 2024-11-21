<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Rupadana\ApiService\ApiServicePlugin;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;

class DashboardPanelProvider extends PanelProvider
{
    
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('dashboard')
            ->path('dashboard')
            ->login()
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Sky,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            // ->brandLogo(asset('images/logo.svg'))
            ->brandName('KawalSUARA')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->navigationGroups([
                'Lokasi TPS',
                'Data Perolehan Suara',
                'Pengaturan',
            ])
            ->collapsibleNavigationGroups(false)
            // ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()
            ->userMenuItems([
                 MenuItem::make()
                    ->label('Edit Profil')
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-wrench')
            ])           
            
            ->plugins([
                //API Service
                ApiServicePlugin::make(),
                FilamentEditProfilePlugin::make()
                        ->slug('edit-profil')
                        ->setIcon('heroicon-m-wrench')
                        ->setNavigationGroup('Pengaturan')
                        ->shouldShowAvatarForm()
            ])
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
            // ->navigationItems([
            //     NavigationItem::make('Kirim Data')
            //         // ->visible(fn(): bool => auth()->users()->role('saksi'))
            //         ->url('dashboard/data-perolehan-suara/create', shouldOpenInNewTab: true)
            //         ->icon('heroicon-o-paper-airplane')
            //         // ->group('Reports')
            //         // ->sort(3), //presentation-chart-line
            //     // ...
            // ]);

    }
}
