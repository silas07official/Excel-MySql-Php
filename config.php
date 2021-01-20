<?php
$conn = new mysqli("localhost","root","","import_excel");
if ($conn->connect_error) {
    die("could not connect to database".$conn->connect_error);
}
?>