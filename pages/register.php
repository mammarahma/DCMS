<?php
session_start();
include_once '../includes/db.php';
include_once '../includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    // User account fields
    $username = check_empty_input($_POST["username"]);
    $password = check_empty_input($_POST["password"]);
    $password_verify = check_empty_input($_POST["password_verify"]);
    
    // Patient fields
    $patient_sin = check_empty_input($_POST["patient_sin"]);
    $address = check_empty_input($_POST["address"]);
    $fullname = check_empty_input($_POST["fullname"]);
    $gender = check_empty_input($_POST["gender"]);
    $email = check_empty_input($_POST["email"]);
    $phone = check_empty_input($_POST["phone"]);
    $date_of_birth = check_empty_input($_POST["date_of_birth"]);
    $insurance = sanitize_input($_POST["insurance"]); // insurance not required

    // Validation checks
    if ($password != $password_verify) {
        $error = "Passwords do not match.";
    } elseif (!is_numeric($patient_sin) || strlen($patient_sin) != 9) {
        $error = "Invalid SIN. It must be 9 digits.";
    } elseif ($username != -1 && $password != -1 && $patient_sin != -1 && $address != -1 && $fullname != -1 && $gender != -1 && $email != -1 && $phone != -1 && $date_of_birth != -1) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into user_account table
        $stmt = $dbconn->prepare("INSERT INTO user_account (username, password, type) VALUES (:username, :password, 'Patient')");
        $stmt->execute(['username' => $username, 'password' => $hashed_password]);

        // Insert into patient_info table
        $stmt = $dbconn->prepare("INSERT INTO patient_info (sin_info, address, name, gender, email, phone, date_of_birth, insurance) VALUES (:sin_info, :address, :name, :gender, :email, :phone, :date_of_birth, :insurance)");
        $stmt->execute([
            'sin_info' => $patient_sin,
            'address' => $address,
            'name' => $fullname,
            'gender' => $gender,
            'email' => $email,
            'phone' => $phone,
            'date_of_birth' => $date_of_birth,
            'insurance' => $insurance
        ]);

        // Redirect to login page
        header('Location: index.php');
        exit();
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DCMS - Register</title>
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <h1>Dental Clinic Management System - Registration</h1>
    <form method="post" action="">
        <h3>User Account Information</h3>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="password_verify" placeholder="Verify Password" required>
        
        <h3>Patient Information</h3>
        <input type="text" name="patient_sin" placeholder="Social Insurance Number (SIN)" required>
        <input type="text" name="address" placeholder="Address" required>
        <input type="text" name="fullname" placeholder="Full Name" required>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="M">Male</option>
            <option value="F">Female</option>
            <option value="X">Other/Prefer not to say</option>
        </select>
        <input type="email" name="email" placeholder="Email" required>
        <input type="tel" name="phone" placeholder="Phone Number" required>
        <input type="date" name="date_of_birth" placeholder="Date of Birth" required>
        <input type="text" name="insurance" placeholder="Insurance (if applicable)">

        <button type="submit">Register</button>
    </form>
    <p class="error"><?php echo $error; ?></p>

    <script src="../js/script.js"></script>

</body>
</html>