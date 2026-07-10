<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $orderProcess = Order::where('status', 'process')
            ->whereBetween('delivery_date', [$startOfMonth, $endOfMonth])
            ->count();

        $orderDone = Order::where('status', 'done')
            ->whereBetween('delivery_date', [$startOfMonth, $endOfMonth])
            ->count();

        $omzetBulanIni = Invoice::whereBetween('invoice_date', [$startOfMonth, $endOfMonth])
            ->sum('total_amount');

        $invoiceBelumMasuk = Invoice::where('status', 'belum_masuk')->count();
        $totalBelumMasuk = Invoice::where('status', 'belum_masuk')->sum('total_amount');

        $totalCustomer = Customer::count();

        return [
            Stat::make('Order Bulan Ini', "{$orderDone} selesai / {$orderProcess} proses")
                ->description('Total order untuk periode ini')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Omzet Bulan Ini', 'Rp ' . number_format($omzetBulanIni, 0, ',', '.'))
                ->description('Dari invoice yang diterbitkan')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Invoice Belum Masuk', $invoiceBelumMasuk . ' invoice')
                ->description('Rp ' . number_format($totalBelumMasuk, 0, ',', '.') . ' belum dibayar')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color($invoiceBelumMasuk > 0 ? 'warning' : 'success'),

            Stat::make('Customer Aktif', $totalCustomer)
                ->description('Total customer terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('gray'),
        ];
    }
}