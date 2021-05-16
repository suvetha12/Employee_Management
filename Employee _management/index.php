<?php error_reporting(0);
session_start();
include("connection.php"); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Empoyee Details</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<?php if(isset($_GET['up']))
{
	 $_SESSION['message']="Employee Details Updated";

}
?>

	<?php if (isset($_SESSION['message']) && $_SESSION['message']!='')
	{
		 ?>
		<div class="msg">
		<?php
				echo $_SESSION['message'];
				unset($_SESSION['message']);
			?>
		</div>
	<?php } ?>

	<?php $results = mysqli_query($conn, "SELECT * FROM users where del_status=0");
$rowcount=mysqli_num_rows($results);
//echo $rowcount;
	 ?>
<body>
	<table border="0" width="100%">
		<thead>
			<tr>

				<th colspan="9" class="msg">Employee Details</th>
			</tr>
		</thead>

	  <thead>
      <tr>

        <th colspan="9" align=right><a href="add_user.php" class="edit_btn">Add User</a></th>
      </tr>
    </thead>

    <thead>
			<tr>
				<th>Name</th>
				<th>Code</th>
				<th>Email</th>
				<th>Phone</th>
				<th>Picture</th>

				<th>DOB</th>

				<th colspan="2">Action</th>
						<th>Status</th>
			</tr>
		</thead>

		<?php
if($rowcount!='0')
{
		while ($row = mysqli_fetch_array($results)) { ?>
			<tr>
				<td><?php echo $row['name']; ?></td>
				<td><?php echo $row['code']; ?></td>
				<td><?php echo $row['email']; ?></td>
				<td><?php echo $row['phone']; ?></td>
				<td><img src="<?php echo $row['pic']; ?>" height="100" width="100"></td>
				<td><?php echo $row['dob']; ?></td>

				<td>
					<a href="edit_user.php?edit=<?php echo $row['id']; ?>" class="edit_btn" >Edit</a>
				</td>
				<td>
					<a class="del_btn" onclick="return confirm('Do you want to delete?');" href="php_code.php?del=<?php echo $row['id']; ?>">Delete</a>
				</td>
				<td>
<?php if($row['status']=='1')
{ ?>
					<a href="php_code.php?dact=<?php echo $row['id']; ?>" class="del_btn">De Activate</a>
<?php } else { ?>
<a href="php_code.php?act=<?php echo $row['id']; ?>" class="edit_btn">Activate</a>

<?php } ?>
				</td>
			</tr>
		<?php }
	}
	else
	{
?>
<tr>

	<th colspan="9" class="msg">No Employee found</th>
</tr>

<?php
} ?>
	</table>
	<script type="text/javascript">
	function Validate()
	{

	if(email!='')
	{
		alert("Enter Valid Email");
	}
}
</script>
