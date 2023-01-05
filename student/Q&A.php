<?php
require "C:/xampp/htdocs/HUMG_A1_EXAM_chau/coures4u/config.php";
require abs_path('db/db_helper.php');
require abs_path('db/db_connect.php');
// CSS
require abs_path('student/layout/css_link.php');
$lecture_id = 1;
$CURRENT_USER_EMAIL = 'hieu2k3@gmail.com';

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        .row {
            display: grid;
            grid-template-columns: 90px 570px 60px;
            padding-left: 20px
        }

        ;

        .row_chill {
            margin-left: 90px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <?php $sql_u = "SELECT user_id FROM user WHERE email = '" . $CURRENT_USER_EMAIL . "'";
$u_rls = executeResult($sql_u, $onlyOne = true); ?>
    <form method="post">
        <textarea name="comment" id="" cols="90" rows="5" style="margin: 20px; border-radius:9px"></textarea>
        <input type="hidden" name="lecture_id" value=" <?= $lecture_id ?> ">
        <input type="hidden" name="user_id" value=" <?= $u_rls['user_id'] ?> ">
        <br><input style="margin-left: 640px;" type="submit" value="send question" name="cmt">
    </form>
    <?php
    if (isset($_POST['cmt'])) {

        $comment_content = $_POST['comment'];
        $lecture_id = $_POST['lecture_id'];
        $user_id = $_POST['user_id'];
        $sql = "INSERT INTO lecture_comment(comment_content, user_id, lecture_id) VALUES ('" . $comment_content . "','" . $user_id . "','" . $lecture_id . "')";
    if ($conn->query($sql)===true) {?>
        <script type="text/javascript">
            window.location.href = 'http://localhost:3000/coures4u/student/Q&A.php';
        </script>
    <?php
    }}
    ?>
    <?php
    $sql_cmt = "SELECT comment_id,comment_content,parent_comment_id,user_id,created_date FROM lecture_comment WHERE lecture_id = $lecture_id";
    $cmt_rls = executeResult($sql_cmt);
    $paren_comment_id = [];
    foreach ($cmt_rls as $value) {
        if ($value['parent_comment_id'] == null) {
            array_push($paren_comment_id, $value['comment_id']);
        }
    }

    foreach ($paren_comment_id as $value) {
        $sql_user = "SELECT user_id, real_name, avatar FROM user WHERE user_id = (SELECT user_id FROM lecture_comment WHERE comment_id =  '" . $value . "')";
        $user_rls = executeResult($sql_user, $onlyOne = true);
        $sql_cmt = "SELECT comment_id,comment_content,parent_comment_id,user_id,created_date FROM lecture_comment WHERE comment_id =  '" . $value . "'";
        $cmt_rls = executeResult($sql_cmt, $onlyOne = true);
    ?>

    <div class="row">
        <img src="<?= abs_url('assets/img/avatar/' . $user_rls['avatar']) ?>"
            style="padding:0px;width:90px; height: 90px;border-radius:50%;width: 70px;border-style: solid;">
        <div style="display: flex;align-items: center;">
            <?php echo ($user_rls['real_name']) ?>
        </div>

    </div>
    <div class="row">
        <div></div>
        <div style="height:90px;border-radius:50px;border-style: solid;margin-top:-20px">
            <p style="padding-top: 10px;">
                <?php echo ($cmt_rls['comment_content']) ?>
            </p>
        </div>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo($value);?>">
            reply
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal<?php echo($value);?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <textarea name="comment" id="" cols="50" rows="5"
                                style="margin: 20px; border-radius:9px"></textarea>
                            <input type="hidden" name="lecture_id" value=" <?= $lecture_id ?> ">
                            <input type="hidden" name="user_id" value=" <?= $u_rls['user_id'] ?> ">
                            <input type="hidden" name="cmt_id" value=" <?= $value ?> ">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button name = "reply" type="button submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
        $chill_sql_cmt = "SELECT comment_id,comment_content,parent_comment_id,user_id,created_date FROM lecture_comment WHERE parent_comment_id = '" . $value . "'";
        $chill_sql_rls = executeResult($chill_sql_cmt);
        foreach ($chill_sql_rls as $value) {
            $sql_user = "SELECT user_id, real_name, avatar FROM user WHERE user_id = '" . $value['user_id'] . "'";
            $user_rls = executeResult($sql_user, $onlyOne = true); ?>
    <div style="border-left: 5px solid grey;margin-left:90px;margin-top:10px;">
        <div class="row">
            <img src="<?= abs_url('assets/img/avatar/' . $user_rls['avatar']) ?>"
                style="padding:0px;width:90px; height: 90px;border-radius:50%;width: 70px;border-style: solid;">
            <div style="display: flex;align-items: center;">
                <?php echo ($user_rls['real_name']) ?>
            </div>

        </div>
        <div class="row">
            <div></div>
            <div style="height:90px;border-radius:50px;border-style: solid;margin-top:-20px">
                <p style="padding-top: 10px;">
                    <?php echo ($value['comment_content']) ?>
                </p>
            </div>
        </div>
    </div>
    <?php }
    } ?>
<?php
if (isset($_POST['reply'])) {
    $comment_content = $_POST['comment'];
    $lecture_id = $_POST['lecture_id'];
    $parent_comment_id = $_POST['cmt_id'];
    $user_id = $_POST['user_id'];
    $sql = "INSERT INTO lecture_comment(comment_content,parent_comment_id, user_id, lecture_id) VALUES ('" . $comment_content . "','" . $parent_comment_id . "','" . $user_id . "','" . $lecture_id . "')";
    if ($conn->query($sql)===true) {?>
        <script type="text/javascript">
            window.location.href = 'http://localhost:3000/coures4u/student/Q&A.php';
        </script>
    <?php
    }}
    ?>



</body>

</html>