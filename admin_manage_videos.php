<?php require_once 'includes/admin_auth.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Videos - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.5.0/mdb.min.css" rel="stylesheet"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.5.0/mdb.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="scripts/adminManageVideo.js"></script>
    <link rel="stylesheet" href="css/adminManageVideo.css">
</head>
<body>
    <?php include "includes/admin_sidebar.html"; ?>
    <div class="main">
        <div class="video-list-container">
            <form method="GET">
                <div class="search-box">
                    <div class="input-group">
                        <input name="searchkey" type="search" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" value="<?php if (isset($_GET['searchkey'])) echo $_GET['searchkey']; ?>"/>
                        <button type="submit" class="btn btn-outline-dark">search</button>
                    </div>
                </div>
            </form>
            <div class="list-controls">
                <div class="video-filter">
                    <text>Filter By</text>
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-mdb-toggle="dropdown" aria-expanded="false">Video Status</button>
                        <ul id="video_status" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li value="Pending"><a class="dropdown-item" href="#">Pending</a></li>
                            <li value="Rejected"><a class="dropdown-item" href="#">Rejected</a></li>
                            <li value="Deleted"><a class="dropdown-item" href="#">Deleted</a></li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-mdb-toggle="dropdown" aria-expanded="false">Video Type</button>
                        <ul id="video_type" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li value="all"><a class="dropdown-item" href="#">All</a></li>
                            <li value="performance"><a class="dropdown-item" href="#">Performance</a></li>
                            <li value="lesson"><a class="dropdown-item" href="#">Lesson</a></li>
                        </ul>
                    </div>
                </div>
                <div class="video-sort"></div>
                    <text>Sort By</text>
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-mdb-toggle="dropdown" aria-expanded="false">Sort Value</button>
                        <ul id="video_sort" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li value="first"><a class="dropdown-item" href="#">Newest</a></li>
                            <li value="last"><a class="dropdown-item" href="#">Oldest</a></li>
                            <li value="titleAsc"><a class="dropdown-item" href="#">Title (Ascending)</a></li>
                            <li value="titleDesc"><a class="dropdown-item" href="#">Title (Descending)</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="video-list"></div>
        </div>
    </div>
</body>
</html>