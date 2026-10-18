<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION['user_role'] != 'admin') {
    echo "<h2 style='text-align:center; margin-top:100px;'>
            Bạn không có quyền truy cập!
          </h2>";
    exit;
}

include "../config/database.php";

$sql = "SELECT * FROM products ORDER BY id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Quản lý sản phẩm - Fashion Shop</title>

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

        .header a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }

        .container {
            width: 95%;
            margin: 30px auto;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-button {
            background: #111;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 25px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background: #111;
            color: white;
        }

        img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
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

        <a href="index.php">
            Quản trị
        </a>

        <a href="../index.php">
            Trang chủ
        </a>

        <a href="../logout.php">
            Đăng xuất
        </a>

    </div>

</div>


<div class="container">

    <div class="top">

        <h1>QUẢN LÝ SẢN PHẨM</h1>

        <a href="index.php" class="back-button">
            ← Quay lại
        </a>

    </div>


    <table>

        <tr>

            <th>ID</th>

            <th>Ảnh</th>

            <th>Tên sản phẩm</th>

            <th>Giá</th>

            <th>Số lượng</th>

            <th>Size</th>

            <th>Màu</th>

        </tr>


        <?php if ($result && $result->num_rows > 0) { ?>

            <?php while ($row = $result->fetch_assoc()) { ?>

                <tr>

                    <td>
                        <?php echo $row['id']; ?>
                    </td>

                    <td>

                        <?php if (!empty($row['image'])) { ?>

                            <img
                                src="../assets/image/<?php echo htmlspecialchars($row['image']); ?>"
                                alt=""
                            >

                        <?php } else { ?>

                            Không có ảnh

                        <?php } ?>

                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['name']); ?>
                    </td>

                    <td>
                        <?php echo number_format($row['price'], 0, ',', '.'); ?>
                        VNĐ
                    </td>

                    <td>
                        <?php echo $row['quantity']; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['size']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['color']); ?>
                    </td>

                </tr>

            <?php } ?>

        <?php } else { ?>

            <tr>

                <td colspan="7">
                    Chưa có sản phẩm nào.
                </td>

            </tr>

        <?php } ?>

    </table>

</div>


</body>

</html>