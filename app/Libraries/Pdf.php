<?php

namespace App\Libraries;

use Codedge\Fpdf\Fpdf\Fpdf;

class Pdf extends Fpdf
{
    public $data;

    public function __construct($data = null)
    {
        parent::__construct(); // Panggil konstruktor FPDF
        $this->data = $data;  // Simpan data yang diterima
    }

    // Header
    function Header()
    {
        // Menambahkan logo
        $this->Image(public_path('assets/img/logo/logo.png'), 22, 8, 24);
        $this->SetY(11);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Times', 'B', 12);
        $this->SetX(47);
        $this->MultiCell(154, 5, "RUKUN TETANGGA ".$this->data->pembuat->rt." RUKUN WARGA ".$this->data->pembuat->rw, 0, 'C', FALSE);
        $this->SetX(47);
        $this->MultiCell(154, 6, "KELURAHAN BABAKAN KECAMATAN BABAKAN CIPARAY", 0, 'C', FALSE);
        $this->SetFont('Times', 'B', 14);
        $this->SetX(47);
        $this->MultiCell(154, 7, "KOTA BANDUNG", 0, 'C', FALSE);

        // Garis horizontal
        $this->SetY(40);
        $this->SetX(14);
        $this->MultiCell(182, 0.2, "", 1, 'C', TRUE);
        $this->SetY(40.9);
        $this->SetX(14);
        $this->MultiCell(182, 0.2, "", 1, 'C', TRUE);

        $this->Ln(8.1);
    }

    // Footer (jika diperlukan)
    // function Footer()
    // {
    //     // Posisi di 1.5 cm dari bawah
    //     $this->SetY(-15);
    //     $this->SetFont('Arial', 'I', 8);
    //     $this->Cell(0, 10, 'Halaman ' . $this->PageNo(), 0, 0, 'C');
    // }
}
