<?php
// Include database connection or any necessary files
include('includes/dbconnection.php');

// Example: Fetch data from birds_mortality.php
$sqlBirdsMortality = "SELECT * FROM birds_mortality";
$queryBirdsMortality = $dbh->prepare($sqlBirdsMortality);
$queryBirdsMortality->execute();
$resultsBirdsMortality = $queryBirdsMortality->fetchAll(PDO::FETCH_ASSOC);

// Example: Fetch data from birds_produced.php
$sqlBirdsProduced = "SELECT * FROM birds_produced";
$queryBirdsProduced = $dbh->prepare($sqlBirdsProduced);
$queryBirdsProduced->execute();
$resultsBirdsProduced = $queryBirdsProduced->fetchAll(PDO::FETCH_ASSOC);

// Repeat similar fetch operations for other pages and tables...

// Display summaries or detailed information as needed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Records Summary</title>
    <!-- Include any necessary CSS -->
    <style>
        /* General styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
}

.container {
    width: 80%;
    margin: auto;
    padding: 20px;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

h2 {
    margin-top: 30px;
    margin-bottom: 15px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table th,
table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

table th {
    background-color: #f2f2f2;
}

/* Responsive styles */
@media (max-width: 768px) {
    .container {
        width: 90%;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Records Summary</h1>

        <h2>Birds Mortality</h2>
        <table border="1">
            <thead>
                <tr>
                     <th>id</th>
                    <th>date</th>
                    <th>total_birds</th>
                    <th>number_of_deaths</th>
                    <th>remaining_birds</th>
                    <th>cause_of_mortality</th>
                    <!-- Add more headers as needed -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultsBirdsMortality as $row): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['total_birds']; ?></td>
                        <td><?php echo $row['number_of_deaths']; ?></td>
                        <td><?php echo $row['remaining_birds']; ?></td>
                        <td><?php echo $row['cause_of_mortality']; ?></td>
                        <!-- Add more columns based on your table structure -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Birds Mortality</h2>
        <table border="1">
            <thead>
                <tr>
                     <th>id</th>
                    <th>date</th>
                    <th>total_birds</th>
                    <th>number_of_deaths</th>
                    <th>remaining_birds</th>
                    <th>cause_of_mortality</th>
                    <!-- Add more headers as needed -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultsBirdsMortality as $row): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['total_birds']; ?></td>
                        <td><?php echo $row['number_of_deaths']; ?></td>
                        <td><?php echo $row['remaining_birds']; ?></td>
                        <td><?php echo $row['cause_of_mortality']; ?></td>
                        <!-- Add more columns based on your table structure -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


<h2>Birds Mortality</h2>
        <table border="1">
            <thead>
                <tr>
                     <th>id</th>
                    <th>date</th>
                    <th>total_birds</th>
                    <th>number_of_deaths</th>
                    <th>remaining_birds</th>
                    <th>cause_of_mortality</th>
                    <!-- Add more headers as needed -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultsBirdsMortality as $row): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['total_birds']; ?></td>
                        <td><?php echo $row['number_of_deaths']; ?></td>
                        <td><?php echo $row['remaining_birds']; ?></td>
                        <td><?php echo $row['cause_of_mortality']; ?></td>
                        <!-- Add more columns based on your table structure -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


<h2>Birds Mortality</h2>
        <table border="1">
            <thead>
                <tr>
                     <th>id</th>
                    <th>date</th>
                    <th>total_birds</th>
                    <th>number_of_deaths</th>
                    <th>remaining_birds</th>
                    <th>cause_of_mortality</th>
                    <!-- Add more headers as needed -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultsBirdsMortality as $row): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['total_birds']; ?></td>
                        <td><?php echo $row['number_of_deaths']; ?></td>
                        <td><?php echo $row['remaining_birds']; ?></td>
                        <td><?php echo $row['cause_of_mortality']; ?></td>
                        <!-- Add more columns based on your table structure -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


        <!-- Repeat similar tables or sections for other data -->

    </div>
    <!-- Include any necessary JavaScript -->
    <script src="path/to/your/script.js"></script>
</body>
</html>
