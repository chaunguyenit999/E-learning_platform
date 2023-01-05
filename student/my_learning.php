<!-- Load helper files -->
<?php
require "C:/xampp/htdocs/HUMG_A1_EXAM_chau/coures4u/config.php";

require abs_path('db/db_helper.php');

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .notification{
            font-size: smaller;
            color: red;
        }
    </style>
    <?php
    // CSS
    require abs_path('student/layout/css_link.php');
    //NAVBAR
    require abs_path('student/layout/nav.php');
    ?>
    <script>

        document.addEventListener("DOMContentLoaded", function(){
            document.querySelectorAll('.open_modal').forEach( function(link) {
                link.onclick = function () {
                    let require_rising_star = false
                    let require_input = false
                    let course_id = link.classList[1]
                    let submit_form = document.querySelector(`.submit${course_id}`)
                    let content = document.querySelector(`#rate${course_id}`)
                    let noti = document.querySelector(`.notification${course_id}`)
                    let rating_value = document.querySelector(`.rating_value${course_id}`)
                    let form = document.querySelector(`#rating${course_id} .modal-content`)
                    let all_star = document.querySelectorAll(`.rate${course_id} .fa `)

                    submit_form.disabled = true

                    // nếu đã có cmt thì ko cảnh báo
                    if (content.innerHTML !='') {
                        noti.innerHTML = ""
                    }

                    // ktra điều kiến input >30 kí tự
                    content.oninput = function() {
                        let inp = content.value;
                        if (inp.length<30) {
                            require_input = false
                            submit_form.disabled = true
                            noti.innerHTML = `bạn cần đánh giá thêm ${30 - inp.length} kí tự`;
                        }
                        else {
                            noti.innerHTML = "";
                            require_input = true
                        }
                    }

                    function mouve_move() {
                        star_value = String(this.classList[0]).slice(-1)
                        for (let index = 1; index < 6; index++) {
                            if (index <= star_value) {
                                document.querySelector(`.rate${course_id} .rating__star${index}`).classList.value
                                = document.querySelector(`.rate${course_id} .rating__star${index}`).classList.value.replace('fa-star-o', 'fa-star checked')
                            }
                            else {
                                document.querySelector(`.rate${course_id} .rating__star${index}`).classList.value
                                = document.querySelector(`.rate${course_id} .rating__star${index}`).classList.value.replace('fa-star checked', 'fa-star-o')
                            }
                        }
                        rating_value.value = star_value
                    }

                    function mouse_out() {
                        require_rising_star = false;
                        for (let index = 1; index < 6; index++) {
                            document.querySelector(`.rate${course_id} .rating__star${index}`).classList.value
                            = document.querySelector(`.rate${course_id} .rating__star${index}`).classList.value.replace('fa-star checked', 'fa-star-o')
                        }
                        rating_value.value = 0
                    }

                    // thêm skien cho sao
                    all_star.forEach( function(star) {
                        star.addEventListener('mousemove',mouve_move)

                        if (rating_value.value == 0) {
                            star.addEventListener('mouseout',mouse_out)
                            star.addEventListener('click',function() {
                                require_rising_star = true;
                                all_star.forEach( function(star) {
                                    star.removeEventListener('mouseout',mouse_out)
                                })
                            })
                        }
                        
                        else{
                            star.addEventListener('click',function() {
                                require_rising_star = true
                            })
                        }
                    })

                    form.addEventListener("click", function(){
                        if (require_input && require_rising_star) {
                            submit_form.disabled = false
                        }
                    })
                }
            });

        });

</script>
</head>


<body>
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
                    <img src="<?= abs_url('assets/img/slider/slider.jpg') ?>" class="d-block w-100" alt="slider" >

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
            My learning
        </div>
        <?php
        if (isset($_SESSION['email'])) {
            $sql_process = "SELECT user_id, course_id, process FROM course_student WHERE user_id = (SELECT user_id FROM user WHERE email =  '" . $CURRENT_USER_EMAIL . "')";
            $process_course = executeResult($sql_process);
            $course_id = [];
            $process = [];


            foreach ($process_course as $value) {
                $course_id[] = $value['course_id'];
                $process[] = $value['process'];
            }
            foreach ($course_id as $key => $value) {
                $sql_course = "SELECT user_id, course_id, course_name, course_img FROM course WHERE course_id = '" . $value . "'";
                $infor_course = executeResult($sql_course);
                $sql_user = "SELECT real_name FROM user WHERE user_id = '" . $infor_course[0]['user_id'] . "'";
                $infor_user = executeResult($sql_user);
                $process_course[$key]['course_name'] = $infor_course[0]['course_name'];
                $process_course[$key]['course_img'] = $infor_course[0]['course_img'];
        ?>
                <div class="grid-4column__box">
                    <a href=<?= abs_url('student/learning/course.php?course_id=' . $infor_course[0]['course_id']) ?>>
                        <div class="grid-4column__box--img">
                            <img src="<?= abs_url('assets/img/course/' . $infor_course[0]['course_img']); ?>" width=100% alt="">
                        </div>
                        <div class="grid-4column__box--name">
                            <em>
                                <?php echo ($infor_course[0]['course_name']) ?>
                            </em>
                        </div>
                    </a>
                    <div class="grid-4column__box--inf">
                        <div class="grid-4column__box--inf--name">
                            <p style="font-size:15px">
                                <?php echo ($infor_user[0]['real_name']) ?>
                            </p>
                        </div>
                        <div class="grid-4column__box--inf--meter">
                            <meter style="width:100%" value="<?php echo ($process[$key]); ?>" min="0" max="1"></meter>
                        </div>
                        <div class="grid-4column__box--inf-left">
                            <p style="font-size:11px">
                                <?php echo ($process[$key]) * 100; ?>% complete
                            </p>
                        </div>
                        <div></div>
                        <div class="grid-4column__box--inf-right">
                            <div style="height:100%;font-size:15px">
                                <?php
                                $sql_rate = "SELECT * FROM course_rate WHERE user_id = (SELECT user_id FROM user WHERE email =  '" . $CURRENT_USER_EMAIL . "') AND course_id = '" . $value . "'";
                                $rate = executeResult($sql_rate);
                                if ($rate != []) {
                                    // display star card
                                    for ($i = 0; $i < $rate[0]['rate']; $i++) {
                                        echo "<div class='fa fa-star checked'></div>";
                                    }
                                    for ($i = 0; $i < 5 - $rate[0]['rate']; $i++) {
                                        echo "<div class='fa fa-star-o '></div>";
                                    } ?>
                                    <br><a class = "open_modal <?= $infor_course[0]['course_id'] ?>" data-bs-toggle="modal" data-bs-target="#rating<?= $infor_course[0]['course_id'] ?>" style="font-size:11px;background-color:white;border:none;">
                                        edit your rating
                                    </a>
                                    <!-- Modal -->
                                    <div class="modal fade" id="rating<?= $infor_course[0]['course_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">How would you rate this
                                                        course?</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                              <form action=<?= abs_url("helpers/insert_change_my_learning.php")?> method="post" class = "rate<?= $infor_course[0]["course_id"] ?>">
                                                    <div class="modal-body" style="text-align: center">
                                                        <!-- display star form -->
                                                        <?php
                                                        for ($i = 1; $i < 6; $i++) {
                                                            if ($i <= $rate[0]['rate']) {?>
                                                                <div style='font-size:32px' class='rating__star<?= $i ?> fa rate fa-star checked'></div>
                                                        <?php
                                                            }
                                                            else{ ?>
                                                                <div style='font-size:32px' class='rating__star<?= $i ?> fa rate fa-star-o'></div>
                                                            <?php }
                                                        }
                                                        ?>
                                                        <input type="hidden" class="rating_value<?= $infor_course[0]['course_id'] ?>" name="rating_value<?= $infor_course[0]['course_id'] ?>" value = "<?=$rate[0]['rate']?>">
                                                        <input type="hidden" name="course_id" value="<?= $infor_course[0]['course_id'] ?>">
                                                        <textarea id = "rate<?= $infor_course[0]['course_id'] ?>" rows=10 cols=50 name="content" required><?= $rate[0]['content'] ?></textarea>
                                                        <p class="notification notification<?= $infor_course[0]['course_id'] ?>">bạn cần đánh giá trên 30 kí tự</p>
                                                        <input type="hidden" name="user_id" value="<?= $process_course[0]['user_id'] ?>">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" name="delete">Delete</button>
                                                        <button type="submit" id ="submit" name="form_submit" class="btn btn-primary submit<?= $infor_course[0]['course_id'] ?>">Save changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        <?php } else {
                                    // display star card
                                    for ($i = 0; $i < 5; $i++) {
                                        echo "<div class='fa fa-star-o'></div>";
                                    } ?>
                            <a class = "open_modal <?= $infor_course[0]['course_id'] ?>" data-bs-toggle="modal" data-bs-target="#rating<?= $infor_course[0]['course_id'] ?>" style="font-size:11px;background-color:white;border:none;">
                                rating
                            </a>
                            <!-- Modal -->
                            <div class="modal fade" id="rating<?= $infor_course[0]['course_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">How would you rate this course?
                                            </h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                       <form action=<?= abs_url("helpers/insert_change_my_learning.php")?> method="post" class = "rate<?= $infor_course[0]["course_id"] ?>">
                                            <div class="modal-body" style="text-align: center">
                                                <!-- display star form -->
                                                <?php
                                                    for ($i = 1; $i < 6; $i++) {?>
                                                            <div style='font-size:32px' class='rating__star<?= $i ?> fa rate fa-star-o'></div>
                                                    <?php } ?>
                                                <input type="hidden" name="course_id" value="<?= $infor_course[0]['course_id'] ?>">
                                                <input type="hidden" class="rating_value<?= $infor_course[0]['course_id'] ?>" name="rating_value<?= $infor_course[0]['course_id'] ?>" value = "0">
                                                <textarea id = "rate<?= $infor_course[0]['course_id'] ?>" rows=10 cols=50 name="content" required></textarea>
                                                <p class="notification notification<?= $infor_course[0]['course_id'] ?>">bạn cần đánh giá trên 30 kí tự</p>
                                                <input type="hidden" name="user_id" value="<?= $process_course[0]['user_id'] ?>">
                                            </div>

                                            <div class="modal-footer">
                                                <button type="submit" id ="submit" name="form_submit" class="btn btn-primary submit<?= $infor_course[0]['course_id'] ?>">Rating</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                </div>
    </div>
<?php } ?>


<?php } ?>
</div>
</div>

<!-- FOOTER -->
<?php require abs_path('student/layout/footer.php'); ?>
</body>

</html>