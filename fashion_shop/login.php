<?php
session_start();
include "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {

        $user = $result->fetch_assoc();

        // Tạm thời kiểm tra mật khẩu dạng chữ
        if ($password == $user['password']) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // Nếu có sản phẩm đang muốn mua
            if (isset($_GET['id'])) {
                header("Location: checkout.php?id=" . intval($_GET['id']));
            } else {
                header("Location: index.php");
            }

            exit;

        } else {
            $error = "Mật khẩu không đúng!";
        }

    } else {
        $error = "Email không tồn tại!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Đăng nhập - Fashion Shop</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>

        .login-box {
            width: 400px;
            margin: 70px auto;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .login-box h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
        }

        .login-button {
            width: 100%;
            padding: 13px;
            background: #111;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .login-button:hover {
            background: #e53935;
        }

        .error {
            background: #ffe5e5;
            color: #d00;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: #e53935;
            text-decoration: none;
            font-weight: bold;
        }

    </style>

</head>

<body>

<header>

    <div class="logo">
        FASHION SHOP
    </div>

    <nav>
        <a href="index.php">Trang chủ</a>
        <a href="#">Sản phẩm</a>
        <a href="#">Danh mục</a>
        <a href="orders.php">Đơn hàng</a>
        <a href="#">Khách hàng</a>
    </nav>

</header>


<div class="login-box">

    <h1>ĐĂNG NHẬP</h1>

    <?php if ($error != "") { ?>

        <div class="error">
            <?php echo $error; ?>
        </div>

    <?php } ?>


    <form method="POST">

        <div class="form-group">

            <label>Email</label>

            <input
                type="email"
                name="email"
                placeholder="Nhập email"
                required
            >

        </div>


        <div class="form-group">

            <label>Mật khẩu</label>

            <input
                type="password"
                name="password"
                placeholder="Nhập mật khẩu"
                required
            >

        </div>


        <button type="submit" class="login-button">
            ĐĂNG NHẬP
        </button>

    </form>


    <div class="register-link">

        Chưa có tài khoản?
        <a href="register.php">Đăng ký ngay</a>

    </div>

</div>


<footer>

    <h3>FASHION SHOP</h3>

    <p>Hệ thống quản lý cửa hàng thời trang</p>

    <p>© 2026 Fashion Shop</p>

</footer>

</body>

</html>