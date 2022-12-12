<!-- Load helper files -->
<?php
require "C:/xampp/htdocs/HUMG_A1_EXAM/coures4u/config.php";
require abs_path('db/db_helper.php');
require abs_path('helpers/upload_file.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses4U | Teacher</title>
    <style>
        .return_page-text {
            display: block;
            padding: 1.2vw;
            text-decoration: none;
            width: 100% !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }
    </style>
    <!-- Import css & script link -->

    <?php require abs_path('teacher/layout/css_link.php'); ?>

    <?php
    $current_chapter_id = $_GET['chapter_id'];
    $current_course_id = $_GET['course_id'];
    ?>
</head>

<body>
    <div class="learning_interface--layout">
        <!-- NAV -->
        <?php require abs_path('teacher/layout/nav.php'); ?>

        <!-- BODY -->
        <div class="body-wrapper">
            <div class="body-wrapper__content row">
                <div class="col-3 bg-light">
                    <?php
                    $sql = "SELECT chapter_title FROM chapter WHERE chapter_id=$current_chapter_id";
                    $chapter_title = executeResult($sql, $onlyOne = true)['chapter_title'];
                    ?>
                    <h6 style="margin: 5px;"><i>Chapter ID: <?= $current_chapter_id ?></i></h6>
                    <h6 style="margin: 5px;"><i>Chapter Name: <?= $chapter_title ?></i></h6>
                    <a href=<?= abs_url('teacher/course/my_course.php') ?> class="return_page-text">Back to Courses</a>
                    <a href=<?= abs_url('teacher/course/chapter/view.php?course_id=' . $current_course_id) ?> class="return_page-text">Back to Chapters</a>

                    <div class="accordion accordion-flush" id="sidebar--left">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingTwo--left">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo--left" aria-expanded="false" aria-controls="flush-collapseTwo--left">
                                    Lecture
                                </button>
                            </h2>
                            <div id="flush-collapseTwo--left" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo--left" data-bs-parent="#sidebar--left">
                                <div class="accordion-collapse__elements">
                                    <form action="create.php">
                                        <a href=<?= abs_url('teacher/course/lecture/create.php?chapter_id=' . $current_chapter_id . '&course_id=' . $current_course_id) ?>>Create lecture</a>
                                    </form>
                                    <a href="">View All Lecture</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <div class="table-responsive">
                        <table class="table table-success table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Lecture ID</th>
                                    <th>Lecture Title</th>
                                    <th>Video</th>
                                    <th>Chapter ID</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM lecture
                                ORDER BY lecture_id ASC";
                                $lectures = executeResult($sql);
                                foreach ($lectures as $lecture) { ?>
                                    <tr>
                                        <td><?= $lecture['lecture_id'] ?></td>
                                        <td><?= substr($lecture['lecture_title'], 0, 20) . '...' ?></td>
                                        <td><?= substr($lecture['lecture_video'], 0, 20) ?></td>
                                        <td><?= $current_chapter_id ?></td>
                                        <td>
                                            <div>
                                                <!-- Edit Chapter -->
                                                <a class="btn btn-warning" href=<?= abs_url('teacher/course/lecture/update.php?chapter_id=' . $current_chapter_id . '&course_id=' . $current_course_id . '&lecture_id=' . $lecture['lecture_id']) ?>>
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <!-- Delete Chapter -->
                                                <a class="btn btn-danger" href=<?= abs_url('teacher/course/lecture/delete.php?chapter_id=' . $current_chapter_id . '&course_id=' . $current_course_id . '&lecture_id=' . $lecture['lecture_id']) ?>>
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <!-- <footer></footer> -->
    </div>

    <!-- SIDEBAR -->
    <?php require abs_path('teacher/layout/sidebar.php'); ?>
</body>

</html>