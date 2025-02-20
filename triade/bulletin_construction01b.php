<?php
session_start();
error_reporting(0);
/***************************************************************************
 *                              T.R.I.A.D.E
 *                            ---------------
 *
 *   begin                : Janvier 2000
 *   copyright            : (C) 2000 E. TAESCH - T. TRACHET - 
 *   Site                 : http://www.triade-educ.com
 *
 *
 ***************************************************************************/
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
include_once("./librairie_php/lib_licence.php");
include_once("./common/config.inc.php");
include_once("./librairie_php/lib_get_init.php");
$id=php_ini_get("safe_mode");
if ($id != 1) {
	set_time_limit(900);
}

?>
<HTML>
<HEAD>
<META http-equiv="CacheControl" content = "no-cache">
<META http-equiv="pragma" content = "no-cache">
<META http-equiv="expires" content = -1>
<meta name="Copyright" content="Triade©, 2001">
<LINK TITLE="style" TYPE="text/CSS" rel="stylesheet" HREF="./librairie_css/css.css">
<script language="JavaScript" src="./librairie_js/verif_creat.js"></script>
<script language="JavaScript" src="./librairie_js/lib_defil.js"></script>
<script language="JavaScript" src="./librairie_js/clickdroit.js"></script>
<script language="JavaScript" src="./librairie_js/function.js"></script>
<title>Triade - Compte de <?php print "$_SESSION[nom] $_SESSION[prenom] "?></title>
</head>
<body id='bodyfond' marginheight="0" marginwidth="0" leftmargin="0" topmargin="0" onload="Init();" >
<?php include("./librairie_php/lib_attente.php"); ?>
<SCRIPT language="JavaScript" <?php print "src='./librairie_js/".$_SESSION[membre].".js'>" ?></SCRIPT>
<?php include("./librairie_php/lib_defilement.php"); ?>
</TD><td width="472" valign="middle" rowspan="3" align="center">
<div align='center'><?php top_h(); ?>
<SCRIPT language="JavaScript" <?php print "src='./librairie_js/".$_SESSION[membre]."1.js'>" ?></SCRIPT>
<table border="0" cellpadding="3" cellspacing="1" width="100%" bgcolor="#0B3A0C" height="85">
<tr id='coulBar0' ><td height="2"><b><font   id='menumodule1' ><?php print LANGBULL5?></font></b></td></tr>
<tr id='cadreCentral0'>
<td >



<!-- // fin  --><br> <br>
<?php
include_once('librairie_php/db_triade.php');
$cnx=cnx();
if ($_SESSION["membre"] == "menuprof") {
	$data=aff_enr_parametrage("autorisebulletinprof"); 
	if ($data[0][1] == "oui") {
		validerequete("3");
	}else{
		verif_profp_class($_SESSION["id_pers"],$_POST["saisie_classe"]);
	}
}else{
	validerequete("2");
}
$debut=deb_prog();
$valeur=visu_affectation_detail_bulletin($_POST["saisie_classe"]);
if (count($valeur)) {

	if ($_POST["typetrisem"] == "trimestre") {
	if ($_POST["saisie_trimestre"] == "trimestre1" ) { $textTrimestre=LANGBULL22; $triabsconet="T1"; }
	if ($_POST["saisie_trimestre"] == "trimestre2" ) { $textTrimestre=LANGBULL23; $triabsconet="T2"; }
	if ($_POST["saisie_trimestre"] == "trimestre3" ) { $textTrimestre=LANGBULL24; $triabsconet="T3"; }
}

if ($_POST["typetrisem"] == "semestre") {
	if ($_POST["saisie_trimestre"] == "trimestre1" ) { $textTrimestre=LANGBULL25; $triabsconet="T1"; }
	if ($_POST["saisie_trimestre"] == "trimestre2" ) { $textTrimestre=LANGBULL26; $triabsconet="T2"; }
}

$hauteurphoto=$_POST["hauteurphoto"];
$largeurphoto=$_POST["largeurphoto"];
$hauteurlogo=$_POST["hauteurlogo"];
$largeurlogo=$_POST["largeurlogo"];
$avecexamenblanc=$_POST["avecexamenblanc"];
$affichemoyengeneral=$_POST["affichemoyengeneral"];

if ($_SESSION["membre"] != "menuadmin") $affichemoyengeneral="oui";

$affichematierecoefzero=$_POST["affichematierecoefzero"];
$abssconet=$_POST["abssconet"];
$afficherang=$_POST["afficherang"];
$npAfficheSousMatiere=$_POST["npAfficheSousMatiere"];
$hauteurMatiere=$_POST["hauteurMatiere"];
$npAfficheCoef=$_POST["npAfficheCoef"];
$coef100=$_POST["coef100"];
$noteviescolairedansmoyennegeneral=$_POST["noteviescolairedansmoyennegeneral"];
$affichenomprofp=$_POST["affichenomprofp"];

if (trim($hauteurphoto) == "") {
	$hauteurphoto=16.3;
	$largeurphoto=10.8;
}
if (trim($hauteurlogo) == "") {
	$hauteurlogo=25;
	$largeurlogo=25;
}

config_param_ajout($hauteurlogo,"hauteurlogo");
config_param_ajout($largeurlogo,"largeurlogo");
config_param_ajout($hauteurphoto,"hauteurphoto");
config_param_ajout($largeurphoto,"largeurphoto");
config_param_ajout($avecexamenblanc,"avecexamenblanc");
config_param_ajout($affichemoyengeneral,"affichemoyengeneral");
config_param_ajout($affichematierecoefzero,"affichematierecoefzero");
config_param_ajout($abssconet,"abssconet");
config_param_ajout($afficherang,"afficherang");
config_param_ajout($npAfficheSousMatiere,"npAfficheSousMatiere");
config_param_ajout($hauteurMatiere,"hauteurMatiere");
config_param_ajout($npAfficheCoef,"npAfficheCoef");
config_param_ajout($coef100,"coef100");
config_param_ajout($noteviescolairedansmoyennegeneral,"notescolairegeneral");
config_param_ajout($affichenomprofp,"affichenomprofp");


// recupe du nom de la classe
$data=chercheClasse($_POST["saisie_classe"]);
$classe_nom=$data[0][1];

// recup année scolaire
$anneeScolaire=$_POST["annee_scolaire"];
?>
<ul>
<font class="T2">
      <?php print LANGBULL27?> : <?php print $textTrimestre?><br> <br>
      <?php print LANGBULL28?> : <?php print ucwords($classe_nom)?><br> <br>
      <?php print LANGBULL29?> : <?php print $anneeScolaire?><br /><br />
</font>
</ul>

<?php
include_once('librairie_php/recupnoteperiode.php');

// recuperation des coordonnées
// de l etablissement
$data=visu_paramViaIdSite(chercheIdSite($_POST["saisie_classe"]));
for($i=0;$i<count($data);$i++) {
       $nom_etablissement=trim(TextNoAccent($data[$i][0]));
       $adresse=trim($data[$i][1]);
       $postal=trim($data[$i][2]);
       $ville=trim($data[$i][3]);
       $tel=trim($data[$i][4]);
       $mail=trim($data[$i][5]);
       $directeur=trim($data[$i][6]);
       $urlsite=trim($data[$i][7]);
}
// fin de la recup


if (MODNAMUR0 == "oui") {
	$recupInfo=recupCaractVieScolaire($_POST["saisie_classe"]);
	$persVieScolaire=$recupInfo[0][4];
	$coefBull=$recupInfo[0][1];
	$coefProf=$recupInfo[0][2];
	$coefVieScol=$recupInfo[0][3];
}

// recherche des dates de debut et fin
$dateRecup=recupDateTrimByIdclasse($_POST["saisie_trimestre"],$_POST["saisie_classe"]);
for($j=0;$j<count($dateRecup);$j++) {
	$dateDebut=$dateRecup[$j][0];
	$dateFin=$dateRecup[$j][1];
}
$dateDebut=dateForm($dateDebut);
$dateFin=dateForm($dateFin);

$idClasse=$_POST["saisie_classe"];
$ordre=ordre_matiere_visubull_trim($_POST["saisie_classe"],$_POST["saisie_trimestre"]); // recup ordre matiere

// creation PDF
//
define('FPDF_FONTPATH','./librairie_pdf/fpdf/font/');
include_once('./librairie_pdf/fpdf/fpdf.php');
include_once('./librairie_pdf/html2pdf.php');
include_once('./librairie_pdf/fpdf_merge.php');
$merge=new FPDF_Merge();

$nofooterPDF=NOFOOTERPDF;

$pdf=new PDF();  // declaration du constructeur

$noteMoyEleG=0; // pour la moyenne de l'eleve general
$coefEleG=0; // pour la moyenne de l'eleve general
$eleveT=recupEleve($_POST["saisie_classe"]); // recup liste eleve

$moyenClasseGen=""; // pour le calcul moyenne classe
$moyenClasseMin=1000; // pour la calcul moyenne min classe
$moyenClasseMax=""; // pour la calcul moyenne max  classe
$nbeleve=0;
$noteMoyEleG1=0; // pour la moyenne  general
$coefEleG1=0; // pour la moyenne  general

// pour le calcul de moyenne classe
if  ($avecexamenblanc == "oui") {
	$moyenClasseGen=calculMoyenClasse($idClasse,$eleveT,$dateDebut,$dateFin,$ordre);
}else{
	$moyenClasseGen=calculMoyenClasseSansExam($idClasse,$eleveT,$dateDebut,$dateFin,$ordre);
}
if ($moyenClasseGen ==  -1 ) { $moyenClasseGen=""; }
// Fin du Calcul moyenne classe
// ----------------------------


// calcul min et max general
//-------------------------
	$max="";
	$min=1000;
	for($g=0;$g<count($eleveT);$g++) {
		// variable eleve
		$idEleveMoyen=$eleveT[$g][4];
		$noteMoyEleG=0;
		$coefEleG=0;
		$moyenEleve2="";
		for($t=0;$t<count($ordre);$t++) {
			$idMatiere=$ordre[$t][0];
			$idprof=recherche_prof($idMatiere,$idClasse,$ordre[$t][2]);
			
			$verifGroupe=verifMatiereAvecGroupe($ordre[$t][0],$idEleveMoyen,$idClasse,$ordre[$t][2]);
			if ($verifGroupe) {  continue; } // verif pour l'eleve de l'affichage de la matiere

			if ($affichematierecoefzero != "oui") {
				$coeffaff=recupCoeff($ordre[$i][0],$idClasse,$ordre[$i][2]);
				if ($coeffaff == "0.00") { continue; } 
			}

			if ($avecexamenblanc == "oui") {
				$noteaff=moyenneEleveMatiere($idEleveMoyen,$idMatiere,$dateDebut,$dateFin,$idprof);
			}else{
				$noteaff=moyenneEleveMatiereSansExam($idEleveMoyen,$idMatiere,$dateDebut,$dateFin,$idprof);
			}

			if ( $noteaff != "" ) {
				$coeffaff=recupCoeff($idMatiere,$idClasse,$ordre[$t][2]);
				$noteMoyEleGTempo = $noteaff * $coeffaff;
			       	$noteMoyEleG=$noteMoyEleG + $noteMoyEleGTempo;
				$coefEleG=$coefEleG + $coeffaff;
			}
			unset($noteaff);
			unset($coeffaff);
		}
                if (MODNAMUR0 == "oui") {
	        	$noteaff=calculNoteVieScolaire($idEleveMoyen,$coefProf,$coefVieScol,$_POST["saisie_trimestre"]);
		        if ( $noteaff != "" ) {
			        $noteMoyEleGTempo = $noteaff * $coefBull;
		                $noteMoyEleG=$noteMoyEleG + $noteMoyEleGTempo;
		                $coefEleG=$coefEleG + $coefBull;
		        }
		}

                if ($noteMoyEleG != "") {
                        $moyenEleve2=moyGenEleve($noteMoyEleG,$coefEleG);
                }
                if (trim($moyenEleve2) != "") {
			$moyenEleve2=preg_replace('/,/','.',$moyenEleve2);
			$classementG[]=$moyenEleve2;
	                $min=preg_replace('/,/','.',$min);
                        $max=preg_replace('/,/','.',$max);
                        if ($moyenEleve2 <= $min) { $min=$moyenEleve2; }
			if ($moyenEleve2 >= $max) { $max=$moyenEleve2; }
			
                }
        }

        if ($min == 1000) { $min=""; }
        $min=preg_replace('/\./',',',$min);
        $max=preg_replace('/\./',',',$max);
        $moyenClasseMin=$min;
        $moyenClasseMax=$max;
// fin min et max
// -------------

$plageEleve=$_POST["plageEleve"];
if ($plageEleve == "tous") { $dep=0; $nbEleveT=count($eleveT); }
if ($plageEleve == "10") { $dep=0; $nbEleveT=9; }
if ($plageEleve == "20") { $dep=9; $nbEleveT=19; }
if ($plageEleve == "30") { $dep=19; $nbEleveT=29; }
if ($plageEleve == "40") { $dep=29; $nbEleveT=39; }
if ($plageEleve == "50") { $dep=39; $nbEleveT=49; }
if ($plageEleve == "60") { $dep=49; $nbEleveT=59; }
if ($nbEleveT > count($eleveT)) { $nbEleveT=count($eleveT); }
for($j=$dep;$j<$nbEleveT;$j++) {  // premiere ligne de la creation PDF
	// variable eleve
	$nomEleve=ucwords($eleveT[$j][0]);
	$prenomEleve=ucfirst($eleveT[$j][1]);
	$lv1Eleve=$eleveT[$j][2];
	$lv2Eleve=$eleveT[$j][3];
	$idEleve=$eleveT[$j][4];

	//---------------------------------//
	// recherche le nombre de retard
	$nbretard=0;
	$nbretard1=0;
	$nbheureabs=0;
	$nbjoursabs=0;
	$nbabs=0;
	$nbabsnonjustifier=0;
	if ($abssconet == "oui") {
		$nbretard=nombre_retard_sconet($idEleve,$triabsconet);
		$nbabs=nombre_abs_sconet($idEleve,$triabsconet);
		$nbabsnonjustifier=nombre_abs_nonjustifie_sconet($idEleve,$triabsconet);
	}else{
		$nbretard=nombre_retard($idEleve,dateFormBase($dateDebut),dateFormBase($dateFin)); // ideleve,debutdate,findate
		$nbretard=count($nbretard);
		// recherche le nombre d absence
		// elev_id, date_ab, date_saisie, origin_saisie, duree_ab ,date_fin, motif, duree_heure
		$nbabs=nombre_abs($idEleve,dateFormBase($dateDebut),dateFormBase($dateFin)); // ideleve,debutdate,findate
		for($o=0;$o<=count($nbabs);$o++) {
			if ($nbabs[$o][4] > 0) {
		       		$nbjoursabs = $nbjoursabs + $nbabs[$o][4];
			}else{
				$nbheureabs = $nbheureabs + $nbabs[$o][7];	
			}
		}
		$nbabs=$nbjoursabs * 2;
	}
	//---------------------------------//



	$pdf->AddPage();
	$pdf->SetTitle("Bulletin - $nomEleve $prenomEleve");
	$pdf->SetCreator("T.R.I.A.D.E.");
	$pdf->SetSubject("Bulletin de notes $textTrimestre "); 
	$pdf->SetAuthor("T.R.I.A.D.E. - www.triade-educ.com"); 


	// declaration variable
	$coordonne0=strtoupper($nom_etablissement);
	$coordonne1=$adresse;
	$coordonne2=$postal." - ".ucwords($ville);
	$coordonne3="Téléphone : ".$tel;
	$coordonne4=$urlsite;


	$titre="<B><U>".LANGBULL30."</U> <U>".ucwords($textTrimestre)."</u></B>";

	$nomEleve=strtoupper(trim($nomEleve));
	$prenomEleve=trim($prenomEleve);
	$nomprenom=trunchaine("<b>$nomEleve</b> $prenomEleve",30);


	$infoeleve=LANGBULL31." : $nomprenom";
	$infoeleve2=LANGELE4." : ";
	$classe_nom2=preg_replace('/_/',' ',$classe_nom);
	$infoeleveclasse=ucwords($classe_nom2);

	$titrenote1=LANGBULL32;
	$titrenote2=LANGBULL31;
	$titrenote3=LANGBULL33;
	$titrenote4=LANGBULL34;
	$soustitre5=LANGBULL35;
	$soustitre6=LANGBULL36;
	$soustitre7=LANGBULL37;
	$soustitre8=LANGBULL38;


	$appreciation="Observations du conseil de classe :";
	if ($abssconet == "oui") {
	//	$appreciationbis="($nbretard retard(s) / $nbabs absence(s) / $nbabsnonjustifier absence(s) non justifié(s) ) " ;
	}else{
	//	$appreciationbis="($nbretard retard(s) / $nbabs demi-journée d'absence(s) / $nbheureabs heure(s) d'absence(s) ) " ;
	}

	$barre="________________________________________________________________________________________________";
	$appreciation2=LANGBULL40;
	$duplicata=LANGBULL41 . " - $urlsite - $mail";
	$signature=LANGBULL42;
	$signature2="";
	$signature="";
	// FIN variables

	$xtitre=80;  // sans logo
	$xcoor0=3;   // sans logo
	$ycoor0=3;   // sans logo

	// mise en place du logo

/*	$logo="./image/banniere/banniere-ipac.jpg";
	if (file_exists($logo)) {
		$xtitre=90; // avec logo
		$pdf->Image($logo,130,3,75,25);
		
	}
*/				
	// mise en place du logo
        $photo=recup_photo_bulletin_idsite(chercheIdSite($_POST["saisie_classe"]));
        if (count($photo) > 0) {
                $logo="./data/image_pers/".$photo[0][0];
                if (file_exists($logo)) {
                        $xlogo=$largeurlogo;
                        $ylogo=$hauteurlogo;
                        $xtitre=90; // avec logo
			$ps=210-10-$largeurlogo;
                        $pdf->Image($logo,$ps,3,$xlogo,$ylogo);
                }
        }

	// fin du logo
	//
	$xcoor0=5;
	$idprofp=rechercheprofp($_POST["saisie_classe"]);
	$profp=recherche_personne2($idprofp);


	// Debut création PDF
	// mise en place des coordonnées
	$pdf->SetFont('Arial','',12);
	$pdf->SetXY($xcoor0,$ycoor0);
	$pdf->WriteHTML($coordonne0);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($xcoor0,$ycoor0+5);
	$pdf->WriteHTML($coordonne1);
	$pdf->SetXY($xcoor0,$ycoor0+10);
	$pdf->WriteHTML($coordonne2);
	$pdf->SetXY($xcoor0,$ycoor0+15);
	$pdf->WriteHTML($coordonne3);
	$pdf->SetXY($xcoor0,$ycoor0+20);
	$pdf->WriteHTML($coordonne4);
	//fin coordonnees


	// insertion de la Annee SCOLAIRE
	$Pdate=LANGBULL43." ".$anneeScolaire;
	$pdf->SetFont('Courier','',10);
	$pdf->SetXY(5,30);
	$pdf->WriteHTML($Pdate);
	// fin d'insertion

	// Titre
	$pdf->SetXY($xtitre,30);
	$pdf->SetFont('Courier','',18);
	$pdf->WriteHTML($titre);
	// fin titre

	// cadre du haut

	if (count($ordre) == 20) {
                $Y=40;
        }else{
                $Y=45;
        }

	$pdf->SetFont('Arial','',10);
	$pdf->SetFillColor(220);
	$pdf->SetXY(5,$Y); // placement du cadre du nom de l eleve
	$pdf->MultiCell(194,20,'',1,'L',1);
	$photo=image_bulletin($idEleve);

	//$photowidth=18;
	//$photoheight=18;
	$photowidth=$largeurphoto;
	$photoheight=$hauteurphoto;

	$Xv1=10;
	$Xv11=111;
	if (file_exists($photo)){
		$xphoto=194-($photowidth/2.3);
		$yphoto=$Y+2;
		$pdf->Image($photo,$xphoto,$yphoto,$photowidth/2.3,$photoheight/2.3);	
	}
	$pdf->SetXY($Xv1,$Y+1); // placement du nom de l'eleve
	$pdf->WriteHTML($infoeleve);
	$pdf->SetXY($Xv1+80,$Y+1);
	$pdf->WriteHTML($infoeleve2);
	$pdf->SetXY($Xv1+94,$Y+1);
	$pdf->WriteHTML($infoeleveclasse);


	// adresse de l'élève
	// elev_id, nomtuteur, prenomtuteur, adr1, code_post_adr1, commune_adr1, adr2, code_post_adr2, commune_adr2, numeroEleve, class_ant, date_naissance, regime, civ_1, civ_2
	$dataadresse=chercheadresse($idEleve);
	for($ik=0;$ik<=count($dataadresse);$ik++) {
		$nomtuteur=$dataadresse[$ik][1];
		$prenomtuteur=$dataadresse[$ik][2];
		$adr1=$dataadresse[$ik][3];
		$code_post_adr1=$dataadresse[$ik][4];
		$commune_adr1=$dataadresse[$ik][5];
		$numero_eleve=$dataadresse[$ik][9];
		$datenaissance=$dataadresse[$ik][11];
		if ($datenaissance != "") {  $datenaissance=dateForm($datenaissance); }
		$regime=$dataadresse[$ik][12];
		$class_ant=trim(trunchaine($dataadresse[$ik][10],20));

		$pdf->SetXY($Xv1,$Y+5); 
		$pdf->SetFont('Arial','',8);
		$pdf->WriteHTML("N°: $numero_eleve ");
		$pdf->SetXY($Xv1,$Y+9);
		$pdf->WriteHTML("Né(e) le $datenaissance");
		$pdf->SetXY($Xv1,$Y+13); 
		$pdf->WriteHTML("Regime: $regime ");
		$pdf->SetXY($Xv1+80,$Y+9);
		$class_ant=trunchaine($class_ant,40);
		$class_ant2=preg_replace('/_/',' ',$class_ant);
		$pdf->WriteHTML("Classe ant.: $class_ant2 ");

		/*
		$pdf->SetFont('Arial','',10);
		$pdf->SetXY($Xv11,36);
		$chaine=LANGBULL44." ".trim(strtoupper($nomtuteur))." ".trim(ucwords(strtolower($prenomtuteur)));
		$pdf->WriteHTML(trunchaine($chaine,30));
		$pdf->SetXY($Xv11,42);
		$chaine=trim($num_adr1)." ".trim($adr1);
		$pdf->WriteHTML(trunchaine($chaine,30));;
		$pdf->SetXY($Xv11,48);
		$chaine=trim($code_post_adr1)." ".trim($commune_adr1);
		$pdf->WriteHTML(trunchaine($chaine,30));
		*/
	}

	// fin cadre du haut

	// cadre des notes
	// ---------------
	// Barre des titres
	if (count($ordre) == 20) {
		$Y=63;
	}else{
		$Y=70;
	}
	$pdf->SetFont('Arial','',9);
	$pdf->SetFillColor(220);
	$pdf->SetXY(5,$Y); //  placement  cadre titre
	$pdf->MultiCell(194,11,'',1,'C',1);
	$pdf->SetXY(15,$Y+2); // placement contenu titre
	$pdf->WriteHTML($titrenote1);
	$pdf->SetX(67);
	$pdf->WriteHTML($titrenote2);
	$pdf->SetX(90);
	$pdf->WriteHTML($titrenote3);
	$pdf->SetX(125);
	$pdf->WriteHTML($titrenote4);
	// fin des titres

	// possition des sous-titres
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(45,$Y+6);
	if ($npAfficheCoef != "oui") {
		$pdf->WriteHTML($soustitre5);
	}
	$pdf->SetX(55);
	$pdf->WriteHTML("Partiel");
	$pdf->SetX(82);
	$pdf->WriteHTML($soustitre6);
	$pdf->SetX(92);
	$pdf->WriteHTML($soustitre7);
	$pdf->SetX(102);
	$pdf->WriteHTML($soustitre8);
	// fin des sous-titres

	if ($afficherang == "oui") {
		$pdf->SetX(113);
		$pdf->WriteHTML("Rang");	
	}


	// Mise en place des matieres et nom de prof
	$Xmat=5;
	$Ymat=$Y+11;
	$Xmatcont=6;
	$Ymatcont=$Y+11;

	$Xprof=45;
	$Yprof=$Ymat;
	$Xcoeff=45;
	$Ycoeff=$Ymat;
	$Xmoyeleve=$Xcoeff + 10;
	$Ymoyeleve=$Ymat;
	$Xmoyclasse=$Xmoyeleve + 15;
	$Ymoyclasse=$Ymat;

	$XnomProfcont=46;
	$YnomProfcont=$Ymatcont;
	$Xnote=$Xmoyclasse + 32;
	$Ynote=$Ymat;
	$XnotVal=$Xcoeff + 12;
	$YnotVal=$Ycoeff + 3;
	$XcoeffVal=$Xcoeff + 1;
	$YcoeffVal=$Ymat + 3;
	$XprofVal=10; // x en nom prof
	$YprofVal=$Ymat + 4; // y en nom du prof
	$XmoyMatGVal=$Xcoeff + 26 ;
	$YmoyMatGVal=$Ycoeff + 3 ;

	$nbNoteMin=0;
	$nbNotemax=0;

	$noteMoyEleG=0;
	$coefEleG=0;
	$ii=0;

	for($i=0;$i<count($ordre);$i++) {
		$matiere=chercheMatiereNom($ordre[$i][0]);
		$idMatiere=$ordre[$i][0];
		$idprof=recherche_prof($idMatiere,$idClasse,$ordre[$i][2]);
		$nomprof=recherche_personne2($ordre[$i][1]);
		$verifGroupe=verifMatiereAvecGroupe($ordre[$i][0],$idEleve,$idClasse,$ordre[$i][2]);
		if ($verifGroupe) {  continue; } // verif pour l'eleve de l'affichage de la matiere
		if ($affichematierecoefzero != "oui") {
			$coeffaff=recupCoeff($ordre[$i][0],$idClasse,$ordre[$i][2]);
			if ($coeffaff == "0.00") { continue; } 
		}
    		$idgroupe=verifMatierAvecGroupeRecupId($idMatiere,$idEleve,$idClasse,$ordre[$i][2]);
		if ($idgroupe == "0") {
			$classement=Rangs($ordre[$i][0],$dateDebut,$dateFin,$idClasse,$idprof);
    		}else {
        		$classement=RangsGroupe($ordre[$i][0],$dateDebut,$dateFin,$idgroupe,$idprof);
		}	
		$largeurMat=40;
		if ($npAfficheCoef == "oui") $largeurMat=50;
		$datasousmatiere=verifsousmatierebull($idMatiere);
		$nomMatierePrincipale=$matiere;
		if ($datasousmatiere != "0") {
			$nomMatierePrincipale=$datasousmatiere[0][2];
			$nomSousMatiere=$datasousmatiere[0][1];
		}

		if (($npAfficheSousMatiere == "oui") && ($i != 0)) {
			$TT=$i-1;
			$idMatierePrecedente=$ordre[$TT][0];
			$datasousmatiere=verifsousmatierebull($idMatierePrecedente);
			if ($datasousmatiere != "0")  	{ 
				$nomMatierePrecedente=$datasousmatiere[0][2];  
				if ($nomMatierePrincipale == $nomMatierePrecedente ) continue;
			}
		}



		// fin de la gestion sous matiere
		// ------------------------------
		$ii++;
		if ($ii == 25) {
			$pdf->AddPage();
			$Xmat=5;
			$Ymat=20;
			$Xmatcont=6;
			$Ymatcont=20;

			$Xprof=45;
			$Yprof=$Ymat;
			$Xcoeff=45;
			$Ycoeff=$Ymat;
			$Xmoyeleve=$Xcoeff + 10 ;
			$Ymoyeleve=$Ymat;
			$Xmoyclasse=$Xmoyeleve + 15 ;
			$Ymoyclasse=$Ymat;

			$XnomProfcont=46;
			$YnomProfcont=$Ymatcont;
			$Xnote=$Xmoyclasse + 32;
			$Ynote=$Ymat;
			$XnotVal=$Xcoeff + 12;
			$YnotVal=$Ycoeff + 3;
			$XcoeffVal=$Xcoeff + 1;
			$YcoeffVal=$Ymat + 3;
			$XprofVal=10; // x en nom prof
			$YprofVal=$Ymat + 4; // y en nom du prof
			$XmoyMatGVal=$Xcoeff + 26 ;
			$YmoyMatGVal=$Ycoeff + 3 ;
			$ii=0;
		}

		$pdf->SetFont('Arial','',6.5);
		$pdf->SetXY($Xmat,$Ymat);
		$pdf->MultiCell($largeurMat,$hauteurMatiere,'',1,'L',0);
		$pdf->SetXY($Xmatcont-1,$Ymatcont);

		if ($npAfficheSousMatiere == 'oui') $matiere=chercheMatiereNom3($ordre[$i][0]);

		if ($npAfficheCoef != "oui") {
			$pdf->WriteHTML('<B>'.trunchaine(strtoupper(sansaccent(strtolower($matiere))),25).'</B>');
		}else{
			$pdf->WriteHTML('<B>'.trunchaine(strtoupper(sansaccent(strtolower($matiere))),30).'</B>');
		}
		// $pdf->WriteHTML('<B>'.trunchaine(sansaccentmajuscule(strtoupper($matiere)),20).'</B>');
		$Ymat=$Ymat + $hauteurMatiere;
		$Ymatcont=$Ymatcont + $hauteurMatiere;
		// mise en place de la colonne coeff
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY($Xcoeff,$Ycoeff);
		if ($npAfficheCoef != "oui") {
			$pdf->MultiCell(10,$hauteurMatiere,'',1,'L',0);
		}

		// mise en place 
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY($Xcoeff+10,$Ycoeff);		
		$pdf->MultiCell(10,$hauteurMatiere,'',1,'L',0);
		$Ycoeff=$Ycoeff + $hauteurMatiere;

		// mise en place moyenne eleve
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY($Xmoyeleve+10,$Ymoyeleve);
		$pdf->SetFillColor(240);  // couleur du cadre de l'eleve
		$pdf->MultiCell(15,$hauteurMatiere,'',1,'L',1);
		$Ymoyeleve=$Ymoyeleve + $hauteurMatiere;
		// mise en place moyenne classe
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY($Xmoyclasse+10,$Ymoyclasse);
		$pdf->MultiCell(32,$hauteurMatiere,'',1,'L',0);
		
		// mise en place des rangs
		if ($afficherang == "oui") {
			$pdf->SetFont('Arial','',8);
			$pdf->SetXY($Xmoyclasse+42,$Ymoyclasse);
			$pdf->MultiCell(10,$hauteurMatiere,'',1,'L',0);	
		}

		$Ymoyclasse=$Ymoyclasse + $hauteurMatiere;

		// mise en place du cadre note
		if ($afficherang == "oui") {
			$pdf->SetXY($Xnote+20,$Ynote);
		}else{
			$pdf->SetXY($Xnote+10,$Ynote);
		}
		$pdf->MultiCell(87,$hauteurMatiere,'',1,'',0);
		$Ynote=$Ynote + $hauteurMatiere;

		// mise en place des notes
		$coeffaff=recupCoeff($ordre[$i][0],$idClasse,$ordre[$i][2]);
		if ($npAfficheSousMatiere != "oui") {	
			if ($idgroupe == "0") {
				if ($avecexamenblanc == "oui") {
					$noteaff=moyenneEleveMatiere($idEleve,$ordre[$i][0],$dateDebut,$dateFin,$idprof);
				}else{
					$noteaff=moyenneEleveMatiereSansExam($idEleve,$ordre[$i][0],$dateDebut,$dateFin,$idprof);
				}
				$notePartiel=moyenneEleveMatiereExamen($idEleve,$ordre[$i][0],$dateDebut,$dateFin,$idprof,"Partiel");
			}else{
				if ($avecexamenblanc == "oui") {
					$noteaff=moyenneEleveMatiereGroupe($idEleve,$ordre[$i][0],$dateDebut,$dateFin,$idgroupe,$idprof);
				}else{
					$noteaff=moyenneEleveMatiereGroupeSansExam($idEleve,$ordre[$i][0],$dateDebut,$dateFin,$idgroupe,$idprof);
				}
				$notePartiel=moyenneEleveMatiereGroupeExamen($idEleve,$ordre[$i][0],$dateDebut,$dateFin,$idgroupe,$idprof,"Partiel");
			}
		}else{
			if ($idgroupe == "0") {
                                if ($avecexamenblanc == "oui") {
                                        $noteaff=moyenneEleveMatiere($idEleve,$ordre[$i][0],$dateDebut,$dateFin,$idprof);
                                }else{
                                        $noteaff=moyenneEleveMatiereSansExam($idEleve,$ordre[$i][0],$dateDebut,$dateFin,$idprof);
				}
				$notePartiel=moyenneEleveMatiereExamen($idEleve,$ordre[$i][0],$dateDebut,$dateFin,$idprof,"Partiel");
                        }else{
                                if ($avecexamenblanc == "oui") {
                                        $noteaff=moyenneEleveMatiereGroupe($idEleve,$ordre[$i][0],$dateDebut,$dateFin,$idgroupe,$idprof);
                                }else{
                                        $noteaff=moyenneEleveMatiereGroupeSansExam($idEleve,$ordre[$i][0],$dateDebut,$dateFin,$idgroupe,$idprof);
				}
				$notePartiel=moyenneEleveMatiereGroupeExamen($idEleve,$ordre[$i][0],$dateDebut,$dateFin,$idgroupe,$idprof,"Partiel");
                        }
		
			if ($noteaff != "") {
				$noteaffMoyen=$noteaff*$coeffaff;
				$nbCoefMoyen=$coeffaff;
			}else{
				$noteaffMoyen="";
				$nbCoefMoyen="";
			}


			$TT=$i+1;
			while(true) {
				$idMatiereSuivante=$ordre[$TT][0];
				$datasousmatiere=verifsousmatierebull($idMatiereSuivante);
				if ($datasousmatiere != "0")  	{ 
					$nomMatierePrincipaleSuivante=$datasousmatiere[0][2];   // code_mat,sous_matiere,libelle
					if ($nomMatierePrincipale == $nomMatierePrincipaleSuivante) {
						$idprofSuivant=recherche_prof($idMatiereSuivante,$idClasse,$ordre[$TT][2]);
						$idgroupeSuivant=verifMatierAvecGroupeRecupId($idMatiereSuivante,$idEleve,$idClasse,$ordre[$TT][2]);
						$coeffaff=recupCoeff($ordre[$TT][0],$idClasse,$ordre[$TT][2]);
						if ($idgroupeSuivant == "0") {
			                        	if ($avecexamenblanc == "oui") {
			                                	$noteaff=moyenneEleveMatiere($idEleve,$ordre[$TT][0],$dateDebut,$dateFin,$idprofSuivant);
			                                }else{
			                                        $noteaff=moyenneEleveMatiereSansExam($idEleve,$ordre[$TT][0],$dateDebut,$dateFin,$idprofSuivant);
							}
							$notePartiel=moyenneEleveMatiereExamen($idEleve,$ordre[$TT][0],$dateDebut,$dateFin,$idprof,"Partiel");
			                        }else{
			                                if ($avecexamenblanc == "oui") {
			                                        $noteaff=moyenneEleveMatiereGroupe($idEleve,$ordre[$TT][0],$dateDebut,$dateFin,$idgroupeSuivant,$idprofSuivant);
			                                }else{
			                                        $noteaff=moyenneEleveMatiereGroupeSansExam($idEleve,$ordre[$TT][0],$dateDebut,$dateFin,$idgroupeSuivant,$idprofSuivant);
							}
							$notePartiel=moyenneEleveMatiereGroupeExamen($idEleve,$ordre[$TT][0],$dateDebut,$dateFin,$idgroupe,$idprof,"Partiel");
						}
						if ($noteaff != "") {
							$noteaffMoyen+=$noteaff*$coeffaff;
							$nbCoefMoyen+=$coeffaff;
						}
						$TT++;
        		                }else{
						break;
					}
				}else{
					break;
				}
			}

			// faire la moyenne dans noteaff
			$coeffaff=$nbCoefMoyen;
			if ($noteaffMoyen != "") {
				$noteaff=$noteaffMoyen/$nbCoefMoyen;
				$noteaff=number_format($noteaff,2,'.','');	
			}
			
		
		}
		$noterang=$noteaff;

		$pdf->SetFont('Arial','',8);
		$pdf->SetXY($XnotVal-1,$YnotVal);
		if (($notePartiel < 10) && ($notePartiel != "")) { $notePartiel="0".$notePartiel; }
		$notePartiel=number_format($notePartiel,2,'.','');
		$pdf->WriteHTML("$notePartiel");

		$pdf->SetXY($XnotVal+10,$YnotVal);

		$pdf->SetFont('Arial','',12);
		$pdf->SetXY($XnotVal+10,$YnotVal);
		$noteaff1=$noteaff;
		if (($noteaff1 < 10) && ($noteaff1 != "")) { $noteaff1="0".$noteaff1; }

		if ($coef100 == "oui") {
			$coeffaff=recupCoeff($idMatiere,$idClasse,$ordre[$i][2]);
			$multiple=($coeffaff*100)/20;
			$noteaff1=$noteaff1*$multiple;
			$noteaff1=number_format($noteaff1,0,'','');
			if ($noteaff1 < 100) $noteaff1="0".$noteaff1;
		}
		

		$pdf->WriteHTML($noteaff1);

	        if ($npAfficheSousMatiere == "oui") {
	                if ( $noteaff != "" ) {
        	                $noteMoyEleGTempo = $noteaff * $nbCoefMoyen;
                	        $noteMoyEleG=$noteMoyEleG + $noteMoyEleGTempo;
                        	$coefEleG=$coefEleG + $nbCoefMoyen;
                	}
        	}
		unset($noteaffMoyen);
                unset($nbCoefMoyen);



		$YnotVal=$YnotVal + $hauteurMatiere;
		// mise en place des coeff
		if ($npAfficheSousMatiere != "oui") $coeffaff=recupCoeff($idMatiere,$idClasse,$ordre[$i][2]);
		$coeffaff=number_format($coeffaff,2,'.','');
		$pdf->SetFont('Arial','',9);
		$pdf->SetXY($XcoeffVal,$YcoeffVal);
		if ($npAfficheCoef != "oui") {
			$pdf->WriteHTML($coeffaff);
		}
		$YcoeffVal=$YcoeffVal + $hauteurMatiere;

// --------------------------------------------------------------------------------------------------------------------------------	
		$coeffaff=recupCoeff($ordre[$i][0],$idClasse,$ordre[$i][2]);
	        if ($npAfficheSousMatiere != "oui") {
			// mise en place des moyennes de classe
			if ($idgroupe == "0") {
				// idMatiere,datedebut,dateFin,idclasse
				if ($avecexamenblanc == "oui") {
		           		$moyeMatGen=moyeMatGen($ordre[$i][0],$dateDebut,$dateFin,$idClasse,$idprof);
				}else{
					$moyeMatGen=moyeMatGenSansExam($ordre[$i][0],$dateDebut,$dateFin,$idClasse,$idprof);
				}
			}else {
				if ($avecexamenblanc == "oui") {
		           		$moyeMatGen=moyeMatGenGroupe($ordre[$i][0],$dateDebut,$dateFin,$idgroupe,$idprof);
				}else{
					$moyeMatGen=moyeMatGenGroupeSansExam($ordre[$i][0],$dateDebut,$dateFin,$idgroupe,$idprof);
				}
	    		}
		}else{
			if ($idgroupe == "0") {
                                // idMatiere,datedebut,dateFin,idclasse
                                if ($avecexamenblanc == "oui") {
                                        $moyeMatGen=moyeMatGen($ordre[$i][0],$dateDebut,$dateFin,$idClasse,$idprof);
                                }else{
                                        $moyeMatGen=moyeMatGenSansExam($ordre[$i][0],$dateDebut,$dateFin,$idClasse,$idprof);
                                }
                        }else {
                                if ($avecexamenblanc == "oui") {
                                        $moyeMatGen=moyeMatGenGroupe($ordre[$i][0],$dateDebut,$dateFin,$idgroupe,$idprof);
                                }else{
                                        $moyeMatGen=moyeMatGenGroupeSansExam($ordre[$i][0],$dateDebut,$dateFin,$idgroupe,$idprof);
                                }
                        }
			if ($moyeMatGen != "") {
                                $noteaffMoyen=$moyeMatGen*$coeffaff;
                                $nbCoefMoyen=$coeffaff;
                        }else{
				$noteaffMoyen="";
				$nbCoefMoyen="";
			}


			$TT=$i+1;
                        while(true) {
                                $idMatiereSuivante=$ordre[$TT][0];
                                $datasousmatiere=verifsousmatierebull($idMatiereSuivante);
                                if ($datasousmatiere != "0")    {
                                        $nomMatierePrincipaleSuivante=$datasousmatiere[0][2];   // code_mat,sous_matiere,libelle
                                        if ($nomMatierePrincipale == $nomMatierePrincipaleSuivante) {
                                                $idprofSuivant=recherche_prof($idMatiereSuivante,$idClasse,$ordre[$TT][2]);
                                                $idgroupeSuivant=verifMatierAvecGroupeRecupId($idMatiereSuivante,$idEleve,$idClasse,$ordre[$TT][2]);
                                                $coeffaff=recupCoeff($ordre[$TT][0],$idClasse,$ordre[$TT][2]);
                                                if ($idgroupeSuivant == "0") {
                                			if ($avecexamenblanc == "oui") {
			                                        $moyeMatGen=moyeMatGen($ordre[$TT][0],$dateDebut,$dateFin,$idClasse,$idprofSuivant);
                        			        }else{
			                                        $moyeMatGen=moyeMatGenSansExam($ordre[$TT][0],$dateDebut,$dateFin,$idClasse,$idprofSuivant);
			                                }
			                        }else {
			                                if ($avecexamenblanc == "oui") {
			                                        $moyeMatGen=moyeMatGenGroupe($ordre[$TT][0],$dateDebut,$dateFin,$idgroupeSuivant,$idprofSuivant);
			                                }else{
			                                        $moyeMatGen=moyeMatGenGroupeSansExam($ordre[$TT][0],$dateDebut,$dateFin,$idgroupeSuivant,$idprofSuivant);
			                                }
			                        }
                                                if ($moyeMatGen != "") {
                                                        $noteaffMoyen+=$moyeMatGen*$coeffaff;
                                                        $nbCoefMoyen+=$coeffaff;
                                                }
						$commentaireeleve.=cherche_com_eleve($idEleve,$ordre[$TT][0],$idClasse,$_POST["saisie_trimestre"],$idprofSuivant,$idgroupeSuivant);
                                                $TT++;
                                        }else{
                                                break;
                                        }
                                }else{
                                        break;
                                }
                        }
			$coeffaff=$nbCoefMoyen;
			if ($noteaffMoyen != "") {
				$noteaff=$noteaffMoyen/$nbCoefMoyen;
				$moyeMatGen=number_format($noteaff,2,'.','');	
			}
		}
		$pdf->SetFont('Arial','',9);
		$pdf->SetXY($XmoyMatGVal+10,$YmoyMatGVal);
		$moyeMatGenaff=$moyeMatGen;
		if (($moyeMatGenaff < 10) && ($moyeMatGenaff != "")) { $moyeMatGenaff="0".$moyeMatGenaff; }

		if ($coef100 == "oui") {
			$multiple=($coeffaff*100)/20;
			$moyeMatGenaff=$moyeMatGenaff*$multiple;
			$moyeMatGenaff=number_format($moyeMatGenaff,0,'','');
			if ($moyeMatGenaff < 100) $moyeMatGenaff="0".$moyeMatGenaff;
		}

		$pdf->WriteHTML($moyeMatGenaff);
// --------------------------------------------------------------------------------------------------------------------------------	

// --------------------------------------------------------------------------------------------------------------------------------	
		// calcul du min et du max
		if ($idgroupe == "0") {   // non matiere affectée à un groupe
			$max="";
			$min=1000;
			for($g=0;$g<count($eleveT);$g++) {
				// variable eleve
				$idEleveMoyen=$eleveT[$g][4];
				$idMatiere=$ordre[$i][0];
				if ($avecexamenblanc == "oui") {
					$valeur=moyenneEleveMatiere($idEleveMoyen,$idMatiere,$dateDebut,$dateFin,$idprof);
				}else{
					$valeur=moyenneEleveMatiereSansExam($idEleveMoyen,$idMatiere,$dateDebut,$dateFin,$idprof);
				}
				if ($npAfficheSousMatiere == "oui") {
		                        if ($valeur != "") {
		                        	$valeurTotal+=$valeur*$coeffaff;
		                                $nbCoefMoyen+=$coeffaff;
		                        }else{
						$valeurTotal="";
						$nbCoefMoyen="";
					}
					$TT=$i+1;
                        		while(true) {
                                		$idMatiereSuivante=$ordre[$TT][0];
		                                $datasousmatiere=verifsousmatierebull($idMatiereSuivante);
		                                if ($datasousmatiere != "0") {
		                                        $nomMatierePrincipaleSuivante=$datasousmatiere[0][2];   // code_mat,sous_matiere,libelle
		                                        if ($nomMatierePrincipale == $nomMatierePrincipaleSuivante) {
		                                                $idprofSuivant=recherche_prof($idMatiereSuivante,$idClasse,$ordre[$TT][2]);
		                                                $idgroupeSuivant=verifMatierAvecGroupeRecupId($idMatiereSuivante,$idEleve,$idClasse,$ordre[$TT][2]);
		                                                $coeffaff=recupCoeff($ordre[$TT][0],$idClasse,$ordre[$TT][2]);
								if ($avecexamenblanc == "oui") {
									$valeur=moyenneEleveMatiere($idEleveMoyen,$idMatiereSuivante,$dateDebut,$dateFin,$idprofSuivant);
								}else{
									$valeur=moyenneEleveMatiereSansExam($idEleveMoyen,$idMatiereSuivante,$dateDebut,$dateFin,$idprofSuivant);
								}
		                                                if ($valeur != "") {
		                                                        $valeurTotal+=$valeur*$coeffaff;
		                                                        $nbCoefMoyen+=$coeffaff;
		                                                }
		                                                $TT++;
                		                        }else{
		                                                break;
		                                        }
		                                }else{
		                                        break;
		                                }
		                        }
        		                if ($valeurTotal != "") {
	                                	$valeurTotal=$valeurTotal/$nbCoefMoyen;
                        		        $valeur=number_format($valeurTotal,2,'.','');
						unset($nbCoefMoyen);
						unset($valeurTotal);
                        		}
				}
				if (trim($valeur) != "") {
					if ($valeur >= $max) { $max=$valeur; }
					if ($valeur <= $min) { $min=$valeur; }
				}
			}
			if ($min == 1000) { $min=""; }
			$moyeMatGenMin=$min;
			$moyeMatGenMax=$max;
		}else{
			$max="";
			$min=1000;
			$eleveTg=listeEleveDansGroupe($idgroupe);
			for($g=0;$g<count($eleveTg);$g++) {
				$idEleveMoyen=$eleveTg[$g];
				if ($avecexamenblanc == "oui") {
					$valeur=moyenneEleveMatiereGroupe($idEleveMoyen,$idMatiere,$dateDebut,$dateFin,$idgroupe,$idprof);
				}else{
					$valeur=moyenneEleveMatiereGroupeSansExam($idEleveMoyen,$idMatiere,$dateDebut,$dateFin,$idgroupe,$idprof);
				}
				if ($npAfficheSousMatiere == "oui") {
                                        $TT=$i+1;
                                        while(true) {
                                                $idMatiereSuivante=$ordre[$TT][0];
                                                $datasousmatiere=verifsousmatierebull($idMatiereSuivante);
                                                if ($datasousmatiere != "0") {
                                                        $nomMatierePrincipaleSuivante=$datasousmatiere[0][2];   // code_mat,sous_matiere,libelle
                                                        if ($nomMatierePrincipale == $nomMatierePrincipaleSuivante) {
                                                                $idprofSuivant=recherche_prof($idMatiereSuivante,$idClasse,$ordre[$TT][2]);
                                                                $idgroupeSuivant=verifMatierAvecGroupeRecupId($idMatiereSuivante,$idEleve,$idClasse,$ordre[$TT][2]);
                                                                $coeffaff=recupCoeff($ordre[$TT][0],$idClasse,$ordre[$TT][2]);
								if ($avecexamenblanc == "oui") {
									$valeur=moyenneEleveMatiereGroupe($idEleveMoyen,$idMatiereSuivante,$dateDebut,$dateFin,$idgroupeSuivant,$idprofSuivant);
								}else{
									$valeur=moyenneEleveMatiereGroupeSansExam($idEleveMoyen,$idMatiereSuivante,$dateDebut,$dateFin,$idgroupeSuivant,$idprofSuivant);
								}
                                                                if ($valeur != "") {
                                                                        $valeurTotal+=$valeur*$coeffaff;
                                                                        $nbCoefMoyen+=$coeffaff;
                                                                }
                                                                $TT++;
                                                        }else{
                                                                break;
                                                        }
                                                }else{
                                                        break;
                                                }
                                        }
                                        if ($valeurTotal != "") {
                                                $valeurTotal=$valeurTotal/$nbCoefMoyen;
                                                $valeur=number_format($valeurTotal,2,'.','');
                                                unset($nbCoefMoyen);
                                                unset($valeurTotal);
                                        }
                                }
				if (trim($valeur) != "") {	
					if ($valeur >= $max) { $max=$valeur; }
					if ($valeur <= $min) { $min=$valeur; }
				}
			}
			if ($min == 1000) { $min=""; }
			$moyeMatGenMin=$min;
			$moyeMatGenMax=$max;
		}
		// fin de la calcul de min et max
	
	// mise en place du min
	$XmoyMatGenMinVal=$XmoyMatGVal + 11;
	$pdf->SetXY($XmoyMatGenMinVal+10,$YmoyMatGVal);
	$moyeMatGenMinaff=$moyeMatGenMin;
	if (($moyeMatGenMinaff < 10) && ($moyeMatGenMinaff != "")) { $moyeMatGenMinaff="0".$moyeMatGenMinaff; }
	if ($coef100 == "oui") {
		$multiple=($coeffaff*100)/20;
		$moyeMatGenMinaff=$moyeMatGenMinaff*$multiple;
		$moyeMatGenMinaff=number_format($moyeMatGenMinaff,0,'','');
		if ($moyeMatGenMinaff < 100) $moyeMatGenMinaff="0".$moyeMatGenMinaff;
	}
	$pdf->WriteHTML($moyeMatGenMinaff);

	// mise en place du max
	$XmoyMatGenMaxVal=$XmoyMatGVal + 21;
	$pdf->SetXY($XmoyMatGenMaxVal+10,$YmoyMatGVal);
	$moyeMatGenMaxaff=$moyeMatGenMax;
	if (($moyeMatGenMaxaff < 10) && ($moyeMatGenMaxaff != "")) { $moyeMatGenMaxaff="0".$moyeMatGenMaxaff; }
	if ($coef100 == "oui") {
		$multiple=($coeffaff*100)/20;
		$moyeMatGenMaxaff=$moyeMatGenMaxaff*$multiple;
		$moyeMatGenMaxaff=number_format($moyeMatGenMaxaff,0,'','');
		if ($moyeMatGenMaxaff < 100) $moyeMatGenMaxaff="0".$moyeMatGenMaxaff;
	}
	$pdf->WriteHTML($moyeMatGenMaxaff);

	$Ycom=$YmoyMatGVal - 3;

	$YmoyMatGVal=$YmoyMatGVal + $hauteurMatiere;


	// rangs
 	if ($afficherang == "oui") {
		$nrang=0;
		$rang="";
		foreach ($classement as $key => $val) {	
			$nrang++;
			if ($val == $noterang){
				$rang=$nrang;
				break;
			}
		}
		$pdf->SetFont('Arial','',8);
		$pdf->SetXY($XmoyMatGVal+32+10,$YcoeffVal-7);
		if ($rang != "") {
			if (($rang < 10) && ($rang != "")) { $rang="0".$rang; }				
			$nbtotalRang=count($classement);
			if (($nbtotalRang < 10) && ($nbtotalRang != "")) { $nbtotalRang="0".$nbtotalRang; }
			$pdf->MultiCell(10,3,$rang."/".$nbtotalRang,'0','L',0);
			$noterang="";
			$rang="";
		}
	}
	//----------------------------------------------------------------------



	// mise en place des commentaires
	if ($commentaireeleve == "") $commentaireeleve=cherche_com_eleve($idEleve,$idMatiere,$idClasse,$_POST["saisie_trimestre"],$idprof,$idgroupe);
	$commentaireeleve=preg_replace("/\n/"," ",$commentaireeleve);
	$confPolice=confPolice($commentaireeleve);  // $confPolice[0] -> Cadre ; $confPolice[1] -> Policy

	$Xcom=$XmoyMatGenMaxVal + 10;
	$pdf->SetFont('Arial','',$confPolice[0]);
	if ($afficherang == "oui") {
		$pdf->SetXY($Xcom+20,$Ycom);
	}else{
		$pdf->SetXY($Xcom+10,$Ycom);
	}
	$pdf->MultiCell(87,$confPolice[1],$commentaireeleve,'','','L',0);
	$commentaireeleve="";
	//$pdf->WriteHTML($commentaireeleve);
	
	// mise en place du nom du prof
	$profAff=profAff($ordre[$i][0],$idClasse,$ordre[$i][2]);
	$coeffaff=recupCoeff($ordre[$i][0],$idClasse,$ordre[$i][2]);
	$pdf->SetFont('Arial','',6);
	$pdf->SetXY($XprofVal,$YprofVal);
	$profAff=recherche_personne2($profAff);
	if (trim($profAff) == "M. ou Mme") $profAff="";
	$pdf->WriteHTML(trunchaine($profAff,30));
	$YprofVal=$YprofVal + $hauteurMatiere ;

	// pour le calcul de la moyenne general de l'eleve
	
	if ($npAfficheSousMatiere != "oui") {	
		if ( $noteaff != "" ) {
		        $noteMoyEleGTempo = $noteaff * $coeffaff;
                	$noteMoyEleG=$noteMoyEleG + $noteMoyEleGTempo;
			$coefEleG=$coefEleG + $coeffaff;
		}	
	}

}
// fin de la mise en place des matiere
/*
// Note Vie Scolaire
if (MODNAMUR0 == "oui") {


	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($Xmat,$Ymat);
	$pdf->MultiCell($largeurMat,$hauteurMatiere,'',1,'L',0);
	$pdf->SetXY($Xmatcont,$Ymatcont);
	$pdf->WriteHTML('<B>'.'Vie Scolaire'.'</B>');
	$Ymat=$Ymat + $hauteurMatiere;
	$Ymatcont=$Ymatcont + $hauteurMatiere;
	// mise en place de la colonne coeff
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($Xcoeff,$Ycoeff);
	if ($npAfficheCoef != "oui") {
		$pdf->MultiCell(10,$hauteurMatiere,'',1,'L',0);
	}
	$Ycoeff=$Ycoeff + $hauteurMatiere;
	// mise en place moyenne eleve
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($Xmoyeleve,$Ymoyeleve);
	$pdf->SetFillColor(240);  // couleur du cadre de l'eleve
	$pdf->MultiCell(15,$hauteurMatiere,'',1,'L',1);
	$Ymoyeleve=$Ymoyeleve + $hauteurMatiere;
	// mise en place moyenne classe
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($Xmoyclasse,$Ymoyclasse);
	$pdf->MultiCell(32,$hauteurMatiere,'',1,'L',0);
	$Ymoyclasse=$Ymoyclasse + $hauteurMatiere;
	// mise en place du cadre note
	$pdf->SetXY($Xnote,$Ynote);
	if ($afficherang == "oui") {
		$pdf->MultiCell(97,$hauteurMatiere,'',1,'',0);
	}else{
		$pdf->MultiCell(87,$hauteurMatiere,'',1,'',0);
	}
	$Ynote=$Ynote + $hauteurMatiere;

	// mise en place des notes
	$noteaff=calculNoteVieScolaire($idEleve,$coefProf,$coefVieScol,$_POST["saisie_trimestre"]);
	$pdf->SetFont('Arial','',12);
	$pdf->SetXY($XnotVal,$YnotVal);
	$coeffaff=$coefBull;

	if ($coef100 == "oui") {
		$multiple=($coeffaff*100)/20;
		$noteaff=$noteaff*$multiple;
		$noteaff=number_format($noteaff,0,'','');
		if ($noteaff < 100) $noteaff="0".$noteaff;
	}
	$pdf->WriteHTML($noteaff);


	$YnotVal=$YnotVal + $hauteurMatiere;
	// mise en place des coeff
	$pdf->SetFont('Arial','',9);
	$pdf->SetXY($XcoeffVal,$YcoeffVal);
	if ($npAfficheCoef != "oui") {
		$pdf->WriteHTML($coeffaff);
	}
	$YcoeffVal=$YcoeffVal + $hauteurMatiere;
	

	// mise en place des moyennes de classe
        $moyeMatGen1=moyeMatGenVieScolaire($_POST["saisie_trimestre"],$idClasse); 
	$pdf->SetFont('Arial','',9);
	$pdf->SetXY($XmoyMatGVal,$YmoyMatGVal);
	$moyeMatGenaff=$moyeMatGen1;
	if (($moyeMatGenaff < 10) && ($moyeMatGenaff != "")) { $moyeMatGenaff="0".$moyeMatGenaff; }
	if ($coef100 == "oui") {
		$multiple=($coeffaff*100)/20;
		$moyeMatGenaff=$moyeMatGenaff*$multiple;
		$moyeMatGenaff=number_format($moyeMatGenaff,0,'','');
		if ($moyeMatGenaff < 100) $moyeMatGenaff="0".$moyeMatGenaff;
	}
	$pdf->WriteHTML($moyeMatGenaff);


	// calcul du min et du max
	$max="";
	$min=1000;
	for($g=0;$g<count($eleveT);$g++) {
		// variable eleve
		$idEleveMoyen=$eleveT[$g][4];
		$valeur=calculNoteVieScolaire($idEleveMoyen,$coefProf,$coefVieScol,$_POST["saisie_trimestre"]);
		if (trim($valeur) != "") {
			if ($valeur >= $max) { $max=$valeur; }
			if ($valeur <= $min) { $min=$valeur; }
		}
	}
	if ($min == 1000) { $min=""; }
	$moyeMatGenMin=$min;
	$moyeMatGenMax=$max;
	// fin de la calcul de min et max

	// mise en place du min
	$XmoyMatGenMinVal=$XmoyMatGVal + 11;
	$pdf->SetXY($XmoyMatGenMinVal,$YmoyMatGVal);
	$moyeMatGenMinaff=$moyeMatGenMin;
	if (($moyeMatGenMinaff < 10) && ($moyeMatGenMinaff != "")) { $moyeMatGenMinaff="0".$moyeMatGenMinaff; }
	if ($coef100 == "oui") {
		$multiple=($coeffaff*100)/20;
		$moyeMatGenMinaff=$moyeMatGenMinaff*$multiple;
		$moyeMatGenMinaff=number_format($moyeMatGenMinaff,0,'','');
		if ($moyeMatGenMinaff < 100) $moyeMatGenMinaff="0".$moyeMatGenMinaff;
	}
	$pdf->WriteHTML($moyeMatGenMinaff);

	// mise en place du max
	$XmoyMatGenMaxVal=$XmoyMatGVal + 21;
	$pdf->SetXY($XmoyMatGenMaxVal,$YmoyMatGVal);
	$moyeMatGenMaxaff=$moyeMatGenMax;
	if (($moyeMatGenMaxaff < 10) && ($moyeMatGenMaxaff != "")) { $moyeMatGenMaxaff="0".$moyeMatGenMaxaff; }
	if ($coef100 == "oui") {
		$multiple=($coeffaff*100)/20;
		$moyeMatGenMaxaff=$moyeMatGenMaxaff*$multiple;
		$moyeMatGenMaxaff=number_format($moyeMatGenMaxaff,0,'','');
		if ($moyeMatGenMaxaff < 100) $moyeMatGenMaxaff="0".$moyeMatGenMaxaff;
	}
	$pdf->WriteHTML($moyeMatGenMaxaff);

	$Ycom=$YmoyMatGVal - 3;

	$YmoyMatGVal=$YmoyMatGVal + $hauteurMatiere;





	// mise en place des commentaires
	$commentaireeleve=cherche_com_scolaire_eleve_cpe($idEleve,"-10",$idClasse,$_POST["saisie_trimestre"],"");
	$commentaireeleve=preg_replace("/\n/"," ",$commentaireeleve);
	$confPolice=confPolice($commentaireeleve);  // $confPolice[0] -> Cadre ; $confPolice[1] -> Policy


	$Xcom=$XmoyMatGenMaxVal + 10;
	$pdf->SetFont('Arial','',$confPolice[0]);
	$pdf->SetXY($Xcom,$Ycom);
	$pdf->MultiCell(87,$confPolice[1],$commentaireeleve,'','','L',0);
	
	$commentaireeleve="";

	// mise en place du nom du prof
	$profAff=$persVieScolaire;
	$pdf->SetFont('Arial','',6);
	$pdf->SetXY($XprofVal,$YprofVal);
	$pdf->WriteHTML(trunchaine($profAff,30));
	$YprofVal=$YprofVal + $hauteurMatiere ;

	// pour le calcul de la moyenne general de l'eleve
	if ($noteviescolairedansmoyennegeneral == "oui") {
		if ( $noteaff != "" ) {
		        $noteMoyEleGTempo = $noteaff * $coeffaff;
               	 	$noteMoyEleG=$noteMoyEleG + $noteMoyEleGTempo;
               	 	$coefEleG=$coefEleG + $coeffaff;
		}
	}

}
 */
// fin notes
// --------


// cadre moyenne generale
$YmoyenneGeneral=$Ymoyclasse + 3;
if ($YmoyenneGeneral > 230) {
	$pdf->AddPage();
	$YmoyenneGeneral=20;
}


$LargeurMG=$largeurMat;
$YmoyenneGeneralT=$YmoyenneGeneral + 2;
$XMoyGE= 10 + 15 + $LargeurMG;
$YMoyGE=$YmoyenneGeneral - 10;

if ($affichemoyengeneral == "oui") {

	$YMoyGE=$YmoyenneGeneral;
	$XMoyCL=$XMoyGE + 15;

	$XmoyClasseGValue=$XMoyGE + 10 + 6;
	$YmoyClasseGValue=$YmoyenneGeneralT;
	$XmoyClasseMinValue=$XmoyClasseGValue + 10;
	$YmoyClasseMinValue=$YmoyenneGeneralT;
	$XmoyClasseMaxValue=$XmoyClasseMinValue + 10 ;
	$YmoyClasseMaxValue=$YmoyenneGeneralT;


	$pdf->SetFont('Arial','',9);
	$pdf->SetXY(15,$YmoyenneGeneral);
	$pdf->MultiCell($LargeurMG,10,'',1,'L',0);
	$pdf->SetXY(17,$YmoyenneGeneralT);
	$pdf->WriteHTML("<B>MOYENNE GENERALE</B>");
	$pdf->SetXY($XMoyGE,$YMoyGE);
	$pdf->SetFillColor(220);
	$pdf->MultiCell(15,10,'',1,'L',1);
	$pdf->SetXY($XMoyCL,$YMoyGE);
	$pdf->MultiCell(32,10,'',1,'L',0);

	if ((file_exists("./data/image_pers/logo_signature.jpg")) && ($_POST["ajsignature"] == "oui")){
		$pdf->SetFont('Arial','',7);
		$pdf->SetXY(120,$YmoyenneGeneralT+6.5);
		$pdf->WriteHTML("[ <I>Signature du directeur</I> ]");
		$taille = getimagesize("./data/image_pers/logo_signature.jpg");
		$logox=$taille[0]/25;
		$logoy=$taille[1]/25;
		$pdf->Image("./data/image_pers/logo_signature.jpg","150",$YmoyenneGeneralT-6,$logox,$logoy);
	}
	
	// fin du cadre moyenne generale
	
	// affichage de la moyenne generale eleve
	$XmoyElValue=$LargeurMG + 27;
	$YmoyElGenValue=$YmoyenneGeneral  + 2 ;
	$moyenEleve=moyGenEleve($noteMoyEleG,$coefEleG);
	$pdf->SetFont('Arial','',12);
	$pdf->SetXY($XmoyElValue,$YmoyElGenValue);

	$moyenEleveaff=$moyenEleve;
	
	if ($coef100 == "oui") {
		$multiple=(1*100)/20;
		$moyenEleveaff=$moyenEleveaff*$multiple;
		$moyenEleveaff=number_format($moyenEleveaff,0,'','');
		if ($moyenEleveaff < 100) $moyenEleveaff="0".$moyenEleveaff;
	}

	$pdf->WriteHTML("<B>".$moyenEleveaff."</B>");
	$noteMoyEleG=0; // pour la moyenne de l'eleve general
	$coefEleG=0; // pour la moyenne de l'eleve general
	// fin affichage moy eleve


	$moyenEleveaff=preg_replace('/,/','.',$moyenEleveaff);
	$moyenEleveaffSansarrondi=$moyenEleveaff;

	//affichage  du min et du max et moyenne general
	if ($moyenClasseMin == 1000) {$moyenClasseMin="";}
	if ($moyenClasseGen == 0) {$moyenClasseGen="";}
	$moyenClasseGen=preg_replace('/\./',',',$moyenClasseGen);
	$pdf->SetFont('Arial','',10);
	$pdf->SetXY($XmoyClasseGValue,$YmoyClasseGValue);
	
	$moyenClasseGenaff=$moyenClasseGen;
	if (($moyenClasseGenaff < 10) && ($moyenClasseGenaff != "")) { $moyenClasseGenaff="0".$moyenClasseGenaff; }
	if ($coef100 == "oui") {
		$multiple=(1*100)/20;
		$moyenClasseGenaff=$moyenClasseGenaff*$multiple;
		$moyenClasseGenaff=number_format($moyenClasseGenaff,0,'','');
		if ($moyenClasseGenaff < 100) $moyenClasseGenaff="0".$moyenClasseGenaff;
	}
	$pdf->WriteHTML($moyenClasseGenaff);
	
	$moyenClasseMinaff=$moyenClasseMin;
	$pdf->SetXY($XmoyClasseMinValue,$YmoyClasseMinValue);
	if (($moyenClasseMinaff < 10) && ($moyenClasseMinaff != "")) { $moyenClasseMinaff="0".$moyenClasseMinaff; }
	if ($coef100 == "oui") {
		$multiple=(1*100)/20;
		$moyenClasseMinaff=$moyenClasseMinaff*$multiple;
		$moyenClasseMinaff=number_format($moyenClasseMinaff,0,'','');
		if ($moyenClasseMinaff < 100) $moyenClasseMinaff="0".$moyenClasseMinaff;
	}
	$pdf->WriteHTML($moyenClasseMinaff);
	
	$moyenClasseMaxaff=$moyenClasseMax;
	$pdf->SetXY($XmoyClasseMaxValue,$YmoyClasseMaxValue);
	if (($moyenClasseMaxaff < 10) && ($moyenClasseMaxaff != "")) { $moyenClasseMaxaff="0".$moyenClasseMaxaff; }
	if ($coef100 == "oui") {
		$multiple=(1*100)/20;
		$moyenClasseMaxaff=$moyenClasseMaxaff*$multiple;
		$moyenClasseMaxaff=number_format($moyenClasseMaxaff,0,'','');
		if ($moyenClasseMaxaff < 100) $moyenClasseMaxaff="0".$moyenClasseMaxaff;
	}
	$pdf->WriteHTML($moyenClasseMaxaff);
	// fin de la calcul de min et max


	// RANG
	if ($afficherang == "oui") {
		rsort($classementG);
		$i=1;
		$rangG="";
		$rangGT=count($classementG);
		foreach ($classementG as $key => $val) {	
			//	print "$key => $val --- $moyenEleveaffSansarrondi ---  <br>";
			if ($val == $moyenEleveaffSansarrondi) { 
				$rangG = $key + 1; 
				break;
			}
		}
	
		$pdf->SetXY($XmoyElValue+50,$YmoyElGenValue);
		$pdf->SetFont('Arial','',8);
		$pdf->WriteHTML(" Rang général : $rangG / $rangGT ");
	}
	// fin affichage
}

// cadre appréciation
$Ycom=$YMoyGE + 13;
$EpaisCom=30;
$YcomP1=$Ycom + 1;
$YcomP2=$YcomP1 + 10;
$YcomP3=$YcomP2 + 5;
$pdf->SetFont('Arial','',10);
$pdf->SetFillColor(220);
$pdf->SetXY(5,$Ycom);
$pdf->MultiCell(194,$EpaisCom,'',1,'C',0);
$pdf->SetXY(6,$YcomP1);
$pdf->WriteHTML($appreciation);
$pdf->SetFont('Arial','',8);
$pdf->WriteHTML($appreciationbis);

// commentaire direction
$commentairedirection=recherche_com($idEleve,$_POST["saisie_trimestre"],"default");
$commentairedirection=preg_replace("/\n/"," ",$commentairedirection);
$pdf->SetXY(7,$YcomP1+5);
$confPolice=confPolice2($commentairedirection);  // $confPolice[1] -> Cadre ; $confPolice[0] -> Policy
$pdf->SetFont('Arial','',$confPolice[0]);
$pdf->MultiCell(180,$confPolice[1],$commentairedirection,'','','L',0); // commentaire de la direction (visa)

$pdf->SetFont('Arial','',10);
$pdf->SetXY(6,$YcomP2);
$pdf->WriteHTML($barre);
$pdf->SetXY(6,$YcomP3);
$pdf->WriteHTML($appreciation2);
$pdf->SetXY(6+74,$YcomP3);
$pdf->SetFont('Arial','',8);
if ($affichenomprofp == "oui") $pdf->WriteHTML(" ( Directeur des études : ". $profp ." )" );
$pdf->SetFont('Arial','',9);

// commentaire prof principal
$commentaireprofp=recherche_com_profP($idEleve,$_POST["saisie_trimestre"]);
$commentaireprofp=preg_replace("/\n/"," ",$commentaireprofp);
$confPolice=confPolice2($commentaireprofp);  // $confPolice[1] -> Cadre ; $confPolice[0] -> Policy
$pdf->SetXY(7,$YcomP1+20);
$pdf->SetFont('Arial','',$confPolice[0]);
$pdf->MultiCell(180,$confPolice[1],$commentaireprofp,'','','L',0); // commentaire de la prof P (visa)


//duplicata et signature
$YduplicaSign=$Ycom + 1 + $EpaisCom;
$pdf->SetFont('Arial','',5);
$pdf->SetXY(6,$YduplicaSign);
$pdf->WriteHTML("<I>".$duplicata."</I>");
$pdf->SetFont('Arial','',8);
$pdf->SetXY(120,$YduplicaSign);
$pdf->WriteHTML($signature);
$pdf->SetFont('Arial','',5);
$pdf->SetXY(6,$YduplicaSign+3);
$pdf->WriteHTML($signature2);

// ----------------------------------------------------------------------------------------------------------------------
$classe_nom=TextNoAccent($classe_nom);
$classe_nom=TextNoCarac($classe_nom);
$nomEleve=TextNoCarac($nomEleve);
$nomEleve=TextNoAccent($nomEleve);
$prenomEleve=TextNoCarac($prenomEleve);
$prenomEleve=TextNoAccent($prenomEleve);
$classe_nom=preg_replace('/\//',"_",$classe_nom);
$nomEleve=preg_replace('/\//',"_",$nomEleve);
$prenomEleve=preg_replace('/\//',"_",$prenomEleve);
if (!is_dir("./data/pdf_bull/$classe_nom")) { mkdir("./data/pdf_bull/$classe_nom");}
$fichier="./data/pdf_bull/$classe_nom/bulletin_".$nomEleve."_".$prenomEleve."_".$_POST["saisie_trimestre"].".pdf";
@unlink($fichier); // destruction avant creation
$pdf->output('F',$fichier);
$pdf->close();
bulletin_archivage($_POST["saisie_trimestre"],$anneeScolaire,$fichier,$idEleve,$classe_nom,$nomEleve,$prenomEleve);
if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') { $merge->add("$fichier"); }
$listing.="$fichier ";
$pdf=new PDF();
} // fin du for on passe à l'eleve suivant
$merge->output("./data/pdf_bull/$classe_nom/liste_complete.pdf");
if (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN') {
	$cmd="gs -q -dNOPAUSE -sDEVICE=pdfwrite -sOUTPUTFILE=./data/pdf_bull/$classe_nom/liste_complete.pdf -dBATCH $listing";
	$null=system("$cmd",$retval);
}
include_once('./librairie_php/pclzip.lib.php');
@unlink('./data/pdf_bull/'.$classe_nom.'.zip');
$archive = new PclZip('./data/pdf_bull/'.$classe_nom.'.zip');
$archive->create('./data/pdf_bull/'.$classe_nom,PCLZIP_OPT_REMOVE_PATH, 'data/pdf_bull/');
$fichier='./data/pdf_bull/'.$classe_nom.'.zip';
$bttexte="Récupérer le fichier ZIP des bulletins";
@nettoyage_repertoire('./data/pdf_bull/'.$classe_nom);
@rmdir('./data/pdf_bull/'.$classe_nom);
// --------------------------------------------------------------------------------------------------------------------------
?>
<br><ul><ul>
<input type=button onclick="open('visu_pdf_bulletin.php?id=<?php print $fichier?>&idclasse=<?php print $_POST["saisie_classe"] ?>','_blank','');" value="<?php print $bttexte ?>"  STYLE="font-family: Arial;font-size:10px;color:#CC0000;background-color:#CCCCFF;font-weight:bold;">
</ul></ul>
<?php // ----------------------------------------------------------------------------------------------------------------------------   ?>


<br /><br />
<?php
// gestion d'historie
@destruction_bulletin($fichier,$classe_nom,$_POST["saisie_trimestre"],$dateDebut,$dateFin);
$cr=historyBulletin($fichier,$classe_nom,$_POST["saisie_trimestre"],$dateDebut,$dateFin);
if($cr == 1){
		history_cmd($_SESSION["nom"],"CREATION BULLETIN","Classe : $classe_nom");
        	// alertJs("Bulletin créé -- Service Triade");
}else{
	error(0);
}
Pgclose();
?>

<?php
}else {
?>
<br />
<center>
<?php print LANGMESS14?> <br>
<br><br>
<font size=3><?php print LANGMESS15?><br>
<br>
<?php print LANGMESS16?><br>
</center>
<br /><br /><br />
<?php
        }
?>
<!-- // fin  -->
</td></tr></table>
<script language=JavaScript>attente_close();</script>
<?php
// Test du membre pour savoir quel fichier JS je dois executer
if ($_SESSION["membre"] == "menuadmin") :
print "<SCRIPT language='JavaScript' ";
print "src='./librairie_js/".$_SESSION["membre"]."2.js'>";
print "</SCRIPT>";
else :
print "<SCRIPT language='JavaScript' ";
print "src='./librairie_js/".$_SESSION["membre"]."22.js'>";
print "</SCRIPT>";
top_d();
print "<SCRIPT language='JavaScript' ";
print "src='./librairie_js/".$_SESSION["membre"]."33.js'>";
print "</SCRIPT>";
endif ;
// deconnexion en fin de fichier
?>
</BODY></HTML>
<?php
$cnx=cnx();
fin_prog($debut);
Pgclose();

?>
