<?php

$connection= pg_connect("host=localhost port=5432 dbname=project user=postgres password=postgres") or die("Could not connect");

if (!$connection) {
    die("Connection failed: " . pg_last_error());
}
?>
