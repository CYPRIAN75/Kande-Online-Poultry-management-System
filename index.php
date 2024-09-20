<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $sql = "SELECT * FROM tbladmin WHERE UserName=:username AND Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    
    if($query->rowCount() > 0) {
        foreach ($results as $result) {
            $_SESSION['odmsaid'] = $result->ID;
            $_SESSION['login'] = $result->username;
            $_SESSION['names'] = $result->FirstName;
            $_SESSION['permission'] = $result->AdminName;
            $_SESSION['companyname'] = $result->CompanyName;
            $get = $result->Status;
        }
        
        $aa = $_SESSION['odmsaid'];
        $sql = "SELECT * FROM tbladmin WHERE ID=:aa";
        $query = $dbh->prepare($sql);
        $query->bindParam(':aa', $aa, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        
        if($query->rowCount() > 0) {
            foreach($results as $row) {
                if($row->Status == "1") { 
                    echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";      
                } else { 
                    echo "<script>alert('Your account was disabled. Please approach Admin.'); document.location ='index.php'; </script>";
                }
            } 
        } 
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php @include("includes/head.php");?>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <!-- Cover Page Background -->
            <div class="cover-page"></div>
            
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo" align="center">
                                <img class="img-avatar mb-3" src="companyimages/logo1.png" alt="">
                            </div>
                            <h3>Welcome to Kande Online Poultry Mangement System</h3>
                            <form role="form" id="" method="post" enctype="multipart/form-data" class="form-horizontal">  
                                <div class="form-group mb-3">
                                    <input type="text" class="form-control form-control-lg" name="username" id="exampleInputEmail1" placeholder="Username" required>
                                </div>
                                <div class="form-group mt-3">
                                    <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password" required>
                                </div>
                                <div class="mt-3">
                                    <button name="login" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                                </div>
                                <div class="text-center mt-4 font-weight-light"> 
                                    <a href="forgot_password.php" class="text-primary"> 
                                        Forgot Password
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <?php @include("includes/foot.php");?>
    <!-- endinject -->

    <style>
        /* Cover Page Background Styles */
        .cover-page {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('path_to_cover_image.jpg'); /* Replace with your cover image path */
            background-size: cover;
            z-index: -1;
        }
        
        /* Additional Styling */
        .auth-form-light {
            background: rgba(255, 255, 255, 0.8); /* Example: Light background for the form */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        /* Adjust other styles as needed */
    </style>
</body>
</html>
