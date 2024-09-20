<?php
include('includes/checklogin.php');
check_login();

// Check if the logged-in user has permission to access user management
if (!isset($_SESSION['odmsaid'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

$aid = $_SESSION['odmsaid'];
$sql = "SELECT * FROM tbladmin WHERE ID = :aid";
$query = $dbh->prepare($sql);
$query->bindParam(':aid', $aid, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);
if ($query->rowCount() > 0) {
    foreach ($results as $row) {
        if ($row->AdminName != "Admin") {
            // Redirect to unauthorized page or show a message
            echo "<script>alert('You are not authorized to access this page');</script>";
            echo "<script>window.location.href = 'dashboard.php'</script>";
            exit;
        }
    }
}

// Process block user action
if(isset($_GET['delid'])) {
    $rid=intval($_GET['delid']);
    $sql="UPDATE tbladmin SET Status='0' WHERE ID=:rid";
    $query=$dbh->prepare($sql);
    $query->bindParam(':rid',$rid,PDO::PARAM_STR);
    if($query->execute()) {
        echo "<script>alert('User blocked');</script>"; 
        echo "<script>window.location.href = 'userregister.php'</script>";
    } else {
        echo "<script>alert('Failed to block user');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php @include("includes/head.php");?>
<body>
    <div class="container-scroller">
        <!-- partial:../../partials/_navbar.html -->
        <?php @include("includes/header.php");?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:../../partials/_sidebar.html -->
            <?php @include("includes/sidebar.php");?>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="modal-header">
                                    <h5 class="modal-title" style="float: left;">User Permissions</h5>
                                </div>
                                <!-- start modal -->
                                <div id="editData" class="modal fade">
                                    <div class="modal-dialog ">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Change permissions</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" id="info_update">
                                                <?php @include("change_permissions.php");?>
                                            </div>
                                            <div class="modal-footer ">
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->
                                </div>
                                <!-- end modal -->

                                <div class="card-body table-responsive p-3">
                                    <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No.</th>
                                                <th class="d-none d-sm-table-cell" style="width: 20%">Permission Name</th>
                                                <th class="d-none d-sm-table-cell text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql="SELECT * FROM permissions";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                                            $cnt=1;
                                            if($query->rowCount() > 0) {
                                                foreach($results as $row) {    
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo htmlentities($cnt);?></td>
                                                        <td><?php echo htmlentities($row->permission);?></td>
                                                        <td class="d-none d-sm-table-cell text-center">
                                                            <button class="btn btn-primary btn-xs edit_data" id="<?php echo htmlentities($row->id); ?>" title="click for edit">Change Permission</button>
                                                        </td>
                                                    </tr>
                                                    <?php $cnt++;
                                                }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:../../partials/_footer.html -->
                <?php @include("includes/footer.php");?>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <?php @include("includes/foot.php");?>
    <!-- End custom js for this page -->
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('click','.edit_data',function() {
                var edit_id=$(this).attr('id');
                $.ajax({
                    url:"change_permissions.php",
                    type:"post",
                    data:{edit_id:edit_id},
                    success:function(data) {
                        $("#info_update").html(data);
                        $("#editData").modal('show');
                    }
                });
            });
        });
    </script>
</body>
</html>
