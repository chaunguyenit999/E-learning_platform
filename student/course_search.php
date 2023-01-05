<!-- Load helper files -->
<?php
require "C:/xampp/htdocs/HUMG_A1_EXAM_chau/coures4u/config.php";
require abs_path('db/db_helper.php');
require abs_path('db/db_connect.php');
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <?php
    // CSS
    require abs_path('student/layout/css_link.php');
    //NAVBAR
    require abs_path('student/layout/nav.php');
    ?>
</head>

<body>
        <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="10000">
                    <img src="<?= abs_url('assets/img/slider/slider.jpg') ?>" class="d-block w-100" alt="slider">

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
    <!-- BODY -->
    <div class="grid-4column">
        <div class="grid-4column__title">
            <?php
            $type_search = $_GET['type_search'];
            $userSearch = $_GET['Search_form'];
            if ($type_search == 'course') {
                echo ("
                <strong>
                    <em>Kết quả tìm kiếm cho các khóa học tên $userSearch</em>
                </strong>
                </div>");
                // thông số phân trang
                $sql = "SELECT * FROM course,user WHERE course.user_id = user.user_id AND course_name LIKE '%" . $userSearch . "%'";
                $search_results = $conn->query($sql);
                $rowcount=$search_results->num_rows;
                $results_per_page =4;
                if (!isset ($_GET['page']) ) {
                    $page = 1;
                } else {
                    $page = $_GET['page'];
                }
                $number_of_page = round($rowcount / $results_per_page);
                $page_first_result = ($page-1) * $results_per_page;
                //
                $sql = "SELECT * FROM course,user WHERE course.user_id = user.user_id AND course_name LIKE '%" . $userSearch . "%' LIMIT ". $page_first_result . "," . $results_per_page;
                $search_results = $conn->query($sql);
                if ($rowcount >0) {
                    while ($mycourse = $search_results->fetch_assoc()) { ?>
                        <div class="card" style="font-size: 14px !important;">
                            <img src="<?= abs_url('assets/img/course/' . $mycourse['course_img']); ?>" class="card-img-top" alt="...">
                            <div class="card-body card-element">
                                <!-- Card title -->
                                <h6 class="card-title"><b><?= $mycourse['course_name'] ?></b></h6>
                                <p class="card-text" style="color: 	rgb(0, 0, 0, 0.5); font: size 12px;"><?= $mycourse['real_name'] ?></p>
                                <!-- star - HIẾU -->
                                <p>
                                    <?php
                                    $sql_rate = "SELECT * FROM course_rate WHERE course_id = $mycourse[course_id]";
                                    $rate = executeResult($sql_rate);
                                    $average_star = 0;
                                    $num_of_rating = 0;
                                    foreach ($rate as $value) {
                                        $average_star += $value['rate'];
                                        $num_of_rating++;
                                    }
                                    if ($num_of_rating != 0) {
                                        $average_star = round($average_star / $num_of_rating);
                                    }
                                    if ($average_star != 0) {
                                        for ($i = 0; $i < $average_star; $i++) {
                                            echo "<span class='fa fa-star checked'></span>";
                                        }
                                        for ($i = 0; $i < 5 - $average_star; $i++) {
                                            echo "<span class='fa fa-star-o '></span>";
                                        }
                                    } else {
                                        for ($i = 0; $i < 5; $i++) {
                                            echo "<span class='fa fa-star-o'></span>";
                                        }
                                    }
                                    ?>
                                    <span>(<?= $num_of_rating ?>)</span>
                                    <!-- Hiếu end -->
                                </p>
                                <?php
                                if (isset($_SESSION['email'])) {?>
                                <div class="dropend">
                                    <button type="button" class="btn btn-info" data-bs-toggle="dropdown" aria-expanded="false">
                                        See course
                                    </button>
                                    <ul class="dropdown-menu shadow-sm">
                                        <div class="course-infor-more">
                                            <span style="font-size: 18px;"><b><?= $mycourse['course_name'] ?></b></span><br>
                                            <div style="color: 	rgb(0, 0, 0, 0.5); font: size 12px;">
                                                <?php
                                                    if ($mycourse['updated_date']== NULL) {
                                                        $course_update_date = date('Y-m-d', strtotime($mycourse['created_date']));
                                                    } else {
                                                        $course_update_date = date('Y-m-d', strtotime($mycourse['updated_date']));
                                                    }
                                                ?>
                                                <span>Last updated: <b style="color: #198754 !important;"><?= $course_update_date ?></b></span><br>
                                                <!-- Calculate SUM lecture_duration -->
                                                <?php
                                                $sql = "SELECT SUM(lecture_duration) AS total FROM lecture
                                                            WHERE chapter_id IN (
                                                            SELECT course.course_id FROM course, chapter
                                                            WHERE course.course_id = chapter.course_id
                                                            AND course.course_id = $mycourse[course_id])";
                                                $total_duration = executeResult($sql, $onlyOne = true)['total'];
                                                if ($total_duration == NULL) {
                                                    $total_duration = 0;
                                                }
                                                ?>
                                                <!-- Total student -->
                                                <?php
                                                $sql = "SELECT COUNT(*) AS total_student FROM `course_student` WHERE course_id = $mycourse[course_id]";
                                                $total_student = executeResult($sql, $onlyOne = true)['total_student'];
                                                ?>
                                                <span>Total student: <?= $total_student ?></span><br>
                                                <span>
                                                    <?= $total_duration ?> total hours
                                                    - All Levels
                                                    - <?= $mycourse['language_name'] ?>
                                                </span><br>
                                                <span><?= $mycourse['course_title'] ?></span><br><br>
                                                <span><?= $mycourse['course_target'] ?></span>
                                            </div>
                                            <br>
                                            <!-- register course -->
                                            <div class="d-flex justify-content-evenly">
                                                <?php
                                                // $sql = "SELECT user_id FROM course_student WHERE user_id=$CURRENT_USER_INFOR[user_id]";
                                                $sql = "SELECT * FROM `course_student` WHERE course_id = $mycourse[course_id] AND user_id=$CURRENT_USER_INFOR[user_id];";
                                                $student_course_id = executeResult($sql, $onlyOne = true);
                                                // print_r(gettype($student_course_id));
                                                if ($student_course_id == NULL) { ?>
                                                    <form action="<?= abs_url('student/cart/checkout.php') ?>">
                                                        <input type="hidden" name="course_id" value=<?= $mycourse['course_id'] ?>>
                                                        <input type="hidden" name="user_id" value=<?= $CURRENT_USER_INFOR['user_id'] ?>>
                                                        <input type="submit" class="btn btn-success" value="Register">
                                                    </form>
                                                <?php } else { ?>
                                                    <a class="btn btn-success" href=<?= abs_url('student/learning/course.php?course_id=' . $mycourse['course_id']) ?>>Learning</a>
                                                <?php } ?>
                                            </div>
                                            <br>
                                            <br>
                                        </div>
                                    </ul>
                                </div>
                                <?php } ?>

                            </div>
                        </div>
                    <?php }}
                    else {
                    echo "</div><h3 style = 'padding-left:50px;padding-bottom:50px'>Rất tiếc, chúng tôi không tìm thấy nội dung bạn muốn tìm </h3>";
                }
                }
            if ($type_search == 'user') {
                echo ("
                <strong>
                    <em>Kết quả tìm kiếm cho các khóa học của $userSearch</em>
                </strong>
                </div>");
                // thông số phân trang
                $sql = "SELECT * FROM course,user WHERE course.user_id = user.user_id AND course_name LIKE '%" . $userSearch . "%'";
                $search_results = $conn->query($sql);
                $rowcount=$search_results->num_rows;
                $results_per_page =4;
                if (!isset ($_GET['page']) ) {
                    $page = 1;
                } else {
                    $page = $_GET['page'];
                }
                $number_of_page = round($rowcount / $results_per_page);
                $page_first_result = ($page-1) * $results_per_page;
                //
                $sql = "SELECT * FROM course,user WHERE user.user_id = course.user_id AND user.real_name LIKE '%" . $userSearch . "%' LIMIT ". $page_first_result . "," . $results_per_page;
                $search_results = $conn->query($sql);
                if ($rowcount >0) {
                    while ($mycourse = $search_results->fetch_assoc()) { ?>
                        <div class="card" style="font-size: 14px !important;">
                        <img src="<?= abs_url('assets/img/course/' . $mycourse['course_img']); ?>" class="card-img-top" alt="...">
                            <div class="card-body card-element">
                                <!-- Card title -->
                                <h6 class="card-title"><b><?= $mycourse['course_name'] ?></b></h6>
                                <p class="card-text" style="color: 	rgb(0, 0, 0, 0.5); font: size 12px;"><?= $mycourse['real_name'] ?></p>
                                <!-- star - HIẾU -->
                                <p>
                                    <?php
                                    $sql_rate = "SELECT * FROM course_rate WHERE course_id = $mycourse[course_id]";
                                    $rate = executeResult($sql_rate);
                                    $average_star = 0;
                                    $num_of_rating = 0;
                                    foreach ($rate as $value) {
                                        $average_star += $value['rate'];
                                        $num_of_rating++;
                                    }
                                    if ($num_of_rating != 0) {
                                        $average_star = round($average_star / $num_of_rating);
                                    }
                                    if ($average_star != 0) {
                                        for ($i = 0; $i < $average_star; $i++) {
                                            echo "<span class='fa fa-star checked'></span>";
                                        }
                                        for ($i = 0; $i < 5 - $average_star; $i++) {
                                            echo "<span class='fa fa-star-o '></span>";
                                        }
                                    } else {
                                        for ($i = 0; $i < 5; $i++) {
                                            echo "<span class='fa fa-star-o'></span>";
                                        }
                                    }
                                    ?>
                                    <span>(<?= $num_of_rating ?>)</span>
                                    <!-- Hiếu end -->
                                </p>
                                <?php
                                if (isset($_SESSION['email'])) {?>
                                <div class="dropend">
                                    <button type="button" class="btn btn-info" data-bs-toggle="dropdown" aria-expanded="false">
                                        See course
                                    </button>
                                    <ul class="dropdown-menu shadow-sm">
                                        <div class="course-infor-more">
                                            <span style="font-size: 18px;"><b><?= $mycourse['course_name'] ?></b></span><br>
                                            <div style="color: 	rgb(0, 0, 0, 0.5); font: size 12px;">
                                                <?php
                                                    if ($mycourse['updated_date']== NULL) {
                                                        $course_update_date = date('Y-m-d', strtotime($mycourse['created_date']));
                                                    } else {
                                                        $course_update_date = date('Y-m-d', strtotime($mycourse['updated_date']));
                                                    }
                                                ?>
                                                <span>Last updated: <b style="color: #198754 !important;"><?= $course_update_date ?></b></span><br>
                                                <!-- Calculate SUM lecture_duration -->
                                                <?php
                                                $sql = "SELECT SUM(lecture_duration) AS total FROM lecture
                                                            WHERE chapter_id IN (
                                                            SELECT course.course_id FROM course, chapter
                                                            WHERE course.course_id = chapter.course_id
                                                            AND course.course_id = $mycourse[course_id])";
                                                $total_duration = executeResult($sql, $onlyOne = true)['total'];
                                                if ($total_duration == NULL) {
                                                    $total_duration = 0;
                                                }
                                                ?>
                                                <!-- Total student -->
                                                <?php
                                                $sql = "SELECT COUNT(*) AS total_student FROM `course_student` WHERE course_id = $mycourse[course_id]";
                                                $total_student = executeResult($sql, $onlyOne = true)['total_student'];
                                                ?>
                                                <span>Total student: <?= $total_student ?></span><br>
                                                <span>
                                                    <?= $total_duration ?> total hours
                                                    - All Levels
                                                    - <?= $mycourse['language_name'] ?>
                                                </span><br>
                                                <span><?= $mycourse['course_title'] ?></span><br><br>
                                                <span><?= $mycourse['course_target'] ?></span>
                                            </div>
                                            <br>
                                            <!-- register course -->
                                            <div class="d-flex justify-content-evenly">
                                                <?php
                                                // $sql = "SELECT user_id FROM course_student WHERE user_id=$CURRENT_USER_INFOR[user_id]";
                                                $sql = "SELECT * FROM `course_student` WHERE course_id = $mycourse[course_id] AND user_id=$CURRENT_USER_INFOR[user_id];";
                                                $student_course_id = executeResult($sql, $onlyOne = true);
                                                // print_r(gettype($student_course_id));
                                                if ($student_course_id == NULL) { ?>
                                                    <form action="<?= abs_url('student/cart/checkout.php') ?>">
                                                        <input type="hidden" name="course_id" value=<?= $mycourse['course_id'] ?>>
                                                        <input type="hidden" name="user_id" value=<?= $CURRENT_USER_INFOR['user_id'] ?>>
                                                        <input type="submit" class="btn btn-success" value="Register">
                                                    </form>
                                                <?php } else { ?>
                                                    <a class="btn btn-success" href=<?= abs_url('student/learning/course.php?course_id=' . $mycourse['course_id']) ?>>Learning</a>
                                                <?php } ?>
                                            </div>
                                            <br>
                                            <br>
                                        </div>
                                    </ul>
                                </div>
                                <?php } ?>

                            </div>
                        </div>
                    <?php }} else {
                    echo "</div><h3 style = 'padding-left:50px;padding-bottom:50px'>Rất tiếc, chúng tôi không tìm thấy nội dung bạn muốn tìm </h3>";
                }
            }?>
        </div>
            <!-- phân trang -->
        <div style = "padding:50px;padding-top:0px">
            <ul class="pagination justify-content-end">
                <?php
                if ($page==1){?>
                    <li class="page-item disabled"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                <?php }else{?>
                    <li class="page-item"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
            <?php }
            for($page_dipalay = 1; $page_dipalay<= $number_of_page; $page_dipalay++) { ?>
                <li class="page-item"><a class="page-link" href="course_search.php?Search_form=<?= $userSearch ?>&type_search=<?= $type_search ?>&submit=&page=<?= $page_dipalay ?>"><?= $page_dipalay ?></a></li>
            <?php }
                if ($page==$number_of_page){?>
                    <li class="page-item disabled"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&raquo;</span></a></li>
                <?php }else{?>
                    <li class="page-item"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&raquo;</span></a></li>
            <?php }?>
            </ul>
        </div>
        <!-- FOOTER -->
        <?php require abs_path('student/layout/footer.php'); ?>
</body>

</html>