<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Facades\Filament;
use App\Filament\Pages\Settings;
use Filament\Navigation\MenuItem;
use App\Filament\Pages\Auth\Login;
use Filament\Support\Colors\Color;
use Hasnayeen\Themes\ThemesPlugin;
use App\Filament\Pages\ChatDisplay;
use App\Filament\Pages\Auth\Register;
use Filament\Navigation\NavigationItem;
use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\HomePage;
use App\Filament\Pages\LawyerRecommender;
use App\Filament\Resources\ChatbotConfigurationResource;
use App\Filament\Resources\ChatResource;
use App\Filament\Resources\UserResource;
use Filament\Navigation\NavigationGroup;
use App\Filament\Resources\LawyerResource;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Resources\ChatbotResource;
use App\Filament\Resources\LawyersResource;
use Hasnayeen\Themes\Http\Middleware\SetTheme;
use Illuminate\Session\Middleware\StartSession;
use App\Filament\Resources\ConversationResource;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{


    public function panel(Panel $panel): Panel
    {

        return $panel
        ->favicon(asset('chat.png'))


        ->userMenuItems([
            'profile' => MenuItem::make()->label('Edit profile'),
            'logout' => MenuItem::make()->label('Log out'),


            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder
                    ->items([


                        ...HomePage::getNavigationItems(),
                        ...ChatDisplay::getNavigationItems(),
                        ...LawyerRecommender::getNavigationItems(),

                    ])
                    ->groups([
                        NavigationGroup::make('Admin Management')
                            ->items([
                                NavigationItem::make('Dashboard')
                                ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                                ->url(fn (): string => Dashboard::getUrl())
                                ->hidden(fn(): bool => !auth()->user()->is_admin),
                                NavigationItem::make('User')
                                ->url(fn (): string => UserResource::getUrl())
                                ->hidden(fn(): bool => !auth()->user()->is_admin),
                                NavigationItem::make('Conversation')
                                ->url(fn (): string => ChatResource::getUrl())
                                ->hidden(fn(): bool => !auth()->user()->is_admin),
                                NavigationItem::make('Lawyer')
                                ->url(fn (): string => LawyersResource::getUrl())
                                ->hidden(fn(): bool => !auth()->user()->is_admin),
                                NavigationItem::make('Chatbot')
                                ->url(fn (): string => ChatbotConfigurationResource::getUrl())
                                ->hidden(fn(): bool => !auth()->user()->is_admin),
                                ])
                            ->icon('heroicon-m-cog-8-tooth'),
                    ]);
            })



        ->brandLogo(asset('logo.png'))
        ->brandLogoHeight('2.5rem')
        ->brandName('LegalAidPH')

        ->plugins([
            LightSwitchPlugin::make(),
            FilamentBackgroundsPlugin::make()
            ->imageProvider(
                MyImages::make()
                    ->directory('images/swisnl/filament-backgrounds/curated-by-swis')
                ),

        ])

            ->default()
            ->topNavigation()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->profile(EditProfile::class)
            ->registration(Register::class)
            ->passwordReset()
            ->databaseNotifications()

            ->emailVerification()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')

            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([

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
                SetTheme::class
            ])
            // ->tenantMiddleware([

            //     SetTheme::class
            // ])
            ->authMiddleware([
                Authenticate::class,
            ]);

            Filament::adminPanel()->viteTheme('resources/css/filament/admin/theme.css')
            ;

    }
}
