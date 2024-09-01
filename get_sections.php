<?php
require_once 'includes/dbconnection.php';

if (isset($_GET['subject_id'])) {
    $subject_id = $_GET['subject_id'];
    $sections = $dbh->prepare("SELECT * FROM sections WHERE subject_id = :subject_id");
    $sections->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
    $sections->execute();
    $section_list = $sections->fetchAll(PDO::FETCH_ASSOC);

    foreach ($section_list as $section) {
        echo '<div class="section-item">';
        echo '<h6>' . htmlspecialchars($section['section_name']) . '</h6>';
        echo '<button class="btn btn-link" onclick="viewStudents(' . $section['section_id'] . ')">View Students</button>';
        echo '<div id="students-' . $section['section_id'] . '" class="students-list"></div>';
        echo '</div>';
    }
}
?>
