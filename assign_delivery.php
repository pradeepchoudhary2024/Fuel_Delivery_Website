<?php
$conn = pg_connect("host=localhost port=5432 dbname=project user=postgres password=postgres");


if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Get order details from form
$order_id = $_POST['order_id'] ?? null;
$address_line1 = $_POST['address_line1'] ?? '';
$address_line2 = $_POST['address_line2'] ?? '';

if (!$order_id) {
    die("Invalid order ID.");
}

// Find a free delivery boy
$boy_query = "SELECT * FROM delivery_boys WHERE status = 'free' LIMIT 1";
$boy_result = pg_query($conn, $boy_query);
$boy = pg_fetch_assoc($boy_result);

if (!$boy) {
    die("No free delivery boys available.");
}

$boyid = $boy['boyid'];

// Update order status to "out for delivery"
$update_order_query = "UPDATE orders SET status = 'out for delivery' WHERE id = $1";
pg_query_params($conn, $update_order_query, [$order_id]);

// Assign the delivery boy and update drop location
$update_boy_query = "UPDATE delivery_boys SET status = 'busy', drop_location = $1 WHERE boyid = $2";
pg_query_params($conn, $update_boy_query, ["$address_line1 $address_line2", $boyid]);

$msg="Order assigned to Delivery Boy: " . $boy['boy_name'];
echo "<script>alert('$msg'); window.location.href='customer_page.html';</script>";
pg_close($conn);
?>

