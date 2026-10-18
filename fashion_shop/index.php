<?php

session_start();

include "config/database.php";


/* =========================
   LẤY SẢN PHẨM
========================= */

$sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);


/* =========================
   LẤY DANH MỤC
========================= */

$category_sql = "SELECT * FROM categories ORDER BY id ASC";
$category_result = $conn->query($category_sql);

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Fashion Shop</title>

    <link rel="stylesheet" href="assets/css/style.css?v=2">

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


        <a href="#products">
            Sản phẩm
        </a>


        <a href="#categories">
            Danh mục
        </a>


        <!-- =========================
             ĐƠN HÀNG
             CHỈ HIỆN KHI ĐÃ ĐĂNG NHẬP
        ========================= -->

        <?php if (isset($_SESSION['user_id'])) { ?>

            <a href="orders.php">
                Đơn hàng
            </a>

        <?php } ?>


        <!-- =========================
             KHÁCH HÀNG
        ========================= -->

        <?php if (
            isset($_SESSION['user_role'])
            &&
            $_SESSION['user_role'] == 'admin'
        ) { ?>

            <a href="admin/customers.php">
                Khách hàng
            </a>

        <?php } ?>


        <!-- =========================
             TÀI KHOẢN
        ========================= -->

        <?php if (isset($_SESSION['user_id'])) { ?>


            <!-- ADMIN -->

            <?php if (
                isset($_SESSION['user_role'])
                &&
                $_SESSION['user_role'] == 'admin'
            ) { ?>

                <a
                    href="admin/index.php"
                    class="admin-link"
                >
                    QUẢN TRỊ
                </a>

            <?php } ?>


            <!-- TÊN NGƯỜI DÙNG -->

            <span class="welcome-user">

                Xin chào,

                <?php
                echo htmlspecialchars(
                    $_SESSION['user_name']
                );
                ?>

            </span>


            <!-- ĐĂNG XUẤT -->

            <a
                href="logout.php"
                class="logout-link"
            >
                Đăng xuất
            </a>


        <?php } else { ?>


            <!-- CHƯA ĐĂNG NHẬP -->

            <a href="login.php">
                Đăng nhập
            </a>


            <a
                href="register.php"
                class="register-menu-link"
            >
                Đăng ký
            </a>


        <?php } ?>


    </nav>

</header>



<!-- =========================
     BANNER
========================= -->

<section class="banner">


    <!-- ẢNH BANNER -->

    <img
        src="assets/image/banner.jpg"
        class="banner-img"
        alt="Fashion Shop Banner"
    >


    <!-- LỚP PHỦ MỜ -->

    <div class="banner-overlay">


        <!-- NỘI DUNG -->

        <div class="banner-content">

            <h1>
                THỜI TRANG HIỆN ĐẠI
            </h1>


            <p>
                Phong cách của bạn - Cá tính của bạn
            </p>


            <a
                href="#products"
                class="banner-btn"
            >
                XEM SẢN PHẨM
            </a>

        </div>


    </div>

</section>



<!-- =========================
     DANH MỤC
========================= -->

<section
    class="categories"
    id="categories"
>


    <h2>
        DANH MỤC THỜI TRANG
    </h2>


    <div class="category-list">


        <?php

        if (
            $category_result
            &&
            $category_result->num_rows > 0
        ) {

            while (
                $category =
                $category_result->fetch_assoc()
            ) {

        ?>


                <div
                    class="category-box"
                    onclick="
                        filterProducts(
                            <?php echo $category['id']; ?>
                        )
                    "
                >


                    <h3>

                        <?php
                        echo htmlspecialchars(
                            $category['name']
                        );
                        ?>

                    </h3>


                    <p>
                        Xem sản phẩm
                    </p>


                </div>


        <?php

            }

        }

        ?>


    </div>

</section>



<!-- =========================
     SẢN PHẨM
========================= -->

<section
    class="products"
    id="products"
>


    <h2>
        SẢN PHẨM NỔI BẬT
    </h2>


    <!-- NÚT XEM TẤT CẢ -->

    <button
        type="button"
        class="filter-reset"
        id="resetButton"
        onclick="showAllProducts()"
    >
        Xem tất cả sản phẩm
    </button>


    <div class="product-list">


        <?php

        if (
            $result
            &&
            $result->num_rows > 0
        ) {

            while (
                $row =
                $result->fetch_assoc()
            ) {

        ?>


                <div
                    class="product"
                    data-category="<?php echo $row['category_id']; ?>"
                >


                    <!-- ẢNH -->

                    <div class="product-image">

                        <img
                            src="assets/image/<?php
                                echo htmlspecialchars(
                                    $row['image']
                                );
                            ?>"
                            alt="<?php
                                echo htmlspecialchars(
                                    $row['name']
                                );
                            ?>"
                        >

                    </div>


                    <!-- TÊN -->

                    <h3>

                        <?php
                        echo htmlspecialchars(
                            $row['name']
                        );
                        ?>

                    </h3>


                    <!-- GIÁ -->

                    <p class="price">

                        <?php
                        echo number_format(
                            $row['price'],
                            0,
                            ',',
                            '.'
                        );
                        ?>

                        VNĐ

                    </p>


                    <!-- SỐ LƯỢNG -->

                    <p>

                        Số lượng:

                        <?php
                        echo $row['quantity'];
                        ?>

                    </p>


                    <!-- MUA -->

                    <a
                        href="product.php?id=<?php echo $row['id']; ?>"
                        class="buy-link"
                    >
                        Mua ngay
                    </a>


                </div>


        <?php

            }

        } else {

        ?>


            <p class="empty-product">
                Chưa có sản phẩm.
            </p>


        <?php

        }

        ?>


        <!-- KHÔNG CÓ SẢN PHẨM -->

        <div
            class="no-product"
            id="noProduct"
        >
            Không có sản phẩm trong danh mục này.
        </div>


    </div>

</section>



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



<!-- =========================
     JAVASCRIPT
========================= -->

<script>


function filterProducts(categoryId)
{

    var products =
        document.querySelectorAll(".product");


    var resetButton =
        document.getElementById("resetButton");


    var noProduct =
        document.getElementById("noProduct");


    var found = false;


    products.forEach(function(product)
    {

        var productCategory =
            product.getAttribute("data-category");


        if (
            productCategory == categoryId
        )
        {

            product.style.display = "block";

            found = true;

        }
        else
        {

            product.style.display = "none";

        }

    });


    resetButton.style.display = "block";


    if (found)
    {

        noProduct.style.display = "none";

    }
    else
    {

        noProduct.style.display = "block";

    }


    document
        .getElementById("products")
        .scrollIntoView({
            behavior: "smooth"
        });

}



function showAllProducts()
{

    var products =
        document.querySelectorAll(".product");


    var resetButton =
        document.getElementById("resetButton");


    var noProduct =
        document.getElementById("noProduct");


    products.forEach(function(product)
    {

        product.style.display = "block";

    });


    resetButton.style.display = "none";


    noProduct.style.display = "none";


    document
        .getElementById("products")
        .scrollIntoView({
            behavior: "smooth"
        });

}


</script>


</body>

</html>