<?php
// app/helpers/currency_helper.php

function getCurrencySettings($country) {
    $country = strtolower(trim($country));
    
    if (strpos($country, 'bahrain') !== false) return ['code' => 'BHD', 'symbol' => 'BD', 'decimals' => 3];
    if (strpos($country, 'kuwait') !== false) return ['code' => 'KWD', 'symbol' => 'KD', 'decimals' => 3];
    if (strpos($country, 'oman') !== false) return ['code' => 'OMR', 'symbol' => 'OR', 'decimals' => 3];
    if (strpos($country, 'qatar') !== false) return ['code' => 'QAR', 'symbol' => 'QR', 'decimals' => 2];
    if (strpos($country, 'saudi') !== false) return ['code' => 'SAR', 'symbol' => 'SR', 'decimals' => 2];
    if (strpos($country, 'uae') !== false || strpos($country, 'emirates') !== false) return ['code' => 'AED', 'symbol' => 'DH', 'decimals' => 2];
    
    return ['code' => 'INR', 'symbol' => '₹', 'decimals' => 2];
}

function formatCurrency($amount, $countryOverride = null) {
    $country = $countryOverride ?? ($_SESSION['branch_country'] ?? 'India');
    $settings = getCurrencySettings($country);
    $code = '<span style="font-size: 0.8em; font-weight: 500; opacity: 0.7; vertical-align: baseline; margin-right: 2px;">' . $settings['code'] . '</span>';
    
    return $code . ' ' . number_format($amount, $settings['decimals']);
}

function amountToWords($amount, $country = 'India') {
    $settings = getCurrencySettings($country);
    $number = number_format($amount, $settings['decimals'], '.', '');
    $parts = explode('.', $number);
    $main = (int)$parts[0];
    $fraction = isset($parts[1]) ? (int)$parts[1] : 0;

    $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    
    $mainName = 'RUPEES';
    $fractionName = 'PAISE';
    
    $c = strtolower($country);
    if (strpos($c, 'bahrain') !== false || strpos($c, 'kuwait') !== false || strpos($c, 'oman') !== false) {
        $mainName = 'DINARS';
        $fractionName = 'FILS';
    } elseif (strpos($c, 'qatar') !== false || strpos($c, 'saudi') !== false) {
        $mainName = 'RIYALS';
        $fractionName = 'DIRHAMS';
    } elseif (strpos($c, 'uae') !== false || strpos($c, 'emirates') !== false) {
        $mainName = 'DIRHAMS';
        $fractionName = 'FILS';
    }

    $words = strtoupper($f->format($main)) . ' ' . $mainName;
    if ($fraction > 0) {
        $words .= ' AND ' . strtoupper($f->format($fraction)) . ' ' . $fractionName;
    }
    
    return $words . ' ONLY';
}

function getCurrencySymbol() {
    $country = $_SESSION['branch_country'] ?? 'India';
    $settings = getCurrencySettings($country);
    return '<span style="font-size: 0.85em; font-weight: normal; opacity: 0.8;">' . $settings['code'] . '</span>';
}

function formatDateTime($datetime, $country = null) {
    if (!$datetime) return '-';
    
    $country = $country ?? ($_SESSION['branch_country'] ?? 'India');
    $c = strtolower(trim($country));
    
    // Default to India
    $tz = 'Asia/Kolkata';
    
    if (strpos($c, 'bahrain') !== false || strpos($c, 'kuwait') !== false || strpos($c, 'oman') !== false || 
        strpos($c, 'qatar') !== false || strpos($c, 'saudi') !== false || strpos($c, 'uae') !== false || 
        strpos($c, 'emirates') !== false) {
        $tz = 'Asia/Riyadh'; // Most GCC countries are UTC+3
    }
    
    $date = new DateTime($datetime, new DateTimeZone('UTC')); // Assume DB is UTC
    $date->setTimezone(new DateTimeZone($tz));
    
    return $date->format('d M Y, h:i A');
}
