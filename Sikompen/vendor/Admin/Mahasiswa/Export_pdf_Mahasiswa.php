<?php
require_once dirname(__FILE__) . '/../../../vendor/tecnickcom/tcpdf/tcpdf.php';
require_once('../../../vendor/Config.php');
class PDF extends TCPDF
{
    // Page header
    public function Header()
    {
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 10, 'Daftar Mahasiswa', 0, 1, 'C');
        $this->Ln(5);
    }

    // Page footer
    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Halaman ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

// Inisialisasi dokumen PDF
$pdf = new PDF('L', 'mm', 'A4', true, 'UTF-8', false);

// Set informasi dokumen
$pdf->SetCreator('DesignKompen');
$pdf->SetAuthor('Admin');
$pdf->SetTitle('Daftar Mahasiswa');

// Set margin
$pdf->SetMargins(10, 15, 10);

// Tambah halaman
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 10);

// Header tabel
$header = array('No', 'NIM', 'Nama', 'Kelas', 'Semester', 'Prodi', 'Terlambat (Menit)', 'Alfa (Menit)', 'Total');

// Lebar kolom (total = 275 untuk A4 Landscape)
$w = array(15, 30, 70, 30, 20, 40, 30, 30, 30);

// Buat header tabel
$pdf->SetFillColor(220, 220, 220);
$pdf->SetTextColor(0);
$pdf->SetFont('', 'B');

foreach ($header as $i => $h) {
    $pdf->Cell($w[$i], 7, $h, 1, 0, 'C', true);
}
$pdf->Ln();

// Isi tabel
$pdf->SetFont('', '');
$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0);

try {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM tbl_mahasiswa ORDER BY NIM");
    $mahasiswaData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($mahasiswaData)) {
        $pdf->Cell(0, 10, 'Tidak ada data mahasiswa.', 1, 1, 'C');
    } else {
        $no = 1;
        foreach ($mahasiswaData as $row) {
            $pdf->Cell($w[0], 6, $no++, 1, 0, 'C');
            $pdf->Cell($w[1], 6, htmlspecialchars($row['NIM']), 1, 0, 'C');

            // Untuk nama mahasiswa, gunakan MultiCell jika teks panjang
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            $pdf->MultiCell($w[2], 6, htmlspecialchars($row['NAMA']), 1, 'L');
            $pdf->SetXY($x + $w[2], $y);

            $pdf->Cell($w[3], 6, htmlspecialchars($row['KELAS']), 1, 0, 'C');
            $pdf->Cell($w[4], 6, htmlspecialchars($row['SEMESTER']), 1, 0, 'C');
            $pdf->Cell($w[5], 6, htmlspecialchars($row['PRODI']), 1, 0, 'L');
            $pdf->Cell($w[6], 6, htmlspecialchars($row['JUMLAH_TERLAMBAT']), 1, 0, 'C');
            $pdf->Cell($w[7], 6, htmlspecialchars($row['JUMLAH_ALFA']), 1, 0, 'C');
            $pdf->Cell($w[8], 6, htmlspecialchars($row['TOTAL']), 1, 0, 'C');
            $pdf->Ln();
        }
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Output PDF
$pdf->Output('daftar_mahasiswa.pdf', 'D');
