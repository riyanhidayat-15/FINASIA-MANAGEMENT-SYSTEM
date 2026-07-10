<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 40px 20px; 
            padding: 0;
        }

        .page-break {
            page-break-after:always;
        }
        
        .as {
            text-align: right;
            margin: 0;
            font-size: 10px;
        }
        
        .container {
            padding: 6px;
            border: double 3px black;
        }

        .table-top {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
        }

        .cell {
            width: 150px;
        }

        td {
            font-size: 10px;
            height: 25px;
        }

        .titik-dua {
            width: 10px;
        }

        .table-excel,
        .table-count {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-family: Arial, sans-serif;
        }
 
        .table-excel td {
            border: 1px solid black; 
            font-size: 11px;
            height: 15px;

        }

        .table-excel th {
            padding: 10px;
            border: 1px solid black; 
            font-weight: bold;
            text-align: center;
            font-size: 11px;

        }

        .table-count td {
            font-size: 11px;
            height: 15px;

        }

        .table-receipt {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-family: Arial, sans-serif;
            
        }

        .table-receipt .table-right {
            text-align: right;
        }

        .table-receipt td {
            height: 13px;
        }
    </style>
</head>
<body>
    <div class="page-break">
        <p class="as">Account Statement</p>
        <div class="container">
            <table class="table-top" border="0" style="width: 100%;">
                <tr>
                    <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                        <table border="0" style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="width: 90px;"><strong>To</strong></td>
                                <td class="titik-dua">:</td>
                                <td><strong><?php echo e($invoice->customer->name); ?></strong></td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;"><strong>ADDRESS</strong></td>
                                <td class="titik-dua" style="vertical-align: top;">:</td>
                                <td style="vertical-align: top;"><?php echo e($invoice->customer->address); ?></td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td><strong>CITY</strong></td>
                                <td class="titik-dua">:</td>
                                <td><?php echo e($invoice->customer->city); ?></td>
                            </tr>
                            <tr>
                                <td><strong>PHONE</strong></td>
                                <td class="titik-dua">:</td>
                                <td><?php echo e($invoice->customer->phone); ?></td>
                            </tr>
                        </table>
                    </td>

                    <td style="width: 50%; vertical-align: top; padding-left: 20px;">
                        <table border="0" style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="width: 150px;"><strong>INVOICE No</strong></td>
                                <td class="titik-dua">:</td>
                                <td style="width: 150px; text-align: center;"><?php echo e($invoice->invoice_number); ?></td>
                            </tr>
                            <tr>
                                <td><strong>DATE</strong></td>
                                <td class="titik-dua">:</td>
                                <td style="text-align: center;"><?php echo e(\Carbon\Carbon::parse($receipt->invoice->invoice_date)->translatedFormat('l, d M Y')); ?></td>
                            </tr>
                            <tr>
                                <td><strong>PO #</strong></td>
                                <td class="titik-dua">:</td>
                                <td style="text-align: center;"><?php echo e($invoice->po_number); ?></td>
                            </tr>
                            <tr>
                                <td><strong>PERIODE</strong></td>
                                <td class="titik-dua">:</td>
                                <td style="text-align: center;"><?php echo e($invoice->periode); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table class="table-excel">
                <thead>
                    
                    <tr>
                        <th>DESCRIPTION</th>
                        <th>QUANTITY</th>
                        <th>UNIT PRICE</th>
                        <th>CHARGE RP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $TOTAL_ROWS = 12;
                        $items = $invoice->order->items; // Mengambil item dari relasi order invoice
                        $dataCount = count($items);
                    ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = 0; $i < $TOTAL_ROWS; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i < $dataCount): ?>
                            <?php $item = $items[$i]; ?>
                            <tr>
                                <td style="padding-left: 5px;"><?php echo e($item->name); ?></td>
                                <td style="text-align: center;"><?php echo e($item->quantity); ?></td>
                                <td style="text-align: right; padding-right: 5px;"><?php echo e(number_format($item->unit_price, 0, ',', '.')); ?></td>
                                <td style="text-align: right; padding-right: 5px;"><?php echo e(number_format($item->quantity * $item->unit_price, 0, ',', '.')); ?></td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
            <table class="table-count" border="0">
                <tr>
                    <td style="width: 80%; vertical-align: top; padding-right: 20px;">
                        <table border="0" style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="width: 90px;"><strong></strong></td>
                                <td class="titik-dua"></td>
                                <td><strong></strong></td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;"><strong></strong></td>
                                <td class="titik-dua" style="vertical-align: top;"></td>
                                <td style="vertical-align: top;"></td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td><strong></strong></td>
                                <td class="titik-dua"></td>
                                <td></td>
                            </tr>
                        </table>
                    </td>

                    <td style="width: 20%; vertical-align: top; padding-left: 20px;">
                        <table border="0" style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="width: 150px; text-align: right;">SUB TOTAL</td>
                                <td class="titik-dua">:</td>
                                
                                <td style="width: 150px; padding: 0;">
                                    
                                    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
                                        <tr>
                                            <td style="text-align: left; border: none;">RP</td>
                                            <td style="text-align: right; border: none;"><?php echo e(number_format($invoice->subtotal, 0, ',', '.')); ?></td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                            <tr>
                                <td style="height: 2px;"></td>
                            </tr>
                        <tr>
                                <td style="width: 150px; text-align: right;">DISC (%)</td>
                                <td class="titik-dua">:</td>
                                
                                <td style="width: 150px; padding: 0;">
                                    
                                    <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
                                        <tr>
                                            <td style="text-align: left; border: none;">RP</td>
                                            <td style="text-align: right; border: none;"><?php echo e(number_format($invoice->discount_percentage, 0, ',', '.')); ?></td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                            <tr>
                                <td style="height: 2px;"></td>
                            </tr>
                            <tr>
                                <td style="width: 150px; text-align: right;"></td>
                                <td class="titik-dua"></td>
                                
                                <td style="width: 150px; padding: 0;">
                                    
                                    <table style="width: 100%; border-collapse: collapse; border: 1.5px solid black;">
                                        <tr>
                                            <td style="text-align: left; border: none;">RP</td>
                                            <td style="text-align: right; border: none;"><strong><?php echo e(number_format($invoice->total_amount, 0, ',', '.')); ?></strong></td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                            
                        </table>
                    </td>
                </tr>
            </table>
            <table border="0" style="width: 100%; border-collapse: collapse; margin-top: 30px;">
                <tr>
                    <td style="width: 60%; vertical-align: top;">
                        
                        <table border="0" style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="width: 50px; vertical-align: top; font-size: 11px; padding-top: 15px;">Says :</td>
                                <td style="padding: 15px 20px; border: 2px solid black; font-size: 11px; text-align: center; font-weight: bold;">
                                    <?php echo e($invoice->amount_in_words); ?>

                                </td>
                                <td style="width: 40px;">&nbsp;</td>
                            </tr>
                            
                            <tr>
                                <td colspan="3" style="height: 15px;"></td>
                            </tr>
                            
                            <tr>
                                <td>&nbsp;</td>
                                <td colspan="2" style="font-size: 11px; line-height: 1.5;">
                                    <strong>Payment can be transferred to :</strong><br>
                                    <?php echo e($company->bank_account_name); ?><br>
                                    <?php echo e($company->bank_name); ?><br>
                                    <strong><?php echo e($company->bank_account_number); ?></strong>
                                </td>
                            </tr>
                        </table>

                    </td>

                    <td style="width: 40%; vertical-align: top; text-align: center;">
                        
                        <table border="0" style="width: 100%; border-collapse: collapse; text-align: center;">
                            <tr>
                                <td style="font-size: 11px; height: 10px;"><?php echo e($company->city); ?>, <?php echo e(\Carbon\Carbon::parse($receipt->invoice->invoice_date)->format('d M Y')); ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 11px; height: 10px; font-weight: bold; padding-bottom: 75px;"><?php echo e(\Illuminate\Support\Str::upper($company->name)); ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 11px; font-weight: bold; text-decoration: underline;"><?php echo e($company->director_name); ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 11px; color: #333;">Direktur</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table style="width: 100%;">
                <tr>
                    <td style="border: 2px solid black; text-align: center;">Thank you for choosing us</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="receipt-page">
        <div class="container">
            <table class="table-receipt" border="0">
                <tr>
                   <td style="width: 75%;">
                        <img src="<?php echo e(public_path('storage/01KWP25EFPZM0BVGB1ZT42FBAB.png')); ?>" alt="" style="width: 100px; height: auto;">
                    </td>
                    <td colspan="4">
                        <table class="table-right" style="width: 100%;">
                            <tr>
                                <td><?php echo e(\Illuminate\Support\Str::upper($company->name)); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo e($company->address); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo e($company->city); ?> - <?php echo e($company->province); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo e($company->phone); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="titik-dua" style="height: 5px;"></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right; padding-right:5px; font-size: 11px; font-weight: bold;">No . </td>
                    <td style="text-align:right; white-space:nowrap; width: 100px; border: 1px solid black; height:15px; font-size: 11px; font-weight: bold;"><?php echo e($receipt->receipt_number); ?></td>
                </tr>
                <tr>
                    <td colspan="5" style="font-size: 14px; text-decoration: underline; text-align: center;">RECEIPT</td>
                </tr>
                <tr>
                    <td colspan="5" style="font-size: 9px; text-align: center;">Kwitansi</td>
                </tr>
                <tr>
                    <td class="titik-dua" style="height: 5px;"></td>
                </tr>
                <tr>
                    <td style="font-size: 12px;">RECEIVED FROM</td>
                    <td class="titik-dua">:</td>
                    <td colspan="3" style="font-size: 12px;"><strong><?php echo e($receipt->invoice->customer->name); ?></strong></td>
                </tr>
                <tr>
                    <td style="height: 3px; font-size: 9px;"><i>Terima Dari</i></td>
                </tr>
                <tr>
                    <td class="titik-dua" style="height: 5px;"></td>
                </tr>
                <tr>
                    <td style="font-size: 12px;">AMOUNT OF</td>
                    <td class="titik-dua">:</td>
                    <td colspan="3" rowspan="2" style="font-size: 12px; border: 1px solid black; border-bottom: 1px solid black; background-color: #f2f2f2;"><?php echo e($receipt->amount_in_words); ?></td>
                </tr>
                <tr>
                    <td style="height: 3px; font-size: 9px;"><i>Sejumlah</i></td>
                </tr>
                <tr>
                    <td class="titik-dua" style="height: 5px;"></td>
                </tr>
                <tr>
                    <td style="font-size: 12px;">PAYMENT FOR</td>
                    <td class="titik-dua">:</td>
                    <td colspan="3" style="font-size: 12px;"><?php echo e($receipt->invoice->order->name); ?></td>
                </tr>
                <tr>
                    <td style="height: 3px; font-size: 9px;"><i>Untuk Pembayaran</i></td>
                </tr>
            </table>
            <table style="width: 100%; padding:0; border-collapse:collapse; margin-top:10px; text-align: center;" border="0">
                <tr style="margin:0;">
                    <td colspan="2" style="width: 30%; height:10px;"></td>
                    <td style="width: 30%; height:10px;" ><span><?php echo e($company->city); ?>, <?php echo e(\Carbon\Carbon::parse($receipt->invoice->invoice_date)->format('d M Y')); ?></span></td>
                </tr>
                <tr style="margin:0;">
                    <td colspan="2" style="width: 30%; height:10px;"></td>
                    <td style="width: 30%; height:10px;"><?php echo e(\Illuminate\Support\Str::upper($company->name)); ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="height: 80px;"></td>
                    
                </tr>
                <tr>
                    <td style="font-size: 14px; text-align:center; border: 1px solid black; background-color: #f2f2f2;"><strong>Rp. <?php echo e(number_format($receipt->amount, 0, ',', '.')); ?></strong></td>
                    <td></td>
                    <td style="text-decoration: underline; font-size: 11px;"><strong><?php echo e($company->director_name); ?></strong></td>
                </tr>
                <tr style="margin:0;">
                    <td colspan="2" style="width: 30%; height:10px;"></td>
                    <td style="width: 30%; height:10px;">Director</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html><?php /**PATH /home/riyanh/project/catering-saas/resources/views/pdf/invoice-receipt.blade.php ENDPATH**/ ?>