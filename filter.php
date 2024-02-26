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
  /* CSS styles */
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
          <!-- Form for filtering data -->
          <select class="form-select" id="select-khoahoc">
            <option selected disabled>Chọn khóa học</option>
            <?php
            // Generate options for course selection
            for ($year = 2000; $year <= 2022; $year++) {
              $nextYear = $year + 1;
              echo "<option>$year-$nextYear</option>";
            }
            ?>
          </select>
        </form>
        <div class="table-responsive mt-4">
          <table class="table table-bordered table-striped">
            <!-- Table structure -->
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <!-- JavaScript for filtering -->
  <script>
  document.addEventListener("DOMContentLoaded", function() {
    // Listen for change event on select element
    document.querySelector("#select-khoahoc").addEventListener("change", function() {
      var selectedYear = this.value; // Get the selected value
      var xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object
      xhr.open("GET", "filter.php?year=" + selectedYear, true); // Open a new GET request to filter.php
      xhr.onload = function() {
        if (xhr.status == 200) {
          // If the request was successful, update the table content
          document.querySelector(".table-responsive").innerHTML = xhr.responseText;
        }
      };
      xhr.send(); // Send the request
    });
  });
  </script>
</body>

</html>