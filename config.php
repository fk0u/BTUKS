<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'buku_tamu_uks';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fungsi untuk memanggil Supabase API
function callSupabase($endpoint, $data = null, $method = 'GET') {
    $ch = curl_init();

    // URL API Supabase (ganti dengan URL project kamu)
    $url = "https://uppoiodhfbeywolkxtfl.supabase.co/rest/v1/$endpoint";

    // API Key dari Supabase (ganti dengan API Key kamu)
    $headers = [
        'Content-Type: application/json',
        'apikey: ' . 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InVwcG9pb2RoZmJleXdvbGt4dGZsIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MjUwMDAzMjYsImV4cCI6MjA0MDU3NjMyNn0.aq--OxUkDB_rpJ0_-kFYyPMXIPL2r24P_b8-YLRjHIo',
        'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InVwcG9pb2RoZmJleXdvbGt4dGZsIiwicm9sZSI6ImFub24iLCJpYXQiOjE3MjUwMDAzMjYsImV4cCI6MjA0MDU3NjMyNn0.aq--OxUkDB_rpJ0_-kFYyPMXIPL2r24P_b8-YLRjHIo',
    ];

    // Setting cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // Jika metode POST/PATCH, kirimkan data
    if ($method === 'POST' || $method === 'PATCH') {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }

    // Jika metode DELETE, set metode cURL ke DELETE
    if ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    // Eksekusi cURL dan ambil respons
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

// Include Tailwind CSS
echo '<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">';
?>
