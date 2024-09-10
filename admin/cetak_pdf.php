<?php
require '../vendor/autoload.php'; // Pastikan path ini benar
include '../config.php'; // Pastikan koneksi database

use Dompdf\Dompdf;
use Dompdf\Options;

// Ambil nama_tamu dari query parameter
$namaTamu = isset($_GET['nama_tamu']) ? $_GET['nama_tamu'] : '';

// Fungsi untuk mengambil data riwayat
function fetchRiwayatData($conn, $namaTamu) {
    $stmt = $conn->prepare("SELECT nama_tamu, asal_kelas, keluhan, penanganan, created_at FROM tamu WHERE nama_tamu = ?");
    $stmt->bind_param("s", $namaTamu);
    $stmt->execute();
    return $stmt->get_result();
}

$result = fetchRiwayatData($conn, $namaTamu);

// Inisialisasi dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);

// Mulai output buffering
ob_start();

$html = '<html><head><style>
    body { font-family: Arial, sans-serif; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
    </style></head><body>';

$html .= '<h1>Riwayat Kesehatan Siswa</h1>';
$html .= '<h2>Nama Tamu: ' . htmlspecialchars($namaTamu) . '</h2>';

// Ambil data kelas dari baris pertama hasil query
$firstRow = $result->fetch_assoc();
$html .= '<h3>Kelas: ' . htmlspecialchars($firstRow['asal_kelas']) . '</h3>';

$html .= '<table>
    <thead>
        <tr>
            <th>Keluhan</th>
            <th>Penanganan</th>
            <th>Tanggal Masuk UKS</th>
        </tr>
    </thead>
    <tbody>';

while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
        <td>' . htmlspecialchars($row['keluhan']) . '</td>
        <td>' . htmlspecialchars($row['penanganan']) . '</td>
        <td>' . htmlspecialchars($row['created_at']) . '</td>
    </tr>';
}

$html .= '</tbody>
</table>';

$html .= '</body></html>';

// Selesai output buffering
ob_end_clean();

// Load HTML ke dompdf
$dompdf->loadHtml($html);

// (Opsional) Set ukuran kertas dan orientasi
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Output PDF ke browser dengan nama file sesuai nama_tamu
$dompdf->stream($namaTamu . ' Riwayat Kesehatan.pdf', ['Attachment' => 0]);
?>
