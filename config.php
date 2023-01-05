<?php
function abs_url($path)
{
    return 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . "/coures4u/" . $path;
}

function abs_path($path)
{
    return 'C:/xampp/htdocs/HUMG_A1_EXAM_chau/coures4u/' . $path;
}
?>