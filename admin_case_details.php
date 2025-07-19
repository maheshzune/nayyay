<?php
session_start();
if ($_SESSION['login'] == TRUE && $_SESSION['status'] == 'Active') {
    include("db_con/dbCon.php");
    $conn = connect();
}

// Handle delete action
if (isset($_POST['delete_case'])) {
    $case_id = $_POST['case_id'];
    $query = "DELETE FROM `case` WHERE case_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $case_id);
    if (mysqli_stmt_execute($stmt)) {
        $success = "Case deleted successfully!";
    } else {
        $error = "Error deleting case: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Handle update action
if (isset($_POST['update_case'])) {
    $case_id    = $_POST['case_id'];
    $client_id  = $_POST['client_id'];
    $lawyer_id  = $_POST['lawyer_id'];
    $case_type  = $_POST['case_type'];
    $location   = $_POST['location'];
    $fees       = $_POST['fees'];
    $status     = $_POST['status'];

    $update_query = "UPDATE `case` SET client_id = ?, lawyer_id = ?, case_type = ?, location = ?, fees = ?, status = ? WHERE case_id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "iisssdi", $client_id, $lawyer_id, $case_type, $location, $fees, $status, $case_id);
    if (mysqli_stmt_execute($stmt)) {
        $success = "Case updated successfully!";
    } else {
        $error = "Error updating case: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
}

// Fetch all cases with associated client and lawyer names
$query = "
    SELECT c.case_id, c.case_type, c.location, c.fees, c.document_path, c.status,
           c.client_id, c.lawyer_id,
           client.first_name AS client_fname, client.last_name AS client_lname,
           lawyer.first_name AS lawyer_fname, lawyer.last_name AS lawyer_lname
    FROM `case` c
    JOIN `user` client ON c.client_id = client.u_id
    JOIN `user` lawyer ON c.lawyer_id = lawyer.u_id
";
$result = mysqli_query($conn, $query);

// Fetch all users for dropdowns in update modals
$users_query = "SELECT u_id, first_name, last_name, role FROM `user`";
$users_result = mysqli_query($conn, $users_query);
$users = [];
while ($user = mysqli_fetch_assoc($users_result)) {
    $users[] = $user;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lawyer Management System - Admin Cases</title>
  <!-- Bootstrap and FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root {
      --primary-color: #00274D;
      --accent-color: #FFD700;
      --secondary-color: #00A8A8;
      --background-color: #F8F9FA;
    }
    html, body { height: 100%; }
    body {
      font-family: 'Poppins', sans-serif;
      background: var(--background-color);
      margin: 0;
      padding-top: 90px;
    }
    /* Page Wrapper for sticky footer */
    .page-wrapper {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    /* Main content area (flex row with sidebar and content) */
    .main-content {
      flex: 1;
      display: flex;
      flex-direction: row;
    }
    /* Sidebar always visible */
    #sidebar-wrapper {
      width: 250px;
      background: #FFF;
      box-shadow: 2px 0 15px rgba(0,0,0,0.1);
      z-index: 1000;
      overflow-y: auto;
    }
    .sidebar-heading {
      background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
      color: #FFF;
      padding: 15px;
      font-size: 1.4rem;
      font-weight: 700;
    }
    /* Content area */
    #page-content-wrapper {
      flex: 1;
      padding: 40px 20px;
    }
    /* Navbar */
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
      color: var(--primary-color);
      font-weight: 700;
      font-size: 1.8rem;
    }
    .navbar-brand:hover { color: var(--accent-color); }
    .nav-link {
      color: var(--primary-color);
      font-weight: 500;
      padding: 10px 15px;
      transition: all 0.3s ease;
    }
    .nav-link:hover { color: var(--accent-color); transform: scale(1.05); }
    .nav-link.active { color: var(--accent-color); border-bottom: 3px solid var(--accent-color); }
    .gradient-btn {
      background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
      color: #FFF;
      border-radius: 25px;
      padding: 8px 20px;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .gradient-btn:hover { background: linear-gradient(45deg, var(--secondary-color), var(--accent-color)); }
    /* Table styles */
    .table-responsive { margin-top: 20px; }
    .table thead th {
      background: var(--primary-color);
      color: var(--accent-color);
      font-weight: 600;
    }
    /* Buttons */
    .btn-edit {
      background: var(--secondary-color);
      color: #FFF;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    .btn-edit:hover { background: var(--accent-color); }
    .btn-delete {
      background: #DC3545;
      color: #FFF;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    .btn-delete:hover { background: #c82333; }
    .btn-view-doc {
      background: var(--primary-color);
      color: #FFF;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    .btn-view-doc:hover { background: var(--secondary-color); }
    .btn-details {
      background: #17A2B8;
      color: #FFF;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    .btn-details:hover { background: #138496; }
    /* Modal styles */
    .modal-content {
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    .modal-header {
      background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
      color: #FFF;
      border-top-left-radius: 12px;
      border-top-right-radius: 12px;
    }
    /* Footer */
    footer {
      background: var(--primary-color);
      color: #FFF;
      padding: 25px 0;
      text-align: center;
      font-weight: 500;
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
            <li class="nav-item"><a class="nav-link" href="admin_lawyer.php">Lawyers</a></li>
            <li class="nav-item"><a class="nav-link" href="admin_user.php">Users</a></li>
            <li class="nav-item"><a class="nav-link active" href="admin_case_details.php">Cases</a></li>
            <li class="nav-item ms-3"><a class="nav-link btn gradient-btn" href="logout.php">Log Out</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Main Content (Sidebar + Page Content) -->
    <div class="d-flex">
      <!-- Sidebar (always visible) -->
      <div id="sidebar-wrapper">
        <div class="sidebar-heading">Admin Panel</div>
        <div class="list-group list-group-flush">
          <a href="admin_dashboard.php" class="list-group-item list-group-item-action bg-light">Dashboard</a>
          <a href="admin_lawyer.php" class="list-group-item list-group-item-action bg-light">See Lawyers</a>
          <a href="admin_user.php" class="list-group-item list-group-item-action bg-light">See Users</a>
          <a href="admin_case_details.php" class="list-group-item list-group-item-action bg-light active">See Cases</a>
        </div>
      </div>

      <!-- Page Content -->
      <div id="page-content-wrapper" class="flex-grow-1">
        <?php if (isset($success)): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php elseif (isset($error)): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <div class="dashboard-card">
          <h4 class="text-gradient">Case Management</h4>
          <p class="mb-4 fw-medium">View, edit, or delete case details below.</p>

          <div class="table-responsive">
            <table class="table table-hover" id="caseTable">
              <thead>
                <tr>
                  <th>Case ID</th>
                  <th>Client</th>
                  <th>Lawyer</th>
                  <th>Type</th>
                  <th>Location</th>
                  <th>Fees</th>
                  <th>Status</th>
                  <th>Document</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                  <tr>
                    <td><?php echo $row['case_id']; ?></td>
                    <td><?php echo $row['client_fname'] . ' ' . $row['client_lname']; ?></td>
                    <td><?php echo $row['lawyer_fname'] . ' ' . $row['lawyer_lname']; ?></td>
                    <td><?php echo $row['case_type']; ?></td>
                    <td><?php echo $row['location']; ?></td>
                    <td>$<?php echo number_format($row['fees'], 2); ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                      <?php if ($row['document_path']): ?>
                        <a href="<?php echo $row['document_path']; ?>" target="_blank" class="btn btn-view-doc btn-sm">View</a>
                      <?php else: ?>
                        <span class="text-muted">No document</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <!-- Edit Button -->
                      <button class="btn btn-edit btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['case_id']; ?>">Edit</button>
                      <!-- View Details Button -->
                      <button class="btn btn-details btn-sm"
                              data-caseid="<?php echo $row['case_id']; ?>"
                              data-client="<?php echo $row['client_fname'] . ' ' . $row['client_lname']; ?>"
                              data-lawyer="<?php echo $row['lawyer_fname'] . ' ' . $row['lawyer_lname']; ?>"
                              data-casetype="<?php echo $row['case_type']; ?>"
                              data-location="<?php echo $row['location']; ?>"
                              data-fees="<?php echo number_format($row['fees'], 2); ?>"
                              data-status="<?php echo $row['status']; ?>"
                              data-document="<?php echo $row['document_path']; ?>"
                              onclick="openCaseDetailsModal(this)">View Details</button>
                      <!-- Delete Form -->
                      <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this case?');">
                        <input type="hidden" name="case_id" value="<?php echo $row['case_id']; ?>">
                        <button type="submit" name="delete_case" class="btn btn-delete btn-sm">Delete</button>
                      </form>
                    </td>
                  </tr>

                  <!-- Edit Modal for this Case -->
                  <div class="modal fade" id="editModal<?php echo $row['case_id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $row['case_id']; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Edit Case #<?php echo $row['case_id']; ?></h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <form method="POST">
                            <input type="hidden" name="case_id" value="<?php echo $row['case_id']; ?>">
                            <div class="mb-3">
                              <label class="form-label">Client</label>
                              <select name="client_id" class="form-select" required>
                                <?php foreach ($users as $user): ?>
                                  <?php if ($user['role'] == 'Client'): ?>
                                    <option value="<?php echo $user['u_id']; ?>" <?php echo $user['u_id'] == $row['client_id'] ? 'selected' : ''; ?>>
                                      <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                                    </option>
                                  <?php endif; ?>
                                <?php endforeach; ?>
                              </select>
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Lawyer</label>
                              <select name="lawyer_id" class="form-select" required>
                                <?php foreach ($users as $user): ?>
                                  <?php if ($user['role'] == 'Lawyer'): ?>
                                    <option value="<?php echo $user['u_id']; ?>" <?php echo $user['u_id'] == $row['lawyer_id'] ? 'selected' : ''; ?>>
                                      <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                                    </option>
                                  <?php endif; ?>
                                <?php endforeach; ?>
                              </select>
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Case Type</label>
                              <input type="text" name="case_type" class="form-control" value="<?php echo $row['case_type']; ?>" required>
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Location</label>
                              <input type="text" name="location" class="form-control" value="<?php echo $row['location']; ?>" required>
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Fees</label>
                              <input type="number" step="0.01" name="fees" class="form-control" value="<?php echo $row['fees']; ?>" required>
                            </div>
                            <div class="mb-3">
                              <label class="form-label">Status</label>
                              <select name="status" class="form-select" required>
                                <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Active" <?php echo $row['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                                <option value="Won" <?php echo $row['status'] == 'Won' ? 'selected' : ''; ?>>Won</option>
                                <option value="Lost" <?php echo $row['status'] == 'Lost' ? 'selected' : ''; ?>>Lost</option>
                              </select>
                            </div>
                            <div class="modal-footer">
                              <button type="submit" name="update_case" class="btn gradient-btn">Update Case</button>
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Floating Modal for Viewing Case Details -->
    <div class="modal fade" id="viewCaseModal" tabindex="-1" aria-labelledby="viewCaseModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="viewCaseModalLabel">Case Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p><strong>Case ID:</strong> <span id="detail_case_id"></span></p>
            <p><strong>Client:</strong> <span id="detail_client"></span></p>
            <p><strong>Lawyer:</strong> <span id="detail_lawyer"></span></p>
            <p><strong>Case Type:</strong> <span id="detail_case_type"></span></p>
            <p><strong>Location:</strong> <span id="detail_location"></span></p>
            <p><strong>Fees:</strong> $<span id="detail_fees"></span></p>
            <p><strong>Status:</strong> <span id="detail_status"></span></p>
            <p><strong>Document:</strong> <span id="detail_document"></span></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
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

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" 
          integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" 
          crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Real-time Search Function for Case Table
    function searchTable() {
      const input = document.getElementById('searchInput').value.toLowerCase();
      const table = document.getElementById('caseTable');
      const rows = table.getElementsByTagName('tr');
      for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const caseId = row.cells[0].textContent.toLowerCase();
        const client = row.cells[1].textContent.toLowerCase();
        const lawyer = row.cells[2].textContent.toLowerCase();
        const caseType = row.cells[3].textContent.toLowerCase();
        if (caseId.includes(input) || client.includes(input) || lawyer.includes(input) || caseType.includes(input)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      }
    }
    // Open the "View Details" modal and populate it using data attributes
    function openCaseDetailsModal(button) {
      const caseId = button.getAttribute('data-caseid');
      const client = button.getAttribute('data-client');
      const lawyer = button.getAttribute('data-lawyer');
      const caseType = button.getAttribute('data-case-type');
      const location = button.getAttribute('data-location');
      const fees = button.getAttribute('data-fees');
      const status = button.getAttribute('data-status');
      const documentPath = button.getAttribute('data-document');

      document.getElementById('detail_case_id').innerText = caseId;
      document.getElementById('detail_client').innerText = client;
      document.getElementById('detail_lawyer').innerText = lawyer;
      document.getElementById('detail_case_type').innerText = caseType;
      document.getElementById('detail_location').innerText = location;
      document.getElementById('detail_fees').innerText = fees;
      document.getElementById('detail_status').innerText = status;
      if (documentPath && documentPath.trim() !== "") {
        document.getElementById('detail_document').innerHTML = `<a href="${documentPath}" target="_blank" class="btn btn-view-doc btn-sm">View Document</a>`;
      } else {
        document.getElementById('detail_document').innerText = "No document";
      }
      var detailsModal = new bootstrap.Modal(document.getElementById('viewCaseModal'));
      detailsModal.show();
    }
  </script>
</body>
</html>
<?php
    mysqli_close($conn);

?>
