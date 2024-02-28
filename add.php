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
$addresses = array("Ha Noi", "Ho Chi Minh", "Da Nang", "Hue", "Can Tho", "Hai Phong", "Nha Trang", "Vung Tau", "Ha Noi", "Hai Phong", "Quang Ninh", "Lao Cai", "Lai Chau", "Yen Bai", "Dien Bien", "Son La", "Hoa Binh", "Ha Giang", "Cao Bang", "Bac Kan", "Lang Son", "Tuyen Quang", "Thai Nguyen", "Phu Tho", "Bac Giang", "Quang Ninh", "Bac Ninh", "Ha Nam", "Hai Duong", "Hung Yen", "Nam Dinh", "Thai Binh", "Vinh Phuc", "Nghe An", "Thanh Hoa", "Ha Tinh", "Quang Binh", "Quang Tri", "Thua Thien Hue", "Quang Nam", "Quang Ngai", "Binh Dinh", "Phu Yen", "Khanh Hoa", "Ninh Thuan", "Binh Thuan", "Kon Tum", "Gia Lai", "Dak Lak", "Dak Nong", "Lam Dong", " Da Nang", "Binh Duong", "Binh Phuoc", "Tay Ninh", "Ba Ria – Vung Tau", "Dong Nai", "Long An", "Tien Giang", "Ben Tre", "Vinh Long", "Tra Vinh", "Dong Thap", "Hau Giang", "An Giang", "Kien Giang", "Bac Lieu", "Soc Trang", "Ca Mau");

// Hàm để tạo quê quán ngẫu nhiên
function generateRandomAddress()
{
  global $addresses;
  return $addresses[array_rand($addresses)];
}

// Hàm để tạo thông tin sinh viên tự động
function generateStudents($lopHocId, $numStudents, $connection)
{
  for ($j = 1; $j <= $numStudents; $j++) {
    $tenSinhVien = "Sinh viên $j";
    $ngaySinh = date('Y-m-d', strtotime("-" . rand(18, 28) . " years")); // Sinh viên từ 18 đến 28 tuổi
    $gioiTinh = rand(0, 1) ? 'Nam' : 'Nữ';
    $chieuCao = rand(150, 190);
    $canNang = rand(45, 90);
    $queQuan = generateRandomAddress();
    $diemThi = rand(0, 10);
    $diemKhuVuc = rand(0, 2);
    // $diemThiDauVao = rand(0, 10);
    $sql = "INSERT INTO sinhvien (idLopHoc, ten, diemThi, diemKhuVuc, ngaySinh, gioiTinh, chieuCao, canNang, queQuan, diemThiDauVao) VALUES ('$lopHocId', '$tenSinhVien', '$diemThi', '$diemKhuVuc', '$ngaySinh', '$gioiTinh', '$chieuCao', '$canNang', '$queQuan')";
    $connection->query($sql);
  }
}

// Kiểm tra nếu người dùng nhấn vào nút "Add"
if (isset($_POST['add_users'])) {
  $numUsers = isset($_POST['num_users']) ? intval($_POST['num_users']) : 0;

  if ($numUsers > 0) {

       for ($i = 1; $i <= $numUsers; $i++) {
    //     echo "<script>alert(1)</script>";
    //     $lopHocId = rand(1, 50); // Random một lớp học từ 1 đến 50
    //     $numStudents = rand(1, 10); // Random số lượng sinh viên từ 1 đến 10
    //     generateStudents($lopHocId, $numStudents, $connection);
       }
  }
  // header("Location: {$_SERVER['PHP_SELF']}"); // Chuyển hướng lại trang hiện tại
  // exit;
}

?>

<!-- Mã HTML và Bootstrap -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Users</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
  <div class="container">
    <h1>Add Users</h1>
    <form method="post" class="mb-3" action="">
      <div class="form-group">
        <label for="num_users">Number of users to add:</label>
        <input type="number" class="form-control" id="num_users" name="num_users" min="1" max="10">
      </div>
      <input type="submit" name="add_users" class="btn btn-primary" id="add_users" value="Add_Users">
    </form>
  </div>
</body>

</html>