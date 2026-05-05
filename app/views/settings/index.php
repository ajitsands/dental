<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <div class="card p-3 mb-4">
            <div class="nav flex-column nav-pills" id="settings-tabs">
                <button class="nav-link active mb-2 text-start" data-bs-toggle="pill" data-bs-target="#tab-clinic"><i class="fas fa-clinic-medical me-2"></i> Clinic Profile</button>
                <button class="nav-link mb-2 text-start" data-bs-toggle="pill" data-bs-target="#tab-tax"><i class="fas fa-percentage me-2"></i> Tax & Billing</button>
                <button class="nav-link mb-2 text-start" data-bs-toggle="pill" data-bs-target="#tab-localization"><i class="fas fa-language me-2"></i> Localization</button>
                <button class="nav-link mb-2 text-start" data-bs-toggle="pill" data-bs-target="#tab-users"><i class="fas fa-users-cog me-2"></i> Staff Roles</button>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="tab-content" id="settings-tabContent">
            <!-- Clinic Profile -->
            <div class="tab-pane fade show active" id="tab-clinic">
                <div class="card p-4">
                    <h4 class="mb-4">Clinic Profile</h4>
                    <form>
                        <div class="row g-3">
                            <div class="col-md-12 text-center mb-3">
                                <div class="densmart-logo mx-auto mb-2" style="width: 80px; height: 80px; font-size: 2rem;">
                                    <i class="fas fa-tooth"></i>
                                </div>
                                <button class="btn btn-sm btn-outline-primary">Change Logo</button>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Clinic Name</label>
                                <input type="text" class="form-control" value="DenSmart Central">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Primary Email</label>
                                <input type="email" class="form-control" value="contact@densmart.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Number</label>
                                <input type="text" class="form-control" value="+91 9876543210">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" rows="2">123 Dental Street, Medical Plaza, City</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Save Profile</button>
                    </form>
                </div>
            </div>

            <!-- Tax & Billing -->
            <div class="tab-pane fade" id="tab-tax">
                <div class="card p-4">
                    <h4 class="mb-4">Tax & Billing Configuration</h4>
                    <form>
                        <div class="mb-4">
                            <label class="form-label d-block">Tax Type</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="tax_type" id="tax_gst" checked>
                                <label class="btn btn-outline-primary" for="tax_gst">GST (India)</label>
                                
                                <input type="radio" class="btn-check" name="tax_type" id="tax_vat">
                                <label class="btn btn-outline-primary" for="tax_vat">VAT (GCC/Middle East)</label>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">VAT/GST Number</label>
                                <input type="text" class="form-control" placeholder="Enter Registration Number">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tax Rate (%)</label>
                                <input type="number" class="form-control" value="18">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Currency</label>
                                <select class="form-select">
                                    <option value="INR">INR (₹)</option>
                                    <option value="BHD">BHD (Bahraini Dinar)</option>
                                    <option value="AED">AED (UAE Dirham)</option>
                                    <option value="SAR">SAR (Saudi Riyal)</option>
                                    <option value="USD">USD ($)</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Save Tax Settings</button>
                    </form>
                </div>
            </div>

            <!-- Localization -->
            <div class="tab-pane fade" id="tab-localization">
                <div class="card p-4">
                    <h4 class="mb-4">Localization Settings</h4>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Default Language</label>
                            <select class="form-select">
                                <option value="en">English (US)</option>
                                <option value="ar">Arabic (العربية)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Time Zone</label>
                            <select class="form-select">
                                <option value="Asia/Kolkata">(GMT+05:30) India Standard Time</option>
                                <option value="Asia/Bahrain">(GMT+03:00) Bahrain</option>
                                <option value="Asia/Dubai">(GMT+04:00) Dubai</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Apply Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
