<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1">Billing & Invoices</h2>
        <p class="text-muted">Manage payments, generate invoices, and track outstanding dues.</p>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-success"><i class="fas fa-file-invoice me-1"></i> Create Invoice</button>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white p-3">
            <h6 class="mb-1 opacity-75">Total Revenue (Monthly)</h6>
            <h3 class="mb-0">₹1,25,000</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white p-3">
            <h6 class="mb-1 opacity-75">Collected Amount</h6>
            <h3 class="mb-0">₹98,500</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white p-3">
            <h6 class="mb-1 opacity-75">Outstanding Dues</h6>
            <h3 class="mb-0">₹26,500</h3>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Recent Invoices</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Invoice #</th>
                        <th>Patient</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Tax (GST/VAT)</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-4 fw-bold">INV-2026-001</td>
                        <td>Amit Shah</td>
                        <td>2026-05-05</td>
                        <td>₹12,000</td>
                        <td>₹2,160 (18%)</td>
                        <td><span class="badge bg-success">Paid</span></td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-print"></i></button>
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-4 fw-bold">INV-2026-002</td>
                        <td>Sara Khan</td>
                        <td>2026-05-05</td>
                        <td>₹4,500</td>
                        <td>₹810 (18%)</td>
                        <td><span class="badge bg-warning">Partial</span></td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-print"></i></button>
                            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
