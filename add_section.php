<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sectionName = $_POST['section_name'];

    try {
        $query = "INSERT INTO sections (section_name) VALUES (:section_name)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':section_name', $sectionName, PDO::PARAM_STR);
        $stmt->execute();

        // Get the last inserted ID
        $sectionId = $dbh->lastInsertId();

        $response = [
            'status' => 'success',
            'section_id' => $sectionId,
            'section_name' => htmlspecialchars($sectionName)
        ];
    } catch (PDOException $e) {
        $response = [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }

    echo json_encode($response);
}
?>
