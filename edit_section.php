<?php
session_start();
include('includes/dbconnection.php');

if (isset($_POST['section_id']) && isset($_POST['section_name'])) {
    $section_id = $_POST['section_id'];
    $section_name = $_POST['section_name'];

    $query = "UPDATE sections SET section_name = ? WHERE section_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $section_name, $section_id);

    if ($stmt->execute()) {
        echo "Section updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid input";
}
$conn->close();
?>
