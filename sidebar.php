<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <?php
            $aid = $_SESSION['odmsaid'];
            $sql = "SELECT * FROM tbladmin WHERE ID = :aid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':aid', $aid, PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);
            if ($query->rowCount() > 0) {
                foreach ($results as $row) {
                    ?>
                    <a href="#" class="nav-link">
                        <div class="nav-profile-image">
                            <?php if ($row->Photo == "avatar15.jpg") { ?>
                                <img class="img-avatar" src="assets/img/avatars/avatar15.jpg" alt="">
                            <?php } else { ?>
                                <img class="img-avatar" src="profileimages/<?php echo $row->Photo; ?>" alt="">
                            <?php } ?>
                            <span class="login-status online"></span>
                        </div>
                        <div class="nav-profile-text d-flex flex-column">
                            <span class="font-weight-bold mb-2"><?php echo $row->FirstName . ' ' . $row->LastName; ?></span>
                            <?php
                            $sql = "SELECT * FROM tblcompany";
                            $query = $dbh->prepare($sql);
                            $query->execute();
                            $companyResults = $query->fetchAll(PDO::FETCH_OBJ);
                            if ($query->rowCount() > 0) {
                                foreach ($companyResults as $companyRow) {
                                    ?>
                                    <span class="text-secondary text-small"><?php echo $companyRow->companyname; ?></span>
                                    <?php
                                }
                            } ?>
                        </div>
                    </a>
                    <?php
                }
            } ?>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#product-management" aria-expanded="false" aria-controls="product-management">
                <span class="menu-title">Product management</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-archive menu-icon"></i>
            </a>
            <div class="collapse" id="product-management">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="category.php">Manage Category</a></li>
                    <li class="nav-item"> <a class="nav-link" href="product.php">Manage Product</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#farm-expenses" aria-expanded="false" aria-controls="farm-expenses">
                <span class="menu-title">Farm Expenses</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-archive menu-icon"></i>
            </a>
            <div class="collapse" id="farm-expenses">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="feed_purchase.php">Feed Purchase</a></li>
                    <li class="nav-item"> <a class="nav-link" href="medicine_purchase.php">Medicine Purchase</a></li>
                    <li class="nav-item"> <a class="nav-link" href="payroll.php">Payroll</a></li>
                    <li class="nav-item"> <a class="nav-link" href="birds_mortality.php">Birds Mortality</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#farm-sales" aria-expanded="false" aria-controls="farm-sales">
                <span class="menu-title">Farm Produce</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-archive menu-icon"></i>
            </a>
            <div class="collapse" id="farm-sales">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="birds_produced.php">Birds Produced</a></li>
                    <li class="nav-item"> <a class="nav-link" href="eggs_produced.php">Eggs Produced</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#others" aria-expanded="false" aria-controls="others">
                <span class="menu-title">Consumption</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-archive menu-icon"></i>
            </a>
            <div class="collapse" id="others">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="feed_consumption.php">Feed Consumption</a></li>
                    <li class="nav-item"> <a class="nav-link" href="medicine_consumed.php">Medicine Consumption</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#farm-management" aria-expanded="false" aria-controls="farm-management">
                <span class="menu-title">Farm Management</span>
                <i class="menu-arrow"></i>
                <i class="mdi mdi-bank menu-icon"></i>
            </a>
            <div class="collapse" id="farm-management">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="farmprofile.php">Farm Profile</a></li>
                    <li class="nav-item"> <a class="nav-link" href="store.php">Manage Store</a></li>
                     
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="sell_product.php">
                <span class="menu-title">Sell Product</span>
                <i class="mdi mdi-cart menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="invoices.php">
                <span class="menu-title">Invoices</span>
                <i class="mdi mdi-book menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="supplier_invoice.php">
                <span class="menu-title">Supplier's Invoices</span>
                <i class="mdi mdi-book menu-icon"></i>
            </a>
        </li>

        <?php
        $aid = $_SESSION['odmsaid'];
        $sql = "SELECT * FROM tbladmin WHERE ID = :aid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':aid', $aid, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        if ($query->rowCount() > 0) {
            foreach ($results as $row) {
                if ($row->AdminName == "Admin") {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#user-management" aria-expanded="false" aria-controls="user-management">
                            <span class="menu-title">User Management</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-account-multiple menu-icon"></i>
                        </a>
                        <div class="collapse" id="user-management">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="userregister.php">Register User</a></li>
                                <?php
                                if ($row->CompanyName == "compconsult") {
                                    ?>
                                    <li class="nav-item"> <a class="nav-link" href="user_permission.php">User Permissions</a></li>
                                    <?php
                                } ?>
                            </ul>
                        </div>
                    </li>
                    <?php
                }
            }
        } ?>
    </ul>
</nav>
