<?php
	session_start();
	include("connection.php"); ?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>Empoyee Details</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
<?php		$ppic = "";
	$name = "";
	$address = "";
	//$id = 0;
	$update = false;
  $uploadOk = 1;
	$err_msg=1;
	if (isset($_POST['submit'])) {

    $max_id = mysqli_query($conn, "SELECT MAX(id) as max_id FROM users");
		$row = mysqli_fetch_row($max_id);
    $highest_id = $row[0];
		//echo $row[0];
		$name = $_POST['name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];

		//$name = test_input($name);
		if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
		  $_SESSION['message'] = "Please Enter valid name";
			$err_msg=0;
		}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

		  $_SESSION['message'] = "Invalid email format";
			$err_msg=0;
		}

		//$code = $_POST['code'];
		$id1=$highest_id+1;
    $code='DCKAP'.$id1;
		$fileName = basename($_FILES["ppic"]["name"]);

		$targetFilePath = 'images/' . $fileName;
    $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
    $var1 = rand(1111,9999);
		$pic = $_FILES["ppic"]["name"];
	  $dst = "./images/".$var1.$pic;
		$dst_db = "images/".$var1.$pic;

		// Check file size
		if ($_FILES["ppic"]["size"] > 500000) {
		  echo "Sorry, your file is too large.";
		  $uploadOk = 0;
		}
		if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg"
		&& $fileType != "gif" ) {
		  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		  $uploadOk = 0;
		}


move_uploaded_file($_FILES["ppic"]["tmp_name"],$dst);

$dobm = $_POST['dobMonth'];
$dobd = $_POST['dobDay'];
$doby = $_POST['dobYear'];
		$dob = $dobd.'/'.$dobm.'/'.$doby;
	  $address = $_POST['address'];
if($err_msg==1)
{
		mysqli_query($conn, "INSERT INTO users (name, email,phone,code,pic,dob,address,status,del_status) VALUES ('$name','$email','$phone','$code','$dst_db','$dob', '$address','1','0')");
		$_SESSION['message'] = "Employee created successfully";
		header('location: index.php');
	}
	else
	{
	echo $_SESSION['message'];
		header('location: add_user.php');
	}
}



	if (isset($_GET['del'])) {
	$id = $_GET['del'];
	mysqli_query($conn, "DELETE from users WHERE id=$id");
	$_SESSION['message'] = "Employee deleted!";
	header('location: index.php');
}

if (isset($_GET['dact'])) {
$id = $_GET['dact'];
mysqli_query($conn, "UPDATE users SET status=0 WHERE id=$id");
$_SESSION['message'] = "Employee is Deactivated";
header('location: index.php');
}

if (isset($_GET['act'])) {
$id = $_GET['act'];
mysqli_query($conn, "UPDATE users SET status=1 WHERE id=$id");
$_SESSION['message'] = "Employee is Activated";
header('location: index.php');
}

?>
