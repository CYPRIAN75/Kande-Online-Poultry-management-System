<?php
// Start session and include database connection
session_start();
include('includes/dbconnection.php');

// Handle form submission
if(isset($_POST['submit'])) {
    $medicine_category = $_POST['medicine_category'];
    $importance = $_POST['importance'];
    $use = $_POST['use'];
    $date = $_POST['date'];
    $person_in_charge = $_POST['person_in_charge'];
    $total_medicine_store = $_POST['total_medicine_store'];
    $quantity_consumed = $_POST['quantity_consumed'];
    $remaining_medicine_store = $total_medicine_store - $quantity_consumed;

    $sql = "INSERT INTO tblMedicineConsumed (medicine_category, importance, use_purpose, date, person_in_charge, total_medicine_store, quantity_consumed, remaining_medicine_store) 
            VALUES (:medicine_category, :importance, :use_purpose, :date, :person_in_charge, :total_medicine_store, :quantity_consumed, :remaining_medicine_store)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':medicine_category', $medicine_category, PDO::PARAM_STR);
    $query->bindParam(':importance', $importance, PDO::PARAM_STR);
    $query->bindParam(':use_purpose', $use, PDO::PARAM_STR);
    $query->bindParam(':date', $date, PDO::PARAM_STR);
    $query->bindParam(':person_in_charge', $person_in_charge, PDO::PARAM_STR);
    $query->bindParam(':total_medicine_store', $total_medicine_store, PDO::PARAM_INT);
    $query->bindParam(':quantity_consumed', $quantity_consumed, PDO::PARAM_INT);
    $query->bindParam(':remaining_medicine_store', $remaining_medicine_store, PDO::PARAM_INT);
    $query->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medicine Consumed</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        /* Form Styles */
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        form h2 {
            margin-top: 0;
            color: #4CAF50;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="date"],
        .form-group select,
        .form-group input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        .button-edit {
            background-color: #ffc107;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button-edit:hover {
            background-color: #e0a800;
        }

        .button-delete {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button-delete:hover {
            background-color: #c82333;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 95%;
            }

            table, 
            form {
                font-size: 14px;
            }
        }
    </style>
    <script>
        // Define medicine data
        var medicineData = {
            "Antibiotics": {
                "importance": "Antibiotics are used to treat bacterial infections in poultry.",
                "use": "They help in controlling and eliminating bacterial diseases such as respiratory infections, enteritis, and other bacterial ailments."
            },
            "Anticoccidials": {
                "importance": "Anticoccidials are crucial for preventing and treating coccidiosis, a common intestinal disease in poultry caused by protozoan parasites.",
                "use": "They help in controlling outbreaks and reducing the impact of coccidiosis on bird health and productivity."
            },
            "Antivirals": {
                "importance": "Antivirals are used to treat viral infections in poultry.",
                "use": "They help in managing diseases caused by viruses, such as Newcastle disease, avian influenza, and infectious bronchitis."
            },
            "Anthelmintics (Dewormers)": {
                "importance": "Anthelmintics are used to control and eliminate internal parasites (worms) in poultry.",
                "use": "They prevent and treat conditions like roundworms, tapeworms, and other internal parasites that can affect the health and growth of birds."
            },
            "Vaccines": {
                "importance": "Vaccines are essential for preventing infectious diseases in poultry.",
                "use": "They stimulate the bird's immune system to develop protection against specific diseases, reducing the risk of outbreaks and improving overall flock health."
            },
            "Anti-inflammatory drugs": {
                "importance": "Anti-inflammatory drugs help in reducing inflammation and pain in poultry.",
                "use": "They are used to alleviate discomfort and aid in recovery from injuries, surgeries, or inflammatory conditions."
            },
            "Vitamins and Supplements": {
                "importance": "Vitamins and supplements are essential for maintaining overall health and enhancing immune function in poultry.",
                "use": "They address nutritional deficiencies, support growth, improve egg production, and boost the bird's resistance to diseases."
            },
            "Probiotics": {
                "importance": "Probiotics promote gut health and support digestion in poultry.",
                "use": "They maintain a healthy balance of beneficial gut bacteria, improving nutrient absorption, and enhancing the bird's immune response."
            },
            "Disinfectants": {
                "importance": "Disinfectants are used for cleaning and sanitizing poultry housing, equipment, and water sources.",
                "use": "They help in preventing the spread of diseases by killing harmful pathogens and reducing contamination risks."
            },
            "Growth Promoters": {
                "importance": "Growth promoters are used to enhance growth rates and feed efficiency in poultry.",
                "use": "They promote faster weight gain and efficient conversion of feed into muscle, improving overall productivity in commercial poultry farming."
            }
        };

        // Function to populate importance and use based on selected medicine category
        function populateDetails() {
            var medicineCategory = document.getElementById('medicine_category').value;
            var importanceField = document.getElementById('importance');
            var useField = document.getElementById('use');

            if(medicineData.hasOwnProperty(medicineCategory)) {
                importanceField.value = medicineData[medicineCategory].importance;
                useField.value = medicineData[medicineCategory].use;
            } else {
                importanceField.value = "";
                useField.value = "";
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Medicine Consumed</h2>
        <form method="post">
            <div class="form-group">
                <label for="medicine_category">Medicine Category:</label>
                <select id="medicine_category" name="medicine_category" onchange="populateDetails()" required>
                    <option value="">Select Medicine Category</option>
                    <option value="Antibiotics">Antibiotics</option>
                    <option value="Anticoccidials">Anticoccidials</option>
                    <option value="Antivirals">Antivirals</option>
                    <option value="Anthelmintics (Dewormers)">Anthelmintics (Dewormers)</option>
                    <option value="Vaccines">Vaccines</option>
                    <option value="Anti-inflammatory drugs">Anti-inflammatory drugs</option>
                    <option value="Vitamins and Supplements">Vitamins and Supplements</option>
                    <option value="Probiotics">Probiotics</option>
                    <option value="Disinfectants">Disinfectants</option>
                    <option value="Growth Promoters">Growth Promoters</option>
                </select>
            </div>
            <div class="form-group">
                <label for="importance">Importance:</label>
                <input type="text" id="importance" name="importance" readonly required>
            </div>
            <div class="form-group">
                <label for="use">Use:</label>
                <textarea id="use" name="use" rows="4" readonly required></textarea>
            </div>
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="person_in_charge">Person in Charge:</label>
                <input type="text" id="person_in_charge" name="person_in_charge" required>
            </div>
            <div class="form-group">
                <label for="total_medicine_store">Total Medicine in Store (kg):</label>
                <input type="number" id="total_medicine_store" name="total_medicine_store" required>
            </div>
            <div class="form-group">
                <label for="quantity_consumed">Quantity Consumed (kg):</label>
                <input type="number" id="quantity_consumed" name="quantity_consumed" required>
            </div>
            <button type="submit" name="submit">Submit</button>
            <a href="dashboard.php" class="btn btn-light">Go Back</a>
        </form>

        <!-- Display table of medicine consumed -->
        <h2>Medicine Consumption Records</h2>
        <table>
         <table>
            <thead>
                <tr>
                    <th>Medicine Category</th>
                    <th>Importance</th>
                    <th>Use</th>
                    <th>Date</th>
                    <th>Person in Charge</th>
                    <th>Total Medicine in Store (kg)</th>
                    <th>Quantity Consumed (kg)</th>
                    <th>Remaining Medicine in Store (kg)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch medicine consumption records with importance and use_purpose
                $sql = "SELECT id, medicine_category, importance, use_purpose, date, person_in_charge, total_medicine_store, quantity_consumed, remaining_medicine_store FROM tblMedicineConsumed";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);

                if($query->rowCount() > 0) {
                    foreach($results as $row) {
                        echo "<tr>";
                        echo "<td>{$row->medicine_category}</td>";
                        echo "<td>{$row->importance}</td>";
                        echo "<td>{$row->use_purpose}</td>";
                        echo "<td>{$row->date}</td>";
                        echo "<td>{$row->person_in_charge}</td>";
                        echo "<td>{$row->total_medicine_store}</td>";
                        echo "<td>{$row->quantity_consumed}</td>";
                        echo "<td>{$row->remaining_medicine_store}</td>";
                        
                        echo "<td class='table-actions'>";
                        echo "<a href='edit_medicine_consumed.php?id={$row->id}' class='button-edit'>Edit</a>";
                        echo "<a href='delete_medicine_consumed.php?id={$row->id}' class='button-delete'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>