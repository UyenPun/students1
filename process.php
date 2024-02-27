<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$database = "student";

// Tạo kết nối
$connection = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Hàm để tạo giới tính ngẫu nhiên
function generateRandomGender()
{
    $genders = array("Nam", "Nữ");
    return $genders[array_rand($genders)];
}

// Hàm để tạo quê quán ngẫu nhiên
function generateRandomAddress()
{
    $addresses = array("Hanoi", "Ho Chi Minh City", "Da Nang", "Hue", "Can Tho", "Hai Phong", "Nha Trang", "Vung Tau");
    return $addresses[array_rand($addresses)];
}

// Hàm để tạo tên ngẫu nhiên
function generateRandomName()
{
    $names = array("John", "Jane", "Doe", "Smith", "Alice", "Bob", "Charlie", "David");
    return $names[array_rand($names)];
}

// Hàm để tạo ngày sinh ngẫu nhiên
function generateRandomDate()
{
    return date('Y-m-d', rand(strtotime("1950-01-01"), strtotime("2005-12-31")));
}

// Hàm để thêm người dùng mới với thông tin ngẫu nhiên
function addRandomUserToDatabase($connection)
{
    $name = generateRandomName();
    $birthday = generateRandomDate();
    $gender = generateRandomGender();
    $height = rand(150, 200);
    $weight = rand(40, 100);
    $address = generateRandomAddress();
    $score = rand(0, 10);

    $sql = "INSERT INTO userinformation (Ten, NgaySinh, GioiTinh, ChieuCao, CanNang, QueQuan, DiemThi) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssiisi", $name, $birthday, $gender, $height, $weight, $address, $score);
    $stmt->execute();
    $stmt->close();
}

// Xử lý xóa toàn bộ hàng từ bảng cơ sở dữ liệu
if (isset($_GET['delete_all'])) {
    $sql_delete_all = "TRUNCATE TABLE userinformation";
    if ($connection->query($sql_delete_all) === TRUE) {
        // Xóa thành công, chuyển hướng người dùng đến trang hiện tại
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Nếu có lỗi xảy ra trong quá trình xóa, hiển thị thông báo lỗi
        echo "Error deleting record: " . $connection->error;
    }
}

// Xử lý khi người dùng nhấn nút "Add Users"
if (isset($_POST['add_users']) && isset($_POST['quantity'])) {
    $quantity = intval($_POST['quantity']);
    if ($quantity > 0) {
        for ($i = 0; $i < $quantity; $i++) {
            addRandomUserToDatabase($connection);
        }
        // Chuyển hướng người dùng đến trang hiện tại để refresh danh sách
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Tính toán số lượng nam và nữ từ cơ sở dữ liệu
$sql_gender_count = "SELECT GioiTinh, COUNT(*) AS count FROM userinformation GROUP BY GioiTinh";
$result_gender_count = $connection->query($sql_gender_count);

$gender_counts = array(
    'Nam' => 0,
    'Nữ' => 0
);

if ($result_gender_count->num_rows > 0) {
    while ($row = $result_gender_count->fetch_assoc()) {
        $gender_counts[$row['GioiTinh']] = $row['count'];
    }
}

$gender_labels = array_keys($gender_counts);
$gender_values = array_values($gender_counts);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trang 1</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</head>

<body>
  <div class="container my-5">
    <h2 class="d-flex align-items-center">List of Student</h2>
    <div class="d-flex justify-content-between mb-3">
      <span>
        <a href="/Student-List/create.php" class="btn btn-primary me-2" role="button">New Student</a>
        <a href="/Student-List/filter.php" class="btn btn-primary me-2" role="button">Filter Student</a>
      </span>
      <form method="post" class="d-flex">
        <input type="number" name="quantity" class="form-control me-2" placeholder="Enter quantity" required>
        <button type="submit" name="add_users" class="btn btn-primary">Add Users</button>
      </form>
      <a href="?delete_all=true" class="btn btn-primary custom-gradient-4 ms-2" role="button">Delete All</a>
    </div>

    <canvas id="genderChart" width="400" height="400"></canvas>

    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Ten</th>
          <th>Ngay Sinh</th>
          <th>Gioi Tinh</th>
          <th>Chieu Cao</th>
          <th>Can Nang</th>
          <th>Que Quan</th>
          <th>Diem Thi</th>
          <th>Thao Tac</th>
        </tr>
      </thead>

      <!-- Char Pie -->
      <canvas id="genderChart" width="400" height="400"></canvas>

      <tbody>
        <?php
                // Đọc tất cả các hàng từ bảng cơ sở dữ liệu
                $sql = "SELECT * FROM userinformation";
                $result = $connection->query($sql);

                if ($result->num_rows > 0) {
                    // Đọc dữ liệu của mỗi hàng
                    while ($row = $result->fetch_assoc()) {
                        echo "
                        <tr>
                            <td>{$row['ID']}</td>
                            <td>{$row['Ten']}</td>
                            <td>{$row['NgaySinh']}</td>
                            <td>{$row['GioiTinh']}</td>
                            <td>{$row['ChieuCao']}</td>
                            <td>{$row['CanNang']}</td>
                            <td>{$row['QueQuan']}</td>
                            <td>{$row['DiemThi']}</td>
                            <td>
                                <a class='btn btn-primary btm-sm' href='/Student-List/edit.php?ID={$row['ID']}'>Edit</a>
                                <a class='btn btn-danger btm-sm' href='/Student-List/delete.php?ID={$row['ID']}'>Delete</a>
                            </td>
                        </tr>
                        ";
                    }
                } else {
                    echo "<tr><td colspan='9'>No data available</td></tr>";
                }
                ?>
      </tbody>
    </table>
  </div>

  <script>
  var ctx = document.getElementById('genderChart').getContext('2d');
  var genderChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: <?php echo json_encode($gender_labels); ?>,
      datasets: [{
        label: 'Gender Distribution',
        data: <?php echo json_encode($gender_values); ?>,
        backgroundColor: [
          'rgba(255, 99, 132, 0.5)', // Màu cho giới tính Nam
          'rgba(54, 162, 235, 0.5)' // Màu cho giới tính Nữ
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      // Các tùy chọn khác cho biểu đồ
    }
  });


  // Char Pie:
  // Đọc dữ liệu từ PHP và tạo biểu đồ Pie
  <?php
    // Truy vấn cơ sở dữ liệu để lấy số lượng nam và nữ
    $sql_gender_count = "SELECT gioiTinh, COUNT(*) AS count FROM sinhvien GROUP BY gioiTinh";
    $result_gender_count = $connection->query($sql_gender_count);

    $gender_data = array();
    if ($result_gender_count->num_rows > 0) {
        while ($row = $result_gender_count->fetch_assoc()) {
            $gender_data[$row['gioiTinh']] = $row['count'];
        }
    }
    ?>

  // Tạo biểu đồ Pie
  var ctx = document.getElementById('genderChart').getContext('2d');
  var genderChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ['Nam', 'Nữ'],
      datasets: [{
        label: 'Gender Distribution',
        data: [<?php echo isset($gender_data['Nam']) ? $gender_data['Nam'] : 0; ?>,
          <?php echo isset($gender_data['Nữ']) ? $gender_data['Nữ'] : 0; ?>
        ],
        backgroundColor: [
          'rgba(255, 99, 132, 0.5)',
          'rgba(54, 162, 235, 0.5)'
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true
    }
  });
  </script>
</body>

</html>
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
            <div class="col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Lọc</button>
              <!-- <button type="button" class="btn btn-success" id="addButton">Add</button> -->
              <button type="submit" class="btn btn-primary">
                <a href="http://localhost/student1/index.php">Reset</a>
              </button>
              <form method="post" class="d-flex">
                <input type="number" name="quantity" class="form-control me-2 form-btn" placeholder="Enter quantity"
                  required>
                <button type="submit" name="add_users" class="btn btn-primary">Add Users</button>
              </form>
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