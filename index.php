<?php
date_default_timezone_set("PRC");

$servername = "rds1j3i30m4ylkys5mi5o.mysql.rds.aliyuncs.com";
$username = "ydzb3_read_only";
$password = "ydzb3_read_only";
$dbname = "ydzb3";


// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);
if (!$conn)
{
    die('Could not connect: ' . mysqli_connect_error());
}



/*$sql = "SELECT id, mobile FROM wm_user_users  limit 2";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 输出数据
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - mobile: " . $row["mobile"]. " " . $row["mobile"]. "<br>";
    }
} else {
    echo "0 结果";
}*/


$sql = "SELECT count(*) FROM wm_user_users";
$sqlC = $conn->query($sql);
 $row = $sqlC->fetch_assoc();
 $sqlCount= $row ["count(*)"];

set_time_limit(0);
ini_set('memory_limit', '512M');

$sqlLimit = 100000;//每次只从数据库取100000条以防变量缓存太大
// 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
$limit = 100000;
// buffer计数器
$cnt = 0;
$fileNameArr = array();
// 逐行取出数据，不浪费内存
for ($i = 0; $i < ceil($sqlCount / $sqlLimit); $i++) {

    $sqlbigan=$i*$sqlLimit;
    $sql = "SELECT id, mobile ,real_name  , id_card ,accountid , account_type  FROM wm_user_users  limit $sqlbigan,100000 ";
    $Users = $conn->query($sql);
   // $Users=$result->fetch_assoc();

   // var_dump($Users);die;
    //为fputcsv()函数打开文件句柄
    $output = fopen('php://output', 'w') or die("can't open php://output");
    //告诉浏览器这个是一个csv文件
    $filename = "userusers表" . date('Y-m-d', time());
    header("Content-Type: application/csv");
    header("Content-Disposition: attachment; filename=$filename.csv");
    //输出表头
    $table_head = array('id','mobile','real_name','id_card', 'accountid', 'account_type');
    fputcsv($output, $table_head);
    //输出每一行数据到文件中

    while($row = $Users->fetch_assoc()) {

        fputcsv($output, array_values($row));

    }
    /*foreach ( $Users->fetch_assoc() as $e) {
        //    unset($e['xx']);//若有多余字段可以使用unset去掉
        //    $e['xx'] = isset($e['xxx']) ? "xx" : 'x'; //可以根据需要做相应处理
        //输出内容
        fputcsv($output, array_values($e));
    }*/
    // echo 111;
    // unset('memory_limit');
    fclose($output);

}



