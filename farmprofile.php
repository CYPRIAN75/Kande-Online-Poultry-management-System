<?php
include('includes/checklogin.php');
include('includes/dbconnection.php'); // Include database connection file

check_login(); // Ensures user is logged in

// Check if the user is logged in and is an admin
if ($_SESSION['permission'] !== 'Admin') {
    echo '<script>alert("Sorry, you do not have permission for this action")</script>';
    echo '<script>window.location.href="dashboard.php";</script>';
    exit;
}

if (isset($_POST['submit'])) {
    // Fetch and sanitize input data
    $companyemail = trim($_POST['companyemail']);
    $companyname = trim($_POST['companyname']);
    $companyaddress = trim($_POST['companyaddress']);
    $regno = trim($_POST['regno']);
    $country = trim($_POST['country']);
    $mobno = trim($_POST['mobilenumber']);

    // Validate inputs (if needed)

    // Prepare and execute SQL update statement
    $sql = "UPDATE tblcompany SET companyaddress = :companyaddress, companyname = :companyname, companyemail = :companyemail, regno = :regno, companyphone = :mobilenumber, country = :country";
    $query = $dbh->prepare($sql);
    $query->bindParam(':companyaddress', $companyaddress, PDO::PARAM_STR);
    $query->bindParam(':companyemail', $companyemail, PDO::PARAM_STR);
    $query->bindParam(':regno', $regno, PDO::PARAM_STR);
    $query->bindParam(':country', $country, PDO::PARAM_STR);
    $query->bindParam(':mobilenumber', $mobno, PDO::PARAM_STR);
    $query->bindParam(':companyname', $companyname, PDO::PARAM_STR);
    
    if ($query->execute()) {
        echo '<script>alert("Profile has been updated")</script>';
    } else {
        echo '<script>alert("Update failed! Try again later")</script>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<?php include("includes/head.php");?>
<body>
    <div class="container-scroller">
        <?php include("includes/header.php");?>
        <div class="container-fluid page-body-wrapper">
            <?php include("includes/sidebar.php");?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="modal-header">
                                    <h5 class="modal-title" style="float: left;">Company details</h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    // Fetch company details for display
                                    $sql = "SELECT * FROM tblcompany";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $row) {
                                    ?>
                                            <form method="post">
                                                <div class="control-group">
                                                    <label class="control-label" for="basicinput">Logo</label>
                                                    <div class="controls">
                                                        <?php 
                                                        if ($row->companylogo == "avatar15.jpg") { 
                                                        ?>
                                                            <img class=""  src="companyimages/logo1.png" alt="" width="100" height="100">
                                                        <?php 
                                                        } else { 
                                                        ?>
                                                            <img style="height: 100px; width: 100px;" src="companyimages/<?php echo $row->companylogo;?>" width="150" height="100">
                                                        <?php 
                                                        }
                                                        ?>
                                                    </div>
                                                </div>  
                                                <div>&nbsp;</div>
                                                <div class="row">
                                                    <div class="form-group row col-md-6">
                                                        <label class="col-12" for="register1-username">Company name:</label>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control" name="companyname" value="<?php echo htmlspecialchars($row->companyname);?>" >
                                                        </div>
                                                    </div>
                                                    <div class="form-group row col-md-6">
                                                        <label class="col-12" for="register1-email">Company reg no.:</label>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control" name="regno" value="<?php echo htmlspecialchars($row->regno);?>" required='true'  >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group row col-md-6">
                                                      <label class="col-12" for="register1-email">Physical address:</label>
                                                      <div class="col-12">
                                                        <input type="text" class="form-control" name="companyaddress" value="<?php echo htmlspecialchars($row->companyaddress);?>" placeholder="Enter company address" required='true'  >
                                                    </div>
                                                </div>
                                                <div class="form-group row col-md-6">
                                                    <label class="col-12" for="register1-email">Company email:</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" name="companyemail" value="<?php echo htmlspecialchars($row->companyemail);?>" required='true' >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row"> 
                                                <div class="form-group row col-md-6">
                                                    <label class="col-12" for="register1-password">Country:</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" name="country" value="<?php echo htmlspecialchars($row->country);?>" required='true' >
                                                    </div>
                                                </div>
                                                <div class="form-group row col-md-6">
                                                    <label class="col-12" for="register1-password">Contact Number:</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" name="mobilenumber" value="0<?php echo htmlspecialchars($row->companyphone);?>" required='true' placeholder="Enter company contact no" maxlength='10'>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php 
                                        }
                                    } 
                                    ?>
                                    <br>
                                    <button type="submit" name="submit" class="btn btn-primary btn-fw mr-2" style="float: left;">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include("includes/footer.php");?>
        </div>
    </div>
    <?php include("includes/foot.php");?>
</body>
</html>
