<?php
require_once "db_main.php";
require_once 'db_info.php';

$db = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);

if(isset($_GET['id'])) {
    $std_id = $_GET['id'];

    try {
        $pdo = $db->connect();
        $select_query = 'SELECT * FROM students WHERE id = :id';
        $prepared_statement = $pdo->prepare($select_query); 
        $prepared_statement->bindParam(':id', $std_id, PDO::PARAM_INT);
        $execution = $prepared_statement->execute();
        if ($execution) {
            $student = $prepared_statement->fetch(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if(isset($_POST['id'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $grade = $_POST['grade'];
    $track = $_POST['track'];
    try {
        $pdo = $db->connect(); 
        $update_data = array(
            'name' => $name,
            'email' => $email,
            'grade' => $grade,
            'track' => $track
        );
        $res = $db->update('students', $_POST['id'], $update_data);
        if ($res) {
            echo "<h3 style='color:green'>Student Updated Successfully</h3>";
        }
        header("Location: db_select.php");
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
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
    <h2>Edit Student</h2>
    <form method="POST">
        <div style="margin-bottom: 15px;">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($student['name']) ? $student['name'] : ''; ?>">
        </div>
        <div style="margin-bottom: 15px;">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($student['email']) ? $student['email'] : ''; ?>">
        </div>
        <div style="margin-bottom: 15px;">
            <label for="grade">Grade:</label>
            <input type="text" class="form-control" id="grade" name="grade" value="<?php echo isset($student['grade']) ? $student['grade'] : ''; ?>">
        </div>
        <div style="margin-bottom: 15px;">
            <label for="track">Track:</label>
            <input type="text" class="form-control" id="track" name="track" value="<?php echo isset($student['track']) ? $student['track'] : ''; ?>">
        </div>
        <input type="hidden" name="id" value="<?php echo isset($std_id) ? $std_id : ''; ?>">
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
<script>
$(document).ready(function(){
    function fetchStudentData() {
        $.ajax({
            type: 'GET',
            url: 'db_select.php?id=<?php echo $std_id; ?>',
            success: function(response){
                console.log("response values",response);
                var row = $(response).find('tbody tr').filter(function() {
                    return $(this).find('td:first').text() === '<?php echo $std_id; ?>'; 
                });
                var name = row.find('td:nth-child(2)').text();
                var email = row.find('td:nth-child(3)').text();
                var grade = row.find('td:nth-child(4)').text();
                var track = row.find('td:nth-child(5)').text();
                $('#name').val(name);
                $('#email').val(email);
                $('#grade').val(grade);
                $('#track').val(track);
            },
            error: function(xhr, status, error){
                console.error(xhr.responseText);
            }
        });
    }

    fetchStudentData();
    setInterval(fetchStudentData, 5000);
});


</script>
</body>
</html>
