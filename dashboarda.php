<?php
include 'connection.php';
include 'api/checklogin.php';

$query = "SELECT * FROM `users`";
$qryResult = mysqli_query($con, $query);
$totalUsers = mysqli_num_rows($qryResult);
// echo $totalUsers;


$query = "SELECT * FROM users
        WHERE DATE(createdAt) = CURDATE()";
$qryResult = mysqli_query($con, $query);
$createdToday = mysqli_num_rows($qryResult);
// echo $createdToday;

$query = "SELECT * FROM users
        WHERE DATE(modifiedAt) = CURDATE()";
$qryResult = mysqli_query($con, $query);
$updateToday = mysqli_num_rows($qryResult);
// echo $updateToday;

$query = "SELECT * FROM users
        WHERE subscription = 'true'";
$qryResult = mysqli_query($con, $query);
$subUsers = mysqli_num_rows($qryResult);
// echo $subUsers;
$currentMonth = date('m');
$currentYear = date('Y');

$query = "SELECT MONTH(createdAt) AS month, DAY(createdAt) AS day, COUNT(*) AS user_count
          FROM users
          WHERE MONTH(createdAt) = $currentMonth AND YEAR(createdAt) = $currentYear
          GROUP BY MONTH(createdAt), DAY(createdAt)
          ORDER BY MONTH(createdAt), DAY(createdAt)";


$qryResult = mysqli_query($con, $query);
$result = mysqli_num_rows($qryResult);


while ($row = mysqli_fetch_assoc($qryResult)) {
        $data[] = array((string)((string)$row['day']), (int)$row['user_count']);
}




$query = "SELECT MONTH(modifiedAt) AS month, DAY(modifiedAt) AS day, COUNT(*) AS user_count
          FROM users
          WHERE MONTH(modifiedAt) = $currentMonth AND YEAR(modifiedAt) = $currentYear
          GROUP BY MONTH(modifiedAt), DAY(modifiedAt)
          ORDER BY MONTH(modifiedAt), DAY(modifiedAt)";


$qryResult = mysqli_query($con, $query);
$result = mysqli_num_rows($qryResult);


while ($row = mysqli_fetch_assoc($qryResult)) {
        $data1[] = array((string)((string)$row['day']), (int)$row['user_count']);
}







// Fetch data and format it for Highcharts
// $data = array();
// $totalDesignations = 0;
// while ($row = mysqli_fetch_assoc($result)) {
//     $data[] = array(
//         'name' => $row['designation'],
//         'y' => (int)$row['designation_count']
//     );
//     $totalDesignations += (int)$row['designation_count'];
// }

// // Calculate the percentage for each designation
// foreach ($data as &$datum) {
//     $datum['percentage'] = ($datum['y'] / $totalDesignations) * 100;
// }

// // JSON encode the data for Highcharts
// $dataJson = json_encode($data);
