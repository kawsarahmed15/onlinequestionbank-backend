<?php

namespace App\Filament\Pages;

use App\Models\SystemSetting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'require_otp_verification' => filter_var(SystemSetting::get('require_otp_verification', false), FILTER_VALIDATE_BOOLEAN),
            'allow_google_login' => filter_var(SystemSetting::get('allow_google_login', true), FILTER_VALIDATE_BOOLEAN),
            'free_tier_year_limit' => (int)SystemSetting::get('free_tier_year_limit', 3),
            'tier1_price' => (float)SystemSetting::get('tier1_price', 99.00),
            'tier2_price' => (float)SystemSetting::get('tier2_price', 149.00),
            'referral_discount_percent' => (int)SystemSetting::get('referral_discount_percent', 20),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Authentication Configuration')
                    ->schema([
                        Toggle::make('require_otp_verification')
                            ->label('Require SMS OTP Verification')
                            ->helperText('Enable OTP validation verification checks during signup/signin.'),
                        Toggle::make('allow_google_login')
                            ->label('Enable Google Login')
                            ->helperText('Allow users to register and login using their Google accounts.'),
                    ]),

                Section::make('Monetization & Pricing Tiers')
                    ->schema([
                        TextInput::make('free_tier_year_limit')
                            ->label('Free Tier Year Limit')
                            ->numeric()
                            ->required()
                            ->helperText('Max number of unique years a free tier user is allowed to preview/download.'),
                        TextInput::make('tier1_price')
                            ->label('Tier 1 Price (₹)')
                            ->numeric()
                            ->required()
                            ->helperText('Price for 1 Year access to selected class & stream.'),
                        TextInput::make('tier2_price')
                            ->label('Tier 2 Price (₹)')
                            ->numeric()
                            ->required()
                            ->helperText('Price for 2 Years access with unlimited class changes.'),
                        TextInput::make('referral_discount_percent')
                            ->label('Referral Discount (%)')
                            ->numeric()
                            ->required()
                            ->helperText('Discount percentage applied when using a valid referral code.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        SystemSetting::set('require_otp_verification', $data['require_otp_verification'] ? 'true' : 'false');
        SystemSetting::set('allow_google_login', $data['allow_google_login'] ? 'true' : 'false');
        SystemSetting::set('free_tier_year_limit', (string)$data['free_tier_year_limit']);
        SystemSetting::set('tier1_price', (string)$data['tier1_price']);
        SystemSetting::set('tier2_price', (string)$data['tier2_price']);
        SystemSetting::set('referral_discount_percent', (string)$data['referral_discount_percent']);

        Notification::make()
            ->title('Settings saved successfully.')
            ->success()
            ->send();
    }
}
