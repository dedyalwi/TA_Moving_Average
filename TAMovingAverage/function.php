<?php
function connect_db() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "forecasting_obat";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function get_data_penjualan($conn) {
    $sql = "SELECT jumlah_penjualan FROM penjualan ORDER BY tanggal ASC";
    $result = $conn->query($sql);

    $data_penjualan = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data_penjualan[] = $row['jumlah_penjualan'];
        }
    }
    return $data_penjualan;
}

function get_all_penjualan($conn) {
    $sql = "SELECT tanggal, jumlah_penjualan FROM penjualan ORDER BY tanggal ASC";
    $result = $conn->query($sql);

    $data_penjualan = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data_penjualan[] = $row;
        }
    }
    return $data_penjualan;
}

function single_moving_average($data_penjualan, $periode) {
    $n = count($data_penjualan);
    if ($n < $periode) {
        return [];
    }

    $moving_averages = [];
    for ($i = 0; $i <= $n - $periode; $i++) {
        $sum = 0;
        for ($j = 0; $j < $periode; $j++) {
            $sum += $data_penjualan[$i + $j];
        }
        $moving_averages[] = $sum / $periode;
    }
    
    return $moving_averages;
}

function calculate_errors($data_penjualan, $forecasts) {
    $errors = [];
    for ($i = 0; $i < count($forecasts); $i++) {
        $penjualan = isset($data_penjualan[$i]) ? $data_penjualan[$i] : 0;
        $forecast = isset($forecasts[$i]) ? $forecasts[$i] : 0;
        
        $error = $penjualan - $forecast;
        $absolute_error = abs($error);
        $percentage_error = ($penjualan != 0) ? ($absolute_error / $penjualan) * 100 : 0;
        
        $errors[] = [
            'error' => $error,
            'absolute_error' => $absolute_error,
            'percentage_error' => $percentage_error
        ];
    }
    return $errors;
}

function calculate_mape($errors) {
    $total_percentage_error = 0;
    $count = 0;

    foreach ($errors as $error) {
        $percentage_error = isset($error['percentage_error']) ? $error['percentage_error'] : 0;
        if (is_numeric($percentage_error)) {
            $total_percentage_error += $percentage_error;
            $count++;
        }
    }

    return $count > 0 ? round($total_percentage_error / $count, 2) : 0; // Rata-rata dan bulatkan menjadi 2 digit
}

function predict_next_period($data_penjualan, $periode) {
    $n = count($data_penjualan);
    $moving_averages = single_moving_average($data_penjualan, $periode);
    
    return count($moving_averages) > 0 ? end($moving_averages) : null;
}
?>
