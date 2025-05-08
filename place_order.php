<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $food_id = $_POST['food_id'];
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $customization = $_POST['customization'];
    $quantity = (int)$_POST['quantity'];

    // Get food price
    $food_query = "SELECT price FROM food WHERE id = $1";
    $food_result = pg_query_params($connection, $food_query, array($food_id));

    if ($food_result && pg_num_rows($food_result) > 0) {
        $food = pg_fetch_assoc($food_result);
        $total_price = $food['price'] * $quantity;

        // Insert into orders
        $query = "INSERT INTO orders (food_id, fullname, phone, email, address, customization, quantity, total_price, status)
                  VALUES ($1, $2, $3, $4, $5, $6, $7, $8, 'Ordered')";

        $result = pg_query_params($connection, $query, array(
            $food_id, $fullname, $phone, $email, $address, $customization, $quantity, $total_price
        ));

        if ($result) {
            echo "<script>alert('Order Placed Successfully!'); window.location.href='customer_index.php';</script>";
            exit;
        } else {
            echo "<script>alert('Failed to place order. Please try again.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Invalid Food ID.'); window.history.back();</script>";
    }
}
?>
