<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Customer')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Customer')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-user'),

                        TextInput::make('phone')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->prefixIcon('heroicon-o-phone'),

                        Select::make('province')
                            ->label('Provinsi')
                            // value dropdown = NAMA provinsi (value sama dengan label)
                            ->options(function () {
                                $names = array_values(self::getProvinces());
                                return array_combine($names, $names);
                            })
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn ($set) => $set('city', null)),

                        Select::make('city')
                            ->label('Kota/Kabupaten')
                            ->options(function ($get) {
                                $provinceName = $get('province');

                                if (! $provinceName) {
                                    return [];
                                }

                                $provinces = self::getProvinces();
                                $provinceId = array_search($provinceName, $provinces);

                                if (! $provinceId) {
                                    return [];
                                }

                                $cities = self::getCitiesByProvince($provinceId);
                                $cityNames = array_values($cities);

                                return array_combine($cityNames, $cityNames);
                            })
                            ->searchable()
                            ->disabled(fn ($get) => ! $get('province'))
                            ->helperText(fn ($get) => ! $get('province') ? 'Pilih provinsi dahulu' : null),

                        TextInput::make('postal_code')
                            ->label('Kode Pos')
                            ->maxLength(10),

                        Textarea::make('address')
                            ->label('Alamat')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Ambil daftar provinsi [id => nama], cache 30 hari.
     */
    protected static function getProvinces(): array
    {
        return Cache::remember('wilayah_provinces', now()->addDays(30), function () {
            $response = Http::timeout(10)->get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');

            if (! $response->successful()) {
                return [];
            }

            return collect($response->json())
                ->mapWithKeys(fn ($item) => [$item['id'] => $item['name']])
                ->toArray();
        });
    }

    /**
     * Ambil daftar kota/kabupaten [id => nama] untuk provinsi tertentu, cache 30 hari.
     */
    protected static function getCitiesByProvince(string $provinceId): array
    {
        return Cache::remember("wilayah_cities_{$provinceId}", now()->addDays(30), function () use ($provinceId) {
            $response = Http::timeout(10)->get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$provinceId}.json");

            if (! $response->successful()) {
                return [];
            }

            return collect($response->json())
                ->mapWithKeys(fn ($item) => [$item['id'] => $item['name']])
                ->toArray();
        });
    }
}