<?php
// app/controllers/Settings.php

class Settings extends Controller {
    private $branchModel;

    public function __construct() {
        $this->checkAuth();
        $this->branchModel = $this->model('BranchModel');
    }

    public function index() {
        $isSuperAdmin = ((int)$_SESSION['role_id'] === 6);
        $currentBranchId = $_SESSION['branch_id'] ?? 1;

        if ($isSuperAdmin) {
            $branches = $this->branchModel->getAllBranches();
        } else {
            // Branch Admin only sees their own branch
            $branches = [$this->branchModel->getBranchById($currentBranchId)];
        }
        
        $currentBranch = $this->branchModel->getBranchById($currentBranchId);
        
        $data = [
            'title' => 'Settings - DenSmart',
            'branches' => $branches,
            'currentBranch' => $currentBranch,
            'isSuperAdmin' => $isSuperAdmin
        ];
        $this->view('settings/index', $data);
    }

    public function saveBranch() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $isSuperAdmin = ((int)$_SESSION['role_id'] === 6);
            $currentBranchId = $_SESSION['branch_id'] ?? 1;
            $targetId = $_POST['id'] ?? null;

            // Security Check: If not Super Admin, force update to current branch only
            if (!$isSuperAdmin) {
                $targetId = $currentBranchId;
            }

            $current = $this->branchModel->getBranchById($targetId);
            
            // Logo Upload Logic
            $logoPath = $current->logo ?? '';
            $uploadDebug = 'No file uploaded';
            
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'e:/dental/public/uploads/logos/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileExt = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($fileExt, $allowed)) {
                    $fileName = 'logo_' . $targetId . '_' . time() . '.' . $fileExt;
                    if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadDir . $fileName)) {
                        $logoPath = 'uploads/logos/' . $fileName;
                        $uploadDebug = 'Upload successful: ' . $logoPath;
                    } else {
                        $uploadDebug = 'Failed to move uploaded file to ' . $uploadDir;
                    }
                } else {
                    $uploadDebug = 'Invalid file extension: ' . $fileExt;
                }
            } else if (isset($_FILES['logo'])) {
                $uploadDebug = 'Upload error code: ' . $_FILES['logo']['error'];
            }

            $data = [
                'id' => $targetId,
                'name' => trim($_POST['name'] ?? ($current->name ?? '')),
                'email' => trim($_POST['email'] ?? ($current->email ?? '')),
                'contact' => trim($_POST['contact'] ?? ($current->contact ?? '')),
                'address' => trim($_POST['address'] ?? ($current->address ?? '')),
                'country' => trim($_POST['loc_country'] ?? ($_POST['country'] ?? ($current->country ?? 'India'))),
                'tax_number' => trim($_POST['tax_number'] ?? ($current->tax_number ?? '')),
                'tax_type' => (strtolower(trim($_POST['loc_country'] ?? ($_POST['country'] ?? ($current->country ?? '')))) == 'india') ? 'GST' : 'VAT',
                'tax_pct' => $_POST['tax_pct'] ?? ($current->tax_pct ?? 18.00),
                'timezone' => $_POST['loc_timezone'] ?? ($_POST['timezone'] ?? ($current->timezone ?? 'Asia/Kolkata')),
                'logo' => $logoPath
            ];

            if ($data['id']) {
                $result = $this->branchModel->updateBranch($data);
                $message = 'Settings updated successfully';
            } else {
                // Only Super Admin can add new branches
                if ($isSuperAdmin) {
                    $result = $this->branchModel->addBranch($data);
                    $message = 'New branch added successfully';
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Unauthorized to create branches']);
                    exit;
                }
            }

            if ($result) {
                // Update session if editing current branch
                if ($data['id'] == ($_SESSION['branch_id'] ?? 0)) {
                    $_SESSION['branch_name'] = $data['name'];
                    $_SESSION['branch_country'] = $data['country'];
                    $_SESSION['tax_pct'] = $data['tax_pct'];
                    $_SESSION['tax_type'] = $data['tax_type'];
                    $_SESSION['branch_logo'] = $data['logo'];
                }
                echo json_encode([
                    'status' => 'success', 
                    'message' => $message,
                    'debug_received_country' => $_POST['loc_country'] ?? ($_POST['country'] ?? 'NOT_SENT')
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to save settings']);
            }
            exit;
        }
    }

    public function getBranch($id) {
        header('Content-Type: application/json');
        
        $isSuperAdmin = ((int)$_SESSION['role_id'] === 6);
        $currentBranchId = $_SESSION['branch_id'] ?? 1;

        // Security Check
        if (!$isSuperAdmin && $id != $currentBranchId) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
            exit;
        }

        $branch = $this->branchModel->getBranchById($id);
        if ($branch) {
            echo json_encode(['status' => 'success', 'data' => $branch]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Branch not found']);
        }
        exit;
    }
    public function refreshSession() {
        $branchId = $_SESSION['branch_id'] ?? 1;
        $branch = $this->branchModel->getBranchById($branchId);
        if ($branch) {
            $_SESSION['branch_name'] = $branch->name;
            $_SESSION['branch_country'] = $branch->country;
            $_SESSION['tax_pct'] = $branch->tax_pct;
            $_SESSION['tax_type'] = (strtolower($branch->country) == 'india') ? 'GST' : 'VAT';
        }
        header('Location: ' . BASE_URL . '/settings');
        exit;
    }
    public function setQatarFix() {
        $branchId = $_SESSION['branch_id'] ?? 1;
        $data = [
            'id' => $branchId,
            'country' => 'Qatar',
            'tax_type' => 'VAT',
            'tax_pct' => 0.00, // Or whatever the user wants, usually 0 or 5
            'timezone' => 'Asia/Qatar'
        ];

        // Fetch current to avoid clearing other fields
        $current = $this->branchModel->getBranchById($branchId);
        $data['name'] = $current->name;
        $data['email'] = $current->email;
        $data['contact'] = $current->contact;
        $data['address'] = $current->address;
        $data['tax_number'] = $current->tax_number;

        if ($this->branchModel->updateBranch($data)) {
            $_SESSION['branch_country'] = 'Qatar';
            $_SESSION['tax_pct'] = $data['tax_pct'];
            $_SESSION['tax_type'] = 'VAT';
        }
        header('Location: ' . BASE_URL . '/settings');
        exit;
    }

    public function deleteBranch() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');

            // 1. Authorization check
            $isSuperAdmin = ((int)$_SESSION['role_id'] === 6);
            if (!$isSuperAdmin) {
                http_response_code(403);
                echo json_encode(['status' => 'error', 'message' => 'Unauthorized access. Only Super Admins can delete branches.']);
                exit;
            }

            $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
            $actionType = $_POST['action_type'] ?? ''; // 'delete' or 'transfer'
            $targetBranchId = isset($_POST['target_branch_id']) ? (int)$_POST['target_branch_id'] : null;

            if (!$id) {
                echo json_encode(['status' => 'error', 'message' => 'Branch ID is required.']);
                exit;
            }

            // 2. Prevent deleting the active/current branch (cannot delete branch you are logged into/impersonating)
            $currentBranchId = (int)$_SESSION['branch_id'];
            if ($id === $currentBranchId) {
                echo json_encode(['status' => 'error', 'message' => 'You cannot delete the branch you are currently logged in to or impersonating. Please switch branches first.']);
                exit;
            }

            // 3. Prevent deleting the last remaining branch
            $branches = $this->branchModel->getAllBranches();
            if (count($branches) <= 1) {
                echo json_encode(['status' => 'error', 'message' => 'Cannot delete this branch. At least one branch must remain in the system.']);
                exit;
            }

            // 4. Handle Action
            if ($actionType === 'transfer') {
                if (!$targetBranchId) {
                    echo json_encode(['status' => 'error', 'message' => 'Target branch must be selected for transferring data.']);
                    exit;
                }
                if ($targetBranchId === $id) {
                    echo json_encode(['status' => 'error', 'message' => 'Target branch cannot be the branch you are deleting.']);
                    exit;
                }
                
                // Verify target branch exists
                $targetBranch = $this->branchModel->getBranchById($targetBranchId);
                if (!$targetBranch) {
                    echo json_encode(['status' => 'error', 'message' => 'Target branch not found.']);
                    exit;
                }

                $result = $this->branchModel->deleteBranchAndTransferData($id, $targetBranchId);
                $message = 'Branch deleted and all data successfully transferred to ' . $targetBranch->name . '.';
            } elseif ($actionType === 'delete') {
                $result = $this->branchModel->deleteBranchWithAllData($id);
                $message = 'Branch and all associated data permanently deleted.';
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid action type.']);
                exit;
            }

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => $message]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database operation failed during branch deletion.']);
            }
            exit;
        }
    }
}
