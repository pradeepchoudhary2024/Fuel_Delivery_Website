<?php
// Database connection
$conn = pg_connect("host=localhost port=5432 dbname=project user=postgres password=postgres");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Capture form data
$address_line1 = $_POST['address_line1'] ?? '';
$address_line2 = $_POST['address_line2'] ?? '';
$city = $_POST['city'] ?? '';
$postal_code = $_POST['postal_code'] ?? '';
$state = $_POST['state'] ?? '';
$country = $_POST['country'] ?? '';
$asset_type = $_POST['asset_type'] ?? '';
$product_quantity = $_POST['quantity'] ?? 0;

// Capture asset details based on asset type
$asset_name = '';
$tank_capacity = '';
$identification_no = '';

if ($asset_type === 'genset') {
    $asset_name = $_POST['genset_name'] ?? '';
    $tank_capacity = $_POST['genset_tank_capacity'] ?? '';
    $identification_no = $_POST['genset_identification_no'] ?? '';
} elseif ($asset_type === 'tank') {
    $asset_name = $_POST['tank_name'] ?? '';
    $tank_capacity = $_POST['tank_tank_capacity'] ?? '';
    $identification_no = $_POST['tank_identification_no'] ?? '';
} elseif ($asset_type === 'diesel') {
    $asset_name = $_POST['diesel_name'] ?? '';
    $tank_capacity = $_POST['diesel_tank_capacity'] ?? '';
    $identification_no = $_POST['diesel_identification_no'] ?? '';
}

// Insert data into orders table
$query = "INSERT INTO orders (address_line1, address_line2, city, postal_code, state, country, asset_type, asset_name, tank_capacity, identification_no, product_quantity, status)
          VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, 'ordered')";

$result = pg_query_params($conn, $query, [
    $address_line1, $address_line2, $city, $postal_code, $state, $country,
    $asset_type, $asset_name, $tank_capacity, $identification_no, $product_quantity
]);

if ($result) {
    echo "<script>alert('Order Placed Successfully!'); window.location.href='customer_page.html';</script>";
} else {
    echo "Error placing order: " . pg_last_error($conn);
}

// Close connection
pg_close($conn);
?>
