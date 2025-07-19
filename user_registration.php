<!-- header section -->
<header>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

	<script>
		function MySuccessFn() {
			swal({
				title: "Registration Successful!",
				text: "You can now log in.",
				type: "success",
				showConfirmButton: true
			}, function () {
				window.location = 'http://localhost/lawyermanagementsystem/login.php';
			});
		}

		function MyCheckFn() {
			swal({
				title: "Email already exists!",
				text: "Please try again with a different email.",
				type: "warning",
				showConfirmButton: true
			}, function () {
				window.location = 'http://localhost/lawyermanagementsystem/user_register.php';
			});
		}
	</script>
</header>

<?php
	include_once 'db_con/dbCon.php';

	$okFlag = TRUE;

	if ($okFlag) {
		function generateRandomString() {
			$characters = '0123456789';
			$length = 5;
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
		}

		if (isset($_FILES["fileToUpload"]["name"]) && $_FILES["fileToUpload"]["name"] != '') {
			$target_dir = "images/upload/";
			$newName = date('YmdHis_') . basename($_FILES["fileToUpload"]["name"]);
			$target_file = $target_dir . $newName;
			$uploadOk = 1;
			$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

			$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
			if ($check === false) {
				$uploadOk = 0;
			}

			if (file_exists($target_file)) {
				$uploadOk = 0;
			}

			if ($_FILES["fileToUpload"]["size"] > 5000000) {
				$uploadOk = 0;
			}

			if (!in_array(strtolower($imageFileType), ['jpg', 'jpeg', 'png', 'gif'])) {
				$uploadOk = 0;
			}

			if ($uploadOk == 1) {
				if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					$uploadOk = 0;
				}
			}

			if ($uploadOk == 0) {
				$newName = ''; // default image or handle error
			}
		} else {
			$newName = $_POST['image'] ?? '';
		}

		$u_id = uniqid('Client');
		$first_Name = $_REQUEST['first_Name'];
		$last_Name = $_REQUEST['last_Name'];
		$email = $_REQUEST['email'];
		$contact_number = $_REQUEST['contact_number'];
		$full_address = $_REQUEST['full_address'];
		$city = $_REQUEST['city'];
		$zip_code = $_REQUEST['zip_code'];
		$password = generateRandomString();

		$conn = connect();

		// Check for duplicate email
		$duplicate = mysqli_query($conn, "SELECT * FROM user WHERE email='$email'");
		if (mysqli_num_rows($duplicate) > 0) {
			echo "<script>MyCheckFn();</script>";
		} else {
			$sql = "INSERT INTO `user` (`u_id`, `first_Name`, `last_Name`, `email`, `password`, `status`, `role`) 
					VALUES ('$u_id', '$first_Name', '$last_Name', '$email', '$password', 'Active', 'User')";
			
			$result = mysqli_query($conn, $sql);
			if ($result) {
				$sql2 = "INSERT INTO `client` (`client_id`, `contact_number`, `full_address`, `city`, `zip_code`, `image`) 
						VALUES ('$u_id', '$contact_number', '$full_address', '$city', '$zip_code', '$newName')";
				
				$result2 = mysqli_query($conn, $sql2);
				if ($result2) {
					echo "<script>MySuccessFn();</script>";
				}
			}
		}
	}
?>
