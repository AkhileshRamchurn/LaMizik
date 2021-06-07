//Admin Cards------------------------------------------------------------------

var url1 = "ajax/getAdminCardsInfo.php";
$.ajax({
    url: url1,
    accepts: "application/json",
    method: "POST", 
    error: function(xhr){
            alert("An error occured: " + xhr.status + " " + xhr.statusText);
    }
})
.done(function(data)
{
    console.log(data);
    $("#total-accounts").html(data[0]["Users"]);
    $("#banned").html(data[0]["Banned"]);
    $("#total-videos").html(data[0]["Videos"]);
    $("#lifetime-views").html(data[0]["Views"]);
    $("#total-donations").html(data[0]["Donation"]);
    $("#donations-this-month").html(data[0]["DonationMonth"]);
    $("#pending-requests").html(data[0]["Requests"]);
    $("#received-requests").html(data[0]["RequestsToday"]);
});


//Chart 1------------------------------------------------------------------

var url2 = "ajax/getTotalViewsByMonthAjax.php";
$.ajax({
    url: url2,
    accepts: "application/json",
    method: "POST", 
    error: function(xhr){
            alert("An error occured: " + xhr.status + " " + xhr.statusText);
    }
})
.done(function(data)
{
    var options = {
        colors: ["#29200c"],
        series: [{
          name: "Views",
          data: Object.values(data)
        }],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        title: {
            text: 'Video Views this Year',
            align: 'left'
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        xaxis: {
            categories: Object.keys(data),
        }
    };
    
    var chart = new ApexCharts(document.querySelector("#chart-views"), options);
    chart.render();
});


//Chart 2------------------------------------------------------------------

var url3 = "ajax/getTotalRegisteredUsersByMonthAjax.php";
$.ajax({
    url: url3,
    accepts: "application/json",
    method: "POST", 
    error: function(xhr){
            alert("An error occured: " + xhr.status + " " + xhr.statusText);
    }
})
.done(function(data)
{
    var options2 = {
        colors: ["#edb33d"],
        series: [{
          name: "Accounts",
          data: Object.values(data)
        }],
        chart: {
            height: 350,
            type: 'line',
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        title: {
            text: 'New Users Registered this Year',
            align: 'left'
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        xaxis: {
            categories: Object.keys(data),
        }
    };
    
    var chart = new ApexCharts(document.querySelector("#chart-userAccounts"), options2);
    chart.render();
});