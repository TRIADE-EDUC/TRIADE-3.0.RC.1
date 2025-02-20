<?php
session_start();
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
<script language="JavaScript" src="./librairie_js/lib_css.js"></script>
<title>Triade - Compte de <?php print $_SESSION["nom"]." ".$_SESSION["prenom"] ?></title>
</head>
<body id='bodyfond' marginheight="0" marginwidth="0" leftmargin="0" topmargin="0"  >
<?php 
include_once("./librairie_php/lib_licence.php"); 
// connexion (après include_once lib_licence.php obligatoirement)
include_once("librairie_php/db_triade.php");
validerequete("3");
$cnx=cnx(); 
$date="jj/mm/aaaa";
$heure1="hh:mm";
$heure2="hh:mm";
$info="";
$enr="pasok";
if (isset($_POST["create"])) {
	$equipement=$_POST["saisie_equip"];
	$date=$_POST["saisie_date"];
	$heure1=$_POST["saisie_heure1"];
	$heure2=$_POST["saisie_heure2"];
	$info=$_POST["saisie_info"];
	$periode=$_POST["periode"];
	$nbperiode=$_POST["jours"];
	
	$cr=0;
	$cr=verif_si_resa_possible($equipement,$date,$heure1,$heure2);
	if($cr == 0){
		if (RESERV == "oui") { $confirm=1; }
		$cr=create_resa($equipement,$date,$_SESSION["id_pers"],$heure1,$heure2,$info,$confirm,$periode,$nbperiode);
		if($cr == 1){
			$info="Demande de réservation : ".recherche_equip($equipement)." pour le $date";
			prevenir("resa",$_SESSION["nom"],$_SESSION["prenom"],stripSlashes($info));
			history_cmd($_SESSION["nom"],"DEMANDE","reservation");
			$enr="ok";
		}else{
			$enr="pasok";
			$messageresa="<font color=red>"."Equipement déjà réservé à l'une des dates récurrentes.";
			$messageresa.="<br>".LANGRESA47.".</font> ";
			$messageresa.="[<a href=\"#\" onclick=\"open('resr_equip_plan.php?equip=".$equipement."&saisiedate=".$date."','resa','width=600,height=500,scrollbars=yes')\">consulter</a>]";
		}
	}else {
		$enr="pasok";
		$messageresa="<font color=red>".LANGRESA46.".";
		$messageresa.="<br>".LANGRESA47.".</font> ";
		$messageresa.="[<a href=\"#\" onclick=\"open('resr_equip_plan.php?equip=".$equipement."&saisiedate=".$date."','resa','width=600,height=500,scrollbars=yes')\">consulter</a>]";
	}
}

?>
<table border="0" cellpadding="3" cellspacing="1" width="100%" bgcolor="#0B3A0C" height="100%">
<tr id='coulBar0' ><td height="2"><b><font id='menumodule1' >
<?php print LANGRESA44?></font></b></td>
</tr>
<tr id='cadreCentral0' >
<td>
<?php if ($enr == "pasok") { ?>
<form name=formulaire method=post action="resr_equip_fen.php" onsubmit="return validresa()">
<table width=100% height=150 border=0>
<tr>
<td align=right><font class="T2"><?php print LANGRESA45?> :</font></td>
<td><select name="saisie_equip">
<?php
	if (isset($_POST["saisie_equip"])) {
		print "<option  value=".$equipement." STYLE='color:#000066;background-color:#FCE4BA'>".recherche_equip($equipement)."</option>";
	}
?>
<option value="choix" STYLE='color:#000066;background-color:#FCE4BA'><?php print LANGCHOIX?></option>
<?php
select_equip(); // creation des options
?>
</select>
</td>
</tr>
<tr><td align=right width=50%><font class=T2><?php print LANGRESA71 ?> :</font> </td>
<?php
if (isset($_GET["date"])) {
		$date=dateForm($_GET["date"]);
}
?>
<td><input type=text name=saisie_date value="<?php print $date?>"  onclick="this.value=''" size=12 class=bouton2>
<?php
include_once("librairie_php/calendar.php");
calendar("id1","document.formulaire.saisie_date",$_SESSION["langue"],"0");
?>
</td></tr>

<tr><td align=right><font class=T2><?php print LANGRESA72 ?> :</font> </td>
<td><input type=text name=saisie_heure1 value="<?php print $heure1?>"  onclick="this.value=''" size=5 onKeyPress="onlyChar2(event)" ></td></tr>

<tr><td align=right><font class=T2><?php print LANGRESA73 ?> :</font> </td>
<td><input type=text name=saisie_heure2 value="<?php print $heure2?>"  onclick="this.value=''" size=5 onKeyPress="onlyChar2(event)" ></td></tr>

<tr><td align=right valign=top><font class=T2><?php print LANGRESA74 ?> :</font> </td>
<td><textarea name=saisie_info cols=30 rows=3><?php print $info?></textarea></td></tr>

<tr><td align=right><font class=T2><?php print "Récurrence" ?> :</font> </td>
<td><font class=T2>jusqu'au </font><input type="text" name="periode" readonly="readonly" size=10> 
				     <?php include_once("librairie_php/calendar.php");
				     calendarDim('id2','document.formulaire.periode',$_SESSION["langue"],"1","0");?></td></tr>
<?php
	if ($date != "jj/mm/aaaa") {
		$jour=date_jour2($date); // "di","lu","ma","me","je","ve","sa"
		switch($jour) {
			case "di" :  $checkDi="checked='checked'" ; break;
			case "lu" :  $checkLu="checked='checked'" ; break;
			case "ma" :  $checkMa="checked='checked'" ; break;
			case "me" :  $checkMe="checked='checked'" ; break;
			case "je" :  $checkJe="checked='checked'" ; break;
			case "ve" :  $checkVe="checked='checked'" ; break;
			case "sa" :  $checkSa="checked='checked'" ; break;
		}
	}
?>

<tr><td align=right valign=top><font class=T2><?php print "Redondance" ?> :</font> </td>
<td><table border=0>
<tr>
<td width=5>&nbsp;<?php print LANGL ?></td>
<td>&nbsp;<?php print LANGM ?></td>
<td>&nbsp;<?php print LANGME ?></td>
<td>&nbsp;<?php print LANGJ ?></td>
<td>&nbsp;<?php print LANGV ?></td>
<td>&nbsp;<?php print LANGS ?></td>
<td>&nbsp;<?php print LANGD ?></td>
</tr>
<tr>
<td><input type="checkbox" name="jours[]" value="1" <?php print $checkLu ?> ></td>
<td><input type="checkbox" name="jours[]" value="2" <?php print $checkMa ?> ></td>
<td><input type="checkbox" name="jours[]" value="3" <?php print $checkMe ?> ></td>
<td><input type="checkbox" name="jours[]" value="4" <?php print $checkJe ?> ></td>
<td><input type="checkbox" name="jours[]" value="5" <?php print $checkVe ?> ></td>
<td><input type="checkbox" name="jours[]" value="6" <?php print $checkSa ?> ></td>
<td><input type="checkbox" name="jours[]" value="7" <?php print $checkDi ?> ></td>
</tr>
</table></td></tr>

<tr><td colspan=2 align=center><br><br>
<table align=center><tr><td>
<script language=JavaScript>buttonMagicSubmit("<?php print LANGENR?>","create"); //text,nomInput</script>
<script language=JavaScript>buttonMagicFermeture()</script>&nbsp;&nbsp;
</td></tr></table>
<br>
<?php print $messageresa; ?>
<br><br>
</td></tr>
</table>
<?php
}else {
	if (RESERV == "oui") {
		print "<center><font class=T2>".LANGRESA69."</font></center>";
	}else{
		print "<center><font class=T2>".LANGRESA50."</font></center>";
	}
	print  "<br ><br ><table align='center'><tr><td><script language=JavaScript>buttonMagicFermeture()</script></td></tr></table>";
}
?>
</form>
<?php 
Pgclose();
?>
</BODY>
</HTML>
