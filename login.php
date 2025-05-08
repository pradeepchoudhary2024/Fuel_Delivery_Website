<?php
// Start session to store user session
session_start();

// Database connection
/*$host = "localhost"; // Change to your database host
$db = "users_db"; // Database name
$user = "postgres"; // PostgreSQL username
$password = "password"; // PostgreSQL password*/

// Flag to track if there is an error
$errorMessage = "";

// Connect to PostgreSQL
$conn= pg_connect("host=localhost port=5432 dbname=project user=postgres password=postgres") or die("Could not connect");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to fetch user from database
    $query = "SELECT * FROM users WHERE username = $1";
    $result = pg_query_params($conn, $query, array($username));

    if ($result) {
        $user = pg_fetch_assoc($result);

        // Check if user exists and verify password
        if ($user && password_verify($password, $user['password'])) {
            // Store user info in session
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            switch ($user['role']) {
                case 'customer':
                    header("Location: customer_page.html");
                    break;
                case 'fuelstation':
                    header("Location: fuel_stations.php");
                    break;
                case 'admin':
                    header("Location: admin.php");
                    break;
                case 'deliveryboy':
                    header("Location: deliveryboy.php");
                    break;
                default:
                    echo "Invalid role!";
                    break;
            }
            exit;
        } else {
            // Set error message
            $errorMessage = "Invalid username or password!";
        }
    } else {
        // Set error message if user not found
        $errorMessage = "User not found!";
    }
}

pg_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <script>
        // If there's an error message, display it using alert
        <?php if($errorMessage != ""): ?>
            alert("<?php echo $errorMessage; ?>");
        <?php endif; ?>
    </script>
</head>
<body >
    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <form method="POST" action="login.php">
                <div class="textbox">
                    <input type="text" id="username" name="username" required placeholder="Username">
                </div>
                <div class="textbox">
                    <input type="password" id="password" name="password" required placeholder="Password">
                </div>
                <button type="submit" class="btn">Login</button>
                <p class="signup">Don't have an account? <a href="signup.html">Sign Up</a></p>
            </form>
        </div>
    </div>
</body>
</html>

