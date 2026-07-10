<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Invoice;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;

class RevenueChart extends ChartWidget
{
    use HasFiltersSchema;

    protected ?string $heading = 'Omzet per Bulan';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function filtersSchema(Schema $schema): Schema
    {
        $customers = Customer::pluck('name', 'id')->toArray();

        $years = Invoice::query()
            ->selectRaw('DISTINCT YEAR(invoice_date) as year')
            ->orderByDesc('year')
            ->pluck('year', 'year')
            ->toArray();

        if (empty($years)) {
            $years = [now()->year => now()->year];
        }

        return $schema->components([
            Select::make('customerFilter')
                ->label('Customer')
                ->options(['all' => 'Semua Customer'] + $customers)
                ->default('all')
                ->live(),

            Select::make('yearFilter')
                ->label('Tahun')
                ->options($years)
                ->default(now()->year)
                ->live(),
        ]);
    }

    protected function getData(): array
    {
        $year = $this->filters['yearFilter'] ?? now()->year;
        $customerFilter = $this->filters['customerFilter'] ?? 'all';

        $query = Invoice::query();

        if ($customerFilter !== 'all') {
            $query->where('customer_id', $customerFilter);
        }

        $data = $query
            ->whereYear('invoice_date', $year)
            ->selectRaw('MONTH(invoice_date) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthLabels = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $values = [];
        foreach (range(1, 12) as $month) {
            $values[] = (float) ($data[$month] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Omzet (Rp)',
                    'data' => $values,
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => array_values($monthLabels),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}