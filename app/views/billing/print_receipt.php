<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - <?php echo $data['invoice']->invoice_number; ?></title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            color: #000;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
        }
        
        /* Default for 80mm */
        .receipt-container {
            background: #fff;
            width: 80mm;
            margin: 20px auto;
            padding: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* 58mm mode */
        .receipt-container.v58mm {
            width: 58mm;
            font-size: 11px;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        .item-row { display: flex; justify-content: space-between; margin-bottom: 3px; }
        .footer { margin-top: 15px; font-size: 11px; }
        
        .no-print {
            text-align: center;
            padding: 20px;
            background: #334155;
            color: white;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .no-print button {
            padding: 8px 15px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            background: #3b82f6;
            color: white;
            font-weight: bold;
            margin: 0 5px;
        }

        .no-print select {
            padding: 8px;
            border-radius: 4px;
            border: none;
            margin-right: 10px;
        }

        @media print {
            body { background: #fff; }
            .receipt-container { 
                margin: 0 !important; 
                box-shadow: none !important; 
                width: 100% !important; /* Thermal printer handles the physical width */
            }
            .no-print { display: none !important; }
            @page { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <label>Printer Size: </label>
        <select id="sizeSelector" onchange="changeSize(this.value)">
            <option value="80mm">Standard (80mm)</option>
            <option value="58mm">Small (58mm)</option>
        </select>
        <button onclick="window.print()"><i class="fas fa-print"></i> Print Receipt</button>
        <button onclick="window.close()" style="background: #64748b;">Close</button>
    </div>

    <div class="receipt-container" id="receipt">
        <div class="text-center">
            <?php if($data['branch']->logo): ?>
                <img src="<?php echo BASE_URL . '/' . $data['branch']->logo; ?>" style="max-width: 150px; max-height: 80px; margin-bottom: 10px;">
            <?php endif; ?>
            <h2 style="margin: 0; font-size: 18px;"><?php echo $data['branch']->name; ?></h2>
            <p style="margin: 5px 0;">
                <?php echo $data['branch']->address; ?><br>
                Tel: <?php echo $data['branch']->contact; ?><br>
                <?php if($data['branch']->tax_number): ?>
                    TRN: <?php echo $data['branch']->tax_number; ?>
                <?php endif; ?>
            </p>
        </div>

        <div class="divider"></div>

        <div>
            <div class="item-row">
                <span>Date:</span>
                <span><?php echo date('d-m-Y H:i', strtotime($data['invoice']->created_at)); ?></span>
            </div>
            <div class="item-row">
                <span>Invoice:</span>
                <span class="bold">#<?php echo $data['invoice']->invoice_number; ?></span>
            </div>
            <div class="item-row">
                <span>Patient:</span>
                <span><?php echo $data['invoice']->patient_name; ?></span>
            </div>
        </div>

        <div class="divider"></div>

        <div class="bold">
            <div class="item-row">
                <span style="width: 55%;">Item</span>
                <span style="width: 15%; text-align: center;">Qty</span>
                <span style="width: 30%; text-align: right;">Total</span>
            </div>
        </div>
        <div class="divider" style="margin: 4px 0;"></div>

        <?php foreach($data['items'] as $item): ?>
        <div class="item-row">
            <span style="width: 55%;"><?php echo $item->service_name; ?></span>
            <span style="width: 15%; text-align: center;"><?php echo $item->quantity; ?></span>
            <span style="width: 30%; text-align: right;"><?php echo number_format($item->total_price, 2); ?></span>
        </div>
        <?php endforeach; ?>

        <div class="divider"></div>

        <div class="text-right">
            <div class="item-row">
                <span style="width: 60%;">Subtotal:</span>
                <span style="width: 40%;"><?php echo number_format($data['invoice']->total_amount, 2); ?></span>
            </div>
            <?php if($data['invoice']->tax_amount > 0): ?>
            <div class="item-row">
                <span style="width: 60%;">Tax (<?php echo $data['branch']->tax_pct; ?>%):</span>
                <span style="width: 40%;"><?php echo number_format($data['invoice']->tax_amount, 2); ?></span>
            </div>
            <?php endif; ?>
            
            <div class="divider"></div>
            
            <div class="item-row bold" style="font-size: 1.2em; margin-top: 5px;">
                <span style="width: 60%;">TOTAL:</span>
                <span style="width: 40%;"><?php echo formatCurrency($data['invoice']->final_amount, $data['branch']->country); ?></span>
            </div>
            
            <div class="text-left" style="font-size: 0.85em; margin-top: 10px; font-style: italic; border-top: 1px solid #eee; padding-top: 5px;">
                <div class="bold">Amount in Words:</div>
                <?php echo amountToWords($data['invoice']->final_amount, $data['branch']->country); ?>
            </div>
        </div>

        <div class="divider" style="border-top-style: solid; border-top-width: 2px;"></div>

        <div class="text-center footer">
            <p class="bold" style="font-size: 1.1em;">Thank you for choosing DenSmart!</p>
            <p style="margin-top: 5px;">Powering Digital Dentistry</p>
            <p style="font-size: 0.8em; margin-top: 10px;"><?php echo date('Y-m-d H:i:s'); ?></p>
        </div>
    </div>

    <script>
        function changeSize(size) {
            const receipt = document.getElementById('receipt');
            if(size === '58mm') {
                receipt.classList.add('v58mm');
            } else {
                receipt.classList.remove('v58mm');
            }
        }
    </script>
</body>
</html>
