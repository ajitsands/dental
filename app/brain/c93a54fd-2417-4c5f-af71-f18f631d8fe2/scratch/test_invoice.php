<?php
// Scratch script to verify invoice number generation logic
$year = date('Y');
$prefix = "INV-$year-";

// Mock data simulation
$mockInvoices = [
    ['id' => 1, 'invoice_number' => 'INV-2025-0005'],
    ['id' => 2, 'invoice_number' => 'INV-2026-0001'],
];

function simulate($invoices, $year) {
    $prefix = "INV-$year-";
    $lastInvoice = null;
    
    // Simulate query: WHERE invoice_number LIKE "INV-2026-%" ORDER BY id DESC
    foreach (array_reverse($invoices) as $inv) {
        if (strpos($inv['invoice_number'], $prefix) === 0) {
            $lastInvoice = $inv['invoice_number'];
            break;
        }
    }
    
    $nextNum = 1;
    if ($lastInvoice) {
        $parts = explode('-', $lastInvoice);
        if (count($parts) >= 3) {
            $lastNum = (int)end($parts);
            $nextNum = $lastNum + 1;
        }
    }
    
    return $prefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
}

echo "Current Year: $year\n";
echo "Simulation Result: " . simulate($mockInvoices, $year) . "\n";

$mockEmpty = [];
echo "Empty Simulation Result: " . simulate($mockEmpty, $year) . "\n";
?>
