<?php
include("config.php");
session_start();

$registerError = '';
$registerSuccess = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim((string) ($_POST["username"] ?? ''));
    $email = trim((string) ($_POST["email"] ?? ''));
    $rawPassword = (string) ($_POST["password"] ?? '');

    if (strlen($username) < 3 || strlen($username) > 50) {
        $registerError = 'Tên đăng nhập phải có từ 3 đến 50 ký tự.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registerError = 'Email không hợp lệ.';
    } elseif (strlen($rawPassword) < 6) {
        $registerError = 'Mật khẩu phải có ít nhất 6 ký tự.';
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $check->bind_param("ss", $username, $email);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {
            $registerError = 'Tên đăng nhập hoặc email đã tồn tại.';
        } else {
            $password = password_hash($rawPassword, PASSWORD_DEFAULT);
            $role = 'user';
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $password, $role);
            $registerSuccess = $stmt->execute();
            if (!$registerSuccess) {
                $registerError = 'Không thể tạo tài khoản. Vui lòng thử lại sau.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <title>Đăng ký | Pickleball Trung Ngọc</title>
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
    <h1 class="h2 mt-3 mb-2">Tạo tài khoản</h1>
    <p class="text-secondary mb-4">Đăng ký để theo dõi và quản lý lịch đặt sân của bạn.</p>
    <?php if ($registerSuccess) { ?>
        <div class="alert alert-success" role="status">Đăng ký thành công. <a class="alert-link" href="login.php">Đăng nhập ngay</a></div>
    <?php } elseif ($registerError) { ?>
        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($registerError, ENT_QUOTES, 'UTF-8') ?></div>
    <?php } ?>
    <form action="Sigin.php" method="POST">
        <div class="mb-3"><label class="form-label" for="username">Tên đăng nhập</label><input id="username" class="form-control" type="text" name="username" autocomplete="username" required></div>
        <div class="mb-3"><label class="form-label" for="email">Email</label><input id="email" class="form-control" type="email" name="email" autocomplete="email" required></div>
        <div class="mb-4"><label class="form-label" for="password">Mật khẩu</label><input id="password" class="form-control" type="password" name="password" autocomplete="new-password" minlength="6" required></div>
        <button class="btn btn-success w-100" type="submit">Tạo tài khoản</button>
    </form>
    <p class="text-center text-secondary mt-4 mb-0">Đã có tài khoản? <a class="auth-link" href="login.php">Đăng nhập</a></p>
</section>
</main>
</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <title>Đăng ký</title>
    <style> // CSS cho form đăng ký
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135dg, #4CAF50, #2e8b57);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .register-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 350px;
            box-shadow: 0 4 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
            font-weight: 700;
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;

        } 
        button {
            width: 95%;
            padding: 12px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #45a049;
        }
        a{
            color: #2e8b57;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        </style>
    <?php include("include/header.php"); ?>    
</head>
<body>
    <h2 class="text-center mb-4">Đăng ký</h2>
    <form action="Sigin.php" method="POST">
        <input type="text" name="username" placeholder="Tên đăng nhập" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Mật khẩu" required><br><br>
        <button type="submit">Đăng ký</button>
    </form>
    <p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    //Role Admin
    if ($email === "cvinh05n@gmail.com")
    {
        $role = "admin";
    }
    else
    {
        $role = "user";
    }

    // Kiểm tra username hoặc email trùng
    $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        echo "Username hoặc Email đã tồn tại!";
        exit;
    }
    // Lưu vào Databse
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    if ($stmt->execute()) 
    {
        echo "<div class='alert alert-success mt-3 text-center'>
          Đăng ký thành công!
        <a href='login.php' class='alert-link'>Đăng nhập</a>
        </div>";
    } 
        else {
        echo "<div class='alert alert-danger mt-3 text-center'>
          Lỗi đăng ký!;
        <div>";
    }
}
?>
</body>
<footer>
    <?php include("include/footer.php"); ?>
</footer>
</html>
