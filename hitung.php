<?php
include 'function.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $periode = intval($_POST["periode"]);
    $conn = connect_db();
    $data_penjualan = get_data_penjualan($conn);
    $conn->close();

    $hasil_forecasting = single_moving_average($data_penjualan, $periode);
    $errors = calculate_errors(array_slice($data_penjualan, $periode), $hasil_forecasting);
    $mape = calculate_mape($errors);

    // Prediksi periode berikutnya
    $prediksi_periode_berikutnya = predict_next_period($data_penjualan, $periode);

    session_start();
    $_SESSION['hasil_forecasting'] = $hasil_forecasting;
    $_SESSION['periode'] = $periode;
    $_SESSION['data_penjualan'] = $data_penjualan;
    $_SESSION['errors'] = $errors;
    $_SESSION['mape'] = $mape;
    $_SESSION['prediksi_periode_berikutnya'] = $prediksi_periode_berikutnya;

    header("Location: tampilkan.php");
    exit();
}
?>
