<?php
    include_once ROOTSCRIPTS . 'fpdf/fpdf.php';

    class PDF extends FPDF{
        function __construct($orientation = 'P', $unit = 'mm', $size = 'A4')
        {
            parent::__construct($orientation, $unit, $size);
            $this->SetLineWidth(0.4);
        }

        function Header(){
			/**
			 * @var Etudiant $etudiant
			 */
            GLOBAL $etudiant, $pf;
            $this->SetLineWidth(0.4);
            $this->Image(ROOTUPLOADS.Ecole::getById(Promotion::getById($pf->getIdPromo())->getIdEcole())->getLogo(), 10, 2, 40, 30);

            // Police Arial gras 15
            $this->SetFont('Arial','',12);
            // Décalage à droite
            $this->Cell(60);

            $this->Cell(50,4,utf8_decode('Nom de l\'apprenant : '), 0, 0,'L');
            $this->Cell(0,4,utf8_decode($etudiant->getNom()), 0, 1,'L');

            $this->Cell(60);
            $this->Cell(50,10,utf8_decode('Prénom de l\'apprenant : '), 0, 0,'L');
            $this->Cell(0,10,utf8_decode($etudiant->getPrenom()), 0, 1,'L');

            $this->Ln(10);
            $this->Cell(40,4,utf8_decode('Votre référent : '), 0, 0,'L');
            $this->Cell(0,4,utf8_decode($pf->getResponsable()), 0, 1,'L');

            $this->Cell(0,6,utf8_decode('Période du '.$pf->getDateDebut().' au '.$pf->getDateFin()), 0, 1, 'L');
            $this->Ln(5);

            $this->SetFillColor(119, 147, 60);
            $this->Cell(0, 8, '', 1, 1, 'L', true);
            $this->Cell(15, 8, 'A', 1, 0, 'C', true);
            $this->Cell(45, 8, 'Acquis', 1, 0, 'L', true);
            $this->Cell(15, 8, 'ECA', 1, 0, 'C', true);
            $this->Cell(45, 8, 'En cours d\'acquisition', 1, 0, 'L', true);
            $this->Cell(15, 8, 'NA', 1, 0, 'C', true);
            $this->Cell(0, 8, 'Non Acquis', 1, 1, 'L', true);
        }

        function Footer(){

            // Position at 1.5 cm from bottom
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial','I',8);
            // Page number
            $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
        }

        function headerModule(Module $module){
			/**
			 * @var Intervenant $intervenant
			 */

        	$intervenant = $module->getIntervenant();

            $this->SetLineWidth(0.4);
            $this->SetFont('Arial','B',10);
            $this->SetFillColor(119, 147, 60);
            if ($this->GetY() >= 260){
                $this->AddPage();
            }
            $this->Cell(120, 6, utf8_decode($module->getLibelle().($module->getCode() != ''?' - '.$module->getCode():'')), 'LTB', 0, 'L');
            $this->SetFont('Arial','I',10);
            $this->Cell(40, 6, utf8_decode($intervenant->getNom().' '.$intervenant->getPrenom()), 'RTB', 0, 'R');
            $this->SetFont('Arial','',10);
            $this->Cell(10, 6, 'A', 1, 0, 'C', true);
            $this->Cell(10, 6, 'ECA', 1, 0, 'C', true);
            $this->Cell(10, 6, 'NA', 1, 1, 'C', true);
        }

        function contenuModule(Module $module){
			/**
			 * @var Etudiant $etudiant
			 * @var Intervenant $intervenant
			 * @var ContenuModule $contenuModule
			 */

            GLOBAL $etudiant;
			$intervenant = $module->getIntervenant();
            foreach ($module->getContenu() as $contenuModule){
                $eval = Evaluation::getById($etudiant->getId(), $intervenant->getId(), $contenuModule->getId());
                $acquis = $eval->estAcquis()?' X':'';
                $enacquisition = $eval->estEnCoursAcquisition()?' X':'';
                $nonacquis = $eval->estNonAcquis()?' X':'';
                $this->SetLineWidth(0.2);
                $this->SetFont('Arial','',8);

                $this->Cell(160, 4, '    '.utf8_decode($contenuModule->getLibelle()), 1, 0, 'L');
                $this->Cell(10, 4, $acquis, 1, 0, 'C');
                $this->Cell(10, 4, $enacquisition, 1, 0, 'C');
                $this->Cell(10, 4, $nonacquis, 1, 1, 'C');
                $this->SetFont('Arial','',10);
            }
        }

        function commentModule($commentaireModule){
            if (strtolower(trim($commentaireModule)) == 'pas de commentaire'){
                $commentaireModule = '';
            }
            $this->SetFont('Arial','I',8);
            $this->MultiCell(0, 5, utf8_decode($commentaireModule), 'LBR', 'L');
            $this->SetFont('Arial','',8);
        }

        function evalGenerale($appreciation){
            if (strtolower(trim($appreciation)) == 'pas de commentaire'){
                $appreciation = '';
            }
            $this->SetLineWidth(0.4);
			if ($this->GetY() >= 250){
				$this->AddPage();
			}
            $this->SetFont('Arial','B',12);
            $this->Cell(0, 6, 'Commentaires', 1, 1, 'L', false);
            $this->SetFont('Arial','I',10);
            $this->MultiCell(0, 5, utf8_decode($appreciation), 'LBR', 'L');
            $this->SetFont('Arial','',10);
            $this->SetLineWidth(0.2);
        }
    }
    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->AliasNbPages();
	/**
	 * @var Module $module
	 * @var Intervenant $intervenant
	 * @var ContenuModule $contenuModule
	 * @var Etudiant $etudiant
	 * @var $listeModules
	 */
	if (isset($listeModules)){

		foreach ($listeModules as $module){

			$commentaireModule = null;
			$appreciationG = null;
			$intervenant = $module->getIntervenant();
			if (isset($etudiant)){
				$commentaireModule = (Evaluation::getAppreciationModule($etudiant->getId(), $intervenant->getId(), $module->getId()));
			}
			if ($commentaireModule == null){
				$commentaireModule = 'Pas de commentaire';
			}
			if (isset($etudiant)){
				$appreciationG = Evaluation::getAppreciationGenerale($etudiant->getId(), $pf->getId());
			}
			if ($appreciationG == null){
				$appreciationG = 'Pas de commentaire';
			}

			$pdf->headerModule($module);
			$pdf->contenuModule($module);
			$pdf->commentModule($commentaireModule);
		}
	}

    $pdf->evalGenerale($appreciationG);
    //$pdf->Output();
    $pdfName = $etudiant->getNom().'_'.$etudiant->getPrenom().'_'.date('Ymd').'.pdf';
    $pdf->Output('F', ROOTEXPORTS.$pdfName);
    header('Location: '.ROOTHTMLEXPORTS.$pdfName);
?>