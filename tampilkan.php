<?php
session_start();

if (!isset($_SESSION['hasil_forecasting'])) {
    header("Location: index.php");
    exit();
}
$hasil_forecasting = $_SESSION['hasil_forecasting'];
$periode = $_SESSION['periode'];
$data_penjualan = $_SESSION['data_penjualan'];
$errors = $_SESSION['errors'];
$mape = $_SESSION['mape'];
$prediksi_periode_berikutnya = $_SESSION['prediksi_periode_berikutnya'];

// Format angka dengan 2 digit desimal
function format_decimal($value) {
    return number_format($value, 2, '.', '');
}

// Tangani MAPE yang 0
$mape_display = format_decimal($mape) . '%';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Forecasting Penjualan Obat</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php">Apotek Bululawang 2</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Beranda
                            </a>
                            <a class="nav-link" href="input.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Input Data
                            </a>
                            <a class="nav-link" href="ramal.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Peramalan
                            </a>
                            </nav>
            </div>
            <div id="layoutSidenav_content">
                
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Sistem Peramalan Penjualan Obat</h1>
                        
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Hasil Forecasting Penjualan Obat Periode: <?php echo htmlspecialchars($periode); ?></li>
                      </ol>

<hr>
<div class="card mb-4">
<div class="card-header">
<i class="fas fa-table me-1"></i>
Data Penjualan dan Forecasting
</div>

<div class="card-body">
    <table class="datatable-table">
        <tr>
            <th>Periode</th>
            <th>Penjualan Aktual</th>
            <th>Peramalan</th>
            <th>Error Absolut</th>
            <th>Percentage Error</th>
        </tr>
        <?php
        $total_data = count($data_penjualan);
        $forecast_count = count($hasil_forecasting);
        
        for ($i = 0; $i < $total_data; $i++) {
            $penjualan_aktual = isset($data_penjualan[$i]) ? format_decimal($data_penjualan[$i]) : '0';
            $forecast = ($i >= $periode) ? format_decimal(isset($hasil_forecasting[$i - $periode]) ? $hasil_forecasting[$i - $periode] : '0') : '0';
            $error_abs = ($i >= $periode) ? format_decimal(isset($errors[$i - $periode]) ? $errors[$i - $periode]['absolute_error'] : '0') : '0';
            $percentage_error = ($i >= $periode) ? (isset($errors[$i - $periode]['percentage_error']) ? format_decimal($errors[$i - $periode]['percentage_error']) . '%' : '0%') : '0%';
            
            echo "<tr>";
            echo "<td>" . ($i + 1) . "</td>";
            echo "<td>" . $penjualan_aktual . "</td>";
            echo "<td>" . $forecast . "</td>";
            echo "<td>" . $error_abs . "</td>";
            echo "<td>" . $percentage_error . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
</div>

    <!--<p>MAPE: <?php echo htmlspecialchars($mape_display); ?></p> -->
    <h4>Prediksi Periode Berikutnya: <?php echo format_decimal($prediksi_periode_berikutnya); ?></h4>
<hr>
<div class="card mb-4">
<div class="card-header">
<i class="fas fa-table me-1"></i>
    Grafik Penjualan dan Forecasting
</div>   
    <canvas id="forecastChart" width="400" height="200"></canvas>
    <script>
        var ctx = document.getElementById('forecastChart').getContext('2d');
        var forecastChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [
                    <?php
                    for ($i = 0; $i < $total_data; $i++) {
                        echo '"' . ($i + 1) . '",';
                    }
                    ?>
                    '<?php echo $total_data + 1; ?>' // Label untuk periode berikutnya
                ],
                datasets: [
                    {
                        label: 'Penjualan Aktual',
                        data: [
                            <?php
                            foreach ($data_penjualan as $value) {
                                echo format_decimal($value) . ',';
                            }
                            ?>
                        ],
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: false
                    },
                    {
                        label: 'Forecast',
                        data: [
                            <?php
                            for ($i = 0; $i < $periode; $i++) {
                                echo 'null,';
                            }
                            foreach ($hasil_forecasting as $value) {
                                echo format_decimal($value) . ',';
                            }
                            echo format_decimal($prediksi_periode_berikutnya) . ','; // Data untuk periode berikutnya
                            ?>
                        ],
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        fill: false
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

                    </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Tugas Akhir an. Dedy Alwi (NIM: 23.51-0015)</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
