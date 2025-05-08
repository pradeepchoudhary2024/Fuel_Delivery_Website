<?php
// Database connection
$conn = pg_connect("host=localhost port=5432 dbname=project user=postgres password=postgres");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

// Fetch orders where status is "ordered"
$query = "SELECT * FROM orders WHERE status = 'ordered'";
$result = pg_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuel Station Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #6aa785; color: white; }
        .btn { background: #28a745; color: white; padding: 5px 10px; border: none; cursor: pointer; border-radius: 5px; }
        .btn:disabled { background: grey; cursor: not-allowed; }
    </style>
</head>
<body>

<h2>Fuel Station Dashboard</h2>
<table>
    <tr>
        <th>Order ID</th>
        <th>Address</th>
        <th>City</th>
        <th>Postal Code</th>
        <th>Product Quantity</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php while ($row = pg_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['address_line1'] . " " . $row['address_line2']; ?></td>
            <td><?php echo $row['city']; ?></td>
            <td><?php echo $row['postal_code']; ?></td>
            <td><?php echo $row['product_quantity']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td>
                <form action="assign_delivery.php" method="post">
                    <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="address_line1" value="<?php echo $row['address_line1']; ?>">
                    <input type="hidden" name="address_line2" value="<?php echo $row['address_line2']; ?>">
                    <button type="submit" class="btn">Delivery</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>

<?php pg_close($conn); ?>
