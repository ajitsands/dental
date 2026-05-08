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
        .bg-icons {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .bg-icon {
            position: absolute;
            color: rgba(13, 110, 253, 0.07);
            pointer-events: none;
            user-select: none;
            animation: float-around 25s infinite linear;
        }
        @keyframes float-around {
            0% { transform: translate(0, 0) rotate(0deg) scale(1); }
            33% { transform: translate(50px, 50px) rotate(120deg) scale(1.1); }
            66% { transform: translate(-30px, 80px) rotate(240deg) scale(0.9); }
            100% { transform: translate(0, 0) rotate(360deg) scale(1); }
        }
        body {
            font-family: 'Outfit', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.4);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
            position: relative;
            z-index: 10;
        }
        .login-header {
            background: #0d6efd;
            padding: 45px 20px;
            text-align: center;
            color: white;
        }
        .login-body {
            padding: 40px;
        }
        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            background: rgba(255, 255, 255, 0.9);
        }
        .btn-primary {
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            background: #0d6efd;
            border: none;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.4);
        }
        .logo-text {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -1px;
        }
    </style>
</head>
<body>

<div class="bg-icons">
    <i class="fas fa-tooth bg-icon" style="top: 10%; left: 10%; font-size: 120px; animation-duration: 30s;"></i>
    <i class="fas fa-hospital bg-icon" style="top: 20%; left: 80%; font-size: 100px; animation-duration: 25s; animation-delay: -5s;"></i>
    <i class="fas fa-user-md bg-icon" style="top: 70%; left: 15%; font-size: 140px; animation-duration: 35s; animation-delay: -10s;"></i>
    <i class="fas fa-stethoscope bg-icon" style="top: 80%; left: 75%; font-size: 90px; animation-duration: 20s; animation-delay: -2s;"></i>
    <i class="fas fa-calendar-check bg-icon" style="top: 40%; left: 45%; font-size: 110px; opacity: 0.03; animation-duration: 40s;"></i>
    <i class="fas fa-file-invoice bg-icon" style="top: 60%; left: 85%; font-size: 80px; animation-duration: 28s; animation-delay: -15s;"></i>
    <i class="fas fa-tooth bg-icon" style="top: 85%; left: 40%; font-size: 60px; animation-duration: 22s; animation-delay: -7s;"></i>
</div>

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
                    window.location.href = '<?php echo BASE_URL; ?>/dashboard';
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
