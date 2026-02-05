<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\Checkbox;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Component;

class CustomLogin extends BaseLogin
{
    protected ?string $heading = null;

    public function getHeading(): string
    {
        return config('app.name');
    }

    public function mount(): void
    {
        parent::mount();
        
        // Pre-fill demo credentials (remove in production)
        $this->form->fill([
            'email' => '',
            'password' => '',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email Address')
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1])
            ->placeholder('Enter your email address');
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Password')
            ->password()
            ->required()
            ->extraInputAttributes(['tabindex' => 2])
            ->placeholder('Enter your password');
    }

    protected function getRememberFormComponent(): Component
    {
        return Checkbox::make('remember')
            ->label('Remember me');
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            return parent::authenticate();
        } catch (\Exception $exception) {
            $this->addError('data.email', 'Invalid credentials.');
            return null;
        }
    }
}