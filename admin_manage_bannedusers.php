<?php require_once 'includes/admin_auth.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Banned Users - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.5.0/mdb.min.css" rel="stylesheet"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.5.0/mdb.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="scripts/adminManageUser.js"></script>
    <link rel="stylesheet" href="css/adminManageUser.css">
</head>
<?php include 'includes/navbar.php'; ?>
<body>
    <?php include "includes/admin_sidebar.html"; ?>
    <div class="main">
        <div class="user-list-container">
            <form method="GET">
                <div class="search-box">
                    <div class="input-group">
                        <input name="searchkey" type="search" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" value="<?php if (isset($_GET['searchkey'])) echo $_GET['searchkey']; ?>"/>
                        <button type="submit" class="btn btn-outline-dark">search</button>
                    </div>
                </div>
            </form>
            <div class="list-controls">
                <div class="user-sort">
                    <text>Sort By</text>
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-mdb-toggle="dropdown" aria-expanded="false">Sort Value</button>
                        <ul id="user_sort" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li value="nameAsc"><a class="dropdown-item" href="#">Username (Ascending)</a></li>
                            <li value="nameDesc"><a class="dropdown-item" href="#">Username (Descending)</a></li>
                            <li value="idAsc"><a class="dropdown-item" href="#">User ID (Ascending)</a></li>
                            <li value="idDesc"><a class="dropdown-item" href="#">User ID (Descending)</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="user-list"></div>
        </div>
    </div>
</body>
</html>