<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_start();

require('tfpdf.php');

class PDF extends tFPDF {
    private $name;
    private $property;
    private $cadastre;
    private $contacts;
    protected $fullTexts;

    function __construct() {
        parent::__construct();
        $this->fullTexts = require('texts.php');
    }

    function setPersonalInfo($name, $property, $cadastre, $contacts) {
        $this->name = $name;
        $this->property = $property;
        $this->cadastre = $cadastre;
        $this->contacts = $contacts;
    }

    function Header() {
        // Only show header on first page
        if ($this->PageNo() == 1) {
            $this->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
            $this->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
            $this->SetFont('DejaVu','',10);
            
            // Top address alignment to the right
            $this->Cell(0, 5, 'Vides pārraudzības valsts birojam', 0, 1, 'R');
            $this->Cell(0, 5, 'Rūpniecības ielā 23,Rīgā, LV-1045', 0, 1, 'R');
            $this->Cell(0, 5, 'pasts@vpvb.gov.lv', 0, 1, 'R');
            
            $this->Ln(10);
            
            // Main text paragraph
            $text1 = 'Iebildumi un priekšlikumi par Vēja elektrostaciju parka "Nitaure" izbūvi Amatas, Nītaures, Skujenes un Zaubes pagastā, Cēsu novadā ietekmes uz vidi novērtējuma sākotnējās sabiedriskās apspriešanas ietvaros';
            $this->MultiCell(0, 5, $text1, 0, 'L');
            
            $this->Ln(10);
            
            // Second paragraph
            $text2 = 'Pamatojoties uz laikrakstā "Cēsu novada vēstis" Nr. 12 (263) 2024.gada 27.decembrī publicēto Paziņojumu par vēja elektrostaciju parka "Nitaure" izbūves Amatas, Nītaures, Skujenes un Zaubes pagastā, Cēsu novadā ietekmes uz vidi novērtējuma sākotnējo sabiedrisko apspriešanu, sniedzu šādus iebildumus un priekšlikumus par paredzēto darbību un par ietekmes uz vidi novērtējuma sākotnējās sabiedriskās apspriešanas materiāliem:';
            $this->MultiCell(0, 5, $text2, 0, 'L');
            
            $this->Ln(5);
            $text3 = '1. Par paredzēto darbību: Iebildu pret vēja elektrostaciju (VES) parka "Nitaure" izbūvi,';
            $this->Cell(0, 5, $text3, 0, 1, 'L');
            $this->Cell(0, 5, 'jo:', 0, 1, 'L');
            
            $this->Ln(10);
        }
    }

    function Footer() {
        // Only show footer if it's not the first page
        if ($this->PageNo() > 1) {
            $this->SetY(-50);
            $this->SetFont('DejaVu','',10);
            
            $this->Cell(0, 5, '29.01.2025', 0, 1, 'L');
            $this->Ln(5);
            
            $this->Cell(0, 5, $this->name, 0, 1, 'L');
            $this->Cell(0, 5, $this->property, 0, 1, 'L');
            if (!empty($this->cadastre)) {
                $this->Cell(0, 5, $this->cadastre, 0, 1, 'L');
            }
            if (!empty($this->contacts)) {
                $this->Cell(0, 5, $this->contacts, 0, 1, 'L');
            }
        }
    }

    function getFullText($category, $item) {
        return isset($this->fullTexts[$category][$item]) ? $this->fullTexts[$category][$item] : '';
    }
}

// Initialize PDF
$pdf = new PDF();

// Get form data
$name = $_POST['name'] ?? '';
$surname = $_POST['surname'] ?? '';
$fullname = $name . ' ' . $surname;
$property = $_POST['address'] ?? '';
$cadastre = $_POST['kadastra'] ?? '';
$contacts = $_POST['kontakti'] ?? '';

// Set personal info for footer
$pdf->setPersonalInfo($fullname, $property, $cadastre, $contacts);

$pdf->AddPage();
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);
$pdf->SetFont('DejaVu','',10);

// Collect all selected objections
$allObjections = [];
foreach (['vide', 'veseliba', 'daba', 'kultura', 'aizsardziba', 'citi'] as $category) {
    if (!empty($_POST[$category])) {
        foreach ($_POST[$category] as $item) {
            $text = $pdf->getFullText($category, $item);
            if (!empty($text)) {
                $allObjections[] = $text;
            }
        }
    }
}

// Randomize objections
shuffle($allObjections);

// Add each objection with proper spacing
foreach ($allObjections as $objection) {
    $pdf->MultiCell(0, 5, $objection, 0, 'L');
    $pdf->Ln(5);
}

// Add custom text if provided
if (!empty($_POST['custom_text'])) {
    $pdf->MultiCell(0, 5, $_POST['custom_text'], 0, 'L');
    $pdf->Ln(5);
}

// Clean output buffer and generate PDF
ob_end_clean();
$pdf->Output('D', 'vestule_vvd.pdf');
?>