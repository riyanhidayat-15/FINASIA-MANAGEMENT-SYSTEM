<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Override;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record->invoice()->exists()) {
            Notification::make()
                ->title('Order ini sudah memiliki invoice')
                ->body('Silakan hapus invoice terlebih dahulu jika ingin mengubah data order.')
                ->warning()
                ->persistent()
                ->send();
            }
        return $data;
    }

    public function getMaxContentWidth(): string
    {
        return 'full';
    }
}
