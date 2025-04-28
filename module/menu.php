<?php


$sql_danhmuc = "SELECT * FROM danhmuc ORDER BY madanhmuc ASC";
$query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);

// Lưu tất cả danh mục vào mảng để sử dụng lại
$danhmuc_list = [];
while ($row = mysqli_fetch_assoc($query_danhmuc)) {
    $danhmuc_list[] = $row;
}
?>
<form action="product.php?quanly=timkiem" method="POST">
    <div class="headersp">
        <div class="navbar_2">
            <ul class="list2">
                <!-- PC menu -->
                <li class="list2-item">
                    <select name="locgia" class="filter-select">
                        <option value="0">Sắp xếp giá</option>
                        <option value="1">Cao đến thấp</option>
                        <option value="2">Thấp đến cao</option>
                    </select>
                </li>
                <li class="list2-item" style="width: 250px;">
                    <input type="text" placeholder="Tìm kiếm sản phẩm..." name="keywork" class="search-input">
                    <button type="submit" name="search" class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </li>
                <li class="list2-item"><a href="product.php?quanly=danhmuc&id=all" class="a">Tất cả</a></li>
                <?php foreach ($danhmuc_list as $row_danhmuc): ?>
                    <li class="list2-item"><a href="product.php?quanly=danhmuc&id=<?php echo $row_danhmuc['madanhmuc']; ?>" class="a"><?php echo htmlspecialchars($row_danhmuc['name']); ?></a></li>
                <?php endforeach; ?>
                <li class="list2-item">
                    <a href="product.php?quanly=cart" class="a" style="color: red;">
                        <i class="fas fa-cart-plus" style="font-size: 1.8rem; color: green;"></i>
                    </a>
                </li>
            </ul>

            <!-- Mobile menu -->
            <ul class="list2">
                <li class="list2-item js-filter">Bộ lọc <i class="fa-solid fa-filter"></i></li>
            </ul>
            <div class="filter filter-open">
                <ul class="list2-filter">
                    <li class="list2-item">
                        <i class="fa-solid fa-xmark close-filter"></i>
                    </li>
                    <li class="list2-item">
                        <select name="locgia-mb" class="filter-select">
                            <option value="0">Sắp xếp giá</option>
                            <option value="1">Cao đến thấp</option>
                            <option value="2">Thấp đến cao</option>
                        </select>
                    </li>
                    <li class="list2-item">
                        <input type="text" placeholder="Tìm kiếm sản phẩm..." name="keywork-mb" class="search-input">
                        <button type="submit" name="search-mb" class="search-button">
                            <i class="fas fa-search"></i>
                        </button>
                    </li>
                    <li class="list2-item"><a href="product.php?quanly=danhmuc&id=all" class="a">Tất cả</a></li>
                    <?php foreach ($danhmuc_list as $row_danhmuc): ?>
                        <li class="list2-item"><a href="product.php?quanly=danhmuc&id=<?php echo $row_danhmuc['madanhmuc']; ?>" class="a"><?php echo htmlspecialchars($row_danhmuc['name']); ?></a></li>
                    <?php endforeach; ?>
                    <li class="list2-item">
                        <a href="product.php?quanly=cart" class="a">Giỏ hàng
                            <i class="fas fa-cart-plus" style="font-size: 1.8rem; color: green;"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</form>
<?php

?>
