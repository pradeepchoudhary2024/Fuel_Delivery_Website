<?php
include 'db.php';
session_start();

$username =$_SESSION['username']; // Replace with session username if implementing login

// Fetch restaurant details
$query = "SELECT * FROM fuelstaion WHERE username = '$username'";
$result = pg_query($connection, $query);
$restaurant = pg_fetch_assoc($result);

if (!$restaurant) {
    echo "<script>alert('FuelStaion not found. Please register.'); window.location.href='register.php';</script>";
    exit();
}

$restid = $restaurant['fsid'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
        }
        .navbar {
            background: #343a40;
            padding: 15px;
            text-align: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 10px;
            padding: 10px 15px;
            border-radius: 5px;
            background: #007bff;
        }
        .container {
            width: 80%;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px #ccc;
            border-radius: 5px;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }
        img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
        .delete-btn {
            background: red;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }
        .delete-btn:hover {
            background: darkred;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <!--<a href="add_food.php">Add Food</a>-->
        <a href="view_orders.php">View Orders</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <?php  echo "<div class='container'>";
    	echo "<h2>Welcome, " . $restaurant['fsname'] . "!</h2>";
    	echo "<p><strong>Address:</strong> " . $restaurant['address'] . "</p>";
    	echo "<p><strong>Contact:</strong> " . $restaurant['contact'] . "</p>";
    	echo "<p><strong>Email:</strong> " . $restaurant['email'] . "</p>";
    	echo "<p><strong>Fuel Station ID:</strong> " . $restaurant['fsid'] . "</p>";
    	echo "</div>";?>
        
        <!-- Food Table
        <h3>Your Food Items</h3>
        <table>
            <tr>
                <th>Image</th>
                <th>Food Name</th>
                <th>Price</th>
                <th>Action</th>
            </tr>

            <?php
            $foodQuery = "SELECT * FROM food WHERE restid = $restid";
            $foodResult = pg_query($connection, $foodQuery);

            while ($food = pg_fetch_assoc($foodResult)) {
                echo "<tr>
                        <td><img src='uploads/". htmlspecialchars($food['imgname']) . "' alt='Food Image'></td>
                        <td>" . htmlspecialchars($food['name']) . "</td>
                        <td>$" . number_format($food['price'], 2) . "</td>
                        <td>
                            <form method='POST' action='delete_food.php'>
                                <input type='hidden' name='food_id' value='" . $food['id'] . "'>
                                <button type='submit' class='delete-btn'>Delete</button>
                            </form>
                        </td>
                    </tr>";
            }
            ?>
        </table>
    </div>-->

</body>
</html>
