<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");
    exit;

}

if ($_SESSION['user_role'] != 'admin') {

    echo "<h2 style='text-align:center; margin-top:100px;'>
            Bạn không có quyền truy cập trang quản trị!
          </h2>";

    echo "<p style='text-align:center;'>
            <a href='../index.php'>Quay về trang chủ</a>
          </p>";

    exit;
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Quản trị - Fashion Shop</title>

    <style>

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .header {
            background: #111;
            color: white;
            padding: 20px 40px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h2 {
            margin: 0;
        }

        .header a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }

        .header a:hover {
            color: #e53935;
        }

        .container {
            width: 90%;
            margin: 40px auto;
        }

        .container h1 {
            margin-bottom: 30px;
        }

        .menu {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .menu-item {
            display: block;

            background: white;
            padding: 30px;

            text-align: center;

            border-radius: 10px;

            box-shadow: 0 3px 12px rgba(0,0,0,0.1);

            text-decoration: none;
            color: #111;

            transition: 0.3s;
        }

        .menu-item:hover {
            transform: translateY(-5px);

            box-shadow: 0 6px 18px rgba(0,0,0,0.15);

            color: #e53935;
        }

        .menu-item h3 {
            margin-top: 0;
            font-size: 22px;
        }

        .menu-item p {
            color: #666;
        }

        .back-home {
            margin-top: 30px;
            text-align: center;
        }

        .back-home a {
            color: #111;
            text-decoration: none;
            font-weight: bold;
        }

        .back-home a:hover {
            color: #e53935;
        }

    </style>

</head>

<body>

<div class="header">

    <h2>FASHION SHOP - ADMIN</h2>

    <div>

        Xin chào,

        <strong>
            <?php echo $_SESSION['user_name']; ?>
        </strong>

        <a href="../index.php">
            Trang chủ
        </a>

        <a href="../logout.php">
            Đăng xuất
        </a>

    </div>

</div>


<div class="container">

    <h1>TRANG QUẢN TRỊ</h1>

    <div class="menu">


        <!-- QUẢN LÝ SẢN PHẨM -->

        <a href="products.php" class="menu-item">

            <h3>📦 Quản lý sản phẩm</h3>

            <p>
                Thêm, sửa, xóa sản phẩm
            </p>

        </a>


        <!-- QUẢN LÝ ĐƠN HÀNG -->

        <a href="../order.php" class="menu-item">

            <h3>📋 Quản lý đơn hàng</h3>

            <p>
                Xem và xử lý đơn hàng
            </p>

        </a>


        <!-- QUẢN LÝ KHÁCH HÀNG -->

        <a href="customers.php" class="menu-item">

            <h3>👤 Quản lý khách hàng</h3>

            <p>
                Xem danh sách khách hàng
            </p>

        </a>


        <!-- QUẢN LÝ DANH MỤC -->

        <a href="categories.php" class="menu-item">

            <h3>🏷️ Quản lý danh mục</h3>

            <p>
                Thêm, sửa, xóa danh mục
            </p>

        </a>


    </div>


    <div class="back-home">

        <a href="../index.php">
            ← Quay về trang chủ
        </a>

    </div>

</div>

</body>

</html>