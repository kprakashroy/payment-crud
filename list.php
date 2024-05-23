<?php

include "db_connection.php";


function deleteRecord($id) {
    global $conn;
    $sql = "DELETE FROM `payment_added` WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}


$records_per_page = 10;


$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;


$offset = ($page - 1) * $records_per_page;


$sql = "SELECT * FROM `payment_added` ORDER BY `date` DESC LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $offset, $records_per_page);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amount List with Pagination</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-3">Amount List</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sno</th>
                    <th>Username</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $sno = $offset + 1; 
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $sno++ . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['amount'] . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td><form method='post'><input type='hidden' name='id' value='" . $row['id'] . "'><button type='submit' name='delete' class='btn btn-danger btn-sm'>Delete</button></form></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
        
        $total_records_sql = "SELECT COUNT(*) AS total FROM `payment_added`";
        $total_records_result = $conn->query($total_records_sql);
        $total_records = $total_records_result->fetch_assoc()['total'];
        $total_pages = ceil($total_records / $records_per_page);

        
        if ($page > 1) {
            echo "<a href='?page=" . ($page - 1) . "' class='btn btn-primary'>Previous</a>";
        }

        
        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a href='?page=" . $i . "' class='btn btn-secondary'>$i</a>";
        }

     
        if ($page < $total_pages) {
            echo "<a href='?page=" . ($page + 1) . "' class='btn btn-primary'>Next</a>";
        }
        ?>

    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php

$stmt->close();
$conn->close();


if (isset($_POST['delete']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    deleteRecord($id);
  
    header("Location: {$_SERVER['PHP_SELF']}?page=$page");
    exit;
}
?>
