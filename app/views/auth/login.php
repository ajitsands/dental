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
        .login-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.5);
            width: 100%;
            max-width: 400px;
            overflow: hidden;
        }
        .login-header {
            background: #0d6efd;
            padding: 40px 20px;
            text-align: center;
            color: white;
        }
        .login-body {
            padding: 40px;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
        }
        .btn-primary {
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            background: #0d6efd;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }
        .logo-text {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <div class="logo-text mb-2"><i class="fas fa-tooth me-2"></i>DenSmart</div>
        <p class="mb-0 opacity-75">Sign in to your dental dashboard</p>
    </div>
    <div class="login-body">
        <form id="loginForm">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="admin@densmart.com" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3" id="loginBtn">Login</button>
            <div class="text-center">
                <a href="#" class="text-decoration-none small text-muted">Forgot password?</a>
            </div>
        </form>
    </div>
</div>

<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        
        const btn = $('#loginBtn');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Verifying...');

        $.ajax({
            url: '<?php echo BASE_URL; ?>/auth/login',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    window.location.href = '<?php echo BASE_URL; ?>/auth/selectBranch';
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: response.message,
                        confirmButtonColor: '#0d6efd'
                    });
                    btn.prop('disabled', false).text('Login');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Server connection failed'
                });
                btn.prop('disabled', false).text('Login');
            }
        });
    });
});
</script>

</body>
</html>
