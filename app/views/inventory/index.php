<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1"><?php echo __('inventory_stock'); ?></h2>
        <p class="text-muted"><?php echo __('monitor_clinic_supplies'); ?></p>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo BASE_URL; ?>/inventory/report" class="btn btn-outline-primary rounded-pill px-4 shadow-sm me-2">
            <i class="fas fa-file-alt me-1"></i> <?php echo __('view_report'); ?>
        </a>
        <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#itemModal">
            <i class="fas fa-plus me-1"></i> <?php echo __('add_new_item'); ?>
        </button>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card p-4 border-0 shadow-sm" style="border-radius: 20px;">
            <div class="d-flex align-items-center">
                <div class="densmart-logo bg-primary bg-opacity-10 text-primary me-3">
                    <i class="fas fa-boxes"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted small fw-bold uppercase"><?php echo __('total_items'); ?></h6>
                    <h3 class="mb-0 fw-bold"><?php echo count($data['items']); ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <?php 
            $lowStockCount = 0;
            foreach($data['items'] as $item) if($item->quantity <= $item->low_stock_threshold) $lowStockCount++;
        ?>
        <div class="card p-4 border-0 shadow-sm" style="border-radius: 20px;">
            <div class="d-flex align-items-center">
                <div class="densmart-logo bg-danger bg-opacity-10 text-danger me-3">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted small fw-bold uppercase"><?php echo __('low_stock_alerts'); ?></h6>
                    <h3 class="mb-0 fw-bold"><?php echo $lowStockCount; ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-4 border-0 shadow-sm" style="border-radius: 20px;">
            <div class="d-flex align-items-center">
                <div class="densmart-logo bg-success bg-opacity-10 text-success me-3">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted small fw-bold uppercase"><?php echo __('inventory_status'); ?></h6>
                    <h3 class="mb-0 fw-bold text-success">Healthy</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="inventoryTable">
                <thead>
                    <tr>
                        <th class="ps-4"><?php echo __('item_name'); ?></th>
                        <th><?php echo __('category'); ?></th>
                        <th><?php echo __('current_stock'); ?></th>
                        <th><?php echo __('threshold'); ?></th>
                        <th><?php echo __('status'); ?></th>
                        <th class="text-end pe-4"><?php echo __('actions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['items'] as $item): ?>
                    <tr>
                        <td class="ps-4 fw-bold"><?php echo $item->item_name; ?></td>
                        <td><span class="badge bg-light text-dark border"><?php echo $item->category; ?></span></td>
                        <td class="fs-5 fw-bold <?php echo ($item->quantity <= $item->low_stock_threshold) ? 'text-danger' : 'text-dark'; ?>">
                            <?php echo $item->quantity; ?> <small class="text-muted fw-normal"><?php echo $item->unit; ?></small>
                        </td>
                        <td class="small text-muted"><?php echo $item->low_stock_threshold; ?> <?php echo $item->unit; ?></td>
                        <td>
                            <?php if($item->quantity <= 0): ?>
                                <span class="badge bg-danger rounded-pill px-3">Out of Stock</span>
                            <?php elseif($item->quantity <= $item->low_stock_threshold): ?>
                                <span class="badge bg-warning text-dark rounded-pill px-3">Low Stock</span>
                            <?php else: ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">In Stock</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <button onclick="openEditItemModal(<?php echo $item->id; ?>)" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                                <button onclick="openStockModal(<?php echo $item->id; ?>, '<?php echo addslashes($item->item_name); ?>', 'add')" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fas fa-plus me-1"></i> Add
                                </button>
                                <button onclick="openStockModal(<?php echo $item->id; ?>, '<?php echo addslashes($item->item_name); ?>', 'consume')" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                    <i class="fas fa-minus me-1"></i> Consume
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="itemModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="itemModalTitle">Add Inventory Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="itemForm">
                <input type="hidden" name="id" id="itemId">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Item Name</label>
                        <input type="text" name="item_name" id="itemName" class="form-control" placeholder="e.g. Latex Gloves (Medium)" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Category</label>
                            <select name="category" id="itemCategory" class="form-select">
                                <option>Clinical Supplies</option>
                                <option>Surgical Items</option>
                                <option>Office Supplies</option>
                                <option>Orthodontics</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Unit</label>
                            <select name="unit" id="itemUnit" class="form-select" required>
                                <option value="Pcs">Pcs (Pieces)</option>
                                <option value="Box">Box</option>
                                <option value="Pkt">Pkt (Packet)</option>
                                <option value="Nos">Nos (Numbers)</option>
                                <option value="g">Grams (g)</option>
                                <option value="kg">Kilograms (kg)</option>
                                <option value="ml">Milliliters (ml)</option>
                                <option value="L">Liters (L)</option>
                                <option value="oz">Ounces (oz)</option>
                                <option value="Tab">Tablets (Tab)</option>
                                <option value="Cap">Capsules (Cap)</option>
                                <option value="Strip">Strip</option>
                                <option value="Roll">Roll</option>
                                <option value="Bottle">Bottle</option>
                                <option value="Vial">Vial</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6" id="initialQtyGroup">
                            <label class="form-label small fw-bold">Initial Quantity</label>
                            <input type="number" name="quantity" id="itemQty" class="form-control" value="0" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Low Stock Alert at</label>
                            <input type="number" name="low_stock_threshold" id="itemThreshold" class="form-control" value="5" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-5 rounded-pill shadow">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Stock Modal -->
<div class="modal fade" id="stockModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-3">
                <h5 class="modal-title fw-bold" id="stockModalTitle">Update Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="stockForm">
                <input type="hidden" name="item_id" id="stockItemId">
                <input type="hidden" name="type" id="stockType">
                <div class="modal-body p-4">
                    <h6 id="stockItemName" class="text-primary fw-bold mb-3"></h6>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Quantity</label>
                        <input type="number" name="quantity" class="form-control" required min="1">
                    </div>

                    <div id="patientSelection" class="mb-3 d-none">
                        <label class="form-label small fw-bold">Against Patient</label>
                        <select name="patient_id" class="form-select select2-modal">
                            <option value="">-- Optional: Select Patient --</option>
                            <?php foreach($data['patients'] as $p): ?>
                                <option value="<?php echo $p->id; ?>"><?php echo $p->name; ?> (<?php echo $p->unique_id; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label small fw-bold">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Reason for update..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-5 rounded-pill shadow" id="stockSaveBtn">Update Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let stockModal;
let itemModal;

$(document).ready(function() {
    $('#inventoryTable').DataTable({
        "pageLength": 10,
        "language": dtLanguage
    });

    $('.select2-modal').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#stockModal')
    });

    // Initialize modals after scripts load
    if (document.getElementById('stockModal')) {
        stockModal = new bootstrap.Modal(document.getElementById('stockModal'));
    }
    if (document.getElementById('itemModal')) {
        itemModal = new bootstrap.Modal(document.getElementById('itemModal'));
    }

    $('#itemForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');
        
        $.ajax({
            url: '<?php echo BASE_URL; ?>/inventory/save',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if(res.status === 'success') {
                    Swal.fire('Success', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                    btn.prop('disabled', false).text('Save Item');
                }
            }
        });
    });

    $('#stockForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $('#stockSaveBtn');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Updating...');
        
        $.ajax({
            url: '<?php echo BASE_URL; ?>/inventory/updateStock',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if(res.status === 'success') {
                    Swal.fire('Updated', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                    btn.prop('disabled', false).text('Update Stock');
                }
            }
        });
    });
});


function openEditItemModal(id) {
    if(!itemModal) itemModal = new bootstrap.Modal(document.getElementById('itemModal'));
    
    $('#itemModalTitle').text('Edit Inventory Item');
    $('#itemId').val(id);
    $('#initialQtyGroup').addClass('d-none');
    
    $.get('<?php echo BASE_URL; ?>/inventory/getItem/' + id, function(item) {
        $('#itemName').val(item.item_name);
        $('#itemCategory').val(item.category);
        $('#itemUnit').val(item.unit);
        $('#itemThreshold').val(item.low_stock_threshold);
        itemModal.show();
    }, 'json');
}

// Reset modal when closing
document.getElementById('itemModal').addEventListener('hidden.bs.modal', function () {
    $('#itemForm')[0].reset();
    $('#itemId').val('');
    $('#itemModalTitle').text('Add Inventory Item');
    $('#initialQtyGroup').removeClass('d-none');
});

function openStockModal(id, name, type) {
    if(!stockModal) stockModal = new bootstrap.Modal(document.getElementById('stockModal'));
    
    $('#stockItemId').val(id);
    $('#stockItemName').text(name);
    $('#stockType').val(type);
    
    if(type === 'add') {
        $('#stockModalTitle').text('Add New Stock');
        $('#stockSaveBtn').removeClass('btn-danger').addClass('btn-primary').text('Add Stock');
        $('#patientSelection').addClass('d-none');
    } else {
        $('#stockModalTitle').text('Consume Stock');
        $('#stockSaveBtn').removeClass('btn-primary').addClass('btn-danger').text('Consume Stock');
        $('#patientSelection').removeClass('d-none');
    }
    
    stockModal.show();
}
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
