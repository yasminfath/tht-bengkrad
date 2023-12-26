<?php
$host = "127.0.0.1";
$username = "root";
$password = "sayati";
$database = "server_5G";
$port = "3306";
    
$conn = mysqli_connect($host, $username, $password, $database, $port);


if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}


$query = "SELECT * FROM quality";
$result = $conn->query($query);

$dataPoints = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $LatencyValue = (int) str_replace(' ms', '', $row["Latency"]);
        $dataPoints[] = array("x" => strtotime($row["Timestamp"]), "y" => $LatencyValue);
    }
}


$conn->close();
?>

<!DOCTYPE HTML>
<html>
<head>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script>
        window.onload = function () {
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title: {
                    text: "Latency"
                },
                axisX: {
                    title: "Time"
                },
                axisY: {
                    title: "Latency (ms)"
                },
                data: [{
                    type: "spline",
                    markerSize: 5,
                    xValueFormatString: "D/M/YYYY",
                    yValueFormatString: "#0",
                    xValueType: "dateTime",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });

            chart.render();
        }
    </script>
</head>
<body>
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
</body>
</html>

