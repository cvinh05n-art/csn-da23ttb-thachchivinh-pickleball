<?php
include("config.php");
session_start();

$loginError = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim((string) ($_POST["email"] ?? ''));
    $password = (string) ($_POST["password"] ?? '');

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user"] = $user["username"];
        $_SESSION["user_role"] = $user["role"];
        header("Location: index.php");
        exit;
    }
    $loginError = 'Email hoặc mật khẩu không đúng.';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Đăng nhập | Pickleball Trung Ngọc</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<style>
    :root { --ink: #193129; --green: #168454; --soft: #eef8f2; }
    body { min-height: 100vh; margin: 0; color: var(--ink); background: linear-gradient(135deg, #eaf7ef, #f8fbf9); }
    .auth-shell { min-height: 100vh; display: grid; place-items: center; padding: 24px; }
    .auth-card { width: min(100%, 430px); padding: 36px; background: #fff; border: 1px solid #dfeae3; border-radius: 20px; box-shadow: 0 18px 45px rgba(25,49,41,.1); }
    .brand-mark { display: inline-grid; width: 42px; height: 42px; place-items: center; margin-bottom: 18px; border-radius: 13px; background: var(--soft); font-size: 1.4rem; }
    .auth-card h1 { font-weight: 800; letter-spacing: -.03em; }
    .form-control { min-height: 48px; border-color: #d7e4dc; }
    .btn-success { min-height: 48px; background: var(--green); border-color: var(--green); }
    .btn-success:hover { background: #106944; border-color: #106944; }
    .auth-link { color: var(--green); text-decoration: none; font-weight: 600; }
    .auth-link:hover { text-decoration: underline; }
</style>
</head>
<body>
<main class="auth-shell">
<section class="auth-card">
    <a class="text-decoration-none text-dark" href="index.php"><span class="brand-mark" aria-hidden="true">🏓</span><div class="small text-success fw-semibold">PICKLEBALL TRUNG NGỌC</div></a>
    <h1 class="h2 mt-3 mb-2">Chào mừng trở lại</h1>
    <p class="text-secondary mb-4">Đăng nhập để quản lý tài khoản và lịch đặt sân.</p>
    <?php if ($loginError) { ?><div class="alert alert-danger" role="alert"><?= htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8') ?></div><?php } ?>
    <form method="POST">
        <div class="mb-3"><label class="form-label" for="email">Email</label><input id="email" class="form-control" type="email" name="email" autocomplete="email" required></div>
        <div class="mb-4"><label class="form-label" for="password">Mật khẩu</label><input id="password" class="form-control" type="password" name="password" autocomplete="current-password" required></div>
        <button class="btn btn-success w-100" type="submit">Đăng nhập</button>
    </form>
    <p class="text-center text-secondary mt-4 mb-0">Chưa có tài khoản? <a class="auth-link" href="Sigin.php">Đăng ký ngay</a></p>
</section>
</main>
</body>

</html>
