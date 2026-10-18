<?php

session_start();


// ==========================
// KIỂM TRA ĐĂNG NHẬP
// ==========================

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit;

}


// ==========================
// KIỂM TRA QUYỀN ADMIN
// ==========================

if ($_SESSION['user_role'] != 'admin') {

    echo "<h2 style='text-align:center; margin-top:100px;'>
            Bạn không có quyền truy cập!
          </h2>";

    echo "<p style='text-align:center;'>
            <a href='index.php'>Quay về trang chủ</a>
          </p>";

    exit;
}


include "config/database.php";


// ==========================
// CẬP NHẬT TRẠNG THÁI
// ==========================

if (isset($_POST['update_status'])) {

    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    // Chỉ cho phép 3 trạng thái này
    $allowed_status = array(
        "Chờ xử lý",
        "Hoàn thành",
        "Đã huỷ"
    );

    if (in_array($status, $allowed_status)) {

        $sql = "UPDATE orders
                SET status = '$status'
                WHERE id = $order_id";

        $conn->query($sql);
    }

    header("Location: order.php");
    exit;
}


// ==========================
// LẤY TẤT CẢ ĐƠN HÀNG
// ==========================

$sql = "SELECT * FROM orders
        ORDER BY id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>

<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Quản lý đơn hàng - Fashion Shop</title>

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

            width: 95%;

            margin: 40px auto;

            background: white;

            padding: 30px;

            border-radius: 10px;

            box-shadow: 0 3px 12px rgba(0,0,0,0.1);

        }


        h1 {

            text-align: center;

            margin-bottom: 10px;

        }


        .subtitle {

            text-align: center;

            color: #666;

            margin-bottom: 35px;

        }


        .subtitle span {

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

            padding: 16px 10px;

        }


        td {

            padding: 15px 10px;

            border-bottom: 1px solid #ddd;

            text-align: center;

        }


        tr:hover {

            background: #fafafa;

        }


        .money {

            color: #e53935;

            font-weight: bold;

        }


        /* ==========================
           TRẠNG THÁI
           ========================== */

        .status {

            display: inline-block;

            padding: 9px 15px;

            border-radius: 20px;

            font-size: 14px;

            font-weight: bold;

        }


        .waiting {

            background: #fff0c2;

            color: #9a7200;

        }


        .completed {

            background: #d7f5df;

            color: #16833b;

        }


        .cancelled {

            background: #ffd9d9;

            color: #d00000;

        }


        /* ==========================
           FORM TRẠNG THÁI
           ========================== */

        .status-form {

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 8px;

        }


        select {

            padding: 8px;

            border: 1px solid #ccc;

            border-radius: 5px;

            font-size: 14px;

        }


        .update-button {

            background: #111;

            color: white;

            border: none;

            padding: 9px 12px;

            border-radius: 5px;

            cursor: pointer;

        }


        .update-button:hover {

            background: #e53935;

        }


        .view-button {

            display: inline-block;

            background: #111;

            color: white;

            padding: 9px 14px;

            border-radius: 5px;

            text-decoration: none;

        }


        .view-button:hover {

            background: #e53935;

        }


        .back {

            display: inline-block;

            margin-top: 25px;

            color: #111;

            text-decoration: none;

            font-weight: bold;

        }


        .back:hover {

            color: #e53935;

        }

    </style>

</head>


<body>


<!-- ==========================
     HEADER
     ========================== -->

<div class="header">

    <h2>FASHION SHOP - ADMIN</h2>

    <div>

        Xin chào,

        <strong>
            <?php echo $_SESSION['user_name']; ?>
        </strong>


        <a href="admin/index.php">
            Quản trị
        </a>


        <a href="index.php">
            Trang chủ
        </a>


        <a href="logout.php">
            Đăng xuất
        </a>

    </div>

</div>



<!-- ==========================
     NỘI DUNG
     ========================== -->

<div class="container">

    <h1>ĐƠN HÀNG</h1>


    <div class="subtitle">

        Bạn đang xem
        <span>tất cả đơn hàng</span>

    </div>



    <table>

        <tr>

            <th>Mã đơn</th>

            <th>Khách hàng</th>

            <th>Số điện thoại</th>

            <th>Địa chỉ</th>

            <th>Tổng tiền</th>

            <th>Trạng thái</th>

            <th>Ngày đặt</th>

            <th>Xử lý</th>

            <th>Chi tiết</th>

        </tr>


        <?php

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

                // Xác định class trạng thái

                if ($row['status'] == 'Hoàn thành') {

                    $status_class = 'completed';

                } elseif ($row['status'] == 'Đã huỷ') {

                    $status_class = 'cancelled';

                } else {

                    $status_class = 'waiting';

                }

        ?>

        <tr>

            <!-- MÃ ĐƠN -->

            <td>

                #<?php echo $row['id']; ?>

            </td>


            <!-- KHÁCH HÀNG -->

            <td>

                <?php echo htmlspecialchars($row['customer_name']); ?>

            </td>


            <!-- SỐ ĐIỆN THOẠI -->

            <td>

                <?php echo htmlspecialchars($row['phone']); ?>

            </td>


            <!-- ĐỊA CHỈ -->

            <td>

                <?php echo htmlspecialchars($row['address']); ?>

            </td>


            <!-- TỔNG TIỀN -->

            <td class="money">

                <?php

                echo number_format(
                    $row['total'],
                    0,
                    ',',
                    '.'
                );

                ?>

                đ

            </td>


            <!-- TRẠNG THÁI HIỆN TẠI -->

            <td>

                <span class="status <?php echo $status_class; ?>">

                    <?php echo htmlspecialchars($row['status']); ?>

                </span>

            </td>


            <!-- NGÀY ĐẶT -->

            <td>

                <?php echo $row['created_at']; ?>

            </td>


            <!-- XỬ LÝ -->

            <td>

                <form
                    method="POST"
                    class="status-form"
                >

                    <input
                        type="hidden"
                        name="order_id"
                        value="<?php echo $row['id']; ?>"
                    >


                    <select name="status">

                        <option
                            value="Chờ xử lý"
                            <?php
                            if ($row['status'] == 'Chờ xử lý')
                                echo 'selected';
                            ?>
                        >
                            Chờ xử lý
                        </option>


                        <option
                            value="Hoàn thành"
                            <?php
                            if ($row['status'] == 'Hoàn thành')
                                echo 'selected';
                            ?>
                        >
                            Hoàn thành
                        </option>


                        <option
                            value="Đã huỷ"
                            <?php
                            if ($row['status'] == 'Đã huỷ')
                                echo 'selected';
                            ?>
                        >
                            Đã huỷ
                        </option>

                    </select>


                    <button
                        type="submit"
                        name="update_status"
                        class="update-button"
                    >

                        Lưu

                    </button>

                </form>

            </td>


            <!-- CHI TIẾT -->

            <td>

                <a
                    href="order_detail.php?id=<?php echo $row['id']; ?>"
                    class="view-button"
                >

                    Xem

                </a>

            </td>

        </tr>


        <?php

            }

        } else {

        ?>

        <tr>

            <td colspan="9">

                Chưa có đơn hàng nào.

            </td>

        </tr>

        <?php

        }

        ?>

    </table>


    <a
        href="admin/index.php"
        class="back"
    >

        ← Quay lại trang quản trị

    </a>

</div>


</body>

</html>