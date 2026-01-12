<?php 
include("config.php");
session_start();
 ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="bootstrap cdn/KT2/css/bootstrap.min.css">
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