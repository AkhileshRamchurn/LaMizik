<?php require_once 'includes/admin_auth.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reports - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.5.0/mdb.min.css" rel="stylesheet"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.5.0/mdb.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="scripts/adminManageReport.js"></script>
    <link rel="stylesheet" href="css/adminManageReport.css">
</head>
<?php include 'includes/navbar.php'; ?>
<body>
    <?php include "includes/admin_sidebar.html"; ?>
    <div class="main">
        <div class="comment-list-container">
            <form method="GET">
                <div class="search-box">
                    <div class="input-group">
                        <input name="searchkey" type="search" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" value="<?php if (isset($_GET['searchkey'])) echo $_GET['searchkey']; ?>"/>
                        <button type="submit" class="btn btn-outline-dark">search</button>
                    </div>
                </div>
            </form>
            <div class="list-controls">
                <div class="comment-sort">
                    <text>Sort By</text>
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-mdb-toggle="dropdown" aria-expanded="false">Sort Value</button>
                        <ul id="comment_sort" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li value="first"><a class="dropdown-item" href="#">Newest</a></li>
                            <li value="last"><a class="dropdown-item" href="#">Oldest</a></li>
                            <li value="p_nameAsc"><a class="dropdown-item" href="#">Commenter Username (Ascending)</a></li>
                            <li value="p_nameDesc"><a class="dropdown-item" href="#">Commenter Username (Descending)</a></li>
                            <li value="r_nameAsc"><a class="dropdown-item" href="#">Reporter Username (Ascending)</a></li>
                            <li value="r_nameDesc"><a class="dropdown-item" href="#">Reporter Username (Descending)</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="comment-list"></div>
        </div>
    </div>
</body>
</html>