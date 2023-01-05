<?php
require "C:/xampp/htdocs/HUMG_A1_EXAM/coures4u/config.php";

require abs_path('db/db_helper.php');

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data['search_input'])) {
    echo "da nhan";
    $kw = $data['search_input'];

    $sql = "SELECT * FROM course, category, subcategory, topic, subcategory_topic
    WHERE course.sub_topic_id = subcategory_topic.sub_topic_id
    AND subcategory_topic.topic_id = topic.topic_id
    AND subcategory_topic.subcategory_id = subcategory.subcategory_id
    AND category.category_id = subcategory.category_id
    AND (course_name LIKE '%$kw%'
    OR course_title LIKE '%$kw%'
    OR category.category_name LIKE '%$kw%'
    OR subcategory.subcategory_name LIKE '%$kw%'
    OR topic.topic_name LIKE '%$kw%')
    LIMIT 5;
    ";
    $data_response_lst = executeResult($sql);
    foreach ($data_response_lst as $data_response) {
        echo "
        <option value='$data_response[course_name]'>
        <option value='$data_response[course_title]'>
        <option value='$data_response[category_name]'>
        <option value='$data_response[subcategory_name]'>
        <option value='$data_response[topic_name]'>
        ";
    }
}
