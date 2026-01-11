<?php include("config.php"); 
session_start(); 
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Đăng nhập</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap cdn/KT2/css/bootstrap.min.css">
<style> 
/* CSS cho form đăng nhập */
 body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #4CAF50, #2e8b57);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-box {
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
</head>
<body class="container p-5">
<div class="login-box">
<h2>Đăng nhập</h2>
<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Mật khẩu" required>
    <button type="submit" name="login">Đăng nhập</button>
</form>
<p class="mt-3">Chưa có tài khoản? <a href="Sigin.php">Đăng ký ngay</a></p>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {
        $user = $res->fetch_assoc();

        if (password_verify($password, $user["password"])) {
           $_SESSION["user_id"] = $user["id"];
            $_SESSION["user"] = $user["username"];
            $_SESSION["user_role"] = $user["role"];
            header("Location: index.php");
            exit;
        } else {
            echo "<div class='alert alert-danger mt-3 text-center'>
              Sai mật khẩu!
              </div>";

        }
    } else
    {
        echo "<div class='alert alert-danger mt-3 text-center'>
          Email không tồn tại!
          </div>";
    }
}
?>
</div>
</body>

</html>