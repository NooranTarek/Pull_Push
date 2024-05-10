<?php
require_once "db_main.php";
require_once 'db_info.php';

$std_id = $_GET['id'];

try {
    $db = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);            
    $con = $db->connect();
    $delete_query = "DELETE FROM students WHERE id = :id";
    $prepared_statement = $con->prepare($delete_query);
    $prepared_statement->bindParam(':id', $std_id, PDO::PARAM_INT);
    $res = $prepared_statement->execute();
    if ($prepared_statement->rowCount() == 1) {
        echo "<h3 style='color:green'>Student Deleted Succcessfully</h3>";
    }
    header("Location:db_select.php");
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>

<!-- require_once "db_main.php";
require_once 'db_info.php';

try {
    if(isset($_GET['id'])) {
        $std_id = $_GET['id'];
        $db = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
        $res = $db->delete('os_students', $std_id);
        if ($res == 1) {
            echo "<h3 style='color:green'>Student Deleted Successfully</h3>";
        } else {
            echo "<h3 style='color:red'>Failed to delete student</h3>";
        }
        header("Location: db_select.php");
        exit; 
    } else {
        echo "<h3 style='color:red'>Error: No ID parameter provided</h3>";
    }
} catch (PDOException $e) {
    echo "<h3 style='color:red'>Error: " . $e->getMessage() . "</h3>";
} -->
