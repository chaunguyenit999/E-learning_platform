<?php
require "C:/xampp/htdocs/HUMG_A1_EXAM_chau/coures4u/config.php";
require abs_path('db/db_helper.php');
require abs_path('db/db_connect.php');
// CSS
require abs_path('student/layout/css_link.php');
$course_id = 1;
$CURRENT_USER_EMAIL = 'hieu2k3@gmail.com';
$sql = "SELECT * FROM course_rate WHERE course_id = '".$course_id."'";
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>
<body>

<div class="container text-center">

<?php
$result = executeResult($sql);
$average_star = 0;
$num_of_cmt = 0;
foreach ($result as $value){
    $average_star += $value['rate'];
    $num_of_cmt++;
}
if ($num_of_cmt != 0) {
    $average_star = round($average_star/$num_of_cmt,1);?>
    <div class="row">
        <div style = "text-align: left; font-size:20px" class="col-12">
            <?php echo($average_star); ?><span class='fa fa-star checked'></span> course rating
            <i class="fa fa-circle" style="font-size:9px"></i> <?php echo($num_of_cmt); ?> ratings
        </div>
</div>
<div class="row">
<?php
foreach ($result as $value) {
    $sql = "SELECT role_id,real_name,avatar,email FROM user WHERE user_id = '".$value['user_id']."'";
    $result = executeResult($sql,$onlyOne = true);
    $average_star += $value['rate'];
    ?>
    <div class="col-6" style = "border-style: groove;"}>
        <div class="row">
            <div class="col-2">
                <img src="<?php echo($result['avatar']) ?>" style="padding:0px;width:90px; height: 90px;border-radius:50%;width: 70px;border-style: solid;">
            </div>
            <div class="col-4">
                <div><?php echo($result['real_name']) ?></div>
                <div><?php
                for ($i=0; $i < $value['rate']; $i++) {
                    echo "<span class='fa fa-star checked'></span>";
                }
                for ($i=0; $i < 5-$value['rate']; $i++) {
                    echo "<span class='fa fa-star'></span>";
                }?>
                </div>
                <div><?php echo($value['created_date']) ?></div>
            </div>
            <div class="col-3" id = 'emty'></div>
            <script>

                window.addEventListener('resize', function() {
                    if (document.documentElement.clientWidth<=990) {
                        document.querySelector('.col-4').className = 'col-6';
                        document.querySelector('#btn').className = 'col-4';
                        document.querySelector('#emty').remove();
                    }
                });

            </script>
            <div class="col-3" id = "btn">
                    <div class="btn-group dropend">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            opt
                        </button>
                        <?php
                        if ($result['email'] == $CURRENT_USER_EMAIL) {?>

                        <ul class="dropdown-menu">
                            <li style = "padding:5px">
                                <!-- Button trigger modal -->
                             <input type ="submit" data-bs-toggle="modal" data-bs-target="#exampleModal" value = "sửa">

                            </li>
                            <form style = "margin-bottom: -3px;" method="post">
                            <input type="hidden" name="user_id" value = <?php echo($value ['user_id']) ?> >
                            <input type="hidden" name="course_id" value = <?php echo($value ['course_id']) ?> >
                            <li style = "padding:5px;border-top-style: solid"><input type="submit" name="xoa" value = "xóa"></li>
                            </form>
                        </ul>
                            <?php
                            if (isset($_POST["xoa"])) {
                                $user_id = $_POST['user_id'];
                                $course_id = $_POST['course_id'];
                                $sql = "DELETE FROM course_rate WHERE user_id = '$user_id' AND course_id = '$course_id'";
                                if ($conn->query($sql)===true) {?>
                                    <script>
                                        window.location.href = 'http://localhost:3000/coures4u/student/hien_thi_rate.php';
                                    </script>
                                <?php }}}
                        else {
                            print_r($result['role_id'])
                            ?>
                            <ul class="dropdown-menu">
                            <li style = "padding:5px">report</li>
                            </ul>
                        <?php }?>

                        </div>
            </div>

        </div>
        <div class="row" style = "text-align: left; padding:15px">
            <div class="col">
                <div><?php echo($value['content']) ?></div>

            </div>
        </div>
    </div>
<?php } ?>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?php
                $sql = "SELECT * FROM course_rate WHERE user_id = (SELECT user_id FROM user WHERE email = '".$CURRENT_USER_EMAIL."')";
                $result = executeResult($sql, $onlyOne = true);
            ?>
        <form method = "post">
            <div class="modal-body">
                <input type="hidden" name="user_id" value = "<?php echo ($result['user_id']); ?>" >
                <input type="hidden" name="course_id" value = "<?php echo ($result['course_id']); ?>" >
                <textarea name = "comment" cols = "57" rows="3" ><?php echo ($result['content']); ?></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name = "change_comment" class="btn btn-primary" >Save changes</button>
            </div>
        </form>
        <?php
        if (isset($_POST['change_comment']))
        {
            $comment = $_POST['comment'];
            $user_id = $_POST['user_id'];
            $course_id = $_POST['course_id'];
            $sql = "UPDATE course_rate SET content = '$comment' WHERE user_id = '$user_id' AND course_id = '$course_id'";
            if ($conn->query($sql)===true) {
                ?>
                <script>
                window.location.href = 'http://localhost:3000/coures4u/student/hien_thi_rate.php';
                </script>
                <?php }}?>
            </div>
        </div>
        </div>


<?php
}
?>


</body>
</html>
