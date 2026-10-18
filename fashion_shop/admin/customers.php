<?php

session_start();


// Kiểm tra đã đăng nhập
if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");

    exit;
}


// Chỉ Admin được truy cập
if ($_SESSION['user_role'] != 'admin') {

    echo "<h2 style='text-align:center; margin-top:100px;'>
            Bạn không có quyền truy cập trang quản trị!
          </h2>";

    echo "<p style='text-align:center;'>
            <a href='../index.php'>Quay về trang chủ</a>
          </p>";

    exit;
}


// Kết nối database
include "../config/database.php";


// Lấy danh sách khách hàng
$sql = "SELECT * FROM users ORDER BY id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quản lý khách hàng - Fashion Shop</title>


    <style>

        body {

            margin: 0;

            font-family: Arial, sans-serif;

            background: #f5f5f5;

        }


        /* HEADER */

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


        /* CONTAINER */

        .container {

            width: 90%;

            margin: 40px auto;

        }


        .top {

            display: flex;

            justify-content: space-between;

            align-items: center;

        }


        .top h1 {

            margin: 0;

        }


        .back-button {

            background: #111;

            color: white;

            padding: 10px 18px;

            text-decoration: none;

            border-radius: 5px;

        }


        .back-button:hover {

            background: #e53935;

        }


        /* TABLE */

        table {

            width: 100%;

            border-collapse: collapse;

            background: white;

            margin-top: 30px;

            box-shadow: 0 3px 12px rgba(0,0,0,0.08);

        }


        th {

            background: #111;

            color: white;

            padding: 15px;

        }


        td {

            padding: 13px;

            border-bottom: 1px solid #ddd;

            text-align: center;

        }


        tr:hover {

            background: #f9f9f9;

        }


        .admin {

            color: #e53935;

            font-weight: bold;

        }


        .customer {

            color: #218838;

            font-weight: bold;

        }


        .empty {

            text-align: center;

            padding: 30px;

            color: #777;

        }

    </style>

</head>


<body>


<!-- HEADER -->

<div class="header">

    <h2>
        FASHION SHOP - ADMIN
    </h2>


    <div>

        Xin chào,

        <strong>

            <?php echo htmlspecialchars($_SESSION['user_name']); ?>

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



<!-- NỘI DUNG -->

<div class="container">


    <div class="top">

        <h1>
            QUẢN LÝ KHÁCH HÀNG
        </h1>


        <a
            href="index.php"
            class="back-button"
        >
            ← Quay lại
        </a>

    </div>



    <table>

        <tr>

            <th>
                ID
            </th>


            <th>
                Họ và tên
            </th>


            <th>
                Email
            </th>


            <th>
                Vai trò
            </th>

        </tr>


        <?php if ($result && $result->num_rows > 0) { ?>


            <?php while ($row = $result->fetch_assoc()) { ?>


                <tr>


                    <td>

                        <?php echo $row['id']; ?>

                    </td>


                    <td>

                        <?php echo htmlspecialchars($row['name']); ?>

                    </td>


                    <td>

                        <?php echo htmlspecialchars($row['email']); ?>

                    </td>


                    <td>


                        <?php if ($row['role'] == 'admin') { ?>

                            <span class="admin">
                                ADMIN
                            </span>

                        <?php } else { ?>

                            <span class="customer">
                                KHÁCH HÀNG
                            </span>

                        <?php } ?>


                    </td>


                </tr>


            <?php } ?>


        <?php } else { ?>


            <tr>

                <td
                    colspan="4"
                    class="empty"
                >

                    Chưa có tài khoản nào.

                </td>

            </tr>


        <?php } ?>


    </table>


</div>


</body>

</html>