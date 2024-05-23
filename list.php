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

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'weekly';

switch ($filter) {
    case 'monthly':
        $date_interval = '1 MONTH';
        break;
    case 'yearly':
        $date_interval = '1 YEAR';
        break;
    case 'weekly':
    default:
        $date_interval = '1 WEEK';
        break;
}

$sql = "SELECT * FROM `payment_added` WHERE `date` >= DATE_SUB(NOW(), INTERVAL $date_interval) ORDER BY `date` DESC LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $offset, $records_per_page);
$stmt->execute();
$result = $stmt->get_result();

$total_amount_sql = "SELECT SUM(`amount`) AS total FROM `payment_added` WHERE `date` >= DATE_SUB(NOW(), INTERVAL $date_interval)";
$total_amount_result = $conn->query($total_amount_sql);
$total_amount = $total_amount_result->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amount List with Pagination</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
         body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            margin-top: 50px;
            max-width: 800px;
        }
        .table {
            font-size: 1.1rem;
        }
        .table thead th {
            background-color: #007bff;
            color: white;
            padding: 12px 20px; /* Increased padding for column headings */
            font-size: 1.2rem; /* Increased font size for column headings */
            white-space: nowrap; /* Prevent wrapping of column headings */
        }
        .table th, .table td {
            padding: 12px 20px; /* Increased padding for table cells */
        }
        .table thead th:first-child {
            border-top-left-radius: 10px; /* Rounded corners for first column heading */
        }
        .table thead th:last-child {
            border-top-right-radius: 10px; /* Rounded corners for last column heading */
        }
        .table tbody tr:last-child td {
            border-bottom-left-radius: 10px; /* Rounded corners for last row */
            border-bottom-right-radius: 10px; /* Rounded corners for last row */
        }
        .btn-secondary {
            margin: 0 5px;
        }
        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .btn-danger {
            font-size: 1rem;
        }
        .btn-primary, .btn-secondary {
            font-size: 1rem;
        }
        .filter-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Amount List</h2>
        <div class="filter-container">
            <form method="get" class="d-flex align-items-center">
                <label for="filter" class="me-2">Filter by:</label>
                <select id="filter" name="filter" class="form-select me-2" onchange="this.form.submit()">
                    <option value="weekly" <?php if ($filter == 'weekly') echo 'selected'; ?>>Weekly</option>
                    <option value="monthly" <?php if ($filter == 'monthly') echo 'selected'; ?>>Monthly</option>
                    <option value="yearly" <?php if ($filter == 'yearly') echo 'selected'; ?>>Yearly</option>
                </select>
                <noscript><input type="submit" value="Submit" class="btn btn-primary"></noscript>
            </form>
            <div>
                <strong>Total Amount: </strong> <?php echo number_format($total_amount, 2); ?>
            </div>
        </div>
        <table class="table table-striped text-center">
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

        <div class="pagination-container">
            <?php
            $total_records_sql = "SELECT COUNT(*) AS total FROM `payment_added` WHERE `date` >= DATE_SUB(NOW(), INTERVAL $date_interval)";
            $total_records_result = $conn->query($total_records_sql);
            $total_records = $total_records_result->fetch_assoc()['total'];
            $total_pages = ceil($total_records / $records_per_page);

            if ($page > 1) {
                echo "<a href='?page=" . ($page - 1) . "&filter=" . $filter . "' class='btn btn-primary'>Previous</a>";
            }

            for ($i = 1; $i <= $total_pages; $i++) {
                $active = ($i == $page) ? 'btn-primary' : 'btn-secondary';
                echo "<a href='?page=" . $i . "&filter=" . $filter . "' class='btn $active'>$i</a>";
            }

            if ($page < $total_pages) {
                echo "<a href='?page=" . ($page + 1) . "&filter=" . $filter . "' class='btn btn-primary'>Next</a>";
            }
            ?>
        </div>
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
    header("Location: {$_SERVER['PHP_SELF']}?page=$page&filter=$filter");
    exit;
}
?>
