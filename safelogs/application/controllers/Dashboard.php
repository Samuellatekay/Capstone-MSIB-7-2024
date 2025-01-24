<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Pastikan user login terlebih dahulu
        if (!$this->session->userdata('username')) {
            redirect('login');
        }
    }

    public function index() {
    // Path file JSON
    $filePath = 'C:/laragon/www/capstone/backendd/hasil_evaluasi_model.json';

    // Inisialisasi default data
    $viewData = [
        'username' => ucfirst($this->session->userdata('username')),
        'total_logs' => 0,
        'log_data' => [
            'Normal' => 0,
            'SQL Injection' => 0,
            'XSS' => 0,
            'Brute Force' => 0,
        ],
    ];

    // Cek apakah file JSON ada
    if (file_exists($filePath)) {
        // Baca file JSON
        $jsonData = file_get_contents($filePath);
        $data = json_decode($jsonData, true);

        // Update data jika file JSON valid
        if (!empty($data['metrics_train'])) {
            // Mengambil hasil distribusi dari metrics_train
            $distribution = $data['metrics_train']['distribution'] ?? [];

            // Memetakan distribusi sesuai kategori
            if (count($distribution) === 4) {
                $viewData['log_data']['Normal'] = $distribution[0] ?? 0;      // 23 untuk Normal
                $viewData['log_data']['SQL Injection'] = $distribution[1] ?? 0;  // 0 untuk SQL Injection
                $viewData['log_data']['Brute Force'] = $distribution[2] ?? 0;  // 52 untuk Brute Force
                $viewData['log_data']['XSS'] = $distribution[3] ?? 0;         // 6 untuk XSS
                $viewData['total_logs'] = array_sum($distribution);  // Total log = jumlah distribusi
            }
        }
    }

    // Load view dengan data
    $this->load->view('dashboard/v_dashboard', $viewData);
}


}
