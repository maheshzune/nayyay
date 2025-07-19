<?php
session_start();
if ($_SESSION['login'] == TRUE && $_SESSION['status'] == 'Active') {
    include("db_con/dbCon.php");
    $conn = connect();
    $lawyer_id = $_SESSION['lawyer_id'];

    // Fetch lawyer's current image for navbar
    $image_query = "SELECT image FROM lawyer WHERE lawyer_id = '$lawyer_id'";
    $image_result = mysqli_query($conn, $image_query);
    $lawyer = mysqli_fetch_assoc($image_result);
    $profile_image = !empty($lawyer['image']) ? $lawyer['image'] : 'images/upload/default-profile.png';
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
            --background-color: #F8F9FA;
            --gradient-start: rgba(0, 39, 77, 0.85);
            --gradient-end: rgba(0, 39, 77, 0.65);
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--background-color);
            padding-top: 80px;
        }
        .neo-navbar {
            background: linear-gradient(135deg, rgba(248, 249, 250, 0.95), rgba(248, 249, 250, 0.85)) !important;
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 15px 0;
            transition: background 0.3s ease;
        }
        .neo-navbar.fixed-top {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }
        .navbar-brand {
            color: var(--primary-color) !important;
            font-weight: 600;
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }
        .navbar-brand:hover {
            color: var(--accent-color) !important;
        }
        .nav-link {
            color: var(--primary-color);
            font-weight: 500;
            padding: 10px 15px;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: var(--accent-color);
        }
        .nav-link.active {
            color: var(--accent-color) !important;
            font-weight: 600;
            border-bottom: 2px solid var(--accent-color);
        }
        .gradient-btn {
            background-color: var(--primary-color);
            color: #FFFFFF;
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .gradient-btn:hover {
            background-color: var(--accent-color);
            color: var(--primary-color);
            transform: scale(1.05);
            text-decoration: none;
        }
        .navbar-toggler {
            border: none;
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%2300274D' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        #sidebar-wrapper {
            min-height: 100vh;
            margin-left: -15rem;
            transition: margin .25s ease-out;
            background: #FFFFFF;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }
        #sidebar-wrapper .sidebar-heading {
            background-color: var(--primary-color);
            color: #FFFFFF;
            padding: 15px;
            font-size: 1.2rem;
            font-weight: 600;
        }
        #sidebar-wrapper .list-group-item {
            color: var(--primary-color);
            font-weight: 500;
            border: none;
            padding: 15px 20px;
            transition: all 0.3s ease;
        }
        #sidebar-wrapper .list-group-item:hover {
            background-color: var(--background-color);
            color: var(--accent-color);
        }
        #sidebar-wrapper .list-group-item.active {
            background-color: var(--background-color);
            color: var(--accent-color);
            font-weight: 600;
        }
        #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
        }
        .bookingrqst {
            padding: 40px 20px;
            width: 100%;
        }
        .widget-header {
            background-color: var(--primary-color);
            color: #FFFFFF;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            display: flex;
            align-items: center;
        }
        .widget-header i {
            margin-right: 10px;
        }
        .widget-content {
            background: #FFFFFF;
            padding: 20px;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .form-group label {
            color: var(--primary-color);
            font-weight: 500;
        }
        .form-control {
            border: 1px solid var(--primary-color);
            border-radius: 5px;
            transition: border-color 0.3s ease;
        }
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 5px rgba(255, 215, 0, 0.5);
        }
        .btn-info {
            background-color: var(--primary-color);
            border: none;
            color: #FFFFFF;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn-info:hover {
            background-color: var(--accent-color);
            color: var(--primary-color);
        }
        .profile-image-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid var(--primary-color);
        }
        .profile-img-nav {
            width: 30px;
            height: 30px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--accent-color);
            margin-right: 8px;
        }
        footer {
            background-color: var(--primary-color);
            color: #FFFFFF;
            padding: 20px 0;
            margin-top: 40px;
            text-align: center;
        }
        footer h5 {
            margin: 0;
            font-size: 1rem;
        }
        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }
            #wrapper.toggled #sidebar-wrapper {
                margin-left: -15rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg neo-navbar fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Lawyer Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="lawyer_booking.php">Bookings</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="nav-link btn gradient-btn" href="#">
                            <img src="images/upload/<?php echo $profile_image; ?>" alt="Profile" class="profile-img-nav">
                            <?php echo $_SESSION['first_Name'] . ' ' . $_SESSION['last_Name']; ?>
                        </a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="nav-link btn gradient-btn" href="logout.php">Log Out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex" id="wrapper">
        <div class="bg-light border-right" id="sidebar-wrapper">
            <div class="sidebar-heading">My Profile</div>
            <div class="list-group list-group-flush">
                <a href="lawyer_dashboard.php" class="list-group-item list-group-item-action bg-light">Dashboard</a>
                <a href="lawyer_edit_profile.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'lawyer_edit_profile.php' ? 'active' : ''; ?>">
                    Edit Profile
                    <?php if (basename($_SERVER['PHP_SELF']) == 'lawyer_edit_profile.php'): ?>
                        <i class="fas fa-arrow-right float-end"></i>
                    <?php endif; ?>
                </a>
                <a href="lawyer_booking.php" class="list-group-item list-group-item-action bg-light">Booking Requests</a>
                <a href="lawyer_case_details.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'lawyer_case_details.php' ? 'active' : ''; ?>">
                    Case Details
                    <?php if (basename($_SERVER['PHP_SELF']) == 'lawyer_case_details.php'): ?>
                        <i class="fas fa-arrow-right float-end"></i>
                    <?php endif; ?>
                </a>
                <a href="lawyer_case_management.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'lawyer_case_management.php' ? 'active' : ''; ?>">
                    Case Management</a>
                <a href="update_password_admin.php" class="list-group-item list-group-item-action bg-light">Update Password</a>
            </div>
        </div>

        <section class="bookingrqst">
            <div class="container">
                <?php if (isset($_GET['ok'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                        <strong>Successfully!</strong> Updated your profile.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <div class="span7">
                    <div class="widget stacked widget-table action-table">
                        <div class="widget-header">
                            <i class="fas fa-th-list"></i>
                            <h3>Edit Profile</h3>
                        </div>
                        <div class="widget-content">
                            <?php
                            $a = $_SESSION['lawyer_id'];
                            $result = mysqli_query($conn, "SELECT * FROM user, lawyer WHERE user.u_id = lawyer.lawyer_id AND user.status = 'Active' AND user.u_id = '$a'");
                            while ($row = mysqli_fetch_array($result)) {
                            ?>
                            <form action="save_lawyer_edit_profile.php" method="post" enctype="multipart/form-data" id="validateForm" class="p-3">
                                <!-- Profile Image Display -->
                                <div class="profile-image-container">
                                    <img src="images/upload/<?php echo !empty($row['image']) ? $row['image'] : 'default-profile.png'; ?>" alt="Profile Image" class="profile-image">
                                </div>
                                <!-- Profile Image Upload -->
                                <div class="form-group">
                                    <label for="image">Update Profile Image</label>
                                    <input type="file" class="form-control" name="image" id="image" accept="image/*">
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="first_Name">First Name</label>
                                        <input type="text" class="form-control" id="first_Name" name="first_Name" value="<?php echo $row["first_Name"]; ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="last_Name">Last Name</label>
                                        <input type="text" class="form-control" id="last_Name" name="last_Name" value="<?php echo $row["last_Name"]; ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="contact_number">Contact Number</label>
                                    <input type="text" class="form-control" name="contact_number" id="contact_number" value="<?php echo $row["contact_Number"]; ?>" required>
                                </div>
                                <div class="form-row">
                                    <label><small>Put Your Last Education</small></label>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="university_College">University / College Name</label>
                                        <input type="text" class="form-control" id="university_College" name="university_College" value="<?php echo $row["university_College"]; ?>" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="degree">Degree</label>
                                        <select id="degree" name="degree" class="form-control" required>
                                            <option value="" <?php if (empty($row['degree'])) echo "selected"; ?>>Choose...</option>
                                            <option value="LLB" <?php if ($row['degree'] == 'LLB') echo "selected"; ?>>LLB</option>
                                            <option value="LLM" <?php if ($row['degree'] == 'LLM') echo "selected"; ?>>LLM</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="passing_year">Passing Year</label>
                                        <select id="passing_year" name="passing_year" class="form-control" required>
                                            <option value="" <?php if (empty($row['passing_year'])) echo "selected"; ?>>Choose...</option>
                                            <?php for ($year = 2000; $year <= 2023; $year++): ?>
                                                <option value="<?php echo $year; ?>" <?php if ($row['passing_year'] == $year) echo "selected"; ?>><?php echo $year; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label><small>Put Your Chamber Location</small></label>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="full_address">Full Address</label>
                                        <input type="text" class="form-control" id="full_address" name="full_address" value="<?php echo $row["full_address"]; ?>" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="city">City</label>
                                        <select id="city" name="city" class="form-control" required>
                                            <option value="" <?php if (empty($row['city'])) echo "selected"; ?>>Choose...</option>
                                            <option value="Dhaka" <?php if ($row['city'] == 'Dhaka') echo "selected"; ?>>Dhaka</option>
                                            <option value="Chittagong" <?php if ($row['city'] == 'Chittagong') echo "selected"; ?>>Chittagong</option>
                                            <option value="Sylhet" <?php if ($row['city'] == 'Sylhet') echo "selected"; ?>>Sylhet</option>
                                            <option value="Barishal" <?php if ($row['city'] == 'Barishal') echo "selected"; ?>>Barishal</option>
                                            <option value="Khulna" <?php if ($row['city'] == 'Khulna') echo "selected"; ?>>Khulna</option>
                                            <option value="Mymensingh" <?php if ($row['city'] == 'Mymensingh') echo "selected"; ?>>Mymensingh</option>
                                            <option value="Rajshahi" <?php if ($row['city'] == 'Rajshahi') echo "selected"; ?>>Rajshahi</option>
                                            <option value="Rangpur" <?php if ($row['city'] == 'Rangpur') echo "selected"; ?>>Rangpur</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="zip_code">Zip Code</label>
                                        <input type="text" class="form-control" id="zip_code" name="zip_code" value="<?php echo $row["zip_code"]; ?>" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="practise_Length">Length of Practice</label>
                                    <select id="practise_Length" name="practise_Length" class="form-control" required>
                                        <option value="" <?php if (empty($row['practise_Length'])) echo "selected"; ?>>Choose...</option>
                                        <option value="1-5 years" <?php if ($row['practise_Length'] == '1-5 years') echo "selected"; ?>>1-5 years</option>
                                        <option value="6-10 years" <?php if ($row['practise_Length'] == '6-10 years') echo "selected"; ?>>6-10 years</option>
                                        <option value="11-15 years" <?php if ($row['practise_Length'] == '11-15 years') echo "selected"; ?>>11-15 years</option>
                                        <option value="16-20 years" <?php if ($row['practise_Length'] == '16-20 years') echo "selected"; ?>>16-20 years</option>
                                        <option value="Most Senior" <?php if ($row['practise_Length'] == 'Most Senior') echo "selected"; ?>>Most Senior</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="speciality">My Speciality</label>
                                    <select id="speciality" name="speciality" class="form-control" required>
                                        <option value="" <?php if (empty($row['speciality'])) echo "selected"; ?>>Choose...</option>
                                        <option value="Criminal Law" <?php if ($row['speciality'] == 'Criminal Law') echo "selected"; ?>>Criminal Law</option>
                                        <option value="Civil Law" <?php if ($row['speciality'] == 'Civil Law') echo "selected"; ?>>Civil Law</option>
                                        <option value="Writ Jurisdiction" <?php if ($row['speciality'] == 'Writ Jurisdiction') echo "selected"; ?>>Writ Jurisdiction</option>
                                        <option value="Company Law" <?php if ($row['speciality'] == 'Company Law') echo "selected"; ?>>Company Law</option>
                                        <option value="Contract Law" <?php if ($row['speciality'] == 'Contract Law') echo "selected"; ?>>Contract Law</option>
                                        <option value="Commercial Law" <?php if ($row['speciality'] == 'Commercial Law') echo "selected"; ?>>Commercial Law</option>
                                        <option value="Construction Law" <?php if ($row['speciality'] == 'Construction Law') echo "selected"; ?>>Construction Law</option>
                                        <option value="IT Law" <?php if ($row['speciality'] == 'IT Law') echo "selected"; ?>>IT Law</option>
                                        <option value="Family Law" <?php if ($row['speciality'] == 'Family Law') echo "selected"; ?>>Family Law</option>
                                        <option value="Religious Law" <?php if ($row['speciality'] == 'Religious Law') echo "selected"; ?>>Religious Law</option>
                                        <option value="Investment Law" <?php if ($row['speciality'] == 'Investment Law') echo "selected"; ?>>Investment Law</option>
                                        <option value="Labour Law" <?php if ($row['speciality'] == 'Labour Law') echo "selected"; ?>>Labour Law</option>
                                        <option value="Property Law" <?php if ($row['speciality'] == 'Property Law') echo "selected"; ?>>Property Law</option>
                                        <option value="Taxation Law" <?php if ($row['speciality'] == 'Taxation Law') echo "selected"; ?>>Taxation Law</option>
                                    </select>
                                </div>
                                <button name="update" type="submit" class="btn btn-info">Update</button>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h5>All rights reserved <?php echo date("Y"); ?></h5>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var successAlert = document.getElementById('successAlert');
            if (successAlert) {
                setTimeout(function () {
                    var alert = bootstrap.Alert.getOrCreateInstance(successAlert);
                    alert.close();
                }, 3000);
            }
        });
    </script>
</body>
</html>
<?php
    mysqli_close($conn);
} else {
    header('location:login.php?deactivate');
}
?>