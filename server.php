<?php 
session_start();

//initializing variables
$username="";
$email="";

$errors=array(); //array that will be receiving error

//connect to db
$db=mysqli_connect('localhost','root','','practise') or die("Could not connect to database");

//Register Users
//mysqli_real_escape_string explanation bellow:
//----Required. The string to be escaped. Characters encoded are NUL (ASCII 0), \n, \r, \, ', ", and Control-Z.
$username = mysqli_real_escape_string($db,$_POST['username']);
$email = mysqli_real_escape_string($db,$_POST['email']);
$password_1 = mysqli_real_escape_string($db,$_POST['password_1']);
$password_2 = mysqli_real_escape_string($db,$_POST['password_2']);

//form validation
if(empty($username)){
	array_push($errors,"Username is required");
}
if(empty($email)){
	array_push($errors,"Email is required");
}
if(empty($password_1)){
	array_push($errors,"Password is required");
}
if($password_1!=$password_2){
	array_push($erros, "Passwords do not match");
}

//Check db for existing user with same username
$user_check_query="SELECT * FROM user WHERE username='$username' or email = '$email' LIMIT 1";
$results=mysqli_query($db,$user_check_query);
$user=mysqli_fetch_assoc($result);

if($user['username']===$username){
	array_push($errors,"Username already exists");
}
if($user['email']===$email){
	array_push($errors, "Email already exists");
}

//Finally, register user if there are no errors in the form
if(count($errors)==0){
	$password=md5(password_1);//encrypt the password before saving in the database
	print $password;
	$query="INSERT INTO user(suername, email, password)
	VALUES('$username','$email','$password')";
	mysqli_query($db,$query);
	$_SESSION['username']=$username;
	$_SESSION['success']="You are now logged in";
	header('location: index.php');
}

//Login User
if(isset($_POST['login_user'])){
	$username=mysqli_real_escape_string($db,$_POST['username']);
	$password=mysqli_real_escape_string($db,$_POST['password_1']);

	if(empty($username)){
		array_push($errors,"Username is required");
	}
	if(empty($password)){
		array_push($errors, "Pasword is required");
	}
	if(count($errors)==0){
		$password=md5($password_1);
		$query="SELECT * FROM user WHERE username='$username' AND password='$password'";
		$resuts=mysqli_query($db,$query);	
		
		if(mysqli_num_rows($results)){
			$_SESSION['username']=$username;
			$_SESSION['success']="Logged in Successfully";
			header('location: index.php');
		}else{
			array_push($errors, "Wrong username/password combination. Please try again");

		}
	}
}
?>
