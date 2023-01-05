<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        a{
            border: 1px black solid ;
            text-decoration: none;
            text-align: right;
            color: black;
            width: 20px;
            height: 20px;
        }
        li{
            display: inline-flex;
        }
    </style>
</head>
<body>
<?php
    $severname = "localhost";
    $user_name = "root";
    $pass_word = ""; // No password
    $db = "test_phan_trang";
    // Create connnection
    $conn = new mysqli($severname, $user_name, $pass_word, $db);
    // Check connnection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM alphabet";
    $result = $conn->query($sql);
    $rowcount=$result->num_rows;
    $results_per_page =7;
    if (!isset ($_GET['page']) ) {
        $page = 1;
        print_r($page);
        } else {
        $page = $_GET['page'];
        }
    $number_of_page = round($rowcount / $results_per_page);
    $page_first_result = ($page-1) * $results_per_page;



    $sql = "SELECT * FROM alphabet LIMIT ". $page_first_result . "," . $results_per_page;
    $result = $conn->query($sql);
    if ($result->num_rows >0) {
        while ($row = $result->fetch_assoc()) {
            echo $row['id'] . ' ' . $row['letter'] . '</br>';
        }
    }?>
    <ul>
    <?php for($page = 1; $page<= $number_of_page; $page++) { ?>
        <li><a style = "text-align: center" href = "phantrang.php?page=<?= $page ?>"><?= $page ?></a></li>

    <?php } ?>
    </ul>
</body>
</html>
