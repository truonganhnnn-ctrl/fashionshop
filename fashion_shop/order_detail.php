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
   LẤY ID ĐƠN HÀNG
========================= */

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;


if ($id <= 0) {

    header("Location: orders.php");

    exit;
}


/* =========================
   LẤY THÔNG TIN ĐƠN HÀNG
========================= */


/*
    ADMIN:
    Có thể xem tất cả đơn hàng.

    TÀI KHOẢN THƯỜNG:
    Chỉ xem được đơn hàng có user_id
    đúng với tài khoản đang đăng nhập.
*/

if ($user_role == 'admin') {

    $sql_order = "
        SELECT *
        FROM orders
        WHERE id = $id
    ";

} else {

    $sql_order = "
        SELECT *
        FROM orders
        WHERE id = $id
        AND user_id = $user_id
    ";

}


$result_order = $conn->query($sql_order);


if (!$result_order) {

    die("Lỗi SQL: " . $conn->error);

}


/* =========================
   KHÔNG TÌM THẤY ĐƠN
========================= */

if ($result_order->num_rows == 0) {

    echo "

    <script>

        alert('Bạn không có quyền xem đơn hàng này!');

        window.location.href = 'orders.php';

    </script>

    ";

    exit;
}


$order = $result_order->fetch_assoc();



/* =========================
   LẤY SẢN PHẨM TRONG ĐƠN
========================= */

$sql_detail = "
    SELECT
        order_details.*,
        products.name,
        products.image
    FROM order_details

    INNER JOIN products
        ON order_details.product_id = products.id

    WHERE order_details.order_id = $id
";


$result_detail = $conn->query($sql_detail);


if (!$result_detail) {

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
        Chi tiết đơn hàng - Fashion Shop
    </title>


    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >


    <style>

        .detail-container {

            max-width: 1100px;

            margin: 50px auto;

            background: white;

            padding: 35px;

            border-radius: 12px;

            box-shadow:
                0 4px 15px rgba(0,0,0,0.08);

        }


        .detail-container h1 {

            text-align: center;

            margin-bottom: 30px;

        }


        .customer-info {

            background: #f7f7f7;

            padding: 20px;

            border-radius: 8px;

            margin-bottom: 30px;

        }


        .customer-info h2 {

            margin-bottom: 15px;

        }


        .customer-info p {

            margin: 8px 0;

        }


        .detail-table {

            width: 100%;

            border-collapse: collapse;

        }


        .detail-table th {

            background: #111;

            color: white;

            padding: 14px;

        }


        .detail-table td {

            padding: 14px;

            border-bottom:
                1px solid #ddd;

            text-align: center;

        }


        .product-image {

            width: 70px;

            height: 70px;

            object-fit: cover;

            border-radius: 5px;

        }


        .total {

            text-align: right;

            margin-top: 25px;

            font-size: 22px;

            font-weight: bold;

            color: #e53935;

        }


        .status {

            display: inline-block;

            padding: 7px 15px;

            background: #fff3cd;

            color: #856404;

            border-radius: 20px;

        }


        .back-button {

            display: inline-block;

            margin-top: 25px;

            padding: 10px 20px;

            background: #111;

            color: white;

            text-decoration: none;

            border-radius: 5px;

        }


        .back-button:hover {

            background: #e53935;

        }


        @media (max-width: 700px) {

            .detail-container {

                margin: 20px;

                padding: 15px;

                overflow-x: auto;

            }


            .detail-table {

                min-width: 700px;

            }

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
     CHI TIẾT ĐƠN HÀNG
========================= -->

<div class="detail-container">


    <h1>

        CHI TIẾT ĐƠN HÀNG
        #<?php echo $order['id']; ?>

    </h1>



    <!-- THÔNG TIN KHÁCH HÀNG -->

    <div class="customer-info">


        <h2>

            Thông tin khách hàng

        </h2>


        <p>

            <strong>

                Họ tên:

            </strong>

            <?php

            echo htmlspecialchars(
                $order['customer_name']
            );

            ?>

        </p>


        <p>

            <strong>

                Số điện thoại:

            </strong>

            <?php

            echo htmlspecialchars(
                $order['phone']
            );

            ?>

        </p>


        <p>

            <strong>

                Địa chỉ:

            </strong>

            <?php

            echo htmlspecialchars(
                $order['address']
            );

            ?>

        </p>


        <p>

            <strong>

                Ngày đặt:

            </strong>

            <?php

            echo $order['created_at'];

            ?>

        </p>


        <p>

            <strong>

                Trạng thái:

            </strong>


            <span class="status">

                <?php

                echo htmlspecialchars(
                    $order['status']
                );

                ?>

            </span>

        </p>


    </div>



    <!-- DANH SÁCH SẢN PHẨM -->

    <h2>

        Sản phẩm đã mua

    </h2>


    <br>



    <table class="detail-table">


        <thead>

            <tr>

                <th>

                    Ảnh

                </th>


                <th>

                    Sản phẩm

                </th>


                <th>

                    Đơn giá

                </th>


                <th>

                    Số lượng

                </th>


                <th>

                    Thành tiền

                </th>

            </tr>

        </thead>



        <tbody>


            <?php while (
                $detail =
                $result_detail->fetch_assoc()
            ) { ?>


                <tr>


                    <td>

                        <img

                            class="product-image"

                            src="assets/image/<?php

                                echo htmlspecialchars(
                                    $detail['image']
                                );

                            ?>"

                            alt="Sản phẩm"

                        >

                    </td>



                    <td>

                        <?php

                        echo htmlspecialchars(
                            $detail['name']
                        );

                        ?>

                    </td>



                    <td>

                        <?php

                        echo number_format(
                            $detail['price']
                        );

                        ?>

                        VNĐ

                    </td>



                    <td>

                        <?php

                        echo $detail['quantity'];

                        ?>

                    </td>



                    <td>

                        <?php

                        $subtotal =

                            $detail['price']
                            *
                            $detail['quantity'];


                        echo number_format(
                            $subtotal
                        );

                        ?>

                        VNĐ

                    </td>


                </tr>


            <?php } ?>


        </tbody>

    </table>



    <!-- TỔNG TIỀN -->

    <div class="total">

        Tổng tiền:

        <?php

        echo number_format(
            $order['total']
        );

        ?>

        VNĐ

    </div>



    <!-- QUAY LẠI -->

    <a
        href="orders.php"
        class="back-button"
    >

        ← Quay lại danh sách đơn hàng

    </a>


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