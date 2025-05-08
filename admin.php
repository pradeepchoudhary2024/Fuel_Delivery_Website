<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// PostgreSQL connection
$conn = pg_connect("host=localhost dbname=project user=postgres password=postgres");
if (!$conn) {
    die("Failed to connect to PostgreSQL");
}

// Handle fuelstation delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_fuelstation'])) {
    $fsid = intval($_POST['fsid']);

    // 1. Delete related orders
    pg_query_params($conn, "DELETE FROM orders WHERE fsid = $1", array($fsid));

    // 2. Delete fuel station
    $delete = pg_query_params($conn, "DELETE FROM fuelstation WHERE fsid = $1", array($fsid));

    if ($delete) {
        header("Location: " . $_SERVER['PHP_SELF']); // Refresh the page
        exit;
    } else {
        echo "<script>alert('Failed to delete fuel station');</script>";
    }
}

// Fetch fuel stations
$fuelstations = pg_query($conn, "SELECT * FROM fuelstation");

// Fetch users
$users = pg_query($conn, "SELECT id, username, email FROM users where role='customer'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        h1, h2 { color: #333; }
        .section { margin-bottom: 40px; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #eee; }
        button { background: #d9534f; color: white; padding: 6px 10px; border: none; cursor: pointer; }
        button:hover { background: #c9302c; }
    </style>
</head>
<body>

    <h1>Admin Dashboard</h1>

    <!-- Section 1: Fuel Stations -->
    <div class="section">
        <h2>Fuel Stations</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Address</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Username</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = pg_fetch_assoc($fuelstations)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['fsid']) ?></td>
                    <td><?= htmlspecialchars($row['fsname']) ?></td>
                    <td><?= htmlspecialchars($row['address']) ?></td>
                    <td><?= htmlspecialchars($row['contact']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['profile_status']) ?></td>
                    <td>
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this fuel station and all related orders?');">
                            <input type="hidden" name="fsid" value="<?= $row['fsid'] ?>">
                            <button type="submit" name="delete_fuelstation">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- Section 2: Users -->
    <div class="section">
        <h2>Users</h2>
        <table>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
            </tr>
            <?php while ($row = pg_fetch_assoc($users)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>
