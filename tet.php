<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$database = "student1";

// Tạo kết nối
$connection = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Danh sách các quê quán
$addresses = array("Ha Noi", "Ho Chi Minh", "Da Nang", "Hue", "Can Tho", "Hai Phong", "Nha Trang", "Vung Tau");

// Hàm để tạo quê quán ngẫu nhiên
function generateRandomAddress()
{
    global $addresses;
    return $addresses[array_rand($addresses)];
}

// Hàm để tạo thông tin khóa học, lớp, sinh viên tự động
function generateData($connection)
{
    global $addresses;
    // Tạo thông tin khóa học, lớp và sinh viên từ năm 2000 đến 2023
    for ($year = 2000; $year <= 2023; $year++) {
        // Tạo thông tin khóa học
        $namBatDau = $year;
        $sql = "INSERT INTO khoahoc (namBatDau) VALUES ('$namBatDau')";
        $connection->query($sql);

        // Lấy ID của khóa học vừa tạo
        $khoaHocId = $connection->insert_id;

        // Tạo từ 2 đến 5 lớp cho mỗi khóa học
        $numClasses = rand(2, 5);
        for ($i = 1; $i <= $numClasses; $i++) {
            $tenLop = "Lớp $i Khóa $year";
            $sql = "INSERT INTO lophoc (idKhoaHoc, tenLop) VALUES ('$khoaHocId', '$tenLop')";
            $connection->query($sql);

            // Lấy ID của lớp vừa tạo
            $lopHocId = $connection->insert_id;

            // Tạo từ 30 đến 50 sinh viên cho mỗi lớp
            $numStudents = rand(30, 50);
            for ($j = 1; $j <= $numStudents; $j++) {
                $tenSinhVien = "Sinh viên $j";
                $ngaySinh = date('Y-m-d', strtotime("-" . rand(18, 28) . " years")); // Sinh viên từ 18 đến 28 tuổi
                $gioiTinh = rand(0, 1) ? 'Nam' : 'Nữ';
                $chieuCao = rand(150, 190);
                $canNang = rand(45, 90);
                $queQuan = generateRandomAddress();
                $diemThiDauVao = rand(0, 10);
                $sql = "INSERT INTO sinhvien (idLopHoc, ten, ngaySinh, gioiTinh, chieuCao, canNang, queQuan, diemThiDauVao) VALUES ('$lopHocId', '$tenSinhVien', '$ngaySinh', '$gioiTinh', '$chieuCao', '$canNang', '$queQuan', '$diemThiDauVao')";
                $connection->query($sql);
            }
        }
    }
}

// Kiểm tra nếu người dùng nhấn vào nút "Add"
if (isset($_POST['add'])) {
    generateData($connection);
}

?>

<!-- Các mã HTML và PHP hiện tại -->

<form method="post">
  <button type="submit" name="add" class="btn btn-primary">Add</button>
</form>

<!-- Các mã HTML và PHP tiếp theo -->