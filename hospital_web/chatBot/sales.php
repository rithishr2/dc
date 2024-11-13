<?php

// Turn on error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if form data is set and retrieve inputs
    if(isset($_POST['fname'], $_POST['uname'], $_POST['email'], $_POST['pnumber'], $_POST['passwords'], $_POST['cp'])) {
        $a = $_POST['fname'];
        $b = $_POST['uname'];
        $c = $_POST['email'];
        $d = $_POST['pnumber'];
        $e = $_POST['passwords'];
        $f = $_POST['cp'];
    } else {
        die("Some form fields are missing.");
    }

    // Validate the inputs
    if (empty($a) || empty($b) || empty($c) || empty($d) || empty($e) || empty($f)) {
        die("Please fill all fields.");
    }

    // Validate email format
    if (!filter_var($c, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Validate phone number (basic validation for 10 digits)
    if (!preg_match("/^[0-9]{10}$/", $d)) {
        die("Invalid phone number. Please enter a 10-digit number.");
    }

    // Passwords must match
    if ($e !== $f) {
        die("Passwords do not match.");
    }

    // Hash the password before storing it
    $hashed_password = password_hash($e, PASSWORD_DEFAULT);

    // Create connection
    $con = mysqli_connect("localhost", "root", "", "my");

    // Check for connection errors
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the SQL statement to avoid SQL injection
    $stmt = $con->prepare("INSERT INTO register (fname, uname, email, pnumber, passwords, cp) VALUES (?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($con));
    }

    // Bind parameters to the statement
    $stmt->bind_param("ssssss", $a, $b, $c, $d, $hashed_password, $f);

    // Execute the query
    if ($stmt->execute()) {
        echo "SUCCESSFULLY REGISTERED.";
    } else {
        // Display detailed error message from MySQL
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    mysqli_close($con);

} else {
    echo "Invalid request method. Please use POST.";
}
?>
