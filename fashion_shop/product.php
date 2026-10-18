<?php

session_start();

include "config/database.php";

// Lấy ID sản phẩm
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Kiểm tra sản phẩm
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);

if (!$result) {
    die("Lỗi SQL: " . $conn->error);
}

if ($result->num_rows == 0) {
    die("Không tìm thấy sản phẩm có ID: " . $id);
}

$product = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Chi tiết sản phẩm</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>

        .detail {
            width: 1000px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            display: flex;
            gap: 40px;
            border-radius: 10px;
        }

        .detail img {
            width: 400px;
            height: 450px;
            object-fit: cover;
            border-radius: 10px;
        }

        .info {
            padding: 20px;
        }

        .info h1 {
            font-size: 30px;
            margin-bottom: 20px;
        }

        .info .price {
            color: red;
            font-size: 25px;
            font-weight: bold;
        }

        .info p {
            margin: 15px 0;
            font-size: 17px;
        }

        .buy-button {
            display: inline-block;
            background: #111;
            color: white;
            border: none;
            padding: 13px 30px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 10px;
        }

        .buy-button:hover {
            background: red;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            color: #111;
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

        <a href="products.php">Sản phẩm</a>

        <a href="index.php#categories">Danh mục</a>

        <?php if (isset($_SESSION['user_id'])) { ?>

            <a href="orders.php">Đơn hàng</a>

        <?php } ?>

        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') { ?>

            <a href="admin/customers.php">Khách hàng</a>

        <?php } ?>

    </nav>

</header>


<div class="detail">

    <img
        src="assets/image/<?php echo $product['image']; ?>"
        alt="<?php echo $product['name']; ?>"
    >


    <div class="info">

        <h1>
            <?php echo $product['name']; ?>
        </h1>


        <p class="price">
            <?php echo number_format($product['price']); ?> VNĐ
        </p>


        <p>
            <b>Số lượng:</b>
            <?php echo $product['quantity']; ?>
        </p>


        <p>
            <b>Kích thước:</b>
            <?php echo $product['size']; ?>
        </p>


        <p>
            <b>Màu sắc:</b>
            <?php echo $product['color']; ?>
        </p>


        <p>
            <b>Mô tả:</b>
            <?php echo $product['description']; ?>
        </p>


        <!-- NÚT MUA NGAY -->

        <?php if (isset($_SESSION['user_id'])) { ?>

            <!-- ĐÃ ĐĂNG NHẬP -->
            <a
                href="checkout.php?id=<?php echo $product['id']; ?>"
                class="buy-button"
            >
                Mua ngay
            </a>

        <?php } else { ?>

            <!-- CHƯA ĐĂNG NHẬP -->
            <a
                href="login.php?id=<?php echo $product['id']; ?>"
                class="buy-button"
            >
                Mua ngay
            </a>

        <?php } ?>


        <br>


        <a class="back" href="index.php">
            ← Quay lại
        </a>

    </div>

</div>


</body>

</html>