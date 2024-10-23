<!DOCTYPE html>
<html lang = "en">
<head>  
        <meta charset = "UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Registration Page</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="Style.css">
</head>
<body>
	<div class="container">
		<?php
			if(isset($_POST["Submit"])){
				$firstname = $_POST["fname"];
				$lastname = $_POST["lname"];
				$age = $_POST["age"];
				$email = $_POST["email"];
				$gender = $_POST["gender"];
				$password = $_POST["password"];
				$rpass = $_POST["rpass"];
				/*Password Hashing*/
				$passwordhash = password_hash($password, PASSWORD_DEFAULT);

				error_reporting(E_ALL);
				ini_set('display_errors', 1);

				$error = array();

				if(strlen($password) < 8){
					array_push($error, "Password length must be at least 8 characters long");
				}
				if($rpass !== $password){
					array_push($error, "Password does not match!");
				}
				/* If a field is left empty, error*/
				if(empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($rpass || empty($age) || empty($gender))){	
					array_push($error, "Please Complete all information");		
				}
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
					array_push($error, "Email is not valid");
				}
				/*Initialize db file once*/
				require_once "db.php";
				/*Validates that email is not used/already existing*/
				$sql = "SELECT * FROM users WHERE email = '$email'";
				$result = mysqli_query($conn, $sql);
				$rowCount = mysqli_num_rows($result);
				if($rowCount > 0){
					array_push($error, "Email already exists");
				}
				if(count($error) > 0){
					foreach($error as $err){
						echo "<div class='alert alert-danger'>$err</div>";
					}
				}
				else{/*Insert data into db*/
					$sql = "INSERT INTO users (fname, lname, age, gender, email, password) VALUES (?,?,?,?,?,?)";
					$stmt = mysqli_stmt_init($conn);
					$prep_stmt = mysqli_stmt_prepare($stmt, $sql);
					if($prep_stmt){
						mysqli_stmt_bind_param($stmt,"ssssss", $firstname, $lastname, $age, $gender, $email, $passwordhash);
						mysqli_stmt_execute($stmt);
						echo "<div class='alert alert-success'> Registration Successful!</div>";
					}
					else{
						die("Registration Unsuccessful");
					}
				}
				
			}
		?>
                <form action="Registration.php" method="POST">
                        <div class="form-group">
                                <input type="text" class="form-control" name="fname" placeholder="First Name" required>
                        </div>
                        <div class="form-group">
                                <input type="text" class="form-control" name="lname" placeholder="Last Name" required>
			</div>
			<div class="form-group">
				<input type="number" class="form-control" name="age" placeholder="Age" required>
			</div>
                        <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Email" required>
			</div>
			<div class="form-group">
				<label for="gender"> 	Gender:</label>
				<select name="gender" id="gender" class="form-control" required>
				<option value="male">Male</option>
				<option value="female">Female</option>
				</select>
			</div>
                        <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                                <input type="password" class="form-control" name="rpass" placeholder="Confirm Password" required>
                        </div>
                        <div class="form-btn">
                                <input type="submit" class="btn btn-primary" value="Register" name="Submit">
                        </div>
</body>
</html>

