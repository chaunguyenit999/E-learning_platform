<!-- copy -->
<?php
require "C:/xampp/htdocs/HUMG_A1_EXAM_chau/coures4u/config.php";

require abs_path('db/db_helper.php');
require abs_path('db/db_connect.php');


    if (isset($_POST['form_submit'])) {
        $course_id = $_POST['course_id'];
        $rating_value = $_POST['rating_value'.$course_id];
        $content = $_POST['content'];
        $user_id = $_POST['user_id'];
        $sql = "SELECT * FROM course_rate WHERE user_id = '". $user_id ."' AND course_id = '" . $course_id . "'";
        $sql_result = executeResult($sql, $onlyOne = true);

        if ($sql_result != []) {
            $sql = "UPDATE course_rate SET content = '" . $content . "', rate= '" . $rating_value . "' WHERE user_id = '" . $user_id . "' AND course_id = '" . $course_id . "'";
            if ($conn->query($sql)===true) {
                header('location: http://localhost:3000/coures4u/student/my_learning.php');

            }

        } else {
            $sql = "INSERT INTO course_rate(course_id, content, rate, user_id) VALUES ('" . $course_id . "','" . $content . "','" . $rating_value . "','" . $user_id . "')";
        if ($conn->query($sql)===true) {
                header('location: http://localhost:3000/coures4u/student/my_learning.php');
            }
        }
    }
    elseif (isset($_POST['delete'])) {
        $course_id = $_POST['course_id'];
        $user_id = $_POST['user_id'];
        $sql = "DELETE FROM course_rate WHERE user_id = '" . $user_id . "' AND course_id = '" . $course_id . "'";
        $result = $conn->query($sql);
        if ($conn->query($sql)===true) {
            header('location: http://localhost:3000/coures4u/student/my_learning.php');
        }
    }
?>