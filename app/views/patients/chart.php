<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<style>
    .tooth-container {
        display: grid;
        grid-template-columns: repeat(16, 1fr);
        gap: 5px;
        margin-bottom: 20px;
        padding: 15px;
        background: #f8fafc;
        border-radius: 15px;
        border: 1px solid #e2e8f0;
    }

    .tooth {
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        padding: 5px;
        min-width: 50px;
    }

    .tooth:hover {
        background: #f1f5f9;
        border-radius: 10px;
        transform: translateY(-2px);
    }

    .chart-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
        background: white;
        padding: 12px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .legend-item {
        display: flex;
        align-items: center;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #475569;
    }

    .legend-color {
        width: 10px;
        height: 10px;
        border-radius: 2px;
        margin-right: 6px;
    }

    .tooth svg {
        width: 30px;
        height: 35px;
        fill: #f1f5f9;
        stroke: #94a3b8;
        stroke-width: 1;
        margin-bottom: 5px;
        transition: fill 0.2s;
    }

    .tooth-box {
        width: 35px;
        height: 35px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1.5fr 1fr;
        grid-template-rows: 1fr 1.5fr 1fr;
        border: 1px solid #cbd5e1;
        background: white;
        border-radius: 4px;
        overflow: hidden;
    }

    .surface {
        border: 0.1px solid #e2e8f0;
        transition: background 0.2s;
    }

    /* Condition Colors */
    .tooth.cavity svg { fill: #fee2e2; stroke: #ef4444; }
    .tooth.cavity .surface.active { background: #ef4444 !important; }
    
    .tooth.filling svg { fill: #dcfce7; stroke: #10b981; }
    .tooth.filling .surface.active { background: #10b981 !important; }
    
    .tooth.crown svg { fill: #fef3c7; stroke: #f59e0b; }
    .tooth.crown .surface.active { background: #f59e0b !important; }

    .tooth.root-canal svg { fill: #f3e8ff; stroke: #a855f7; }
    .tooth.root-canal .surface.active { background: #a855f7 !important; }

    .tooth.implant svg { fill: #ccfbf1; stroke: #0d9488; }
    .tooth.implant .surface.active { background: #0d9488 !important; }

    .tooth.braces svg { fill: #fce7f3; stroke: #db2777; }
    .tooth.braces .surface.active { background: #db2777 !important; }
    
    .tooth.extraction { opacity: 0.3; }
    .tooth.extraction svg { fill: #64748b; }
    .tooth.extraction .tooth-box { background: #f1f5f9; }

    .surface.active {
        background: #3b82f6 !important;
    }

    .surface-top { grid-column: 2; grid-row: 1; }
    .surface-left { grid-column: 1; grid-row: 2; }
    .surface-center { grid-column: 2; grid-row: 2; }
    .surface-right { grid-column: 3; grid-row: 2; }
    .surface-bottom { grid-column: 2; grid-row: 3; }

    .tooth-label {
        font-size: 12px;
        font-weight: 800;
        color: #1e293b;
        margin-top: 8px;
        display: block;
    }
</style>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1"><?php echo __('dental_chart'); ?>: <?php echo $data['patient']->name; ?></h2>
        <p class="text-muted"><?php echo __('visual_clinical_view'); ?> | ID: <?php echo $data['patient']->unique_id; ?></p>
    </div>
    <div class="col-md-4 text-end">
        <div class="btn-group me-2" role="group">
            <button type="button" class="btn btn-outline-dark btn-sm active" id="btnAdult" onclick="switchMode('adult')"><?php echo __('adult'); ?> (32)</button>
            <button type="button" class="btn btn-outline-dark btn-sm" id="btnPediatric" onclick="switchMode('pediatric')"><?php echo __('pediatric'); ?> (20)</button>
        </div>
        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#historyModal"><i class="fas fa-history me-1"></i> <?php echo __('view_history'); ?></button>
    </div>
</div>

<div id="chartSummary" class="mb-4 d-none">
    <div class="alert alert-info py-2 px-3 rounded-pill border-0 shadow-sm d-inline-flex align-items-center">
        <i class="fas fa-microscope me-2"></i>
        <span id="summaryText" class="small fw-bold"></span>
    </div>
</div>

<?php if($data['patient']->medical_alerts): ?>
    <div class="alert alert-danger d-flex align-items-center mb-4 rounded-4 border-0 shadow-sm">
        <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
        <div>
            <strong class="d-block"><?php echo __('medical_alert'); ?></strong>
            <span class="small"><?php echo $data['patient']->medical_alerts; ?></span>
        </div>
    </div>
<?php endif; ?>

<div class="chart-legend mb-4 shadow-sm">
    <div class="legend-item"><div class="legend-color" style="background: #ef4444;"></div> <?php echo __('cavity'); ?></div>
    <div class="legend-item"><div class="legend-color" style="background: #10b981;"></div> <?php echo __('filling'); ?></div>
    <div class="legend-item"><div class="legend-color" style="background: #64748b;"></div> <?php echo __('extraction'); ?></div>
    <div class="legend-item"><div class="legend-color" style="background: #f59e0b;"></div> <?php echo __('crown'); ?></div>
    <div class="legend-item"><div class="legend-color" style="background: #a855f7;"></div> <?php echo __('root_canal'); ?></div>
    <div class="legend-item"><div class="legend-color" style="background: #0d9488;"></div> <?php echo __('implant'); ?></div>
    <div class="legend-item"><div class="legend-color" style="background: #db2777;"></div> <?php echo __('braces'); ?></div>
</div>

<!-- Adult View -->
<div id="adultView">
    <!-- Upper Teeth -->
    <div class="row g-0 justify-content-center mb-4">
        <div class="col-auto text-muted fw-bold pe-4 pt-4 border-end"><?php echo __('upper'); ?></div>
        <div class="col-11 ps-2">
            <div class="tooth-container" style="background: white; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);">
                <?php 
                $chart = [];
                foreach($data['chartData'] as $cd) {
                    $chart[$cd->tooth_number] = [
                        'condition' => $cd->condition_name, 
                        'notes' => $cd->notes,
                        'surfaces' => explode(',', $cd->surfaces ?? '')
                    ];
                }
                ?>
                <?php for($i=1; $i<=16; $i++): ?>
                <?php 
                    $cData = $chart[$i] ?? ['condition' => 'healthy', 'notes' => '', 'surfaces' => []];
                    $surfaces = is_array($cData['surfaces']) ? $cData['surfaces'] : [];
                ?>
                <div class="tooth <?php echo $cData['condition']; ?>" data-tooth="<?php echo $i; ?>" data-notes="<?php echo htmlspecialchars($cData['notes']); ?>" data-surfaces='<?php echo json_encode($surfaces); ?>' onclick="openToothModal(<?php echo $i; ?>)" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Tooth #<?php echo $i; ?></b><br><?php echo $cData['condition'] != 'healthy' ? strtoupper($cData['condition']) : 'Healthy'; ?><br><small><?php echo $cData['notes']; ?></small>">
                    <svg viewBox="0 0 100 120">
                        <path d="M20,40 Q20,10 50,10 Q80,10 80,40 Q80,80 50,110 Q20,80 20,40 Z" />
                    </svg>
                    <div class="tooth-box">
                        <div class="surface surface-top <?php echo in_array('T', $surfaces) ? 'active' : ''; ?>"></div>
                        <div class="surface surface-left <?php echo in_array('L', $surfaces) ? 'active' : ''; ?>"></div>
                        <div class="surface surface-center <?php echo in_array('C', $surfaces) ? 'active' : ''; ?>"></div>
                        <div class="surface surface-right <?php echo in_array('R', $surfaces) ? 'active' : ''; ?>"></div>
                        <div class="surface surface-bottom <?php echo in_array('B', $surfaces) ? 'active' : ''; ?>"></div>
                    </div>
                    <div class="tooth-label"><?php echo $i; ?></div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <!-- Lower Teeth -->
    <div class="row g-0 justify-content-center">
        <div class="col-auto text-muted fw-bold pe-4 pt-4 border-end">LOWER</div>
        <div class="col-11 ps-2">
            <div class="tooth-container" style="background: white; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);">
                <?php for($i=17; $i<=32; $i++): ?>
                <?php 
                    $cData = $chart[$i] ?? ['condition' => 'healthy', 'notes' => '', 'surfaces' => []];
                    $surfaces = is_array($cData['surfaces']) ? $cData['surfaces'] : [];
                ?>
                <div class="tooth <?php echo $cData['condition']; ?>" data-tooth="<?php echo $i; ?>" data-notes="<?php echo htmlspecialchars($cData['notes']); ?>" data-surfaces='<?php echo json_encode($surfaces); ?>' onclick="openToothModal(<?php echo $i; ?>)" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Tooth #<?php echo $i; ?></b><br><?php echo $cData['condition'] != 'healthy' ? strtoupper($cData['condition']) : 'Healthy'; ?><br><small><?php echo $cData['notes']; ?></small>">
                    <svg viewBox="0 0 100 120" style="transform: rotate(180deg);">
                        <path d="M20,40 Q20,10 50,10 Q80,10 80,40 Q80,80 50,110 Q20,80 20,40 Z" />
                    </svg>
                    <div class="tooth-box">
                        <div class="surface surface-top <?php echo in_array('T', $surfaces) ? 'active' : ''; ?>"></div>
                        <div class="surface surface-left <?php echo in_array('L', $surfaces) ? 'active' : ''; ?>"></div>
                        <div class="surface surface-center <?php echo in_array('C', $surfaces) ? 'active' : ''; ?>"></div>
                        <div class="surface surface-right <?php echo in_array('R', $surfaces) ? 'active' : ''; ?>"></div>
                        <div class="surface surface-bottom <?php echo in_array('B', $surfaces) ? 'active' : ''; ?>"></div>
                    </div>
                    <div class="tooth-label"><?php echo $i; ?></div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>

<!-- Pediatric View -->
<div id="pediatricView" class="d-none">
    <!-- Upper Teeth -->
    <div class="row g-0 justify-content-center mb-4">
        <div class="col-auto text-muted fw-bold pe-4 pt-4 border-end">UPPER</div>
        <div class="col-10 ps-2">
            <div class="tooth-container" style="background: white; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); grid-template-columns: repeat(10, 1fr);">
                <?php 
                $pUpper = [55,54,53,52,51,61,62,63,64,65];
                foreach($pUpper as $i): 
                    $cData = $chart[$i] ?? ['condition' => 'healthy', 'notes' => '', 'surfaces' => []];
                    $surfaces = is_array($cData['surfaces']) ? $cData['surfaces'] : [];
                ?>
                <div class="tooth <?php echo $cData['condition']; ?>" data-tooth="<?php echo $i; ?>" data-notes="<?php echo htmlspecialchars($cData['notes']); ?>" data-surfaces='<?php echo json_encode($surfaces); ?>' onclick="openToothModal(<?php echo $i; ?>)" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Tooth #<?php echo $i; ?></b><br><?php echo $cData['condition'] != 'healthy' ? strtoupper($cData['condition']) : 'Healthy'; ?><br><small><?php echo $cData['notes']; ?></small>">
                    <svg viewBox="0 0 100 120">
                        <path d="M20,40 Q20,10 50,10 Q80,10 80,40 Q80,80 50,110 Q20,80 20,40 Z" />
                    </svg>
                    <div class="tooth-box">
                        <div class="surface surface-top <?php echo in_array('T', $surfaces) ? 'active' : ''; ?>"></div>
                        <div class="surface surface-left <?php echo in_array('L', $surfaces) ? 'active' : ''; ?>"></div>
                        <div class="surface surface-center <?php echo in_array('C', $surfaces) ? 'active' : ''; ?>"></div>
                        <div class="surface surface-right <?php echo in_array('R', $surfaces) ? 'active' : ''; ?>"></div>
                        <div class="surface surface-bottom <?php echo in_array('B', $surfaces) ? 'active' : ''; ?>"></div>
                    </div>
                    <div class="tooth-label"><?php echo $i; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Lower Teeth -->
    <div class="row g-0 justify-content-center">
        <div class="col-auto text-muted fw-bold pe-4 pt-4 border-end">LOWER</div>
        <div class="col-10 ps-2">
            <div class="tooth-container" style="background: white; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); grid-template-columns: repeat(10, 1fr);">
                <?php 
                $pLower = [85,84,83,82,81,71,72,73,74,75];
                foreach($pLower as $i): 
                    $cData = $chart[$i] ?? ['condition' => 'healthy', 'notes' => '', 'surfaces' => []];
                    $surfaces = is_array($cData['surfaces']) ? $cData['surfaces'] : [];
                ?>
                <div class="tooth <?php echo $cData['condition']; ?>" data-tooth="<?php echo $i; ?>" data-notes="<?php echo htmlspecialchars($cData['notes']); ?>" data-surfaces='<?php echo json_encode($surfaces); ?>' onclick="openToothModal(<?php echo $i; ?>)" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Tooth #<?php echo $i; ?></b><br><?php echo $cData['condition'] != 'healthy' ? strtoupper($cData['condition']) : 'Healthy'; ?><br><small><?php echo $cData['notes']; ?></small>">
                    <svg viewBox="0 0 100 120" style="transform: rotate(180deg);">
                        <path d="M20,40 Q20,10 50,10 Q80,10 80,40 Q80,80 50,110 Q20,80 20,40 Z" />
                    </svg>
                    <div class="tooth-box">
                        <div class="surface surface-top <?php echo in_array('T', $surfaces) ? 'active' : ''; ?>"></div>
                        <div class="surface surface-left <?php echo in_array('L', $surfaces) ? 'active' : ''; ?>"></div>
                        <div class="surface surface-center <?php echo in_array('C', $surfaces) ? 'active' : ''; ?>"></div>
                        <div class="surface surface-right <?php echo in_array('R', $surfaces) ? 'active' : ''; ?>"></div>
                        <div class="surface surface-bottom <?php echo in_array('B', $surfaces) ? 'active' : ''; ?>"></div>
                    </div>
                    <div class="tooth-label"><?php echo $i; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
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
                <div class="surface-selector-container mb-4 text-center">
                    <div class="tooth-box mx-auto shadow-sm" style="width: 120px; height: 120px; font-size: 20px; font-weight: bold; color: #cbd5e1;">
                        <div class="surface surface-top d-flex align-items-center justify-content-center pointer" onclick="toggleSurface('T')" id="ms-T">T</div>
                        <div class="surface surface-left d-flex align-items-center justify-content-center pointer" onclick="toggleSurface('L')" id="ms-L">L</div>
                        <div class="surface surface-center d-flex align-items-center justify-content-center pointer" onclick="toggleSurface('C')" id="ms-C">C</div>
                        <div class="surface surface-right d-flex align-items-center justify-content-center pointer" onclick="toggleSurface('R')" id="ms-R">R</div>
                        <div class="surface surface-bottom d-flex align-items-center justify-content-center pointer" onclick="toggleSurface('B')" id="ms-B">B</div>
                    </div>
                    <div class="small text-muted mt-2">Click box areas to select surfaces (T, L, C, R, B)</div>
                </div>

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
                        <button class="btn btn-outline-warning w-100 py-2" onclick="setCondition('crown')">
                            <i class="fas fa-crown d-block mb-1"></i> Crown
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-outline-info w-100 py-2" style="color: #a855f7; border-color: #a855f7;" onclick="setCondition('root-canal')">
                            <i class="fas fa-syringe d-block mb-1"></i> Root Canal
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-outline-info w-100 py-2" style="color: #0d9488; border-color: #0d9488;" onclick="setCondition('implant')">
                            <i class="fas fa-anchor d-block mb-1"></i> Implant
                        </button>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-outline-info w-100 py-2" style="color: #db2777; border-color: #db2777;" onclick="setCondition('braces')">
                            <i class="fas fa-grip-lines d-block mb-1"></i> Braces
                        </button>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" id="toothNotes" rows="3" placeholder="Add specific notes for this tooth..."></textarea>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <div>
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                </div>
                <div>
                    <button type="button" class="btn btn-outline-danger me-2" onclick="confirmClear()">Clear All</button>
                    <button type="button" class="btn btn-primary px-4" onclick="saveToothData()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><?php echo __('clinical_history_summary'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th><?php echo __('tooth_num'); ?></th>
                                <th><?php echo __('condition'); ?></th>
                                <th><?php echo __('notes'); ?></th>
                                <th><?php echo __('last_updated'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data['chartData'])): ?>
                                <tr><td colspan="4" class="text-center text-muted py-4"><?php echo __('no_conditions'); ?></td></tr>
                            <?php else: ?>
                                <?php foreach($data['chartData'] as $cd): ?>
                                    <tr>
                                        <td><span class="badge bg-primary rounded-pill px-3">#<?php echo $cd->tooth_number; ?></span></td>
                                        <td>
                                            <?php 
                                                $color = '#94a3b8';
                                                if($cd->condition_name == 'cavity') $color = '#ef4444';
                                                elseif($cd->condition_name == 'filling') $color = '#10b981';
                                                elseif($cd->condition_name == 'crown') $color = '#f59e0b';
                                                elseif($cd->condition_name == 'extraction') $color = '#64748b';
                                            ?>
                                            <span class="fw-bold" style="color: <?php echo $color; ?>;"><?php echo strtoupper($cd->condition_name); ?></span>
                                        </td>
                                        <td class="small"><?php echo $cd->notes ?: '<span class="text-muted">'.__('no_notes').'</span>'; ?></td>
                                        <td class="small text-muted"><?php echo date('d M Y, h:i A', strtotime($cd->updated_at)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize Tooltips
$(function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    updateSummary();
});

function switchMode(mode) {
    if (mode === 'adult') {
        $('#adultView').removeClass('d-none');
        $('#pediatricView').addClass('d-none');
        $('#btnAdult').addClass('active');
        $('#btnPediatric').removeClass('active');
    } else {
        $('#adultView').addClass('d-none');
        $('#pediatricView').removeClass('d-none');
        $('#btnAdult').removeClass('active');
        $('#btnPediatric').addClass('active');
    }
}

function updateSummary() {
    let counts = {};
    const conditions = ['cavity', 'filling', 'extraction', 'crown', 'root-canal', 'implant', 'braces'];
    
    document.querySelectorAll('.tooth').forEach(el => {
        conditions.forEach(c => {
            if(el.classList.contains(c)) {
                counts[c] = (counts[c] || 0) + 1;
            }
        });
    });

    let summary = [];
    for(let c in counts) {
        summary.push(`${counts[c]} ${c.replace('-',' ').toUpperCase()}`);
    }

    if(summary.length > 0) {
        $('#chartSummary').removeClass('d-none');
        $('#summaryText').text('Findings: ' + summary.join(' | '));
    } else {
        $('#chartSummary').addClass('d-none');
    }
}

let currentTooth = null;
let currentCondition = 'healthy';
let selectedSurfaces = [];

function openToothModal(num) {
    currentTooth = num;
    const toothEl = document.querySelector(`.tooth[data-tooth="${num}"]`);
    const notes = toothEl.getAttribute('data-notes') || '';
    
    try {
        selectedSurfaces = JSON.parse(toothEl.getAttribute('data-surfaces') || '[]');
    } catch(e) { selectedSurfaces = []; }

    document.getElementById('modalToothNum').innerText = num;
    document.getElementById('toothNotes').value = notes;
    
    // Reset surfaces in modal
    ['T','L','C','R','B'].forEach(s => {
        const el = document.getElementById('ms-' + s);
        if(selectedSurfaces.includes(s)) el.classList.add('active');
        else el.classList.remove('active');
    });

    // Highlight existing condition button
    const conditions = ['cavity', 'filling', 'extraction', 'crown', 'root-canal', 'implant', 'braces'];
    conditions.forEach(c => {
        const btn = document.querySelector(`button[onclick="setCondition('${c}')"]`);
        if (toothEl.classList.contains(c)) {
            btn.classList.add('active');
            currentCondition = c;
        } else {
            btn.classList.remove('active');
        }
    });

    var myModal = new bootstrap.Modal(document.getElementById('toothModal'));
    myModal.show();
}

function toggleSurface(s) {
    const el = document.getElementById('ms-' + s);
    if(selectedSurfaces.includes(s)) {
        selectedSurfaces = selectedSurfaces.filter(x => x !== s);
        el.classList.remove('active');
    } else {
        selectedSurfaces.push(s);
        el.classList.add('active');
    }
}

function confirmClear() {
    Swal.fire({
        title: 'Clear Tooth Data?',
        text: "This will remove all conditions and surfaces for this tooth. Are you sure?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Clear it!'
    }).then((result) => {
        if (result.isConfirmed) {
            setCondition('healthy');
        }
    });
}

function setCondition(condition) {
    currentCondition = condition;
    // Visually update buttons
    const conditions = ['cavity', 'filling', 'extraction', 'crown', 'root-canal', 'implant', 'braces'];
    conditions.forEach(c => {
        const btn = document.querySelector(`button[onclick="setCondition('${c}')"]`);
        if (c === condition) btn.classList.add('active');
        else btn.classList.remove('active');
    });

    if (condition === 'healthy') {
        selectedSurfaces = [];
        ['T','L','C','R','B'].forEach(s => {
            const el = document.getElementById('ms-' + s);
            if(el) el.classList.remove('active');
        });
        saveToothData();
    }
}

function saveToothData() {
    const notes = document.getElementById('toothNotes').value;
    const patientId = '<?php echo $data['patient']->id; ?>';
    
    $.ajax({
        url: '<?php echo BASE_URL; ?>/patient/saveChart',
        type: 'POST',
        data: {
            patient_id: patientId,
            tooth_number: currentTooth,
            condition: currentCondition,
            notes: notes,
            surfaces: selectedSurfaces.join(',')
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const toothEl = document.querySelector(`.tooth[data-tooth="${currentTooth}"]`);
                toothEl.className = 'tooth ' + currentCondition;
                toothEl.setAttribute('data-notes', notes);
                toothEl.setAttribute('data-surfaces', JSON.stringify(selectedSurfaces));
                
                // Update tooltip
                const tip = bootstrap.Tooltip.getInstance(toothEl);
                if(tip) {
                    tip.setContent({'.tooltip-inner': `<b>Tooth #${currentTooth}</b><br>${currentCondition.toUpperCase()}<br><small>${notes}</small>`});
                }

                // Update the tooth box surfaces visually
                const box = toothEl.querySelector('.tooth-box');
                box.querySelectorAll('.surface').forEach(s => s.classList.remove('active'));
                selectedSurfaces.forEach(s => {
                    const selector = s === 'T' ? '.surface-top' : (s === 'L' ? '.surface-left' : (s === 'C' ? '.surface-center' : (s === 'R' ? '.surface-right' : '.surface-bottom')));
                    box.querySelector(selector).classList.add('active');
                });

                updateSummary();
                bootstrap.Modal.getInstance(document.getElementById('toothModal')).hide();
                
                // Show tiny toast
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Saved',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        }
    });
}
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
