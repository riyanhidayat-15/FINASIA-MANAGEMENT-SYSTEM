<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UrgentOrdersWidget extends TableWidget
{
    protected static ?string $heading = 'Order yang Perlu Diperhatikan';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => Order::query()
                    ->where('status', 'process')
                    ->whereDate('delivery_date', '<=', now()->addDay()->toDateString())
                    ->orderBy('delivery_date', 'asc')
                    ->orderBy('delivery_time', 'asc')
            )
            ->columns([
                TextColumn::make('order_number')
                    ->label('No. Order'),

                TextColumn::make('customer.name')
                    ->label('Customer'),

                TextColumn::make('name')
                    ->label('Order'),

                TextColumn::make('delivery_date')
                    ->label('Tgl Kirim')
                    ->date('d M Y')
                    ->description(fn ($record) => $record->delivery_time
                        ? \Carbon\Carbon::parse($record->delivery_time)->format('H:i')
                        : null),

                TextColumn::make('urgency')
                    ->label('Status Waktu')
                    ->state(function ($record) {
                        $today = now()->startOfDay();
                        $deliveryDate = \Carbon\Carbon::parse($record->delivery_date)->startOfDay();

                        if ($deliveryDate->lt($today)) {
                            return 'Terlewat';
                        }

                        if ($deliveryDate->isToday()) {
                            return 'Hari Ini';
                        }

                        return 'Besok';
                    })
                    ->badge()
                    ->color(function ($record) {
                        $today = now()->startOfDay();
                        $deliveryDate = \Carbon\Carbon::parse($record->delivery_date)->startOfDay();

                        if ($deliveryDate->lt($today)) {
                            return 'danger';
                        }

                        if ($deliveryDate->isToday()) {
                            return 'warning';
                        }

                        return 'info';
                    }),
            ])
            ->recordActions([
                Action::make('markAsDone')
                    ->label('Tandai Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['status' => 'done']);

                        Notification::make()
                            ->title('Order ditandai selesai')
                            ->success()
                            ->send();
                    }),

                Action::make('viewOrder')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn ($record) => route('filament.admin.resources.orders.edit', $record)),
            ])
            ->emptyStateHeading('Tidak ada order mendesak')
            ->emptyStateDescription('Semua order untuk hari ini dan besok sudah ditandai selesai.')
            ->emptyStateIcon('heroicon-o-check-badge')
            ->paginated(false);
    }
}