<?php
require "C:/xampp/htdocs/HUMG_A1_EXAM/coures4u/config.php";

require abs_path('db/db_helper.php');

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data['table_name']) || isset($data['column_check']) || isset($data['input_value'])) {
    $table_name = $data['table_name'];
    $column_check = $data['column_check'];
    $input_value = $data['input_value'];

    $sql = "SELECT * FROM `$table_name` WHERE `$column_check` = '$input_value'";
    $result = executeResult($sql, $onlyOne = true);
    if ($result != NULL) {
        echo "existed";
    }
}
?>