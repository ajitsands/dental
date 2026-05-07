<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription - <?php echo $data['details']->patient_name; ?></title>
    <style>
        body { font-family: 'Inter', sans-serif; padding: 40px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #0d6efd; padding-bottom: 20px; margin-bottom: 30px; }
        .clinic-name { font-size: 28px; font-weight: bold; color: #0d6efd; margin-bottom: 5px; }
        .clinic-info { font-size: 14px; color: #666; }
        
        .patient-info { display: flex; justify-content: space-between; margin-bottom: 40px; background: #f8f9fa; padding: 15px; border-radius: 10px; }
        .patient-detail { font-size: 16px; }
        .label { color: #888; font-size: 12px; text-transform: uppercase; display: block; }
        
        .prescription-body { min-height: 400px; }
        .rx-symbol { font-size: 32px; font-weight: bold; color: #0d6efd; margin-bottom: 20px; }
        .medicines { font-size: 18px; line-height: 1.6; white-space: pre-wrap; margin-bottom: 40px; }
        .instructions { font-size: 15px; background: #fffcf0; padding: 15px; border-left: 4px solid #ffc107; }
        
        .footer { margin-top: 60px; display: flex; justify-content: space-between; align-items: flex-end; }
        .signature-box { border-top: 1px solid #ccc; width: 200px; text-align: center; padding-top: 10px; font-size: 14px; }
        
        @media print {
            .no-print { display: none; }
            body { padding: 20px; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #0d6efd; color: white; border: none; border-radius: 5px; cursor: pointer;">Print Now</button>
    </div>

    <div class="header">
        <?php if($data['details']->logo): ?>
            <img src="<?php echo BASE_URL . '/' . $data['details']->logo; ?>" style="max-width: 180px; max-height: 90px; margin-bottom: 15px;">
        <?php endif; ?>
        <div class="clinic-name"><?php echo $data['details']->branch_name; ?></div>
        <div class="clinic-info"><?php echo $data['details']->address; ?> | Tel: <?php echo $data['details']->contact; ?></div>
    </div>

    <div class="patient-info">
        <div class="patient-detail">
            <span class="label">Patient Name</span>
            <strong><?php echo $data['details']->patient_name; ?></strong>
        </div>
        <div class="patient-detail">
            <span class="label">Age / Gender</span>
            <strong><?php echo $data['details']->age; ?> Yrs / <?php echo $data['details']->gender; ?></strong>
        </div>
        <div class="patient-detail" style="text-align: right;">
            <span class="label">Date</span>
            <strong><?php echo date('d M Y', strtotime($data['details']->start_time)); ?></strong>
        </div>
    </div>

    <div class="prescription-body">
        <div class="rx-symbol">Rx</div>
        <div class="medicines"><?php echo nl2br($data['prescription']->medicines); ?></div>
        
        <?php if($data['prescription']->instructions): ?>
            <div class="instructions">
                <strong>Instructions:</strong><br>
                <?php echo nl2br($data['prescription']->instructions); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        <div class="doctor-name">
            <span class="label">Doctor</span>
            <strong>Dr. <?php echo $data['details']->doctor_name; ?></strong>
        </div>
        <div class="signature-box">
            Authorized Signature
        </div>
    </div>
</body>
</html>
