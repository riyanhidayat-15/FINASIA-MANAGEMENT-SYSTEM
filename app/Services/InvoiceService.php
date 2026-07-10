<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Receipt;

class InvoiceService
{
    public function generateInvoiceNumber(): string
    {
        $lastInvoice = Invoice::query()
            ->orderByDesc('id')
            ->first();

        $nextNumber = $lastInvoice ? ((int) $lastInvoice->invoice_number) + 1 : 1;

        return (string) $nextNumber;
    }

    public function generateReceiptNumber(): string
    {
        $prefix = now()->format('Ymd');

        $sequence = Receipt::query()
            ->where('receipt_number', 'like', "{$prefix}%")
            ->count() + 1;

        return $prefix . sprintf('%02d', $sequence);
    }

    public function generateFromOrder(Order $order): Invoice
    {
        $order->loadMissing('items');

        $subtotal = $order->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });

        $discountPercentage = 0;
        $discountAmount = 0;
        $totalAmount = $subtotal - $discountAmount;
        $amountInWords = $this->terbilang($totalAmount) . ' Rupiah';

        $invoice = Invoice::create([
            'invoice_number' => $this->generateInvoiceNumber(),
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'invoice_date' => now()->toDateString(),
            'po_number' => null,
            'periode' => now()->translatedFormat('F Y'),
            'subtotal' => $subtotal,
            'discount_percentage' => $discountPercentage,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'amount_in_words' => $amountInWords,
            'status' => 'belum_masuk',
        ]);

        Receipt::create([
            'receipt_number' => $this->generateReceiptNumber(),
            'invoice_id' => $invoice->id,
            'receipt_date' => $invoice->invoice_date,
            'amount' => $totalAmount,
            'amount_in_words' => $amountInWords,
            'payment_purpose' => 'Pembayaran ' . $order->name,
        ]);

        return $invoice;
    }

    public function terbilang(float $number): string
    {
        $number = (int) $number;
        $words = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

        if ($number < 12) {
            return $words[$number];
        } elseif ($number < 20) {
            return $this->terbilang($number - 10) . ' Belas';
        } elseif ($number < 100) {
            return $this->terbilang(intdiv($number, 10)) . ' Puluh ' . $this->terbilang($number % 10);
        } elseif ($number < 200) {
            return 'Seratus ' . $this->terbilang($number - 100);
        } elseif ($number < 1000) {
            return $this->terbilang(intdiv($number, 100)) . ' Ratus ' . $this->terbilang($number % 100);
        } elseif ($number < 2000) {
            return 'Seribu ' . $this->terbilang($number - 1000);
        } elseif ($number < 1000000) {
            return $this->terbilang(intdiv($number, 1000)) . ' Ribu ' . $this->terbilang($number % 1000);
        } elseif ($number < 1000000000) {
            return $this->terbilang(intdiv($number, 1000000)) . ' Juta ' . $this->terbilang($number % 1000000);
        }

        return (string) $number;
    }
}