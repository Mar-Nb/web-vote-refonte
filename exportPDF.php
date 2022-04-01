<?php
require('fpdf.php');

class PDF extends FPDF {

  // Colored table
  function FancyTable($header, $data) {
    // Colors, line width and bold font
    $this->SetFillColor(62, 63, 58);
    $this->SetTextColor(255);
    $this->SetDrawColor(255);
    $this->SetLineWidth(1.5);
    $this->SetFont("Helvetica", "B");

    // Header
    $w = array(20, 40, 50, 80);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 10, utf8_decode($header[$i]), "B", 0, "L", true);

    $this->Ln();

    // Color and font restoration
    $this->SetFillColor(72, 73, 68);
    $this->SetFont("Helvetica");

    // Data
    $fill = false;
    for ($i = 0; $i < count($data); $i++) { 
      if ($i % count($w) == 0 && $i != 0) {
        $this->Ln();
        $fill = !$fill;
        if ($fill) { $this->SetFillColor(62, 63, 58); }
        else { $this->SetFillColor(72, 73, 68); }  
      }

      $this->Cell($w[$i % count($w)], 10, utf8_decode($data[$i]), 0, 0, "", true);
    }
  }
}

$pdf = new PDF();

// Column headings
$header = array("#", "Moyenne", "Nom", "Matière");
$data = explode(",", $_POST["data"]);

// Data loading
$pdf->AddPage();
$pdf->FancyTable($header, $data);
$pdf->Output("D", "export.pdf");
