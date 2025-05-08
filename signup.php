<?php
// Database connection details
/*host = 'host=localhost port=5432'; // Database host
$dbname = 'user_system'; // Database name
$username = 'postgres'; // Database username
$password = 'postgres'; // Database password*/

// Connect to PostgreSQL using pg_connect
//$conn = pg_connect("host=$host dbname=$dbname user=$username password=$password");
$conn= pg_connect("host=localhost port=5432 dbname=project user=postgres password=postgres") or die("Could not connect");
if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Process the form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $role = $_POST['role'];

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        die('All fields are required.');
    }

    if ($password !== $confirm_password) {
        die('Passwords do not match.');
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL statement to insert user into the database
    $query = "INSERT INTO users (username, email, password, role) 
              VALUES ($1, $2, $3, $4)";
    
    // Using pg_query_params to safely execute the query with parameters
    $result = pg_query_params($conn, $query, array($username, $email, $hashed_password, $role));

    if ($result) {
        echo "Sign-up successful! You can now <a href='login.php'>login</a>.";
    } else {
        echo "Error: " . pg_last_error($conn);
    }
    
    // Close the database connection
    pg_close($conn);
}
?>
