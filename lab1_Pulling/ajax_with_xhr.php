<?php
require_once "db_main.php";
require_once 'db_info.php';

try {
    if(isset($_POST['name'], $_POST['email'], $_POST['track'], $_POST['grade'])) {
        $std_name = $_POST['name'];
        $std_email = $_POST['email'];
        $std_track = $_POST['track'];
        $std_grade = $_POST['grade'];
        $db = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);            
        $con = $db->connect();
        if (!$con) {
            die("Error: Unable to connect to the database");
        }
        $table = 'students';
        $data = array(
            'name' => $std_name,
            'email' => $std_email,
            'track' => $std_track,
            'grade' => $std_grade
        );
        $result = $db->insert($table, $data);
        if ($result > 0){
            echo "Student added successfully!";
            exit; 
        }
    } 
} catch (PDOException $error){
    echo $error->getMessage();
}
?>



<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>
<body>
<div style="max-width: 400px; margin: 0 auto;">
    <h2>Add Student</h2>
    <form id="addStudentForm">
        <div style="margin-bottom: 15px;">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="">
        </div>
        <div style="margin-bottom: 15px;">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="">
        </div>
        <div style="margin-bottom: 15px;">
            <label for="grade">Grade:</label>
            <input type="text" class="form-control" id="grade" name="grade" value="">
        </div>
        <div style="margin-bottom: 15px;">
            <label for="track">Track:</label>
            <input type="text" class="form-control" id="track" name="track" value="">
        </div>
        <button type="submit" class="btn btn-primary">Add</button>
    </form>
</div>

<div id="content"></div>

<script>
$(document).ready(function(){
    $('#addStudentForm').submit(function(e){
        e.preventDefault(); 
        var formData = $(this).serialize(); 
        $.ajax({
            type: 'POST',
            url: 'db_insert.php',
            data: formData,
            success: function(response){
                alert(response); 
                getUsersData();
            },
            error: function(xhr, status, error){
                console.error(xhr.responseText); 
            }
        });
    });

    function getUsersData() {
        var content_div = document.getElementById('content');
        var xhr_element = new XMLHttpRequest();

        xhr_element.open('GET', 'db_select.php'); 
        xhr_element.send(); 
        console.log(xhr_element);

        xhr_element.onreadystatechange = function(){
            if(xhr_element.status === 200 && xhr_element.readyState === 4){
                var data = xhr_element.responseText;
                content_div.innerHTML += `<pre>${data} </pre>`;
            } else if(xhr_element.status === 404 && xhr_element.readyState === 4){
                content_div.innerHTML += `<h4 style='color:red'>Connection failed</h4>`;
            }
        }
    }
});
</script>

</body>
</html>
