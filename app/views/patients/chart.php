<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<style>
    .tooth-container {
        display: grid;
        grid-template-columns: repeat(16, 1fr);
        gap: 10px;
        margin-bottom: 30px;
        padding: 20px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .tooth {
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .tooth svg {
        width: 100%;
        height: auto;
        fill: #f1f5f9;
        stroke: #94a3b8;
        stroke-width: 1;
    }

    .tooth:hover svg {
        fill: #e2e8f0;
        transform: scale(1.1);
    }

    .tooth.active svg {
        fill: #3b82f6;
        stroke: #2563eb;
    }

    .tooth.cavity svg { fill: #ef4444; }
    .tooth.filling svg { fill: #10b981; }
    .tooth.extraction svg { fill: #64748b; opacity: 0.3; }
    .tooth.crown svg { fill: #f59e0b; }

    .tooth-label {
        font-size: 10px;
        font-weight: 600;
        color: #64748b;
        margin-top: 5px;
    }

    .chart-legend {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-bottom: 20px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        font-size: 12px;
        font-weight: 500;
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 3px;
        margin-right: 6px;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
</style>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1">Dental Chart: Amit Shah</h2>
        <p class="text-muted">Unique ID: P-1001 | Age: 45 | Gender: Male</p>
    </div>
    <div class="col-md-4 text-end">
        <span class="badge bg-danger p-2 mb-2"><i class="fas fa-exclamation-triangle me-1"></i> Medical Alert: Diabetes, BP</span>
        <br>
        <button class="btn btn-outline-primary btn-sm"><i class="fas fa-history me-1"></i> View History</button>
    </div>
</div>

<div class="chart-legend">
    <div class="legend-item"><div class="legend-color" style="background: #ef4444;"></div> Cavity</div>
    <div class="legend-item"><div class="legend-color" style="background: #10b981;"></div> Filling</div>
    <div class="legend-item"><div class="legend-color" style="background: #64748b;"></div> Extraction</div>
    <div class="legend-item"><div class="legend-color" style="background: #f59e0b;"></div> Crown</div>
    <div class="legend-item"><div class="legend-color" style="background: #f1f5f9; border: 1px solid #94a3b8;"></div> Healthy</div>
</div>

<!-- Upper Teeth -->
<div class="tooth-container">
    <?php for($i=1; $i<=16; $i++): ?>
    <div class="tooth" data-tooth="<?php echo $i; ?>" onclick="openToothModal(<?php echo $i; ?>)">
        <svg viewBox="0 0 100 120">
            <path d="M20,40 Q20,10 50,10 Q80,10 80,40 Q80,80 50,110 Q20,80 20,40 Z" />
        </svg>
        <div class="tooth-label"><?php echo $i; ?></div>
    </div>
    <?php endfor; ?>
</div>

<!-- Lower Teeth -->
<div class="tooth-container">
    <?php for($i=17; $i<=32; $i++): ?>
    <div class="tooth" data-tooth="<?php echo $i; ?>" onclick="openToothModal(<?php echo $i; ?>)">
        <svg viewBox="0 0 100 120" style="transform: rotate(180deg);">
            <path d="M20,40 Q20,10 50,10 Q80,10 80,40 Q80,80 50,110 Q20,80 20,40 Z" />
        </svg>
        <div class="tooth-label"><?php echo $i; ?></div>
    </div>
    <?php endfor; ?>
</div>

<!-- Tooth Action Modal -->
<div class="modal fade" id="toothModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tooth #<span id="modalToothNum"></span> Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <button class="btn btn-outline-danger w-100 py-3" onclick="setCondition('cavity')">
                            <i class="fas fa-bacteria d-block mb-2"></i> Cavity
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-success w-100 py-3" onclick="setCondition('filling')">
                            <i class="fas fa-fill d-block mb-2"></i> Filling
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-secondary w-100 py-3" onclick="setCondition('extraction')">
                            <i class="fas fa-trash-alt d-block mb-2"></i> Extraction
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-warning w-100 py-3" onclick="setCondition('crown')">
                            <i class="fas fa-crown d-block mb-2"></i> Crown
                        </button>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" id="toothNotes" rows="3" placeholder="Add specific notes for this tooth..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" onclick="setCondition('healthy')">Clear All</button>
                <button type="button" class="btn btn-primary" onclick="saveToothData()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentTooth = null;

function openToothModal(num) {
    currentTooth = num;
    document.getElementById('modalToothNum').innerText = num;
    var myModal = new bootstrap.Modal(document.getElementById('toothModal'));
    myModal.show();
}

function setCondition(condition) {
    const toothEl = document.querySelector(`.tooth[data-tooth="${currentTooth}"]`);
    toothEl.className = 'tooth ' + condition;
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('toothModal')).hide();
    
    // In a real app, this would trigger an AJAX save
    console.log(`Tooth ${currentTooth} set to ${condition}`);
}

function saveToothData() {
    // Implement AJAX save logic here
    bootstrap.Modal.getInstance(document.getElementById('toothModal')).hide();
}
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
