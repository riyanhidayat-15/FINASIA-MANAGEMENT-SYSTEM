<?php

namespace App\Filament\Pages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('logo_path')
                     ->label('Logo Perusahaan')
                    ->image()
                    ->maxSize(2048),
                TextInput::make('name')
                    ->required(),
                Textarea::make('address')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('city')
                    ->default(null),
                TextInput::make('province')
                    ->default(null),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                TextInput::make('director_name')
                    ->default(null),
                TextInput::make('bank_name')
                    ->default(null),
                TextInput::make('bank_account_name')
                    ->default(null),
                TextInput::make('bank_account_number')
                    ->default(null),
            ]);
    }
}