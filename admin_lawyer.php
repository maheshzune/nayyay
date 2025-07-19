<?php
session_start();
if ($_SESSION['login'] == TRUE && $_SESSION['status'] == 'Active') {
    include("db_con/dbCon.php");
    $conn = connect();

    // Handle status change
    if (isset($_GET['toggle_id']) && isset($_GET['new_status'])) {
        $toggle_id = mysqli_real_escape_string($conn, $_GET['toggle_id']);
        $new_status = mysqli_real_escape_string($conn, $_GET['new_status']);
        $update_query = "UPDATE user SET status = ? WHERE u_id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "ss", $new_status, $toggle_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: admin_lawyer.php");
        exit();
    }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Google Fonts, Bootstrap, FontAwesome, and custom CSS files -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="css/all.css">
  <link rel="stylesheet" href="css/simple-sidebar.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/media.css">
  <title>Lawyer Management System - Lawyers</title>
  <style>
    /* Ensure html/body take full height for sticky footer */
    html, body {
      height: 100%;
    }
    /* Flex container to push footer to the bottom */
    .page-wrapper {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    .content-wrap {
      flex: 1;
    }
    /* Navbar styles */
    .neo-navbar {
      background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(248,249,250,0.85));
      backdrop-filter: blur(12px);
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      padding: 15px 0;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1030;
    }
    .navbar-brand {
      color: #00274D;
      font-weight: 700;
      font-size: 1.8rem;
    }
    .navbar-brand:hover {
      color: #FFD700;
    }
    .nav-link {
      color: #00274D;
      font-weight: 500;
      padding: 10px 15px;
      transition: all 0.3s ease;
    }
    .nav-link:hover {
      color: #FFD700;
      transform: scale(1.05);
    }
    .nav-link.active {
      color: #FFD700;
      border-bottom: 3px solid #FFD700;
    }
    .gradient-btn {
      background: linear-gradient(45deg, #00274D, #00A8A8);
      color: #FFFFFF;
      border-radius: 25px;
      padding: 8px 20px;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .gradient-btn:hover {
      background: linear-gradient(45deg, #00A8A8, #FFD700);
      color: #FFFFFF;
      transform: translateY(-2px);
    }
    /* Sidebar */
    #sidebar-wrapper {
      position: fixed;
      top: 90px;
      left: 0;
      width: 250px;
      min-height: calc(100vh - 90px);
      background: #FFFFFF;
      box-shadow: 2px 0 15px rgba(0,0,0,0.1);
      z-index: 1000;
      overflow-y: auto;
    }
    .sidebar-heading {
      background: linear-gradient(90deg, #00274D, #00A8A8);
      color: #FFFFFF;
      padding: 15px;
      font-size: 1.4rem;
      font-weight: 700;
    }
    /* Page Content */
    #page-content-wrapper {
      margin-left: 250px;
      padding: 40px 20px;
      min-height: calc(100vh - 90px);
    }
    /* Dashboard Card and Table */
    .dashboard-card {
      background: linear-gradient(135deg, #FFFFFF 0%, #F8F9FA 100%);
      border-radius: 12px;
      padding: 25px;
      margin-bottom: 25px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
      position: relative;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .dashboard-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 6px;
      height: 100%;
      background: linear-gradient(to bottom, #FFD700, #00A8A8);
    }
    .dashboard-card:hover {
      transform: translateY(-5px) scale(1.02);
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .lawyer-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      border-radius: 8px;
      overflow: hidden;
    }
    .lawyer-table th, .lawyer-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #dee2e6;
    }
    .lawyer-table th {
      background: #00274D;
      color: #FFD700;
      font-weight: 600;
    }
    .lawyer-table tbody tr:nth-child(even) {
      background-color: #F8F9FA;
    }
    .lawyer-table img {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 50%;
    }
    .btn-approve, .btn-details, .btn-toggle, .btn-edit {
      background: linear-gradient(45deg, #00274D, #00A8A8);
      border: none;
      padding: 6px 12px;
      border-radius: 20px;
      transition: all 0.3s ease;
      color: #FFFFFF;
      margin-right: 5px;
    }
    .btn-approve:hover, .btn-details:hover, .btn-toggle:hover, .btn-edit:hover {
      background: linear-gradient(45deg, #00A8A8, #FFD700);
      transform: translateY(-2px);
    }
    .btn-toggle.inactive {
      background: linear-gradient(45deg, #dc3545, #ff6b6b);
    }
    .btn-toggle.inactive:hover {
      background: linear-gradient(45deg, #ff6b6b, #dc3545);
    }
    .details-row {
      display: none;
      background-color: #e9ecef;
    }
    .details-row td {
      padding: 15px;
    }
    /* Sticky Footer */
    footer {
      background: #00274D;
      color: #FFFFFF;
      padding: 25px 0;
      text-align: center;
      font-weight: 500;
    }
    @media (max-width: 768px) {
      #sidebar-wrapper {
        margin-left: -250px;
      }
      #page-content-wrapper {
        margin-left: 0;
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="page-wrapper">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg neo-navbar">
      <div class="container">
        <a class="navbar-brand" href="index.php"><i class="fas fa-balance-scale me-2"></i>Lawyer Management</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link active" href="admin_lawyer.php">Lawyers</a></li>
            <li class="nav-item"><a class="nav-link" href="admin_user.php">Users</a></li>
            <li class="nav-item ms-3"><a class="nav-link btn gradient-btn" href="logout.php">Log Out</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Content Wrap -->
    <div class="content-wrap" id="wrapper">
      <div id="sidebar-wrapper">
        <div class="sidebar-heading">Admin Panel</div>
        <div class="list-group list-group-flush">
          <a href="admin_dashboard.php" class="list-group-item list-group-item-action bg-light">Dashboard</a>
          <a href="admin_lawyer.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'admin_lawyer.php' ? 'active' : ''; ?>">
            See Lawyers
            <?php if (basename($_SERVER['PHP_SELF']) == 'admin_lawyer.php'): ?>
              <i class="fas fa-arrow-right float-end"></i>
            <?php endif; ?>
          </a>
          <a href="admin_user.php" class="list-group-item list-group-item-action bg-light">See Users</a>
          <a href="admin_case_details.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'admin_case.php' ? 'active' : ''; ?>">
            See Cases
          </a>
        </div>
      </div>

      <div id="page-content-wrapper">
        <div class="container-fluid">
          <div class="dashboard-card">
            <h4 class="text-gradient">Registered Lawyers</h4>
            <p class="mb-0 fw-medium">View and manage all registered lawyers in the system.</p>
          </div>

          <div class="dashboard-card">
            <!-- Real-time Search Bar -->
            <div class="input-group mb-3">
              <input type="text" id="searchInput" class="form-control" placeholder="Search by name, email, or speciality" onkeyup="searchTable()">
            </div>

            <?php
            $query = "SELECT * FROM user INNER JOIN lawyer ON user.u_id = lawyer.lawyer_id";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                echo '<table class="lawyer-table" id="lawyerTable">';
                echo '<thead><tr><th>No.</th><th>Name</th><th>Email</th><th>Speciality</th><th>Status</th><th>Action</th></tr></thead>';
                echo '<tbody>';
                $counter = 0;
                while ($row = mysqli_fetch_array($result)) {
                    $counter++;
                    $lawyerId = $row['u_id'];
                    $currentStatus = $row['status'];
                    $newStatus = $currentStatus == 'Active' ? 'Inactive' : 'Active';
                    
                    // Main Row
                    echo "<tr class='main-row'>";
                    echo "<td>$counter</td>";
                    echo "<td>{$row['first_Name']} {$row['last_Name']}</td>";
                    echo "<td>{$row['email']}</td>";
                    echo "<td>{$row['speciality']}</td>";
                    echo "<td>";
                    if ($currentStatus == 'Active') {
                        echo "<span class='badge bg-success rounded-pill'>Active</span>";
                    } else {
                        echo "<span class='badge bg-warning rounded-pill'>Inactive</span>";
                    }
                    echo "</td>";
                    echo "<td>";
                    echo "<a href='admin_lawyer.php?toggle_id=$lawyerId&new_status=$newStatus' class='btn btn-toggle btn-sm " . ($currentStatus == 'Active' ? '' : 'inactive') . "'>";
                    echo $currentStatus == 'Active' ? "<i class='fas fa-toggle-on'></i> Deactivate" : "<i class='fas fa-toggle-off'></i> Activate";
                    echo "</a> ";
                    // Edit Button with data attributes for floating modal
                    echo "<button class='btn btn-edit btn-sm'
                            data-lawyerid='$lawyerId'
                            data-barid='" . htmlspecialchars($row['bar_id']) . "'
                            data-barlicense='" . htmlspecialchars($row['bar_license']) . "'
                            data-contact='" . htmlspecialchars($row['contact_Number']) . "'
                            data-college='" . htmlspecialchars($row['university_College']) . "'
                            data-degree='" . htmlspecialchars($row['degree']) . "'
                            data-passingyear='" . htmlspecialchars($row['passing_year']) . "'
                            data-address='" . htmlspecialchars($row['full_address']) . "'
                            data-city='" . htmlspecialchars($row['city']) . "'
                            data-zip='" . htmlspecialchars($row['zip_code']) . "'
                            data-practiselength='" . htmlspecialchars($row['practise_Length']) . "'
                            data-casehandle='" . htmlspecialchars($row['case_handle']) . "'
                            data-speciality='" . htmlspecialchars($row['speciality']) . "'
                            data-image='" . htmlspecialchars($row['image']) . "'
                            onclick='openEditModal(this)'><i class='fas fa-edit'></i> Edit</button> ";
                    echo "<button class='btn btn-details btn-sm' onclick='toggleDetails(\"$lawyerId\")'>View Details</button>";
                    echo "</td>";
                    echo "</tr>";

                    // Details Row (Hidden by Default)
                    echo "<tr class='details-row' id='details-$lawyerId'>";
                    echo "<td colspan='6'>";
                    echo "<div><strong>ID:</strong> {$row['u_id']}</div>";
                    echo "<div><strong>Bar ID:</strong> " . ($row['bar_id'] ? $row['bar_id'] : "N/A") . "</div>";
                    echo "<div><strong>Bar License:</strong> " . ($row['bar_license'] ? $row['bar_license'] : "N/A") . "</div>";
                    echo "<div><strong>Contact:</strong> +88{$row['contact_Number']}</div>";
                    echo "<div><strong>Address:</strong> {$row['full_address']}, {$row['city']}, {$row['zip_code']}</div>";
                    echo "<div><strong>College:</strong> {$row['university_College']}</div>";
                    echo "<div><strong>Degree & Passing Year:</strong> {$row['degree']} ({$row['passing_year']})</div>";
                    echo "<div><strong>Practice Length:</strong> {$row['practise_Length']}</div>";
                    echo "<div><strong>Cases Handled:</strong> {$row['case_handle']}</div>";
                    echo "<div><strong>Speciality:</strong> {$row['speciality']}</div>";
                    echo "<div><img src='images/upload/{$row['image']}' alt='{$row['image']}' onerror=\"this.src='images/upload/default-profile.png'\" style='width: 100px; height: 100px; border-radius: 50%;'></div>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo '</tbody></table>';
            } else {
                echo '<p class="text-muted">No lawyers found.</p>';
            }
            ?>
        </div>
      </div>
    </div>

    <!-- Floating Modal for Editing Lawyer Details -->
    <div class="modal fade" id="editLawyerModal" tabindex="-1" aria-labelledby="editLawyerModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <form action="admin_lawyer_update.php" method="post" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title" id="editLawyerModalLabel">Edit Lawyer Details</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <!-- Hidden Lawyer ID -->
              <input type="hidden" name="lawyer_id" id="modal_lawyer_id">
              <div class="mb-3">
                <label for="modal_bar_id" class="form-label">Bar ID</label>
                <input type="text" name="bar_id" id="modal_bar_id" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="modal_bar_license" class="form-label">Bar License</label>
                <input type="text" name="bar_license" id="modal_bar_license" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="modal_contact" class="form-label">Contact Number</label>
                <input type="text" name="contact_Number" id="modal_contact" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="modal_college" class="form-label">University/College</label>
                <input type="text" name="university_College" id="modal_college" class="form-control" required>
              </div>
              <div class="row mb-3">
                <div class="col">
                  <label for="modal_degree" class="form-label">Degree</label>
                  <input type="text" name="degree" id="modal_degree" class="form-control" required>
                </div>
                <div class="col">
                  <label for="modal_passing_year" class="form-label">Passing Year</label>
                  <input type="text" name="passing_year" id="modal_passing_year" class="form-control" required>
                </div>
              </div>
              <div class="mb-3">
                <label for="modal_address" class="form-label">Full Address</label>
                <input type="text" name="full_address" id="modal_address" class="form-control" required>
              </div>
              <div class="row mb-3">
                <div class="col">
                  <label for="modal_city" class="form-label">City</label>
                  <input type="text" name="city" id="modal_city" class="form-control" required>
                </div>
                <div class="col">
                  <label for="modal_zip" class="form-label">Zip Code</label>
                  <input type="text" name="zip_code" id="modal_zip" class="form-control" required>
                </div>
              </div>
              <div class="mb-3">
                <label for="modal_practise_length" class="form-label">Practice Length</label>
                <input type="text" name="practise_Length" id="modal_practise_length" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="modal_case_handle" class="form-label">Cases Handled</label>
                <input type="text" name="case_handle" id="modal_case_handle" class="form-control" placeholder="Comma separated list" required>
              </div>
              <div class="mb-3">
                <label for="modal_speciality" class="form-label">Speciality</label>
                <select name="speciality" id="modal_speciality" class="form-select" required>
                  <option value="">Select Speciality...</option>
                  <?php 
                  $specialties = [
                    "Criminal law", "Civil law", "Writ Jurisdiction", "Company law",
                    "Contract law", "Commercial law", "Construction law", "IT law",
                    "Family law", "Religious law", "Investment law", "Labour law",
                    "Property law", "Taxation law"
                  ];
                  foreach ($specialties as $spec) {
                      echo "<option value='" . htmlspecialchars($spec) . "'>" . htmlspecialchars($spec) . "</option>";
                  }
                  ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="modal_profile_image" class="form-label">Profile Image</label>
                <div id="current_profile_image"></div>
                <input type="file" name="profile_image" id="modal_profile_image" class="form-control" accept="image/*">
                <small class="form-text text-muted">Upload a new image to replace the existing one.</small>
              </div>
            </div>
            <div class="modal-footer">
              <a href="admin_lawyer.php" class="btn btn-secondary">Cancel</a>
              <button type="submit" class="btn btn-primary">Update Details</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer>
      <div class="container">
        <div class="row">
          <div class="col">
            <h5>All rights reserved © <?php echo date("Y"); ?></h5>
          </div>
        </div>
      </div>
    </footer>
  </div> <!-- End .page-wrapper -->

  <!-- JavaScript files -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" 
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" 
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Real-time Search Function
    function searchTable() {
      const input = document.getElementById('searchInput').value.toLowerCase();
      const table = document.getElementById('lawyerTable');
      const rows = table.getElementsByTagName('tr');
      for (let i = 1; i < rows.length; i += 2) {
        const mainRow = rows[i];
        const name = mainRow.cells[1].textContent.toLowerCase();
        const email = mainRow.cells[2].textContent.toLowerCase();
        const speciality = mainRow.cells[3].textContent.toLowerCase();
        if (name.includes(input) || email.includes(input) || speciality.includes(input)) {
          mainRow.style.display = '';
          if(rows[i+1]) rows[i+1].style.display = '';
        } else {
          mainRow.style.display = 'none';
          if(rows[i+1]) rows[i+1].style.display = 'none';
        }
      }
    }
    // Toggle Details Function
    function toggleDetails(lawyerId) {
      const detailsRow = document.getElementById(`details-${lawyerId}`);
      detailsRow.style.display = detailsRow.style.display === 'table-row' ? 'none' : 'table-row';
    }
    // Open Edit Modal and populate fields using data attributes
    function openEditModal(button) {
      const lawyerId = button.getAttribute('data-lawyerid');
      const barId = button.getAttribute('data-barid');
      const barLicense = button.getAttribute('data-barlicense');
      const contact = button.getAttribute('data-contact');
      const college = button.getAttribute('data-college');
      const degree = button.getAttribute('data-degree');
      const passingYear = button.getAttribute('data-passingyear');
      const address = button.getAttribute('data-address');
      const city = button.getAttribute('data-city');
      const zip = button.getAttribute('data-zip');
      const practiseLength = button.getAttribute('data-practiselength');
      const caseHandle = button.getAttribute('data-casehandle');
      const speciality = button.getAttribute('data-speciality');
      const profileImage = button.getAttribute('data-image');

      document.getElementById('modal_lawyer_id').value = lawyerId;
      document.getElementById('modal_bar_id').value = barId;
      document.getElementById('modal_bar_license').value = barLicense;
      document.getElementById('modal_contact').value = contact;
      document.getElementById('modal_college').value = college;
      document.getElementById('modal_degree').value = degree;
      document.getElementById('modal_passing_year').value = passingYear;
      document.getElementById('modal_address').value = address;
      document.getElementById('modal_city').value = city;
      document.getElementById('modal_zip').value = zip;
      document.getElementById('modal_practise_length').value = practiseLength;
      document.getElementById('modal_case_handle').value = caseHandle;
      document.getElementById('modal_speciality').value = speciality;
      if (profileImage && profileImage.trim() !== "") {
        document.getElementById('current_profile_image').innerHTML = "<img src='images/upload/" + profileImage + "' alt='Profile Image' style='width: 50px; height: 50px; border-radius: 50%;'>";
      } else {
        document.getElementById('current_profile_image').innerHTML = "No image uploaded.";
      }
      var editModal = new bootstrap.Modal(document.getElementById('editLawyerModal'));
      editModal.show();
    }
  </script>
</body>
</html>
<?php
    mysqli_close($conn);
} else {
    header('location:login.php?deactivate');
}
?>
