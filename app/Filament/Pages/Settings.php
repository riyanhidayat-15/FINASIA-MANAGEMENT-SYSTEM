<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Schemas\CompanyForm;
use App\Models\Company;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use UnitEnum;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.settings';

    protected static ?string $navigationLabel = 'Company';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static string | UnitEnum | null $navigationGroup = 'Settings';

    protected static ?string $title = 'Pengaturan Perusahaan';

    public ?array $data = [];

    public function mount(): void
    {
        $company = Company::first();

        if (! $company) {
            $company = Company::create([
                'name' => 'Nama Perusahaan Anda',
            ]);
        }

        $data = $company->toArray();

        // FileUpload butuh value berupa array of string, bukan string biasa
        if (! empty($data['logo_path']) && is_string($data['logo_path'])) {
            $data['logo_path'] = [$data['logo_path']];
        }

        $this->data = $data;
    }

    public function form(Schema $schema): Schema
    {
        return CompanyForm::configure($schema)
            ->statePath('data');
    }

    public function save(): void
    {
        $company = Company::first();

        $state = $this->form->getState();

        // FileUpload mengembalikan array, ambil elemen pertama untuk disimpan sebagai string
        if (isset($state['logo_path']) && is_array($state['logo_path'])) {
            $state['logo_path'] = reset($state['logo_path']) ?: null;
        }

        $company->update($state);

        Notification::make()
            ->title('Data perusahaan berhasil disimpan')
            ->success()
            ->send();
    }
}