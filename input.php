<?php
    include 'function.php';
    $conn = connect_db();
    $data_penjualan = get_all_penjualan($conn);
    $conn->close();
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
                            <li class="breadcrumb-item active">Halaman Input Data</li>
                        </ol>

<hr>
<form action="simpan.php" method="post">

<!-- area inputan -->
<div class="row" >
<div class="col-xl-6">
<div class="card mb-4">
<div class="card-header">
    <label for="tanggal">Tanggal Penjualan:</label>
    <input type="date" id="tanggal" name="tanggal" class="datatable-input"  required>
</div>
</div>
</div>
<div class="col-xl-6">
<div class="card mb-4">
<div class="card-header">
    <label for="jumlah_penjualan">Jumlah Penjualan:</label>
    <input type="number" id="jumlah_penjualan" name="jumlah_penjualan" class="datatable-input" required>
</div>
</div>
</div>
</div>
<input type="submit" value="Simpan Data Penjualan" class="btn btn-primary">
</form>
<hr>
<!-- table -->
<div class="card mb-4">
<div class="card-header">
<i class="fas fa-table me-1"></i>
Data Penjualan
</div>
<div class="card-body">

<table class="datatable-table">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Jumlah Penjualan</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($data_penjualan) > 0): ?>
            <?php foreach ($data_penjualan as $data): ?>
                <tr>
                    <td><?php echo htmlspecialchars($data['tanggal']); ?></td>
                    <td><?php echo htmlspecialchars($data['jumlah_penjualan']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="2">Tidak ada data penjualan</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
                            </div>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
