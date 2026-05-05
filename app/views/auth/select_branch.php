<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .branch-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.5);
            width: 100%;
            max-width: 500px;
            padding: 40px;
        }
        .branch-item {
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s ease;
            border-radius: 15px;
            background: white;
            margin-bottom: 15px;
        }
        .branch-item:hover {
            border-color: #0d6efd;
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .branch-item.active {
            border-color: #0d6efd;
            background: #f0f7ff;
        }
        .branch-icon {
            width: 50px;
            height: 50px;
            background: #e7f1ff;
            color: #0d6efd;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
    </style>
</head>
<body>

<div class="branch-card text-center">
    <h3 class="fw-bold mb-1">Welcome, <?php echo $_SESSION['user_name']; ?></h3>
    <p class="text-muted mb-4">Please select your clinic branch to continue</p>

    <form action="<?php echo BASE_URL; ?>/auth/selectBranch" method="POST">
        <div class="branch-list text-start">
            <?php foreach($data['branches'] as $branch): ?>
            <div class="branch-item p-3 d-flex align-items-center" onclick="selectThis(this, <?php echo $branch->id; ?>)">
                <div class="branch-icon me-3">
                    <i class="fas fa-clinic-medical"></i>
                </div>
                <div>
                    <div class="fw-bold"><?php echo $branch->name; ?></div>
                    <small class="text-muted"><?php echo $branch->country; ?></small>
                </div>
                <div class="ms-auto">
                    <i class="fas fa-chevron-right text-muted"></i>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <input type="hidden" name="branch_id" id="selected_branch_id">
        <button type="submit" class="btn btn-primary w-100 mt-3 p-3 fw-bold" id="continueBtn" disabled>Enter Dashboard</button>
    </form>
    
    <div class="mt-4">
        <a href="<?php echo BASE_URL; ?>/auth/logout" class="text-muted small text-decoration-none">Not you? Logout</a>
    </div>
</div>

<script>
function selectThis(el, id) {
    document.querySelectorAll('.branch-item').forEach(item => item.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('selected_branch_id').value = id;
    document.getElementById('continueBtn').disabled = false;
}
</script>

</body>
</html>
