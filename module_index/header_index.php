<div class="header">
	<ul class="list">
		<li class="list-item"><img src="img\logo2.png" alt=""></img></li>
		<li class="list-item"><a href="index.php">Trang chủ</a></li>
		<li class="list-item"><a href="product.php?quanly=danhmuc&id=all">Sản phẩm</a>
			<ul class="sub-list">
				<li class="sub-item"><a href="product.php?quanly=danhmuc&id=1">CHÓ CORGI</a></li>
				<li class="sub-item"><a href="product.php?quanly=danhmuc&id=2">CHÓ POODLE</a></li>
				<li class="sub-item"><a href="product.php?quanly=danhmuc&id=3">CHÓ SAMOYED</a></li>
				<li class="sub-item"><a href="product.php?quanly=danhmuc&id=4">MÈO CHÂN NGẮN</a></li>
				<li class="sub-item"><a href="product.php?quanly=danhmuc&id=5">MÈO TAI CỤP</a></li>
				<li class="sub-item"><a href="product.php?quanly=danhmuc&id=6">THỨC ĂN</a></li>
				<li class="sub-item"><a href="product.php?quanly=danhmuc&id=7">PHỤ KIỆN</a></li>
			</ul>
		</li>
		<li class="list-item"><a href="about.php">Về chúng tôi</a></li>
	</ul>

	<ul class="list js-list">
		<li class="list-item js-icon-menu"><i class="fa-solid fa-bars" style="font-size: 20px; font-weight: 600; line-height: 80px"></i></li>	
	<ul>

	<ul class="list">
<?php 
	if(isset($_SESSION['dangnhapuser']))
	{
?>	
		
		<li class="list-item">
			<a href="product.php?quanly=thongtinuser">
				<i class="far fa-user-circle" style="font-size: 20px; font-weight: 600; line-height: 80px"></i>
			</a>
			<ul class="sub-list">
				<li class="sub-item"><a href="product.php?quanly=thongtinuser">Thông tin</a></li>
				<li class="sub-item"><a href="product.php?quanly=lichsudathang">Đơn hàng</a></li>
				<li class="sub-item"><a href="product.php?dangxuatuser=1">Đăng xuất</a></li>
			</ul>	
		</li>
<?php 
	}
	else
	{
?>
		<li class="list-item">
			<a href="product.php?quanly=dangky">
				<i class="fas fa-sign-in-alt" style="font-size: 20px; font-weight: 600; line-height: 80px"></i>
			</a>
		</li>
<?php 
	}
?>				
	</ul>


</div>

<div class="modal">
	<ul class="list-mb js-list-mb">
		<li class="list-item list-item-mb"><a href="index.php">Trang chủ <i class="fa-solid fa-house"></i></a></li>
		<li class="list-item list-item-mb"><a href="product.php?quanly=danhmuc&id=all">Sản phẩm <i class="fa-solid fa-bag-shopping"></i></a></li>
		<?php 
		if(isset($_SESSION['dangnhapuser']))
		{
		?>	
		<li class="list-item list-item-mb"><a href="product.php?quanly=thongtinuser">Thông tin <i class="fa-solid fa-user"></i></a></li>
		<li class="list-item list-item-mb"><a href="product.php?quanly=lichsudathang">Đơn hàng <i class="fa-solid fa-receipt"></i></a></li>
		<li class="list-item list-item-mb"><a href="product.php?dangxuatuser=1">Đăng xuất <i class="fa-solid fa-right-from-bracket"></i></a></li>
		<?php 
		}
		else{
		echo '<li class="list-item list-item-mb"><a href="product.php?quanly=dangky">Đăng ký <i class="fa-solid fa-user-plus"></i></a></li>';
		}
		?>
	</ul>
</div>