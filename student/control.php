<!-- Load helper files -->
<?php
require "C:/xampp/htdocs/HUMG_A1_EXAM_chau/coures4u/config.php";

require abs_path('db/db_helper.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses4U | Home</title>
    <!-- Import css & script link -->
    <?php require abs_path('student/layout/css_link.php'); ?>

    <style>
        .dropend button {
            margin-left: auto;
            margin-right: auto;
        }

        .course-infor-more {
            overflow-y: scroll;
            width: 350px !important;
            height: 400px
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <header>
        <!-- NAVBAR -->
        <?php require abs_path('student/layout/nav.php'); ?>

        <!-- LINK BAR -->
        <ul class="link-bar">
            <?php
            $sql = "SELECT * FROM category ORDER BY category_id ASC";
            $categories = executeResult($sql);
            foreach ($categories as $category) { ?>
                <li>
                    <a href=<?= abs_url('student/course_filter.php?category_id=' . $category['category_id']) ?>><?= $category['category_name'] ?></a>
                    <ul class="sub-link-bar shadow-sm">
                        <?php
                        $sql = "SELECT * FROM subcategory WHERE category_id = $category[category_id] ORDER BY subcategory_id ASC";
                        $subcategories = executeResult($sql);
                        foreach ($subcategories as $subcategory) { ?>
                            <li><a href=<?= abs_url('student/course_filter.php?subcategory_id=' . $subcategory['subcategory_id']) ?>><?= $subcategory['subcategory_name'] ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
        </ul>

        <!-- SLIDER -->
        <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="10000">
                    <img src="<?= abs_url('assets/img/slider/slider.jpg') ?>" class="d-block w-100" alt="slider" style="height: 100%">

                </div>
                <div class="carousel-item" data-bs-interval="2000">
                    <img src="<?= abs_url('assets/img/slider/slider.jpg') ?>" class="d-block w-100" alt="slider">
                </div>
                <div class="carousel-item">
                    <img src="<?= abs_url('assets/img/slider/slider.jpg') ?>" class="d-block w-100" alt="slider">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </header>

    <?php
$cmt_id = [];
$cmt = [];
$lecture = [];
$chapter = [];
$course_id = [];
$course_name = [];
$user_id = [];
$user_name = [];
$sql_cmt = "SELECT comment_content,lecture_id,comment_id FROM lecture_comment WHERE user_id = (SELECT user_id FROM user WHERE email = '$CURRENT_USER_EMAIL')";
$cmt_rsl = executeResult($sql_cmt);
foreach ($cmt_rsl as $value) {
    array_push($cmt, $value["comment_content"]);
    array_push($lecture, $value["lecture_id"]);
    array_push($cmt_id, $value["comment_id"]);
}
foreach ($lecture as $value) {
    $sql_lecture = "SELECT chapter_id FROM lecture WHERE lecture_id = '$value'";
    $lecture_rsl = executeResult($sql_lecture, $onlyOne = true);
    array_push($chapter, $lecture_rsl["chapter_id"]);
}
foreach ($chapter as $value) {
    $sql_chapter = "SELECT course_id FROM chapter WHERE chapter_id = '$value'";
    $chapter_rsl = executeResult($sql_chapter, $onlyOne = true);

    array_push($course_id, $chapter_rsl["course_id"]);
}
foreach ($course_id as $value) {
    $sql_course = "SELECT course_name,user_id FROM course WHERE course_id = $value";
    $course_rsl = executeResult($sql_course, $onlyOne = true);
    array_push($course_name, $course_rsl["course_name"]);
    array_push($user_id, $course_rsl["user_id"]);
}
foreach ($user_id as $value) {
    $sql_user = "SELECT real_name FROM user WHERE user_id = '$value'";
    $user_rsl = executeResult($sql_user, $onlyOne = true);
    array_push($user_name, $user_rsl["real_name"]);
}
// $sql_rate = "SELECT rate,contnet FROM comment_rate WHERE user_id = '$CURRENT_USER_EMAIL'";
// $rate = [];


?>
<form action="db/delete_question.php" method="get">

    <table width="100%px">
        <tr>
            <td colspan="6" style="border: 1px solid black;text-align:center;font-size:30px">Q&A</td>
        </tr>
        <tr>
            <th style="border: 1px solid black;">course name</th>
            <th style="border: 1px solid black;">teacher</th>
            <th style="border: 1px solid black;">chapter</th>
            <th style="border: 1px solid black;">lecture</th>
            <th style="border: 1px solid black;">question</th>
            <th style="border: 1px solid black;">opt</th>
        </tr>
        <?php
        for ($i = 0; $i < count($cmt); $i++) { ?>
        <tr>
            <th style="border: 1px solid black;">
                <?= $course_name[$i] ?>
            </th>
            <th style="border: 1px solid black;">
                <?= $user_name[$i] ?>
            </th>
            <th style="border: 1px solid black;">
                <?= $chapter[$i] ?>
            </th>
            <th style="border: 1px solid black;">
                <?= $lecture[$i] ?>
            </th>
            <th style="border: 1px solid black;">
                <?= $cmt[$i] ?>
            </th>
            <th style="border: 1px solid black;"><input type="checkbox" name="check[]" value="<?= $cmt_id[$i] ?>"></th>
        </tr>
        <?php
        }
        ?>
    </table>
    <button type="submit">delete</button>
</form>



    <!-- FOOTER -->
    <?php require abs_path('student/layout/footer.php'); ?>
</body>

</html>