<?php
include 'db.php';
session_start(); // Start the session

// Check if the user is logged in and retrieve their username
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Please log in first!'); window.location.href='login.php';</script>";
    exit();
}

$username = $_SESSION['username']; // Retrieve the logged-in username

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = pg_escape_string($connection, $_POST['name']);
    $address = pg_escape_string($connection, $_POST['address']);
    $contact = pg_escape_string($connection, $_POST['contact']);
    $email = pg_escape_string($connection, $_POST['email']);
   
    // The username is retrieved from the session, so it's not taken from form input
    $username = pg_escape_string($connection, $username);

    // Generate a unique 4-digit Restaurant ID
    do {
        $fsid = rand(1000, 9999);
        $checkQuery = "SELECT fsid FROM fuelstation WHERE fsid = $fsid";
        $checkResult = pg_query($connection, $checkQuery);
    } while (pg_num_rows($checkResult) > 0);

    // Insert into database
    $query = "INSERT INTO fuelstation (fsid, fsname, address, contact, email, username, profile_status)
              VALUES ($fsid, '$name', '$address', '$contact', '$email', '$username', 'completed')";

    $result = pg_query($connection, $query);

    if ($result) {
        echo "<script>alert('Registration successful!'); window.location.href='fuel_stations.php';</script>";
    } else {
        echo "Error: " . pg_last_error($connection); // Display PostgreSQL error
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            width: 400px;
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px #ccc;
            border-radius: 5px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            width: 100%;
            background: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            font-size: 16px;
        }
        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Register Fuel Station</h2>
    <form action="register.php" method="POST">
        <label for="name">Fuel Station Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required>

        <label for="contact">Contact Number:</label>
        <input type="text" id="contact" name="contact" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <!-- Hidden field for username (auto-filled from session) -->
        <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">

        <button type="submit" class="btn">Register</button>
    </form>
</div>

</body>
</html>

