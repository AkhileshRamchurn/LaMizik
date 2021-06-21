<?php require_once 'includes/admin_auth.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Analytics</title>
    <link rel="stylesheet" href="css/adminAnalytics_style.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<?php include 'includes/navbar.php'; ?>
<body>

    <?php include "includes/admin_sidebar.html"; ?>
    <div class="main">
        <div class="cards-container">
            <div class="card" id="accounts">
                <h4>Total Accounts</h4>
                <h3 id="total-accounts"></h3>
                <p><span id="banned"></span> Banned</p>
            </div>
            <div class="card" id="videos">
                <h4>Total Videos</h4>
                <h3 id="total-videos"></h3>
                <p><span id="lifetime-views"></span> Lifetime Views</p>
            </div>
            <div class="card" id="donations">
                <h4>Total Donation to Lamizik</h4>
                <h3>$<span id="total-donations"></span></h3>
                <p>$<span id="donations-this-month"></span> Received this Month</p>
            </div>
            <div class="card" id="requests">
                <h4>Total Pending Requests</h4>
                <h3 id="pending-requests"></h3>
                <p><span id="received-requests"></span> Received Today</p>
            </div>
        </div>
        <div class="graphs-container">
            <div class="chart-grid" id="chart-views"></div>
            <div class="chart-grid" id="chart-userAccounts"></div>
        </div>
    </div>
</body>
<script src="scripts/adminAnalytics.js"></script>
</html>