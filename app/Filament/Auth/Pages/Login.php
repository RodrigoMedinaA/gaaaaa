<?php

namespace App\Filament\Auth\Pages;

use Filament\Auth\Pages\Login as BaseLogin; // <-- v4
use Filament\Forms\Components\TextInput;

class Login extends BaseLogin
{
    protected function getEmailFormComponent(): TextInput
    {
        return TextInput::make('usuario')
            ->label('Usuario')
            ->required()
            ->autofocus()
            ->autocomplete('username');
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'usuario'  => $data['usuario'] ?? null,
            'password' => $data['password'] ?? null,
        ];
    }
}
