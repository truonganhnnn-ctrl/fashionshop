<?php

session_start();


// ===============================
// KIỂM TRA ĐĂNG NHẬP
// ===============================

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit;

}


// ===============================
// KẾT NỐI DATABASE
// ===============================

include "config/database.php";


// ===============================
// LẤY THÔNG TIN TÀI KHOẢN
// ===============================

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];


// ===============================
// LẤY ID SẢN PHẨM
// ===============================

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;


// ===============================
// LẤY SẢN PHẨM
// ===============================

$sql = "SELECT * FROM products WHERE id = $id";

$result = $conn->query($sql);

if (!$result) {

    die("Lỗi SQL: " . $conn->error);

}


if ($result->num_rows == 0) {

    die("Không tìm thấy sản phẩm!");

}


$product = $result->fetch_assoc();


// ===============================
// XÁC ĐỊNH SIZE THEO SẢN PHẨM
// ===============================

$product_name_lower = mb_strtolower($product['name'], 'UTF-8');

if (
    strpos($product_name_lower, 'giày') !== false ||
    strpos($product_name_lower, 'giay') !== false
) {
    // Giày
    $sizes = array("38", "39", "40", "41", "42", "43", "44");
} elseif (
    strpos($product_name_lower, 'váy') !== false ||
    strpos($product_name_lower, 'vay') !== false
) {
    // Váy
    $sizes = array("S", "M", "L", "XL");
} else {
    // Quần áo
    $sizes = array("S", "M", "L", "XL", "XXL");
}


// ===============================
// XỬ LÝ ĐẶT HÀNG
// ===============================

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $quantity = intval($_POST['quantity']);
    $size = isset($_POST['size']) ? trim($_POST['size']) : '';


    // Kiểm tra dữ liệu

    if ($phone == "") {

        $error = "Vui lòng nhập số điện thoại.";

    } elseif ($address == "") {

        $error = "Vui lòng nhập địa chỉ.";

    } elseif ($quantity <= 0) {

        $error = "Số lượng phải lớn hơn 0.";

    } elseif (!in_array($size, $sizes)) {

        $error = "Vui lòng chọn size.";

    } elseif ($quantity > $product['quantity']) {

        $error = "Số lượng sản phẩm không đủ.";

    } else {


        // ===============================
        // TÍNH TỔNG TIỀN
        // ===============================

        $total = $product['price'] * $quantity;


        // ===============================
        // THÊM ĐƠN HÀNG
        // TÊN LẤY TỪ TÀI KHOẢN
        // ===============================

        $customer_name = $user_name;

        $phone_safe = $conn->real_escape_string($phone);
        $address_safe = $conn->real_escape_string($address);
        $customer_name_safe = $conn->real_escape_string($customer_name);
        $size_safe = $conn->real_escape_string($size);


        $sql_order = "
            INSERT INTO orders
            (
                user_id,
                customer_name,
                phone,
                address,
                total,
                status
            )
            VALUES
            (
                $user_id,
                '$customer_name_safe',
                '$phone_safe',
                '$address_safe',
                $total,
                'Chờ xử lý'
            )
        ";


        if ($conn->query($sql_order)) {


            // ===============================
            // LẤY ID ĐƠN HÀNG VỪA TẠO
            // ===============================

            $order_id = $conn->insert_id;


            // ===============================
            // THÊM CHI TIẾT ĐƠN HÀNG
            // ===============================

            $sql_detail = "
                INSERT INTO order_details
                (
                    order_id,
                    product_id,
                    quantity,
                    price,
                    size
                )
                VALUES
                (
                    $order_id,
                    $id,
                    $quantity,
                    {$product['price']},
                    '$size_safe'
                )
            ";


            if ($conn->query($sql_detail)) {


                // ===============================
                // TRỪ SỐ LƯỢNG SẢN PHẨM
                // ===============================

                $new_quantity = $product['quantity'] - $quantity;


                $sql_update = "
                    UPDATE products
                    SET quantity = $new_quantity
                    WHERE id = $id
                ";


                $conn->query($sql_update);


                // ===============================
                // ĐẶT HÀNG THÀNH CÔNG
                // ===============================

                header("Location: orders.php");

                exit;

            } else {

                $error = "Lỗi khi thêm chi tiết đơn hàng: " . $conn->error;

            }

        } else {

            $error = "Lỗi khi tạo đơn hàng: " . $conn->error;

        }

    }

}

?>

<!DOCTYPE html>

<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đặt hàng - Fashion Shop</title>


    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }


        .checkout {
            width: 900px;
            max-width: 90%;
            margin: 30px auto;
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }


        .checkout h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
        }


        .welcome {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 17px;
        }


        .product-box {
            display: flex;
            align-items: center;
            gap: 25px;
            background: #f7f7f7;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }


        .product-box img {
            width: 160px;
            height: 160px;
            object-fit: cover;
            border-radius: 8px;
        }


        .product-info h2 {
            margin: 0 0 10px;
            font-size: 24px;
        }


        .price {
            color: red;
            font-size: 22px;
            font-weight: bold;
        }


        .form-group {
            margin-bottom: 20px;
        }


        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 17px;
        }


        .form-group input,
        .form-group select {
            width: 100%;
            padding: 14px;
            border: 1px solid #ccc;
            border-radius: 7px;
            font-size: 16px;
            background: white;
        }


        .form-group input:focus,
        .form-group select:focus {
            border-color: #111;
            outline: none;
        }


        .error {
            background: #ffe5e5;
            color: red;
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 20px;
        }


        .order-button {
            width: 100%;
            padding: 15px;
            background: #111;
            color: white;
            border: none;
            border-radius: 7px;
            font-size: 18px;
            cursor: pointer;
        }


        .order-button:hover {
            background: red;
        }


        .back {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #111;
            text-decoration: none;
        }


        @media (max-width: 700px) {

            .checkout {
                padding: 20px;
            }

            .product-box {
                flex-direction: column;
                text-align: center;
            }

            .product-box img {
                width: 200px;
                height: 200px;
            }

        }

    </style>

</head>


<body>


<div class="checkout">


    <h1>ĐẶT HÀNG</h1>


    <!-- TÊN LẤY TỪ TÀI KHOẢN -->

    <div class="welcome">

        Xin chào <b><?php echo htmlspecialchars($user_name); ?></b> 👋

        <br>

        Vui lòng nhập thông tin nhận hàng bên dưới.

    </div>


    <!-- THÔNG TIN SẢN PHẨM -->

    <div class="product-box">

        <img
            src="assets/image/<?php echo htmlspecialchars($product['image']); ?>"
            alt="<?php echo htmlspecialchars($product['name']); ?>"
        >


        <div class="product-info">

            <h2>
                <?php echo htmlspecialchars($product['name']); ?>
            </h2>


            <div class="price">

                <?php echo number_format($product['price']); ?> VNĐ

            </div>


            <p>

                Còn lại:
                <b><?php echo $product['quantity']; ?></b>
                sản phẩm

            </p>

        </div>

    </div>


    <!-- HIỂN THỊ LỖI -->

    <?php if ($error != "") { ?>

        <div class="error">

            <?php echo $error; ?>

        </div>

    <?php } ?>


    <!-- FORM ĐẶT HÀNG -->

    <form method="POST">


        <!-- SỐ ĐIỆN THOẠI -->

        <div class="form-group">

            <label>Số điện thoại</label>

            <input
                type="text"
                name="phone"
                placeholder="Nhập số điện thoại"
                value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                required
            >

        </div>


        <!-- ĐỊA CHỈ -->

        <div class="form-group">

            <label>Địa chỉ</label>

            <input
                type="text"
                name="address"
                placeholder="Nhập địa chỉ nhận hàng"
                value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>"
                required
            >

        </div>


        <!-- SIZE -->

        <div class="form-group">

            <label>Chọn size</label>

            <select name="size" required>

                <option value="">-- Chọn size --</option>

                <?php foreach ($sizes as $item_size) { ?>

                    <option
                        value="<?php echo htmlspecialchars($item_size); ?>"
                        <?php echo (isset($_POST['size']) && $_POST['size'] == $item_size) ? 'selected' : ''; ?>
                    >
                        <?php echo htmlspecialchars($item_size); ?>
                    </option>

                <?php } ?>

            </select>

        </div>


        <!-- SỐ LƯỢNG -->

        <div class="form-group">

            <label>Số lượng</label>

            <input
                type="number"
                name="quantity"
                min="1"
                max="<?php echo $product['quantity']; ?>"
                value="<?php echo isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; ?>"
                required
            >

        </div>


        <!-- NÚT ĐẶT HÀNG -->

        <button type="submit" class="order-button">

            ĐẶT HÀNG

        </button>


    </form>


    <a href="product.php?id=<?php echo $product['id']; ?>" class="back">

        ← Quay lại sản phẩm

    </a>


</div>


</body>

</html>