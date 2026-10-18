<?php

session_start();

include "config/database.php";


/* =========================
   KIỂM TRA ĐĂNG NHẬP
========================= */

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");

    exit;
}


/* =========================
   LẤY THÔNG TIN TÀI KHOẢN
========================= */

$user_id = $_SESSION['user_id'];

$user_role = $_SESSION['user_role'];


/* =========================
   LẤY DANH SÁCH ĐƠN HÀNG
========================= */

// ADMIN XEM TẤT CẢ ĐƠN

if ($user_role == 'admin') {

    $sql = "
        SELECT *
        FROM orders
        ORDER BY id DESC
    ";

}


// TÀI KHOẢN THƯỜNG
// CHỈ XEM ĐƠN CỦA CHÍNH MÌNH

else {

    $sql = "
        SELECT *
        FROM orders
        WHERE user_id = $user_id
        ORDER BY id DESC
    ";

}


$result = $conn->query($sql);


if (!$result) {

    die("Lỗi SQL: " . $conn->error);

}

?>



<!DOCTYPE html>

<html lang="vi">


<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Quản lý đơn hàng - Fashion Shop
    </title>


    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >


    <style>

        .orders-container {

            max-width: 1400px;

            margin: 60px auto;

            background: white;

            padding: 40px;

            border-radius: 15px;

            box-shadow:
                0 5px 20px rgba(0,0,0,0.08);

        }


        .orders-container h1 {

            text-align: center;

            margin-bottom: 35px;

        }


        .account-info {

            text-align: center;

            margin-bottom: 25px;

            color: #666;

        }


        .admin-text {

            color: #e53935;

            font-weight: bold;

        }


        table {

            width: 100%;

            border-collapse: collapse;

        }


        th {

            background: #111;

            color: white;

            padding: 15px;

            text-align: center;

        }


        td {

            padding: 15px;

            border-bottom:
                1px solid #ddd;

            text-align: center;

        }


        tr:hover {

            background: #f8f8f8;

        }


        .total {

            color: #e53935;

            font-weight: bold;

        }


        .status {

            background: #fff3cd;

            color: #856404;

            padding: 8px 15px;

            border-radius: 20px;

            display: inline-block;

        }


        .view-button {

            display: inline-block;

            background: #111;

            color: white;

            padding: 10px 18px;

            border-radius: 5px;

            text-decoration: none;

        }


        .view-button:hover {

            background: #e53935;

        }


        .no-order {

            text-align: center;

            padding: 40px;

            color: #777;

            font-size: 17px;

        }


    </style>

</head>



<body>


<!-- =========================
     HEADER
========================= -->

<header>

    <div class="logo">

        FASHION SHOP

    </div>


    <nav>

        <a href="index.php">

            Trang chủ

        </a>


        <a href="index.php#products">

            Sản phẩm

        </a>


        <a href="index.php#categories">

            Danh mục

        </a>


        <a href="orders.php">

            Đơn hàng

        </a>


        <?php if ($user_role == 'admin') { ?>

            <a href="admin/customers.php">

                Khách hàng

            </a>

        <?php } else { ?>

            <a href="login.php">

                Khách hàng

            </a>

        <?php } ?>


        <?php if ($user_role == 'admin') { ?>

            <a
                href="admin/index.php"
                class="admin-link"
            >

                QUẢN TRỊ

            </a>

        <?php } ?>


        <span class="welcome-user">

            Xin chào,

            <?php

            echo htmlspecialchars(
                $_SESSION['user_name']
            );

            ?>

        </span>


        <a
            href="logout.php"
            class="logout-link"
        >

            Đăng xuất

        </a>

    </nav>

</header>



<!-- =========================
     DANH SÁCH ĐƠN HÀNG
========================= -->

<div class="orders-container">


    <h1>

        QUẢN LÝ ĐƠN HÀNG

    </h1>


    <?php if ($user_role == 'admin') { ?>

        <div class="account-info">

            Bạn đang xem

            <span class="admin-text">

                tất cả đơn hàng

            </span>

        </div>

    <?php } else { ?>

        <div class="account-info">

            Danh sách đơn hàng của

            <strong>

                <?php

                echo htmlspecialchars(
                    $_SESSION['user_name']
                );

                ?>

            </strong>

        </div>

    <?php } ?>



    <?php if (
        $result &&
        $result->num_rows > 0
    ) { ?>


        <table>


            <thead>

                <tr>

                    <th>
                        Mã đơn
                    </th>

                    <th>
                        Khách hàng
                    </th>

                    <th>
                        Số điện thoại
                    </th>

                    <th>
                        Địa chỉ
                    </th>

                    <th>
                        Tổng tiền
                    </th>

                    <th>
                        Trạng thái
                    </th>

                    <th>
                        Ngày đặt
                    </th>

                    <th>
                        Thao tác
                    </th>

                </tr>

            </thead>



            <tbody>


                <?php while (
                    $row =
                    $result->fetch_assoc()
                ) { ?>


                    <tr>


                        <td>

                            #

                            <?php
                            echo $row['id'];
                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row['customer_name']
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row['phone']
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row['address']
                            );

                            ?>

                        </td>


                        <td class="total">

                            <?php

                            echo number_format(
                                $row['total'],
                                0,
                                ',',
                                '.'
                            );

                            ?>

                            VNĐ

                        </td>


                        <td>

                            <span class="status">

                                <?php

                                echo htmlspecialchars(
                                    $row['status']
                                );

                                ?>

                            </span>

                        </td>


                        <td>

                            <?php

                            echo $row['created_at'];

                            ?>

                        </td>


                        <td>

                            <a
                                href="order_detail.php?id=<?php echo $row['id']; ?>"
                                class="view-button"
                            >

                                Xem

                            </a>

                        </td>


                    </tr>


                <?php } ?>


            </tbody>


        </table>


    <?php } else { ?>


        <div class="no-order">

            Bạn chưa có đơn hàng nào.

        </div>


    <?php } ?>


</div>



<!-- =========================
     FOOTER
========================= -->

<footer>

    <h3>

        FASHION SHOP

    </h3>


    <p>

        Hệ thống quản lý cửa hàng thời trang

    </p>


    <p>

        © 2026 Fashion Shop

    </p>

</footer>


</body>

</html>