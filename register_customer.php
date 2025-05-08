<!-- filepath: e:\FUEL PROJECT\food\Fuel Project\front\register_customer.php -->
<?php
// Database connection details
$host = 'your_host';
$db = 'your_database';
$user = 'your_username';
$pass = 'your_password';
$port = 'your_port';

// Create a connection to the PostgreSQL database
$conn = pg_connect("host=$host dbname=$db user=$user password=$pass port=$port");

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Get form data
$name = $_POST['name'];
$mobile = $_POST['mobile'];
$email = $_POST['email'];
$location = $_POST['location'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

// Insert data into the database
$query = "INSERT INTO customers (name, mobile, email, location, password) VALUES ('$name', '$mobile', '$email', '$location', '$password')";
$result = pg_query($conn, $query);

// Check if the query was successful
if ($result) {
    echo "Registration successful!";
} else {
    echo "Error: " . pg_last_error($conn);
}

// Close the database connection
pg_close($conn);
?>