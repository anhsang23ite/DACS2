<?php 
// Kiểm tra nếu giỏ hàng đã được khởi tạo trong Session
if(isset($_SESSION['cart'])) {
    // Giỏ hàng đã có, có thể xử lý giỏ hàng ở đây
}

// Kiểm tra nếu người dùng đã đăng nhập
if(!isset($_SESSION['dangnhapuser'])) {
    $account_user = ""; // Nếu chưa đăng nhập, giá trị user là rỗng
} else {
    $account_user = $_SESSION['dangnhapuser']; // Nếu đã đăng nhập, lấy thông tin người dùng
}
?>

<div class="row">
    <div class="col l-12 c-12">
        <div class="card__cart">
            <div class="card-cart-box">
                
                <?php 
                // Kiểm tra nếu giỏ hàng đã có và người dùng đã đăng nhập
                if(isset($_SESSION['cart']) && $account_user) {
                ?>
                <div class="cart-cart-status">
                    <div class="arrow-steps clearfix">
                        <!-- Các bước trong giỏ hàng -->
                        <div class="step current"> <span> <a href="product.php?quanly=cart">Cart</a></span> </div>
                        <div class="step"> <span><a href="#">Transport</a></span> </div>
                        <div class="step"> <span><a href="#">Payment</a><span> </div>
                        <div class="step"> <span><a href="#">Detail</a><span> </div>
                    </div>
                </div>
                <?php 
                } else {
                ?>
                <div class="cart-cart-status">
                    <!-- Nếu không có giỏ hàng hoặc người dùng chưa đăng nhập -->
                    <div class="arrow-steps clearfix">
                        <div class="step current"> <span> <a href="product.php?quanly=cart">Cart</a></span> </div>
                    </div>
                </div>
                <?php 
                }
                ?>

                <div class="card-cart-box-content">
                <?php 
                // Kiểm tra nếu giỏ hàng đã có
                if(isset($_SESSION['cart'])) {
                    $i = 0; // Biến đếm số lượng sản phẩm trong giỏ
                    $tongtien = 0; // Biến tính tổng tiền
                    // Duyệt qua từng sản phẩm trong giỏ
                    foreach ($_SESSION['cart'] as $cart_item) {
                        $thanhtien = $cart_item['soluong'] * $cart_item['giasanpham']; // Tính thành tiền
                        $tongtien += $thanhtien; // Cộng vào tổng tiền
                        $i++; // Tăng biến đếm
                ?>
                    <div class="card-cart-box-content-item">
                        <!-- Hiển thị thông tin từng sản phẩm trong giỏ -->
                        <h1 class="card-cart-box-content-item-numberle"><?php echo $i ?></h1>
                        <div class="card__cart__content__item__img">
                            <img src="Admin/module/sanpham/uploads/<?php echo $cart_item['anhsanpham'] ?>" alt="" width="100px" height="100px" style="border-radius: 10px;">
                        </div>
                        <div class="card__cart__content__item__info">
                            <h1><?php echo $cart_item['tensanpham'] ?></h1>
                            <ul>
                                <li>Giá: <?php echo number_format($cart_item['giasanpham'],0,',',',') ?> đ</li>
                                <li>Số lượng: 
                                    <!-- Thêm nút giảm và tăng số lượng sản phẩm -->
                                    <a href="module/main/addcart.php?tru=<?php echo $cart_item['id'] ?>"><i class="fas fa-minus-circle" style="color:#cc8a8a;"></i></a>
                                    <?php echo $cart_item['soluong'] ?>
                                    <a href="module/main/addcart.php?cong=<?php echo $cart_item['id'] ?>"><i class="fas fa-plus-circle" style="color:#cc8a8a;"></i></a>
                                </li>
                                <li>Mã sản phẩm: <?php echo $cart_item['masanpham'] ?></li>
                                <!-- Nút xóa sản phẩm khỏi giỏ -->
                                <li><a href="module/main/addcart.php?xoa=<?php echo $cart_item['id'] ?>"><input type="submit" name="" value="Xóa" style="width: 50px; background-color: #c54d4d; color: white; border: none; height: 20px; border-radius: 10px;" class="input3"></a></li>
                            </ul>
                        </div>
                    </div>
                <?php 
                    }        
                ?>        
                    <div class="card-cart-box-content-xoaall">
                        <!-- Nút xóa tất cả sản phẩm trong giỏ -->
                        <a href="module/main/addcart.php?xoatatca=1">Xóa tất cả</a>
                    </div>
                </div>

                <div class="card-cart-box-thanhtoan">
                    <div class="card-cart-box-thanhtoan-item">
                        <div class="card__cart__thanhtoan__item__text">
                            <!-- Hiển thị tổng tiền của giỏ hàng -->
                            <h1>Thành tiền: <?php echo number_format($tongtien,0,',',',') ?> đ</h1>
                        </div>
                        <div class="card-cart-box-thanhtoan-item-btn">    
                            <?php  
                            // Kiểm tra người dùng đã đăng nhập hay chưa để cho phép thanh toán
                            if(isset($_SESSION['dangnhapuser'])) {
                            ?>                    
                            <a href="product.php?quanly=vanchuyen"><input type="submit" name="nextvanchuyen" value="Hình thức vận chuyển" class="btn-buy btn-success"></a>                
                            <?php                
                            } else {
                            ?>
                            <script language="javascript">confirm("Bạn cần đăng nhập để đặt hàng!");</script>
                            <!-- Nút yêu cầu đăng nhập -->
                            <a href="product.php?quanly=dangnhapuser"><input type="submit" name="dangkyuser" value="Đăng nhập" class="btn-buy btn-success"></a>
                            <?php 
                            }
                            ?>
                        </div>    
                    </div>
                </div>
                <?php 
                } else {
                ?>            
                <div class="card-cart-box-content-item">
                    <div class="card__cart__content__item__trong">
                        <!-- Thông báo giỏ hàng trống -->
                        <h1>Hiện giỏ hàng trống <i class="fas fa-shopping-cart"></i></h1>
                    </div>
                </div>
                <?php 
                }
                ?>
            </div>
        </div>
    </div>
</div>
