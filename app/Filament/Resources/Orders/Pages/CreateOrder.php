<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;
use Filament\Support\Enums\MaxWidth; // Pastikan import class ini
use Illuminate\Database\Eloquent\Model;
use Override;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function handleRecordCreation(array $data): Order
    {
        return app(OrderService::class)->create($data);
    }

    public function getMaxContentWidth(): string
    {
        return 'full';
    }
}
