<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Incluimos el archivo fpdf
require_once APPPATH."/third_party/fpdf/fpdf.php";
class pdf extends FPDF {
    public function __construct() {
        parent::__construct();
    }
    // El encabezado del PDF
    public function Header(){
        $this->SetFont('Arial','',9);
			// Movernos a la derecha
			
			// Título
			$this->Image('assets/img/Escudo.png' , 175 ,10, 20 , 20,'png', 'http://localhost/xxx/siipp/index.php/admin/dashboard');
			$this->SetFont('times','B',8);
			$this->Cell(15,5,'ENTIDAD:',0,0,'L');
			$this->SetFont('times','',8);
			$this->Cell(40,5,'1205 - GOBIERNO AUTONOMO DEPARTAMETNAL DE LA PAZ',0,0,'L');
			$this->Ln(4);// Salto de línea
			$this->SetFont('times','B',8);
			$this->Cell(60,5,'POA - PLAN OPERATIVA ANUAL:',0,0,'L');
			$this->SetFont('times','',8);
			$this->Cell(20,5,'2016',0,0,'L');
			$this->Ln(4);// Salto de línea
			$this->SetFont('times','B',8);
			$this->Cell(60,5,'SISTEMA INTEGRADO DE PLANIFICACION MUNICIPAL - SIPLAM',0,0,'L');
			$this->Ln(4);// Salto de línea
			$this->SetFont('times','B',8);
			$this->Cell(30,5,'FORMULARIO:',0,0,'L');
			$this->SetFont('times','',8);
			$this->Cell(20,5,'DICATAMENTE DE PROYECTOS/ACTIVIDAD',0,0,'L');

			
			$this->Ln(10);// Salto de línea
    }
    // El pie del pdf
    public function Footer(){
       $this->SetY(-15);
			// Arial italic 8
			$this->SetFont('Arial','I',8);
			// Número de página
			$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
       
       // $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}