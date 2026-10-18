<?php

session_start();


// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");
    exit;

}


// Kiểm tra quyền admin
if ($_SESSION['user_role'] != 'admin') {

    echo "<h2 style='text-align:center; margin-top:100px;'>
            Bạn không có quyền truy cập trang quản trị!
          </h2>";

    echo "<p style='text-align:center;'>
            <a href='../index.php'>Quay về trang chủ</a>
          </p>";

    exit;
}


include "../config/database.php";


// ==========================
// THÊM DANH MỤC
// ==========================

if (isset($_POST['add'])) {

    $name = trim($_POST['name']);

    if ($name != "") {

        $sql = "INSERT INTO categories (name)
                VALUES ('$name')";

        $conn->query($sql);

    }

    header("Location: categories.php");
    exit;
}


// ==========================
// XÓA DANH MỤC
// ==========================

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $sql = "DELETE FROM categories
            WHERE id = $id";

    $conn->query($sql);

    header("Location: categories.php");
    exit;
}


// ==========================
// LẤY DANH SÁCH DANH MỤC
// ==========================

$sql = "SELECT * FROM categories ORDER BY id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Quản lý danh mục - Fashion Shop</title>

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


        .form-box {

            background: white;

            padding: 25px;

            border-radius: 10px;

            box-shadow: 0 3px 12px rgba(0,0,0,0.1);

            margin-bottom: 30px;

        }


        .form-box h2 {

            margin-top: 0;

        }


        input {

            width: 70%;

            padding: 12px;

            border: 1px solid #ccc;

            border-radius: 5px;

            font-size: 16px;

        }


        button {

            padding: 12px 20px;

            background: #111;

            color: white;

            border: none;

            border-radius: 5px;

            cursor: pointer;

            margin-left: 10px;

        }


        button:hover {

            background: #e53935;

        }


        table {

            width: 100%;

            border-collapse: collapse;

            background: white;

            box-shadow: 0 3px 12px rgba(0,0,0,0.1);

        }


        th {

            background: #111;

            color: white;

            padding: 15px;

        }


        td {

            padding: 15px;

            border-bottom: 1px solid #ddd;

            text-align: center;

        }


        tr:hover {

            background: #f9f9f9;

        }


        .delete {

            color: white;

            background: #e53935;

            padding: 8px 12px;

            border-radius: 5px;

            text-decoration: none;

        }


        .delete:hover {

            background: #b71c1c;

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


    <h1>🏷️ QUẢN LÝ DANH MỤC</h1>



    <!-- FORM THÊM DANH MỤC -->

    <div class="form-box">

        <h2>Thêm danh mục</h2>


        <form method="POST">

            <input
                type="text"
                name="name"
                placeholder="Nhập tên danh mục"
                required
            >


            <button type="submit" name="add">

                + Thêm danh mục

            </button>

        </form>

    </div>



    <!-- DANH SÁCH DANH MỤC -->

    <h2>Danh sách danh mục</h2>


    <table>

        <tr>

            <th>ID</th>

            <th>Tên danh mục</th>

            <th>Thao tác</th>

        </tr>


        <?php

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

        ?>

        <tr>

            <td>

                <?php echo $row['id']; ?>

            </td>


            <td>

                <?php echo $row['name']; ?>

            </td>


            <td>

                <a
                    class="delete"
                    href="categories.php?delete=<?php echo $row['id']; ?>"
                    onclick="return confirm('Bạn có chắc muốn xóa danh mục này không?');"
                >

                    Xóa

                </a>

            </td>

        </tr>

        <?php

            }

        } else {

        ?>

        <tr>

            <td colspan="3">

                Chưa có danh mục nào

            </td>

        </tr>

        <?php

        }

        ?>

    </table>


    <a class="back" href="index.php">

        ← Quay lại trang quản trị

    </a>


</div>


</body>

</html>