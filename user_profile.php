<?php
session_start();
if ($_SESSION['login'] == TRUE && $_SESSION['status'] == 'Active') {
    include("db_con/dbCon.php");
    $conn = connect();
    $client_id = $_SESSION['client_id'];

    // Fetch client's image
    $image_query = "SELECT image FROM client WHERE client_id = '$client_id'";
    $image_result = mysqli_query($conn, $image_query);
    $client = mysqli_fetch_assoc($image_result);
    $profile_image = !empty($client['image']) ? $client['image'] : 'images/upload/default-profile.png';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/simple-sidebar.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media.css">
    <title>Lawyer Management System - Edit Profile</title>
    <style>
        :root {
            --primary-color: #00274D;
            --accent-color: #FFD700;
            --secondary-color: #00A8A8;
            --background-color: #F8F9FA;
            --gradient-start: rgba(0, 39, 77, 0.9);
            --gradient-end: rgba(0, 168, 168, 0.7);
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--background-color);
            padding-top: 90px;
            margin: 0;
        }
        .neo-navbar {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 249, 250, 0.85));
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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
        .navbar-brand:hover {
            color: var(--accent-color);
        }
        .nav-link {
            color: var(--primary-color);
            font-weight: 500;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            color: var(--accent-color);
            transform: scale(1.05);
        }
        .nav-link.active {
            color: var(--accent-color);
            border-bottom: 3px solid var(--accent-color);
        }
        .gradient-btn {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: #FFFFFF;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .gradient-btn:hover {
            background: linear-gradient(45deg, var(--secondary-color), var(--accent-color));
            color: #FFFFFF;
            transform: translateY(-2px);
            text-decoration: none;
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%2300274D' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        #sidebar-wrapper {
            position: fixed;
            top: 90px;
            left: 0;
            width: 250px;
            min-height: calc(100vh - 90px);
            background: #FFFFFF;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar-heading {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: #FFFFFF;
            padding: 15px;
            font-size: 1.4rem;
            font-weight: 700;
        }
        .list-group-item {
            color: var(--primary-color);
            font-weight: 500;
            padding: 15px 20px;
            transition: all 0.3s ease;
        }
        .list-group-item:hover {
            background: var(--background-color);
            color: var(--accent-color);
        }
        .list-group-item.active {
            background: var(--background-color);
            color: var(--accent-color);
            font-weight: 600;
        }
        #wrapper {
            display: flex;
            width: 100%;
        }
        #page-content-wrapper {
            margin-left: 250px;
            padding: 40px 20px;
            width: calc(100% - 250px);
            min-height: 100vh;
        }
        .dashboard-card {
            background: linear-gradient(135deg, #FFFFFF 0%, #F8F9FA 100%);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(to bottom, var(--accent-color), var(--secondary-color));
        }
        .dashboard-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        .dashboard-card h4 {
            color: var(--primary-color);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .text-gradient {
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            color: transparent;
        }
        .profile-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid var(--secondary-color);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .profile-image:hover {
            transform: scale(1.05);
        }
        .profile-img-nav {
            width: 35px;
            height: 35px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--accent-color);
            margin-right: 10px;
        }
        .form-control {
            border: 1px solid var(--primary-color);
            border-radius: 8px;
            transition: border-color 0.3s ease;
        }
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 5px rgba(255, 215, 0, 0.5);
        }
        .form-label {
            color: var(--primary-color);
            font-weight: 500;
        }
        .btn-success {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            background: linear-gradient(45deg, var(--secondary-color), var(--accent-color));
            color: #FFFFFF;
            transform: translateY(-2px);
        }
        footer {
            background: var(--primary-color);
            color: #FFFFFF;
            padding: 25px 0;
            margin-top: 50px;
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
            #wrapper.toggled #sidebar-wrapper {
                margin-left: 0;
            }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .dashboard-card {
            animation: fadeIn 0.5s ease-in;
        }

        /* New styles for view/edit modes */
        .profile-view-mode .form-control {
            background-color: transparent;
            border: none;
            padding-left: 0;
            cursor: default;
        }

        .profile-edit-mode .form-control {
            background-color: white;
            border: 1px solid var(--primary-color);
            padding: 0.375rem 0.75rem;
            cursor: text;
        }

        .image-upload-container {
            display: none;
        }

        .profile-edit-mode .image-upload-container {
            display: block;
            animation: fadeIn 0.3s ease-in;
        }

        .profile-actions {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn-edit {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-edit:hover {
            background: linear-gradient(45deg, var(--secondary-color), var(--accent-color));
            transform: translateY(-2px);
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg neo-navbar">
        <div class="container">
            <img src="logo.png" alt="nayya" height="50px">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="lawyers.php">Lawyers</a></li>
                    <li class="nav-item"><a class="nav-link" href="user_booking.php">My Bookings</a></li>
                    <li class="nav-item ms-3">
                        <a class="nav-link btn gradient-btn" href="#">
                            <img src="/<?php echo $profile_image; ?>" alt="Profile" class="profile-img-nav" onerror="this.src='/images/upload/default-profile.png'">
                            <?php echo $_SESSION['first_Name'] . ' ' . $_SESSION['last_Name']; ?>
                        </a>
                    </li>
                    <li class="nav-item ms-3"><a class="nav-link btn gradient-btn" href="logout.php">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex" id="wrapper">
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">My Profile</div>
            <div class="list-group list-group-flush">
                <a href="user_dashboard.php" class="list-group-item list-group-item-action bg-light">Dashboard</a>
                <a href="user_profile.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'user_profile.php' ? 'active' : ''; ?>">
                    Profile
                    <?php if (basename($_SERVER['PHP_SELF']) == 'user_profile.php'): ?>
                        <i class="fas fa-arrow-right float-end"></i>
                    <?php endif; ?>
                </a>
                <a href="user_booking.php" class="list-group-item list-group-item-action bg-light">My Booking Requests</a>
                <a href="user_case_detail.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'user_case_detail.php' ? 'active' : ''; ?>">
                    Case Details
                    <?php if (basename($_SERVER['PHP_SELF']) == 'user_case_detail.php'): ?>
                        <i class="fas fa-arrow-right float-end"></i>
                    <?php endif; ?>
                </a>
                <a href="user_document_management.php" class="list-group-item list-group-item-action bg-light">Upload Document</a>
                <a href="update_password.php" class="list-group-item list-group-item-action bg-light">Update Password</a>
            </div>
        </div>

        <div id="page-content-wrapper">
            <?php if (isset($_GET['ok'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                    <strong>Success!</strong> Your profile has been updated.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="container-fluid">
                <?php
                $a = $_SESSION['client_id'];
                $result = mysqli_query($conn, "SELECT * FROM user, client WHERE user.u_id = client.client_id AND user.status = 'Active' AND user.u_id = '$a'");
                while ($row = mysqli_fetch_array($result)) {
                ?>
                <div class="dashboard-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="text-gradient"><i class="fas fa-user-circle me-2"></i>My Profile</h4>
                        <button type="button" class="btn btn-edit" id="editProfileBtn">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </button>
                    </div>

                    <form action="save_user_edit_profile.php" method="post" enctype="multipart/form-data" class="profile-view-mode">
                        <div class="text-center">
                            <img src="/<?php echo $profile_image; ?>" alt="Profile Image" class="profile-image" id="profilePreview">
                            <div class="image-upload-container mt-3">
                                <label for="image" class="form-label">Update Profile Image</label>
                                <input type="file" class="form-control" name="image" id="image" accept="image/*">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_Name" class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_Name" id="first_Name" value="<?php echo $row["first_Name"]; ?>" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_Name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_Name" id="last_Name" value="<?php echo $row["last_Name"]; ?>" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="full_address" class="form-label">Full Address</label>
                                <input type="text" class="form-control" name="full_address" id="full_address" value="<?php echo $row["full_address"]; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" name="city" id="city" value="<?php echo $row["city"]; ?>" disabled>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="zip_code" class="form-label">Pin Code</label>
                                <input type="text" class="form-control" name="zip_code" id="zip_code" value="<?php echo $row["zip_code"]; ?>" disabled>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" name="contact_number" id="contact_number" value="<?php echo $row["contact_number"]; ?>" disabled>
                        </div>

                        <div class="profile-actions">
                            <button type="submit" class="btn btn-success" id="saveBtn" style="display: none;">Update Profile</button>
                            <button type="button" class="btn btn-cancel" id="cancelBtn" style="display: none;">Cancel</button>
                        </div>
                    </form>
                </div>
                <?php
                }
                mysqli_close($conn);
                ?>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <h5>All rights reserved © <?php echo date("Y"); ?></h5>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editBtn = document.getElementById('editProfileBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const saveBtn = document.getElementById('saveBtn');
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input');
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('profilePreview');
            
            // Store original values
            const originalValues = {};
            inputs.forEach(input => {
                if(input.type !== 'file') {
                    originalValues[input.name] = input.value;
                }
            });
            const originalImage = imagePreview.src;

            editBtn.addEventListener('click', () => {
                form.classList.add('profile-edit-mode');
                form.classList.remove('profile-view-mode');
                inputs.forEach(input => input.disabled = false);
                document.querySelector('.image-upload-container').style.display = 'block';
                editBtn.style.display = 'none';
                saveBtn.style.display = 'block';
                cancelBtn.style.display = 'block';
            });

            cancelBtn.addEventListener('click', () => {
                form.classList.remove('profile-edit-mode');
                form.classList.add('profile-view-mode');
                inputs.forEach(input => {
                    if(input.type !== 'file') {
                        input.disabled = true;
                        input.value = originalValues[input.name];
                    }
                });
                imagePreview.src = originalImage;
                imageInput.value = '';
                editBtn.style.display = 'block';
                saveBtn.style.display = 'none';
                cancelBtn.style.display = 'none';
            });

            imageInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Auto-hide success alert
            const successAlert = document.getElementById('successAlert');
            if (successAlert) {
                setTimeout(() => {
                    const alert = bootstrap.Alert.getOrCreateInstance(successAlert);
                    alert.close();
                }, 3000);
            }
        });
    </script>
</body>
</html>
<?php
} else {
    header('location:login.php?deactivate');
}