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


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Danh sách Sinh viên</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Custom CSS -->
  <style>
  body {
    background-image: radial-gradient(circle at center, rgba(62, 147, 252, 0.57), rgba(239, 183, 192, 0.44));
  }

  .container {
    max-width: 800px;
    margin-top: 50px;
  }

  .card {
    border-radius: 15px;
    box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.5s ease;
    background: #fff;
  }

  .card:hover {
    transform: scale(1.05);
  }

  .card-header {
    background-color: #007bff;
    color: #fff;
    border-radius: 15px 15px 0 0;
    padding: 10px 20px;
  }

  .card-title {
    margin-bottom: 0;
  }

  .card-body {
    padding: 20px;
  }

  .form-select,
  .form-control {
    border-radius: 10px;
  }

  .btn-primary {
    border-radius: 10px;
  }

  .table {
    border-radius: 15px;
    overflow: hidden;
  }
  </style>
</head>

<body>
  <div class="container-lg mt-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Danh sách Sinh viên</h3>
      </div>
      <div class="card-body">
        <form>
          <div class="row mb-3">
            <div class="col-md-3">
              <select class="form-select">
                <option selected disabled>Chọn khóa học</option>
                <?php
                  // Tạo dữ liệu giả lập cho các tùy chọn của khóa học
                  for ($year = 2000; $year <= 2023; $year++) {
                    echo "<option>$year</option>";
                  }
                ?>
              </select>
            </div>
            <div class="col-md-3">
              <select class="form-select">
                <option selected disabled>Chọn lớp học</option>
                <!-- Thêm các option từ database -->
                <?php
                  // Tạo dữ liệu giả lập cho các tùy chọn của lớp học
                  for ($class = 1; $class <= 5; $class++) {
                    echo "<option>Lớp $class</option>";
                  }
                ?>
              </select>
            </div>
            <div class="col-md-3">
              <select class="form-select">
                <option selected disabled>Chọn giới tính</option>
                <option>Nam</option>
                <option>Nữ</option>
                <option>Khác</option>
              </select>
            </div>
            <div class="col-md-3">
              <input type="text" class="form-control" placeholder="Nhập điểm thi đầu vào">
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Lọc</button>
            </div>
          </div>
        </form>
        <div class="table-responsive mt-4">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>id</th>
                <th>id_Class</th>
                <th>Tên</th>
                <th>Ngày sinh</th>
                <th>Giới tính</th>
                <th>Chiều cao (cm)</th>
                <th>Cân nặng (kg)</th>
                <th>Quê quán</th>
                <th>Điểm thi đầu vào</th>
                <th>Lớp học</th>
                <th>Năm bắt đầu</th>
                <!-- <th>Hành động</th> -->
              </tr>
            </thead>
            <tbody>
              <?php
                // Đọc dữ liệu từ ba bảng bằng cách sử dụng câu truy vấn JOIN
                $sql = "SELECT sinhvien.id AS sinhvien_id, sinhvien.ten AS sinhvien_ten, sinhvien.ngaySinh,
                        sinhvien.gioiTinh, sinhvien.chieuCao, sinhvien.canNang, sinhvien.queQuan,
                        sinhvien.diemThiDauVao, lophoc.tenLop, khoahoc.namBatDau, lophoc.id AS lophoc_id
                        FROM sinhvien
                        JOIN lophoc ON sinhvien.idLopHoc = lophoc.id
                        JOIN khoahoc ON lophoc.idKhoaHoc = khoahoc.id";
                $result = $connection->query($sql);

                if ($result->num_rows > 0) {
                    // Đọc dữ liệu của mỗi hàng
                    while ($row = $result->fetch_assoc()) {
                        echo "
                        <tr>
                            <td>{$row['sinhvien_id']}</td>
                            <td>{$row['lophoc_id']}</td>
                            <td>{$row['sinhvien_ten']}</td>
                            <td>{$row['ngaySinh']}</td>
                            <td>{$row['gioiTinh']}</td>
                            <td>{$row['chieuCao']}</td>
                            <td>{$row['canNang']}</td>
                            <td>{$row['queQuan']}</td>
                            <td>{$row['diemThiDauVao']}</td>
                            <td>{$row['tenLop']}</td>
                            <td>{$row['namBatDau']}</td>
                        </tr>
                        ";
                    }
                } else {
                    echo "<tr><td colspan='11'>No data available</td></tr>";
                }
                ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>