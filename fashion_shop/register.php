<?php

session_start();

include "config/database.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Kiểm tra mật khẩu
    if ($password != $confirm_password) {

        $error = "Mật khẩu nhập lại không khớp!";

    } else {

        // Kiểm tra email đã tồn tại chưa
        $check_sql = "SELECT * FROM users WHERE email = '$email'";
        $check_result = $conn->query($check_sql);

        if ($check_result && $check_result->num_rows > 0) {

            $error = "Email này đã được đăng ký!";

        } else {

            // Tạo tài khoản khách hàng
            $sql = "INSERT INTO users
                    (name, email, password, role)
                    VALUES
                    ('$name', '$email', '$password', 'customer')";

            if ($conn->query($sql)) {

                $success = "Đăng ký tài khoản thành công!";

            } else {

                $error = "Đăng ký thất bại: " . $conn->error;

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

    <title>Đăng ký - Fashion Shop</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>

        .register-box {
            width: 430px;
            margin: 60px auto;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .register-box h1 {
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

        .register-button {
            width: 100%;
            padding: 13px;
            background: #111;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .register-button:hover {
            background: #e53935;
        }

        .error {
            background: #ffe5e5;
            color: #d00;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success {
            background: #e5f8e9;
            color: #218838;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
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


<div class="register-box">

    <h1>ĐĂNG KÝ TÀI KHOẢN</h1>


    <?php if ($error != "") { ?>

        <div class="error">
            <?php echo $error; ?>
        </div>

    <?php } ?>


    <?php if ($success != "") { ?>

        <div class="success">

            <?php echo $success; ?>

            <br><br>

            <a href="login.php">
                Đăng nhập ngay
            </a>

        </div>

    <?php } ?>


    <?php if ($success == "") { ?>

        <form method="POST">

            <div class="form-group">

                <label>Họ và tên</label>

                <input
                    type="text"
                    name="name"
                    placeholder="Nhập họ và tên"
                    required
                >

            </div>


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


            <div class="form-group">

                <label>Nhập lại mật khẩu</label>

                <input
                    type="password"
                    name="confirm_password"
                    placeholder="Nhập lại mật khẩu"
                    required
                >

            </div>


            <button
                type="submit"
                class="register-button"
            >
                ĐĂNG KÝ
            </button>

        </form>

    <?php } ?>


    <div class="login-link">

        Đã có tài khoản?

        <a href="login.php">
            Đăng nhập
        </a>

    </div>

</div>


<footer>

    <h3>FASHION SHOP</h3>

    <p>Hệ thống quản lý cửa hàng thời trang</p>

    <p>© 2026 Fashion Shop</p>

</footer>

</body>

</html>