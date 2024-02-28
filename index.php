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

  .form-select1 {
    display: none;
  }

  a {
    color: #fff;
    text-decoration: none;
  }

  .form-btn {
    display: inline-block;
    width: 140px;
  }

  .form-group {
    display: flex;
  }
  </style>
</head>

<body>
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

// Khai báo biến
$results_per_page = 10;

// Tính toán số trang
$sql = "SELECT COUNT(*) AS total FROM sinhvien";
$result = $connection->query($sql);
$row = $result->fetch_assoc();
$total_pages = ceil($row["total"] / $results_per_page);

// Xác định trang hiện tại
if (!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}

// Xác định vị trí bắt đầu và kết thúc của kết quả trên trang hiện tại
$start_index = ($page - 1) * $results_per_page;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['quantity'])) {
  // Xử lý khi nút "Gửi" được nhấn
  $quantity = $_POST['quantity'];

  // Thực hiện thêm dữ liệu vào cơ sở dữ liệu dựa trên số lượng đã nhập
  for ($i = 0; $i < $quantity; $i++) {
      // Ví dụ: Thêm một bản ghi mới vào cơ sở dữ liệu, có thể thay đổi tùy theo cấu trúc bảng của bạn
      $sql_insert = "INSERT INTO sinhvien (ten, ngaySinh, gioiTinh, chieuCao, canNang, queQuan, diemThiDauVao, idLopHoc)
                      VALUES ('Tên Sinh viên', 'Ngày sinh', 'Giới tính', 'Chiều cao', 'Cân nặng', 'Quê quán', 'Điểm thi', 'ID lớp học')";
      if ($connection->query($sql_insert) === TRUE) {
          echo "Thêm dữ liệu thành công!";
      } else {
          echo "Lỗi: " . $connection->error;
      }
  }

  // Tải lại trang để cập nhật bảng table với dữ liệu mới
  echo "<meta http-equiv='refresh' content='0'>";


// Xử lý xóa toàn bộ dữ liệu từ nhiều bảng
if (isset($_GET['delete_all'])) {
  $sql_delete_sinhvien = "TRUNCATE TABLE sinhvien";
  $sql_delete_lophoc = "TRUNCATE TABLE lophoc";
  $sql_delete_khoahoc = "TRUNCATE TABLE khoahoc";
  // $sql_delete_sinhvien = "DELETE FROM sinhvien";
  // $sql_delete_lophoc = "DELETE FROM lophoc";
  // $sql_delete_khoahoc = "DELETE FROM khoahoc";

  // Thực hiện các truy vấn xóa
  if ($connection->query($sql_delete_sinhvien) === TRUE &&
      $connection->query($sql_delete_lophoc) === TRUE &&
      $connection->query($sql_delete_khoahoc) === TRUE) {
      // Xóa thành công, chuyển hướng người dùng đến trang hiện tại
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
  } else {
      // Nếu có lỗi xảy ra trong quá trình xóa, hiển thị thông báo lỗi
      echo "Error deleting record: " . $connection->error;
  }
}
}

?>
  <div class="container-lg mt-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Danh sách Sinh viên</h3>
      </div>
      <div class="card-body">
        <form>
          <div class="row mb-3">
            <div class="col-md-3">
              <select class="form-select" id="khoa_hoc_select">
                <option selected disabled>Chọn khóa học</option>
                <?php
                            // Tạo dữ liệu cho các tùy chọn của khóa học với mỗi option đại diện cho một khoảng 4 năm
                            for ($year = 2000; $year <= 2022; $year++) {
                                $nextFourYear = $year + 3; // Tính năm kết thúc là 4 năm sau năm bắt đầu
                                echo "<option value='$year'>$year-$nextFourYear</option>";
                            }
                            ?>
              </select>
            </div>
            <div class="col-md-3">
              <select class="form-select" name="lop_hoc" id="lop_hoc_select">
                <option selected disabled>Chọn lớp học</option>
                <!-- Truy vấn cơ sở dữ liệu để lấy tên các lớp học -->
                <?php
                            $sql_lop = "SELECT * FROM lophoc";
                            $result_lop = $connection->query($sql_lop);

                            // Kiểm tra kết quả và tạo các option cho select
                            if ($result_lop->num_rows > 0) {
                                while ($row_lop = $result_lop->fetch_assoc()) {
                                    echo "<option value='" . $row_lop['id'] . "'>" . $row_lop['tenLop'] . "</option>";
                                }
                            } else {
                                echo "<option disabled>Không có lớp học</option>";
                            }
                            ?>
              </select>
            </div>
            <div class="col-md-3">
              <select class="form-select" name="gioi_tinh">
                <option selected disabled>Chọn giới tính</option>
                <option>Nam</option>
                <option>Nữ</option>
                <!-- Có thể thêm tùy chọn "Khác" nếu cần -->
              </select>
            </div>
            <div class="col-md-3">
              <div class="col-md-3">
                <label for="diem_thi">Chọn điểm thi:</label>
                <input type="range" class="form-range" min="0" max="10" step="0.5" id="diem_thi" name="diem_thi">
              </div>

            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-3">
              <select class="form-select form-select1" name="nam_bat_dau">
                <option selected disabled>Chọn năm bắt đầu</option>
                <?php
                            // Tạo các tùy chọn cho năm bắt đầu từ 2000 đến 2022
                            for ($year = 2000; $year <= 2022; $year++) {
                                echo "<option>$year</option>";
                            }
                            ?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <button type="submit" class="btn btn-primary">Lọc</button>
              <button type="submit" class="btn btn-primary">
                <a href="http://localhost/student1/index.php">Reset</a>
              </button>
            </div>
            <div class="col-md-4">
              <form id="myForm">
                <div class="form-group">
                  <input type="number" class="form-control" id="quantity" placeholder="Nhập số lượng"
                    style="width: 100px">
                  <button type=" submit" class="btn btn-primary">Add</button>
                </div>
              </form>
            </div>
            <div class="col-md-4">
              <a href="?delete_all=true" class="btn btn-primary custom-gradient-4 ms-2" role="button">Delete All</a>
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
                <th>Chiều cao (m)</th>
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
                    // Xác định điều kiện lọc
                    $filter_year = isset($_GET['nam_bat_dau']) ? $_GET['nam_bat_dau'] : '';
                    $filter_class = isset($_GET['lop_hoc']) ? $_GET['lop_hoc'] : '';
                    $filter_gender = isset($_GET['gioi_tinh']) ? $_GET['gioi_tinh'] : '';

                    // Thêm điều kiện lọc vào câu truy vấn SQL nếu có
                    $filter_condition = "";
                    if (!empty($filter_year)) {
                        $filter_condition = " WHERE khoahoc.namBatDau = '$filter_year'";
                    }
                    if (!empty($filter_class)) {
                        if (!empty($filter_condition)) {
                            $filter_condition .= " AND ";
                        } else {
                            $filter_condition = " WHERE ";
                        }
                        $filter_condition .= "lophoc.id = '$filter_class'";
                    }
                    if (!empty($filter_gender)) {
                        if (!empty($filter_condition)) {
                            $filter_condition .= " AND ";
                        } else {
                            $filter_condition = " WHERE ";
                        }
                        $filter_condition .= "sinhvien.gioiTinh = '$filter_gender'";
                    }

                    // Cập nhật câu truy vấn SQL để áp dụng bộ lọc
                    $sql = "SELECT sinhvien.id AS sinhvien_id, sinhvien.ten AS sinhvien_ten, sinhvien.ngaySinh,
                                    sinhvien.gioiTinh, sinhvien.chieuCao, sinhvien.canNang, sinhvien.queQuan,
                                    sinhvien.diemThiDauVao, lophoc.tenLop, khoahoc.namBatDau, lophoc.id AS lophoc_id
                                    FROM sinhvien
                                    JOIN lophoc ON sinhvien.idLopHoc = lophoc.id
                                    JOIN khoahoc ON lophoc.idKhoaHoc = khoahoc.id
                                    $filter_condition
                                    LIMIT $start_index, $results_per_page";
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
        <!-- Hiển thị phân trang -->
        <div class="text-center">
          <?php
                // Lấy các tham số lọc từ URL
                $filter_params = http_build_query($_GET);

                // Hiển thị các liên kết phân trang với các tham số lọc
                for ($page = 1; $page <= $total_pages; $page++) {
                    echo "<a href='?page=$page&$filter_params' class='btn btn-primary'>$page</a> ";
                }
                ?>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  // Lắng nghe sự kiện khi chọn option trong select "Chọn khóa học"
  document.getElementById('khoa_hoc_select').addEventListener('change', function() {
    // Lấy giá trị của option đã chọn
    var selectedYear = this.value;
    // Đặt giá trị của select "Năm bắt đầu" thành giá trị đã chọn
    document.getElementsByName('nam_bat_dau')[0].value = selectedYear;
  });
  </script>
</body>

</html>