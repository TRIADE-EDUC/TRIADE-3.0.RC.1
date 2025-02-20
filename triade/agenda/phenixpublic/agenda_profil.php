<?php // Mod applied : 2008-11-02 * Mod_Phenix_V5_Couleur_par_defaut.txt ?>
<?php // Mod applied : 2008-11-02 * Mod_Phenix_V5.5_Aide.txt ?>
<?php // Mod applied : 2008-11-02 * Mod_Phenix_V5_Rappel_Sonore.txt ?>
<?php // Mod applied : 2008-08-04 * Mod_Phenix_V5_Meteo_today_ico.txt ?>
<?php // Mod applied : 2008-08-04 * Mod_Phenix_V5_Menu_Note.txt ?>
<?php // Mod applied : 2008-08-04 * Mod_Phenix_V5_horoscope_hebdo.txt ?>
<?php // Mod applied : 2008-08-04 * Mod_Phenix_V5_fcke_aff_outils.txt ?>
<?php // Mod applied : 2008-08-04 * Mod_Phenix_V5_DD.txt ?>
<?php
  /**************************************************************************\
  * Phenix Agenda                                                            *
  * http://phenix.gapi.fr                                                    *
  * Written by    Stephane TEIL            <phenix-agenda@laposte.net>       *
  * Contributors  Christian AUDEON (Omega) <christian.audeon@gmail.com>      *
  *               Maxime CORMAU (MaxWho17) <maxwho17@free.fr>                *
  *               Mathieu RUE (Frognico)   <matt_rue@yahoo.fr>               *
  *               Bernard CHAIX (Berni69)  <ber123456@free.fr>               *
  * --------------------------------------------                             *
  *  This program is free software; you can redistribute it and/or modify it *
  *  under the terms of the GNU General Public License as published by the   *
  *  Free Software Foundation; either version 2 of the License, or (at your  *
  *  option) any later version.                                              *
  \**************************************************************************/
  // Mod Aide
  // Fichier d'aide contextuel
  ?> <SCRIPT> HelpPhenixCtx="{834C7C2C-9580-4D38-958D-3F077E3B1FEB}.htm"; </SCRIPT> <?php
  // Mod Aide

  if ($USER_SUBSTITUE) {
    if ($idAdmin!=0)
      $admin_PROFILS = $droit_PROFILS;
    $DB_CX->DbQuery("SELECT *, CONCAT(".$FORMAT_NOM_UTIL.") AS nomUtil  FROM ${PREFIX_TABLE}utilisateur WHERE util_id=".$USER_SUBSTITUE);
    $rsProfil = $DB_CX->DbNextRow();
    $ztAction = "UPDATE";
    $nouveau = trad("PROFIL_LIB_NV_PASSWD");
    $labelBouton = trad("PROFIL_BT_MODIFIER");
    if ($USER_SUBSTITUE==$idUser) {
      // Modification de son propre profil
      $actionForm = "agenda_traitement.php?RetProfil=";
      $titrePage = trad("PROFIL_TITRE_MODIFIER_PERSO");
    } else {
      // Modification du profil d'un autre utilisateur
      $actionForm = "agenda_traitement.php?RetProfil=profil";
      $genre = prefixeMot(strtolower(substr($rsProfil['nomUtil'],0,1)),trad("COMMUN_PREFIXE_D"),trad("COMMUN_PREFIXE_DE"));
      $titrePage = sprintf(trad("PROFIL_TITRE_MODIFIER_AUTRE"), $genre, $rsProfil['nomUtil']);
    }
    $btnAnnul = "btAnnul()";
    $DB_CX->DbQuery("SELECT droit_profils, droit_agendas, droit_notes, droit_aff FROM ${PREFIX_TABLE}droit WHERE droit_util_id=".$USER_SUBSTITUE."");
    if ($DB_CX->DbNumRows() && (($droit_PROFILS >= _DROIT_PROFIL_AUTRE_PARAM_BASE) || ($USER_SUBSTITUE==$idUser))) {
      $dr_PROFILS = $DB_CX->DbResult(0,0);
      $dr_AGENDAS = $DB_CX->DbResult(0,1);
      $dr_NOTES = $DB_CX->DbResult(0,2);
      $dr_Aff = substr("000".$DB_CX->DbResult(0,3),-3);
      if ($idAdmin!=0 or $droit_PROFILS >= _DROIT_PROFIL_COMPLET) {
        $droit_PROFILS = _DROIT_PROFIL_COMPLET;
        $droit_Aff = 0;
      } else {
        $droit_Aff = $dr_Aff;
      }
    } else {
      $droit_PROFILS = _DROIT_PROFIL_RIEN;
    }

    $droit_Aff_Login = substr($droit_Aff,0,1);
    $dr_Aff_Login = substr($dr_Aff,0,1);
    $droit_Aff_MDP = substr($droit_Aff,1,1);
    $dr_Aff_MDP = substr($dr_Aff,1,1);
    $droit_Aff_THEME = substr($droit_Aff,2,1);
    $dr_Aff_THEME = substr($dr_Aff,2,1);
  } else {
    $droit_PROFILS = _DROIT_PROFIL_PARAM_BASE;
    $droit_AGENDAS = _DROIT_AGENDA_PARTAGE;
    $droit_NOTES = _DROIT_NOTE_STANDARD_SANS_APPR;
    $droit_Aff_Login = '0';
    $droit_Aff_MDP = '0';
    $droit_Aff_THEME = '0';
    $USER_SUBSTITUE = 0;
    $ztAction = "INSERT";
    $nouveau = trad("PROFIL_LIB_PASSWD");
    $labelBouton = trad("PROFIL_BT_ENREGISTRER");
    $actionForm = "index.php?nc=2";
    $btnAnnul = "window.location.href='index.php?msg=5'";
    $titrePage = trad("PROFIL_TITRE_CREER");
  }
  // Generation des variables pour les jours affiches par defaut dans les planning hebdomadaires et mensuels
  for ($i=1; $i<8; $i++) {
    ${"bt".$i} = substr($rsProfil['util_semaine_type'],$i-1,1);
  }

  $iColor = 1;
  $tabIndex = 1;
?>
<!-- MODULE GESTION DU PROFIL -->
  <SCRIPT language="JavaScript" src="inc/MD5.js" type="text/javascript"></SCRIPT>
  <SCRIPT language="JavaScript">
  <!--
    function autoriseFCKE(theForm,_val,_all) {
      var _statut = (_val=="N");
      if (!_all) {
        theForm.zlFCKEbar.disabled=_statut;
        // Mod fcke_aff_toolbar 
        theForm.zlFCKEbar_aff.disabled=_statut;
        // Mod fcke_aff_toolbar 
        if (_val=="N")
          autoriseFCKE(theForm,"N",true);
        else
          // Mod fcke_aff_toolbar 
          {
          autoriseFCKE(theForm,theForm.zlFCKEbar.value,true);
          autoriseFCKE(theForm,theForm.zlFCKEbar_aff.value,true);
          // Mod fcke_aff_toolbar 
          }
      }
    }
    // Genere un nouveau code pour l'export URL
    var showInfoModif = true;
    function autoCodeURL() {
      var theForm = document.frmProfil;
      var oldURL = theForm.ztURLExport.value;
      var oldURLSynchro = theForm.ztURLSynchro.value;
      theForm.ztCodeURL.value = MD5(Date.parse(new Date())+"px");
      theForm.ztURLExport.value = oldURL.substr(0,oldURL.length-32) + theForm.ztCodeURL.value;
      theForm.ztURLSynchro.value = oldURLSynchro.substr(0,oldURLSynchro.length-32) + theForm.ztCodeURL.value;
      if (showInfoModif) {
        alert("<?php echo trad("PROFIL_JS_VALID_URL"); ?>");
        showInfoModif = false;
      }
    }

    //Genere automatiquement un login
    function loginAuto() {
      var theForm = document.frmProfil;
      var prenomUtil, nomUtil, loginUtil;

      if ((theForm.ztNom.value != "") && (theForm.ztPrenom.value != "")) {
        prenomUtil = theForm.ztPrenom.value;
        nomUtil = theForm.ztNom.value;
        loginUtil = prenomUtil.substr(0,1) + nomUtil.replace(/ +/gi, "");
        loginUtil = loginUtil.substr(0,12);
        theForm.ztLogin.value = loginUtil.toLowerCase();
      }
      else {
        window.alert("<?php echo trad("PROFIL_JS_SAISIR_NOM_PRENOM"); ?>");
        theForm.ztNom.focus();
      }
    }

<?php
    if (($droit_PROFILS >= _DROIT_PROFIL_AUTRE_PARAM_PARTAGE) or (($droit_PROFILS >= _DROIT_PROFIL_PARAM_PARTAGE) and ($idUser==$USER_SUBSTITUE))) {
?>

    function genereListe(_liste, _tabTexte, _tabValue, _tailleTab) {
      for (var i=0; i<_tailleTab; i++)
        _liste.options[i]=new Option(_tabTexte[i], _tabValue[i]);
    }

    function bubbleSort(_tabText, _tabValue,_tailleTab) {
      var i,s;

      do {
        s=0;
        for (i=1; i<_tailleTab; i++)
          if (_tabText[i-1] > _tabText[i]) {
            y = _tabText[i-1];
            _tabText[i-1] = _tabText[i];
            _tabText[i] = y;
            y = _tabValue[i-1];
            _tabValue[i-1] = _tabValue[i];
            _tabValue[i] = y;
            s = 1;
          }
      } while (s);
    }

    function videListe(_liste) {
      var cpt = _liste.options.length;

      for(var i=0; i<cpt; i++) {
        _liste.options[0] = null;
      }
    }

    var vPartage = '<?php echo $rsProfil['util_partage_planning']; ?>';
    var vAffecte = '<?php echo $rsProfil['util_autorise_affect']; ?>';
<?php
    if ($ztAction=="UPDATE") { // On n'est pas dans le cas d'une creation de compte
      $DB_CX->DbQuery("SELECT aff_util_id FROM ${PREFIX_TABLE}planning_affichage WHERE (aff_consultant_id=".$idUser." AND aff_type_planning ='4')");
      if ($DB_CX->DbNumRows()) $affTotal = 1;
      if ($affTotal==1) {
        echo "    var JaffTotal = 1;\n";
      } else {
        $affTotal=0;
        echo "    var JaffTotal = 0;\n";
      }
    }
 ?>
    function selectUtil(_listeSource, _listeDest) {
      var i,j;
      var ok = false;
      var tabDestTexte = new Array();
      var tabDestValue = new Array();
      var tailleTabDest = 0;

      for (i=0; i<_listeDest.options.length; i++) {
        tabDestTexte[tailleTabDest]   = _listeDest.options[i].text;
        tabDestValue[tailleTabDest++] = _listeDest.options[i].value;
      }

      for (j=_listeSource.options.length-1; j>=0; j--) {
        if (_listeSource.options[j].selected) {
          ok = true;
          tabDestTexte[tailleTabDest]   = _listeSource.options[j].text;
          tabDestValue[tailleTabDest++] = _listeSource.options[j].value;
          _listeSource.options[j] = null;
        }
      }

      if (ok) {
        //Trie du tableau
        bubbleSort(tabDestTexte, tabDestValue, tailleTabDest);
        //Vide la liste destination
        videListe(_listeDest);
        //Recree la liste
        genereListe(_listeDest, tabDestTexte, tabDestValue, tailleTabDest);
      }

      if (vPartage==2)
        document.frmProfil.rdPartage[1].checked = true;
      if (vAffecte==3)
        document.frmProfil.zlAffectation.selectedIndex = 2;
    }

    function selectTous(_listeSource, _listeDest) {
      for (var i=0; i<_listeSource.options.length; i++) {
        _listeSource.options[i].selected = true;
      }
      selectUtil(_listeSource, _listeDest);
    }

    function recupSelection(_liste, _champ) {
      _champ.value = "";
      for (var i=0; i<_liste.options.length; i++) {
        _champ.value += ((i) ? "+" : "") + _liste.options[i].value;
      }
    }

<?php
  } else {
?>

    function selectUtil(_listeSource, _listeDest) {}
<?php
  }
?>

    function copieTous(_listeSource, _listeS, _listeDest) {
      for (var i=0; i<_listeS.options.length; i++) {
        _listeS.options[i].selected= false;
        for (var j=0; j<_listeSource.options.length; j++) {
          if ((_listeSource.options[j].text == _listeS.options[i].text) && (_listeSource.options[j].value == _listeS.options[i].value)) {
            _listeS.options[i].selected= true;
          }
        }
      }
      selectUtil(_listeS, _listeDest);
    }
    //Verifie la saisie
    function saisieOK(theForm) {
<?php
  if (($droit_PROFILS >= _DROIT_PROFIL_AUTRE_PARAM_PARTAGE) or (($droit_PROFILS >= _DROIT_PROFIL_PARAM_PARTAGE) and ($idUser==$USER_SUBSTITUE))) {
      echo "      recupSelection(theForm.zlPartage, theForm.ztPartage);\n";
      echo "      recupSelection(theForm.zlAffecte, theForm.ztAffecte);\n";
    $DB_CX->DbQuery("SELECT gr_util_id FROM ${PREFIX_TABLE}groupe_util");
    if ($DB_CX->DbNumRows()>0) {
      $NoGroupe=true;
      echo "      recupSelection(theForm.zlPrtGroupe, theForm.ztPrtGroupe);\n";
      echo "      recupSelection(theForm.zlAffGroupe, theForm.ztAffGroupe);\n";
    }
  }
?>

      if (trim(theForm.ztNom.value) == "") {
        alert("<?php echo trad("PROFIL_JS_SAISIR_NOM"); ?>");
        affOnglet('Info');
        theForm.ztNom.focus();
        return (false);
      }

      if (trim(theForm.ztPrenom.value) == "") {
        alert("<?php echo trad("PROFIL_JS_SAISIR_PRENOM"); ?>");
        affOnglet('Info');
        theForm.ztPrenom.focus();
        return (false);
      }

      if (trim(theForm.ztLogin.value) == "") {
        window.alert("<?php echo trad("PROFIL_JS_SAISIR_LOGIN"); ?>");
        affOnglet('Info');
        theForm.ztLogin.focus();
        return (false);
      }

<?php if ($ztAction == "UPDATE") { ?>
      if (trim(theForm.ztPasswdNew.value) != "") {
        if (trim(theForm.ztOldPasswd.value) == "") {
          window.alert("<?php echo trad("PROFIL_JS_SAISIR_ANCIEN_PASSWD"); ?>");
          affOnglet('Info');
          theForm.ztOldPasswd.focus();
          return (false);
        }

        if (theForm.ztPasswdNew.value != theForm.ztConfirmPasswd.value) {
          window.alert("<?php echo trad("PROFIL_JS_PASSWD_DIFFERENT"); ?>");
          theForm.ztPasswdNew.value = "";
          theForm.ztConfirmPasswd.value = "";
          affOnglet('Info');
          theForm.ztPasswdNew.focus();
          return (false);
        }
        //Cryptage MD5 de l'ancien mot de passe avant submit
        theForm.ztOldPasswdMD5.value = MD5(theForm.ztOldPasswd.value);
        //Mot de passe en clair supprime
        theForm.ztOldPasswd.value = "";
      }

<?php } else { ?>
      if (trim(theForm.ztPasswdNew.value) == "") {
        window.alert("<?php echo trad("PROFIL_JS_PASSWD_OBLIGATOIRE"); ?>");
        affOnglet('Info');
        theForm.ztPasswdNew.focus();
        return (false);
      }

      if (theForm.ztPasswdNew.value != theForm.ztConfirmPasswd.value) {
        window.alert("<?php echo trad("PROFIL_JS_PASSWD_DIFFERENT"); ?>");
        theForm.ztPasswdNew.value = "";
        theForm.ztConfirmPasswd.value = "";
        affOnglet('Info');
        theForm.ztPasswdNew.focus();
        return (false);
      }

<?php } ?>
      if (theForm.zlHeureDebut.selectedIndex > theForm.zlHeureFin.selectedIndex) {
        window.alert("<?php echo trad("PROFIL_JS_HEURE_FIN"); ?>");
        affOnglet('Affichage');
        theForm.zlHeureFin.focus();
        return (false);
      }

      //Cryptage MD5 du mot de passe avant submit (s'il a ete renseigne)
      if (trim(theForm.ztConfirmPasswd.value) != "") {
        theForm.ztPasswdMD5.value = MD5(theForm.ztConfirmPasswd.value);
        //Mots de passe en clair supprimes
        theForm.ztPasswdNew.value = "";
        theForm.ztConfirmPasswd.value = "";
      }

      if (theForm.zlFuseauHoraireORG.value != theForm.zlFuseauHoraire.value) {
        if (window.confirm("<?php echo trad("PROFIL_CHG_TIMEZONE"); ?>")) {
          theForm.zlFuseauHoraireValid.value="OUI";
        }
      }

      theForm.submit();
      return (true);
    }

    function InitProfil() {
<?php
  if ($NoGroupe) {
    echo "      selectUtil(document.frmProfil.zlGroupe, document.frmProfil.zlPrtGroupe);\n";
    echo "      selectUtil(document.frmProfil.zlGroupe2, document.frmProfil.zlAffGroupe);\n";
  }
?>
    }
    // Gestion des onglets
    var tabOnglets = new Array("Info","Affichage","Param","Admin");
    var selOnglet = tabOnglets[0];
    function affOnglet(_onglet) {
        if (_onglet=="Info") HelpPhenixCtx="{77E12098-C143-4D6C-AB5E-660373B46053}.htm";
        if (_onglet=="Affichage") HelpPhenixCtx="{52E3826F-0A74-4031-AE03-2EF75660F860}.htm";
        if (_onglet=="Param") HelpPhenixCtx="{F06EEE75-CA56-4A72-951A-1F8029E2FC77}.htm";
        if (_onglet=="Admin") HelpPhenixCtx="{58782409-74AE-4D5D-9EE4-B21C1588D9B0}.htm";
      document.getElementById("td"+selOnglet).className = "ProfilMenuInactif";
      document.getElementById("href"+selOnglet).className = "ProfilMenuInactif";
      document.getElementById("div"+selOnglet).style.display = "none";
      document.getElementById("td"+_onglet).className = "ProfilMenuActif";
      document.getElementById("href"+_onglet).className = "ProfilMenuActif";
      document.getElementById("div"+_onglet).style.display = "block";
      selOnglet = _onglet;
    }
  //-->
  </SCRIPT>
  <TABLE cellspacing="0" cellpadding="0" width="<?php echo ($idUser) ? "100%" : "565"; ?>" border="0">
  <TR>
    <TD height="28" class="sousMenu"><?php echo $titrePage; ?></TD>
  </TR>
  </TABLE>
  <BR>
  <FORM action="<?php echo $actionForm; ?>" method="post" name="frmProfil">
    <INPUT type="hidden" name="sid" value="<?php echo $sid; ?>">
    <INPUT type="hidden" name="ztAction" value="<?php echo $ztAction; ?>">
    <INPUT type="hidden" name="tcPlg" value="<?php echo $tcPlg; ?>">
    <INPUT type="hidden" name="ztFrom" value="profil">
    <INPUT type="hidden" name="sd" value="<?php echo date("Y-n-j", $sd); ?>">
  <TABLE class="menu" cellspacing="0" cellpadding="0" width="<?php echo ($idUser) ? "585" : "565"; ?>" border="0">
  <TR align="center">
<?php
  if ($droit_PROFILS >= _DROIT_PROFIL_COMPLET or $idAdmin!=0) {
    $tailleCell1 = "40%";
    $tailleCell2 = "20%";
  } else {
    $tailleCell1 = "33%";
    $tailleCell2 = "33%";
  }
?>
    <TD width="<?php echo $tailleCell1; ?>" id="tdInfo" height="22" class="ProfilMenuActif" nowrap><A href="javascript: affOnglet('Info');" id="hrefInfo" class="ProfilMenuActif"><?php echo trad("PROFIL_ONGLET_INFO"); ?></A></TD>
    <TD width="<?php echo $tailleCell2; ?>" id="tdAffichage" class="ProfilMenuInactif"><A href="javascript: affOnglet('Affichage');" id="hrefAffichage" class="ProfilMenuInactif"><?php echo trad("PROFIL_ONGLET_AFFICH"); ?></A></TD>
    <TD width="<?php echo $tailleCell2; ?>" id="tdParam" class="ProfilMenuInactif"><A href="javascript: affOnglet('Param');" id="hrefParam" class="ProfilMenuInactif"><?php echo trad("PROFIL_ONGLET_PARAM"); ?></A></TD>
<?php
  if ($idAdmin!=0) {
?>
    <TD width="<?php echo $tailleCell2; ?>" id="tdAdmin" class="ProfilMenuInactif"><A href="javascript: affOnglet('Admin');" id="hrefAdmin" class="ProfilMenuInactif"><?php echo trad('PROFIL_ONGLET_DROITS'); ?></A></TD>
<?php
  }
?>
  </TR>
  </TABLE>
  <DIV id="divInfo" style="display: block;">
    <TABLE cellspacing="0" cellpadding="0" width="<?php echo ($idUser) ? "585" : "565"; ?>" border="0">
    <TR bgcolor="<?php echo $bgColor[$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_NOM"); ?></TD>
      <TD width="436" class="tabInput"><INPUT type="text" class="Texte" name="ztNom" size="25" maxlength="32" tabindex="<?php echo $tabIndex++; ?>" value="<?php echo htmlspecialchars(stripslashes($rsProfil['util_nom'])); ?>" style="text-transform: <?php echo ($AUTO_UPPERCASE == true) ? "uppercase" : "capitalize"; ?>;"></TD>
    </TR>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_PRENOM"); ?></TD>
      <TD class="tabInput"><INPUT type="text" class="Texte" name="ztPrenom" size="25" maxlength="32" tabindex="<?php echo $tabIndex++; ?>" value="<?php echo htmlspecialchars(stripslashes($rsProfil['util_prenom'])); ?>" style="text-transform: capitalize;"></TD>
    </TR>
<?php
  if ($droit_Aff_Login=="0") {
?>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_LOGIN"); ?></TD>
      <TD class="tabInput"><INPUT type="text" class="Texte" name="ztLogin" size="15" maxlength="12" tabindex="<?php echo $tabIndex++; ?>" value="<?php echo htmlspecialchars(stripslashes($rsProfil['util_login'])); ?>">&nbsp;&nbsp;&nbsp;<INPUT type="button" class="Bouton" value="<?php echo trad("PROFIL_BT_AUTO"); ?>" name="btAutoLogin" tabindex="<?php echo $tabIndex++; ?>" onclick="javascript: loginAuto();"></TD>
    </TR>
<?php
  } else {
?>
    <INPUT type="hidden" name="ztLogin" value="<?php echo htmlspecialchars(stripslashes($rsProfil['util_login'])); ?>">
<?php
  }
  if ($droit_Aff_MDP=="0") {
?>
<?php if ($ztAction == "UPDATE") { ?>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" nowrap height="20"><?php echo trad("PROFIL_LIB_ANCIEN_PASSWD"); ?></TD>
      <TD class="tabInput"><INPUT type="password" class="Texte" name="ztOldPasswd" size="15" maxlength="12" tabindex="<?php echo $tabIndex++; ?>" value=""><INPUT type="hidden" name="ztOldPasswdMD5"></TD>
    </TR>
<?php } ?>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" nowrap height="20"><?php echo $nouveau ?>&nbsp;&nbsp;</TD>
      <TD class="tabInput"><INPUT type="password" class="Texte" name="ztPasswdNew" size="15" maxlength="12" tabindex="<?php echo $tabIndex++; ?>" value=""><INPUT type="hidden" name="ztPasswdMD5"></TD>
    </TR>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_CONFIRMATION"); ?></TD>
      <TD class="tabInput"><INPUT type="password" class="Texte" name="ztConfirmPasswd" size="15" maxlength="12" tabindex="<?php echo $tabIndex++; ?>" value=""></TD>
    </TR>
<?php
  } else {
?>
     <INPUT type="hidden" class="Texte" name="ztOldPasswd"  value=""><INPUT type="hidden" name="ztOldPasswdMD5">
    <INPUT type="hidden" class="Texte" name="ztPasswdNew"  value=""><INPUT type="hidden" name="ztPasswdMD5">
    <INPUT type="hidden" class="Texte" name="ztConfirmPasswd" value="">
<?php
  }
?>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_EMAIL"); ?></TD>
      <TD class="tabInput"><INPUT type="text" class="Texte" name="ztEmail" size="35" maxlength="50" tabindex="<?php echo $tabIndex++; ?>" value="<?php echo htmlspecialchars(stripslashes($rsProfil['util_email'])); ?>">&nbsp;&nbsp;<?php echo trad("PROFIL_RAPPEL_NOTE"); ?></TD>
    </TR>
    </TABLE>
  </DIV>
<?php $iColor = 0; ?>
  <DIV id="divAffichage" style="display: none;">
    <TABLE cellspacing="0" cellpadding="0" width="<?php echo ($idUser) ? "585" : "565"; ?>" border="0">
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_JOURNEE_TYPE"); ?></TD>
      <TD width="436" nowrap class="tabInput"><TABLE cellspacing="0" cellpadding="0" width="320" border="0">
        <TR bgcolor="<?php echo $bgColor[$iColor%2]; ?>">
          <TD width="50%" nowrap><?php echo trad("PROFIL_JOUR_DEBUTE"); ?>&nbsp;<SELECT name="zlHeureDebut" size="1" tabindex="<?php echo $tabIndex++; ?>">
<?php
  for ($i=0; $i<23.5;$i=$i+0.5) {
    $selected = ($i == $rsProfil['util_debut_journee']) ? " selected" : "";
    echo "            <OPTION value=\"".$i."\"".$selected.">".afficheHeure($i,$i,$formatHeure)."</OPTION>\n";
  }
?>
          </SELECT></TD>
          <TD width="50%" nowrap><?php echo trad("PROFIL_JOUR_TERMINE"); ?>&nbsp;<SELECT name="zlHeureFin" size="1" tabindex="<?php echo $tabIndex++; ?>">
<?php
  for ($i=0.5; $i<24;$i=$i+0.5) {
    $selected = ($i == $rsProfil['util_fin_journee']) ? " selected" : "";
    echo "            <OPTION value=\"".$i."\"".$selected.">".afficheHeure($i,$i,$formatHeure)."</OPTION>\n";
  }
?>
          </SELECT></TD>
        </TR>
      </TABLE></TD>
    </TR>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_AFFICH_NOM"); ?></TD>
      <TD class="tabInput"><SELECT name="zlFormatNom" size="1" tabindex="<?php echo $tabIndex++; ?>">
        <OPTION value="0"<?php if ($rsProfil['util_format_nom']=="0") echo " selected"; ?>><?php echo trad("PROFIL_NOM_PRENOM"); ?></OPTION>
        <OPTION value="1"<?php if ($rsProfil['util_format_nom']=="1") echo " selected"; ?>><?php echo trad("PROFIL_PRENOM_NOM"); ?></OPTION>
      </SELECT></TD>
    </TR>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_TELEPHONE"); ?></TD>
      <TD class="tabInput"><LABEL for="vf"><INPUT type="radio" name="rdTelephone" value="O" class="Case" id="vf" tabindex="<?php echo $tabIndex++; ?>"<?php if ($rsProfil['util_telephone_vf']!="N") echo " checked"; ?>>&nbsp;<?php echo trad("COMMUN_OUI"); ?></LABEL>&nbsp;&nbsp;&nbsp;&nbsp;<LABEL for="novf"><INPUT type="radio" name="rdTelephone" value="N" class="Case" id="novf" tabindex="<?php echo $tabIndex++; ?>"<?php if ($rsProfil['util_telephone_vf']=="N") echo " checked"; ?>>&nbsp;<?php echo trad("COMMUN_NON"); ?></LABEL>&nbsp;&nbsp;<?php echo trad("PROFIL_STYLE_TELEPHONE"); ?></TD>
    </TR>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_LANGUE"); ?></TD>
      <TD class="tabInput"><SELECT name="zlLangue" size="1" tabindex="<?php echo $tabIndex++; ?>">
<?php
  // Recuperation des noms de langue directement dans les fichiers du repertoire "lang"
  $rep = opendir("./lang");
  while ($file = readdir($rep)) {
    if ($file!=".." && $file!="." && $file!="" && $file!="index.htm") {
      if (!is_dir("lang/".$file) && $fd = @fopen("lang/".$file, "r")) {
        while (!@feof($fd)) {
          $ligne = @fgets($fd);
          if (@strpos($ligne,"['COMMUN_NOM_LANGUE']")!==false) {
            $pos1 = @strpos($ligne,"\"");
            $pos2 = @strpos(substr($ligne,$pos1+1),"\"");
            break;
          }
        }
        $tabLangue[substr($file,0,@strpos($file,"."))] = @substr($ligne,$pos1+1,$pos2);
        fclose($fd);
      }
    }
  }
  closedir($rep);
  clearstatcache();
  foreach ($tabLangue as $key=>$val) {
    $selected = ($rsProfil['util_langue'] == $key) ? " selected" : "";
    echo "        <OPTION value=\"".$key."\"".$selected.">".$val."</OPTION>\n";
  }
?>
      </SELECT></TD>
    </TR>
<?php
if ($droit_Aff_THEME=="0") {
?>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_INTERFACE"); ?></TD>
      <TD class="tabInput"><SELECT name="zlInterface" size="1" tabindex="<?php echo $tabIndex++; ?>">
<?php
  // Recuperation des noms d'interface directement dans les fichiers du repertoire "skins"
  $rep = opendir("./skins");
  while ($file = readdir($rep)) {
    if ($file!=".." && $file!="." && $file!="" && $file!="index.htm") {
      if (!is_dir("skins/".$file) && $fd = @fopen("skins/".$file, "r")) {
        $ligne = fread($fd, 200);
        $pos1 = @strpos($ligne,"\"");
        $pos2 = @strpos(substr($ligne,$pos1+1),"\"");
        $typeSkin = substr(stristr($ligne,"SkinAccueil="),12,1);
        if ($typeSkin!=1)
          $tabInterface[substr($file,0,@strpos($file,"."))] = trim(@substr($ligne,$pos1+1,$pos2));
        fclose($fd);
      }
    }
  }
  closedir($rep);
  clearstatcache();
  ksort($tabInterface);
  if (!file_exists("skins/".$rsProfil['util_interface'].".php")) {
    $rsProfil['util_interface'] = $APPLI_STYLE;
  }
  foreach ($tabInterface as $nomFic=>$nomSkin) {
    if (!empty($nomSkin)) {
      $selected = (strcasecmp($rsProfil['util_interface'], $nomFic)==0) ? " selected" : "";
      echo "        <OPTION value=\"".$nomFic."\"".$selected.">".$nomSkin."</OPTION>\n";
    }
  }
?>
      </SELECT></TD>
    </TR>
<?php
} else {
?>
    <INPUT type="hidden" name="zlInterface" value="<?php echo $rsProfil['util_interface'];?>">
<?php
}
if ($AUTORISE_FCKE_CFG && $AUTORISE_HTML) {
?>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_FCKE"); ?></TD>
      <TD class="tabInput"><SELECT name="zlFCKE" onchange="javascript: autoriseFCKE(document.frmProfil,this.value,false);" tabindex="<?php echo $tabIndex++; ?>"><OPTION value="O"<?php echo (($rsProfil['util_fcke']!="N") ? " selected" : "").">".trad("COMMUN_OUI"); ?></OPTION><OPTION value="N"<?php echo (($rsProfil['util_fcke']=="N") ? " selected" : "").">".trad("COMMUN_NON"); ?></OPTION></SELECT>
<?php
  if ($FCKE_TOOLBAR_CFG == "User") {
?>
<?php  //  Mod fcke_aff_toolbar ?>
      &nbsp;&nbsp;&nbsp;&nbsp;
<?php  //  Mod fcke_aff_toolbar ?>
      <B><?php echo trad("PROFIL_LIB_FCKE_OUTILS"); ?></B>&nbsp;<SELECT name="zlFCKEbar" <?php echo (($rsProfil['util_fcke']=="N") ? " disabled" : ""); ?>>
        <OPTION value="Basic"<?php if ($rsProfil['util_fcke_toolbar']=="Basic") echo " selected"; ?>><?php echo trad("PROFIL_FKE_OPT1"); ?></OPTION>
        <OPTION value="Intermed"<?php if ($rsProfil['util_fcke_toolbar']=="Intermed") echo " selected"; ?>><?php echo trad("PROFIL_FKE_OPT2"); ?></OPTION>
        <OPTION value="Extend"<?php if ($rsProfil['util_fcke_toolbar']=="Extend") echo " selected"; ?>><?php echo trad("PROFIL_FKE_OPT3"); ?></OPTION>
        <OPTION value="Full"<?php if ($rsProfil['util_fcke_toolbar']=="Full") echo " selected"; ?>><?php echo trad("PROFIL_FKE_OPT4"); ?></OPTION></SELECT>
<?php  //  Mod fcke_aff_toolbar ?>  
        &nbsp;
        <SELECT name="zlFCKEbar_aff" tabindex="<?php echo $tabIndex++; ?>" <?php echo (($rsProfil['util_fcke']=="N") ? " disabled" : ""); ?>><OPTION value="O"<?php echo (($rsProfil['util_fcke_aff_toolbar']!="N") ? " selected" : "").">".trad("MODfcke-aff"); ?></OPTION><OPTION value="N"<?php echo (($rsProfil['util_fcke_aff_toolbar']=="N") ? " selected" : "").">".trad("MODfcke-cach"); ?></OPTION></SELECT>
<?php  //  Mod fcke_aff_toolbar ?>  
<?php
  } else {
        //  Mod fcke_aff_toolbar ?>  
        &nbsp;&nbsp;&nbsp;&nbsp;<B><?php echo trad("PROFIL_LIB_FCKE_OUTILS"); ?></B>&nbsp;
        <SELECT name="zlFCKEbar_aff" tabindex="<?php echo $tabIndex++; ?>" <?php echo (($rsProfil['util_fcke']=="N") ? " disabled" : ""); ?>><OPTION value="O"<?php echo (($rsProfil['util_fcke_aff_toolbar']!="N") ? " selected" : "").">".trad("MODfcke-aff"); ?></OPTION><OPTION value="N"<?php echo (($rsProfil['util_fcke_aff_toolbar']=="N") ? " selected" : "").">".trad("MODfcke-cach"); ?></OPTION></SELECT>
<?php  //  Mod fcke_aff_toolbar
    echo "    <INPUT type=\"hidden\" name=\"zlFCKEbar\" value=\"".$FCKE_TOOLBAR_CFG."\">\n";
  }
  echo ("      </TD>
    </TR>\n");
} else {
?>
    <INPUT type="hidden" name="zlFCKE" value="N">
<?php  //  Mod fcke_aff_toolbar ?>
    <INPUT type="hidden" name="zlFCKEbar_aff" value="<?php echo $rsProfil['util_fcke_aff_toolbar']; ?>">
<?php  //  Mod fcke_aff_toolbar ?>
    <INPUT type="hidden" name="zlFCKEbar" value="<?php echo $FCKE_TOOLBAR_CFG; ?>">
<?php
}
?>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_PLANNING"); ?></TD>
      <TD class="tabInput"><SELECT name="zlPlanning" size="1" tabindex="<?php echo $tabIndex++; ?>">
        <OPTION value="0"<?php if ($rsProfil['util_planning']==0) echo " selected"; ?>><?php echo trad("PROFIL_PLG_QUOT"); ?></OPTION>
        <OPTION value="1"<?php if ($rsProfil['util_planning']==1) echo " selected"; ?>><?php echo trad("PROFIL_PLG_HEBDO"); ?></OPTION>
        <OPTION value="2"<?php if ($rsProfil['util_planning']==2) echo " selected"; ?>><?php echo trad("PROFIL_PLG_MENS"); ?></OPTION>
        <OPTION value="6"<?php if ($rsProfil['util_planning']==6) echo " selected"; ?>><?php echo trad("PROFIL_PLG_QUOTGLOB"); ?></OPTION>
        <OPTION value="5"<?php if ($rsProfil['util_planning']==5) echo " selected"; ?>><?php echo trad("PROFIL_PLG_HEBDGLOB"); ?></OPTION>
        <OPTION value="4"<?php if ($rsProfil['util_planning']==4) echo " selected"; ?>><?php echo trad("PROFIL_PLG_MENSGLOB"); ?></OPTION>
      </SELECT>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <B><?php echo trad("PROFIL_LIB_DISPO"); ?></B>&nbsp;<SELECT name="zlMenuDispo" size="1" tabindex="<?php echo $tabIndex++; ?>">
        <OPTION value="9"<?php if ($rsProfil['util_menu_dispo']==9) echo " selected"; ?>><?php echo trad("PROFIL_DISPO_QUOT"); ?></OPTION>
        <OPTION value="8"<?php if ($rsProfil['util_menu_dispo']==8) echo " selected"; ?>><?php echo trad("PROFIL_DISPO_HEBDO"); ?></OPTION>
        </SELECT></TD>
      </TR>
<?php      
  //  MODS menu note
?>  
      <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
        <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_MENU_NOTE"); ?></TD>
        <TD class="tabInput"><LABEL for="complet"><INPUT type="radio" name="rdMenuNote" value="N" class="Case" id="complet" tabindex="<?php echo $tabIndex++; ?>"<?php if ($rsProfil['util_menu_note']=="N") echo " checked"; ?>>&nbsp;<?php echo trad("PROFIL_MENU_COMPLET"); ?></LABEL>&nbsp;&nbsp;&nbsp;&nbsp;<LABEL for="reduit"><INPUT type="radio" name="rdMenuNote" value="O" class="Case" id="reduit" tabindex="<?php echo $tabIndex++; ?>"<?php if ($rsProfil['util_menu_note']=="O") echo " checked"; ?>>&nbsp;<?php echo trad("PROFIL_MENU_REDUIT"); ?></LABEL></TD>
      </TR>
<?php      
  //  MODS menu note
?>  
      <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
        <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_SEMAINE"); ?></TD>
        <TD class="tabInput"><LABEL for="lundi"><INPUT type="checkbox" name="bt1" value="1" tabindex="<?php echo $tabIndex++; ?>"<?php if ($bt1==1) echo " checked"; ?> class="case" id="lundi">&nbsp;<?php echo trad("PROFIL_LUN"); ?></LABEL>&nbsp;&nbsp;
          <LABEL for="mardi"><INPUT type="checkbox" name="bt2" value="1" tabindex="<?php echo $tabIndex++; ?>"<?php if ($bt2==1) echo " checked"; ?> class="case" id="mardi">&nbsp;<?php echo trad("PROFIL_MAR"); ?></LABEL>&nbsp;&nbsp;
        <LABEL for="mercredi"><INPUT type="checkbox" name="bt3" value="1" tabindex="<?php echo $tabIndex++; ?>"<?php if ($bt3==1) echo " checked"; ?> class="case" id="mercredi">&nbsp;<?php echo trad("PROFIL_MER"); ?></LABEL>&nbsp;&nbsp;
        <LABEL for="jeudi"><INPUT type="checkbox" name="bt4" value="1" tabindex="<?php echo $tabIndex++; ?>"<?php if ($bt4==1) echo " checked"; ?> class="case" id="jeudi">&nbsp;<?php echo trad("PROFIL_JEU"); ?></LABEL>&nbsp;&nbsp;
        <LABEL for="vendredi"><INPUT type="checkbox" name="bt5" value="1" tabindex="<?php echo $tabIndex++; ?>"<?php if ($bt5==1) echo " checked"; ?> class="case" id="vendredi">&nbsp;<?php echo trad("PROFIL_VEN"); ?></LABEL>&nbsp;&nbsp;
        <LABEL for="samedi"><INPUT type="checkbox" name="bt6" value="1" tabindex="<?php echo $tabIndex++; ?>"<?php if ($bt6==1) echo " checked"; ?> class="case" id="samedi">&nbsp;<?php echo trad("PROFIL_SAM"); ?></LABEL>&nbsp;&nbsp;
        <LABEL for="dimanche"><INPUT type="checkbox" name="bt7" value="1" tabindex="<?php echo $tabIndex++; ?>"<?php if ($bt7==1) echo " checked"; ?> class="case" id="dimanche">&nbsp;<?php echo trad("PROFIL_DIM"); ?></LABEL></TD>
    </TR>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_PRECISION"); ?></TD>
      <TD class="tabInput"><SELECT name="zlPrecision" size="1" tabindex="<?php echo $tabIndex++; ?>">
        <OPTION value="1"<?php if ($rsProfil['util_precision_planning']==1) echo " selected"; ?>><?php echo trad("PROFIL_PREC_30_MN"); ?></OPTION>
        <OPTION value="2"<?php if ($rsProfil['util_precision_planning']==2) echo " selected"; ?>><?php echo trad("PROFIL_PREC_15_MN"); ?></OPTION>
      </SELECT>&nbsp;&nbsp;<?php echo trad("PROFIL_PREC_PLANNING"); ?></TD>
    </TR>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_DUREE"); ?></TD>
      <TD class="tabInput"><SELECT name="zlDureeNote" size="1" tabindex="<?php echo $tabIndex++; ?>">
        <OPTION value="1"<?php if ($rsProfil['util_duree_note']==1) echo " selected"; ?>><?php echo trad("PROFIL_DUREE_15_MN"); ?></OPTION>
        <OPTION value="2"<?php if ($rsProfil['util_duree_note']==2) echo " selected"; ?>><?php echo trad("PROFIL_DUREE_30_MN"); ?></OPTION>
        <OPTION value="3"<?php if ($rsProfil['util_duree_note']==3) echo " selected"; ?>><?php echo trad("PROFIL_DUREE_45_MN"); ?></OPTION>
        <OPTION value="4"<?php if ($rsProfil['util_duree_note']==4) echo " selected"; ?>><?php echo trad("PROFIL_DUREE_1_H"); ?></OPTION>
      </SELECT>&nbsp;&nbsp;<?php echo trad("PROFIL_DUREE_SEL_AUTO"); ?></TD>
    </TR>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_ASPECT"); ?></TD>
      <TD class="tabInput"><LABEL for="barree"><INPUT type="radio" name="rdBarree" value="O" class="Case" id="barree" tabindex="<?php echo $tabIndex++; ?>"<?php if ($rsProfil['util_note_barree']!="N") echo " checked"; ?>>&nbsp;<FONT style="text-decoration:line-through;"><?php echo trad("PROFIL_ASPECT_BARREE"); ?></FONT></LABEL>&nbsp;&nbsp;&nbsp;&nbsp;<LABEL for="nonbarree"><INPUT type="radio" name="rdBarree" value="N" class="Case" id="nonbarree" tabindex="<?php echo $tabIndex++; ?>"<?php if ($rsProfil['util_note_barree']=="N") echo " checked"; ?>>&nbsp;<?php echo trad("PROFIL_ASPECT_NON_BARREE"); ?></LABEL></TD>
    </TR>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_FUSEAU"); ?></TD>
      <TD class="tabInput">
      <INPUT type="hidden" name="zlFuseauHoraireORG" value="<?php echo $rsProfil['util_timezone']; ?>">
      <INPUT type="hidden" name="zlFuseauHoraireValid" value="NON">
      <SELECT name="zlFuseauHoraire" size="1" tabindex="<?php echo $tabIndex++; ?>">
<?php
  // On recupere la liste des fuseaux horaires
  $DB_CX->DbQuery("SELECT tzn_zone, tzn_libelle, tzn_gmt FROM ${PREFIX_TABLE}timezone ORDER BY tzn_gmt, tzn_libelle");
  while ($enr = $DB_CX->DbNextRow()) {
    $selected = ($rsProfil['util_timezone'] == $enr['tzn_zone']) ? " selected" : "";
    $signe = ($enr['tzn_gmt']<0) ? "-" : "+";
    $gmt = abs($enr['tzn_gmt']);
    echo "        <OPTION value=\"".$enr['tzn_zone']."\"".$selected.">(GMT".$signe.afficheHeure(floor($gmt),$gmt).") ".htmlentities($enr['tzn_libelle'])."</OPTION>\n";
  }
?>
      </SELECT><BR>
      <LABEL for="fuseau"><INPUT type="checkbox" name="ckFuseauPartage" value="O" class="Case" id="fuseau" tabindex="<?php echo $tabIndex++; ?>"<?php if ($rsProfil['util_timezone_partage']=="O") echo " checked"; ?>>&nbsp;<?php echo trad("PROFIL_FUSEAU_ORIGINE"); ?></LABEL></TD>
    </TR>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_FORMAT_AFFICHAGE"); ?></TD>
      <TD class="tabInput"><SELECT name="zlFormatHeure" size="1" tabindex="<?php echo $tabIndex++; ?>">
        <OPTION value="24"<?php if ($rsProfil['util_format_heure']=="24") echo " selected"; ?>><?php echo trad("PROFIL_AFFICHAGE_24"); ?></OPTION>
        <OPTION value="12"<?php if ($rsProfil['util_format_heure']=="12") echo " selected"; ?>><?php echo trad("PROFIL_AFFICHAGE_12"); ?></OPTION>
      </SELECT></TD>
    </TR>
    <!-- MOD meteo -->
  <SCRIPT>
  function switch_meteo(code_ville) {
  if (code_ville=="") code_ville = document.getElementById('ztMeteoCode').value;
  if (document.getElementById('ztMeteoActif').value == '0') {
    document.getElementById('ztMeteoActif').value = '1';
    document.getElementById('ztMeteo').value = code_ville +';1';
  } 
  else  {
    document.getElementById('ztMeteoActif').value = '0';
    document.getElementById('ztMeteo').value = code_ville +';0';
  }
  }
  </SCRIPT>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
  <?php
  $meteo = explode(";",$rsProfil['util_meteo_code']);
  ?>
      <TD class="tabIntitule" height="20"><?php echo trad("MODMET_PROFIL"); ?></TD>
      <TD class="tabInput"><?php echo trad("MODMET_CODE"); ?> : <INPUT type="text" class="Texte" name="ztMeteoCode" id="ztMeteoCode" size="10" maxlength="10" tabindex="<?php echo $tabIndex++; ?>" value="<?php echo $meteo[0]; ?>">&nbsp;&nbsp;&nbsp;<INPUT type="checkbox" name="ztMeteoActif" id="ztMeteoActif" value="<?php if ($meteo[1]=="1") echo "1";else echo "0"; ?>" class="Case" onchange="javascript: switch_meteo('<?php echo $meteo[0]; ?>');" tabindex="<?php echo $tabIndex++; ?>"<?php if ($meteo[1]=="1") echo " checked"; ?>><?php echo trad("MODMET_ACTIF"); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<?php echo trad("MODMET_EX"); ?> <A href="http://www.weather.com/" target="_blank">weather.com</A>)
    <INPUT type="hidden" id="ztMeteo" name="ztMeteo" value="">
    </TD>
    </TR>
    <!-- fin mod meteo-->  
    <!-- MOD horoscope v1.1-->
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20">Votre horoscope</TD>
      <TD class="tabInput">Choisissez votre signe :
    <SELECT name="ztHoroscope" size="1" tabindex="<?php echo $tabIndex++; ?>">
        <OPTION value=""<?php if ($rsProfil['util_horo']=="") echo " selected"; ?>></OPTION>
      <OPTION value="belier"<?php if ($rsProfil['util_horo']=="belier") echo " selected"; ?>>B&eacute;lier</OPTION>
        <OPTION value="taureau"<?php if ($rsProfil['util_horo']=="taureau") echo " selected"; ?>>Taureau</OPTION>
        <OPTION value="gemeaux"<?php if ($rsProfil['util_horo']=="gemeaux") echo " selected"; ?>>G&eacute;meaux</OPTION>
        <OPTION value="cancer"<?php if ($rsProfil['util_horo']=="cancer") echo " selected"; ?>>Cancer</OPTION>
        <OPTION value="lion"<?php if ($rsProfil['util_horo']=="lion") echo " selected"; ?>>Lion</OPTION>
        <OPTION value="vierge"<?php if ($rsProfil['util_horo']=="vierge") echo " selected"; ?>>Vierge</OPTION>
        <OPTION value="balance"<?php if ($rsProfil['util_horo']=="balance") echo " selected"; ?>>Balance</OPTION>
        <OPTION value="scorpion"<?php if ($rsProfil['util_horo']=="scorpion") echo " selected"; ?>>Scorpion</OPTION>
        <OPTION value="sagittaire"<?php if ($rsProfil['util_horo']=="sagittaire") echo " selected"; ?>>Sagittaire</OPTION>
        <OPTION value="capricorne"<?php if ($rsProfil['util_horo']=="capricorne") echo " selected"; ?>>Capricorne</OPTION>
        <OPTION value="verseau"<?php if ($rsProfil['util_horo']=="verseau") echo " selected"; ?>>Verseau</OPTION>
        <OPTION value="poissons"<?php if ($rsProfil['util_horo']=="poissons") echo " selected"; ?>>Poissons</OPTION>    
     </SELECT>
    </TD>
    </TR>
    <!-- fin mod horoscope-->  
    <!-- MOD D&D -->
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("MODDD_PROFIL"); ?></TD>
      <TD class="tabInput"><INPUT onClick="if (document.frmProfil.ztDDActif.value=='0') document.frmProfil.ztDDActif.value='1';else document.frmProfil.ztDDActif.value='0';" type="checkbox" name="ztDDActif" id="ztDDActif" value="<?php echo $rsProfil['util_dd'];?>" class="Case" tabindex="<?php echo $tabIndex++; ?>" <?php if ($rsProfil['util_dd']=="1") echo " checked"; ?>><?php echo trad("MODDD_PROFIL_DESC"); ?>
	  </TD>
    </TR>
    <!-- Fin mod D&D -->
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_MENU_ONCLICK"); ?></TD>
      <TD class="tabInput"><LABEL for="onclickok"><INPUT type="radio" name="rdOnClick" value="O" class="Case" id="onclickok" tabindex="<?php echo $tabIndex++; ?>"<?php if ($rsProfil['util_menuonclick']!="N") echo " checked"; ?>>&nbsp;<?php echo trad("COMMUN_OUI"); ?></LABEL>&nbsp;&nbsp;&nbsp;&nbsp;<LABEL for="onclickno"><INPUT type="radio" name="rdOnClick" value="N" class="Case" id="onclickno" tabindex="<?php echo $tabIndex++; ?>"<?php if ($rsProfil['util_menuonclick']=="N") echo " checked"; ?>>&nbsp;<?php echo trad("COMMUN_NON"); ?></LABEL></TD>
    </TR>
    </TABLE>
  </DIV>
<?php $iColor = 0; ?>
  <DIV id="divParam" style="display: none;">
    <TABLE cellspacing="0" cellpadding="0" width="<?php echo ($idUser) ? "585" : "565"; ?>" border="0">
<?php
  // MOD Couleur par defaut
  if ($ztAction=="UPDATE") { // On n'est pas dans le cas d'une creation de compte
?>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_COULEUR"); ?></TD>
      <TD class="tabInput"><?php
    //Recuperation des couleurs/categories de notes
    $tabTemp    = array(trad("COMMUN_COUL_DEFAUT") => $AgendaFondNotePerso);
    $tabCouleur = array_merge($tabTemp,getListeCouleur());
    //Construction de la liste des couleurs/categories de notes
    reset($tabCouleur);
    if (empty($rsProfil['util_couleur']))
      $rsProfil['util_couleur'] = $AgendaFondNotePerso;
    echo "<SELECT name=\"zlCouleur\" style=\"background-color:".$rsProfil['util_couleur'].";\" onchange=\"javascript: changeCouleurListe(this,document.frmProfil.ztCouleur);\">\n";
    while (list($key, $val) = each($tabCouleur)) {
      $selected = ($val==$rsProfil['util_couleur']) ? " selected" : "";
      echo "        <OPTION style=\"background-color:".$val.";\" value=\"".$val."\"".$selected.">".$key."</OPTION>\n";
    }
?>
      </SELECT>&nbsp;&nbsp;&nbsp;<INPUT type="text" name="ztCouleur" class="Texte" value="<?php echo trad("NOTE_APPARENCE_NOTE"); ?>" style="background:<?php echo $rsProfil['util_couleur']; ?>; text-align:center; font-weight:bold; height:17px;" size=25 readonly tabindex="1000"></TD>
    </TR>
<?php
  }
  // Fin MOD Couleur par defaut
?>
<?php
  if ($rsProfil['util_rappel_delai']) {
    $rdRappel1 = "";
    $rdRappel2 = " checked";
  } else {
    $rdRappel1 = " checked";
    $rdRappel2 = "";
  }
?>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>">
      <TD class="tabIntitule"><?php echo trad("PROFIL_RAPPEL_CREATION"); ?></TD>
      <TD width="436" class="tabInput" style="padding:0px;"><TABLE cellspacing="0" cellpadding="0" width="100%" border="0">
        <TR>
          <TD height="20"><IMG src="image/trans.gif" alt="" width="2" height="1" border="0"><LABEL for="rdRappel1"><INPUT type="radio" name="rdRappel" id="rdRappel1" value="1" tabindex="<?php echo $tabIndex++; ?>" class="Case"<?php echo $rdRappel1; ?>>&nbsp;<?php echo trad("PROFIL_PAS_RAPPEL"); ?></LABEL></TD>
        </TR>
        <TR>
          <TD height="20"><IMG src="image/trans.gif" alt="" width="2" height="1" border="0"><LABEL for="rdRappel2"><INPUT type="radio" name="rdRappel" id="rdRappel2" value="2" tabindex="<?php echo $tabIndex++; ?>" class="Case"<?php echo $rdRappel2; ?>>&nbsp;<?php echo trad("COMMUN_LIB_RAPPEL"); ?></LABEL>&nbsp;<SELECT name="zlRappelDelai" tabindex="<?php echo $tabIndex++; ?>" onFocus="document.frmProfil.rdRappel[1].checked='true';">
<?php
  if (!$rsProfil['util_rappel_delai']) {
    $rsProfil['util_rappel_delai'] = 5;
    $rsProfil['util_rappel_type'] = 1;
    $rsProfil['util_rappel_email'] = 0;
  }
  for ($i=1;$i<60;$i++) {
    $selected = ($rsProfil['util_rappel_delai']==$i) ? " selected" : "";
    echo "            <OPTION value=\"".$i."\"".$selected.">".$i."</OPTION>\n";
  }
?>
          </SELECT>
          <SELECT name="zlRappelType" tabindex="<?php echo $tabIndex++; ?>" onFocus="document.frmProfil.rdRappel[1].checked='true';">
            <OPTION value="1"<?php if ($rsProfil['util_rappel_type']==1) echo " selected"; ?>><?php echo trad("COMMUN_MINUTE"); ?></OPTION>
            <OPTION value="60"<?php if ($rsProfil['util_rappel_type']==60) echo " selected"; ?>><?php echo trad("COMMUN_HEURE"); ?></OPTION>
            <OPTION value="1440"<?php if ($rsProfil['util_rappel_type']==1440) echo " selected"; ?>><?php echo trad("COMMUN_JOUR"); ?></OPTION>
          </SELECT>&nbsp;<?php echo trad("COMMUN_AVANCE"); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<LABEL for="AlEmail"><INPUT type="checkbox" name="ckRappelEmail" value="1" class="Case" id="AlEmail" tabindex="<?php echo $tabIndex++; ?>"<?php if ($rsProfil['util_rappel_email']==1) echo " checked"; ?>>&nbsp;<?php echo trad("PROFIL_COPIE_MAIL"); ?></LABEL></TD>
        </TR>
      </TABLE></TD>
    </TR>
<?php  // Mod Son ?>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("MOD_SON_AFFICHAGE"); ?></TD>
      <TD class="tabInput">
        <LABEL for="RappelSon"><INPUT type="checkbox" name="ckRappelSon" value="O" class="Case" id="RappelSon" tabindex="<?php echo $tabIndex++; ?>"<?php if ($rsProfil['util_rappel_son']=="O") echo " checked"; ?>>&nbsp;<?php echo trad("MOD_SON_OUI"); ?></LABEL>
        &nbsp;&nbsp;&nbsp;&nbsp;<b><?php echo trad("MOD_SON_CHOIX"); ?></b>&nbsp;<SELECT name="zlSon" size="1" tabindex="<?php echo $tabIndex++; ?>">
<?php
  // Recuperation des noms de fichiers sons du repertoire "son"
  $rep = opendir("./son");
  while ($file = readdir($rep)) {
    if ($file!=".." && $file!="." && $file!="" && $file!="index.htm") {
      if (!is_dir("son/".$file)) {
        $tabSon[]=$file;
      }
    }
  }
  closedir($rep);
  clearstatcache();
  foreach ($tabSon as $key=>$val) {
    $pos = strrpos($val,".");
    $NomSon= substr($val,0,$pos);
    $selected = ($rsProfil['util_choix_son'] == $val) ? " selected" : "";
    echo "        <OPTION value=\"".$val."\"".$selected.">".$NomSon."</OPTION>\n";
  }
?>
      </SELECT></TD>
    </TR>
<?php  // Mod Son ?>
<?php
  if ($rsProfil['util_rappel_anniv']) {
    $rdAnniv1 = "";
    $rdAnniv2 = " checked";
  }
  else {
    $rdAnniv1 = " checked";
    $rdAnniv2 = "";
  }
?>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>">
      <TD class="tabIntitule"><?php echo trad("PROFIL_LIB_RAPPEL_ANNIV"); ?></TD>
      <TD class="tabInput" style="padding:0px;"><TABLE cellspacing="0" cellpadding="0" width="100%" border="0">
        <TR>
          <TD height="20"><IMG src="image/trans.gif" alt="" width="2" height="1" border="0"><LABEL for="rdAnniv1"><INPUT type="radio" name="rdRappelAnniv" id="rdAnniv1" value="1" tabindex="<?php echo $tabIndex++; ?>" class="Case"<?php echo $rdAnniv1; ?>>&nbsp;<?php echo trad("PROFIL_PAS_RAPPEL"); ?></LABEL></TD>
        </TR>
        <TR>
          <TD height="20"><IMG src="image/trans.gif" alt="" width="2" height="1" border="0"><LABEL for="rdAnniv2"><INPUT type="radio" name="rdRappelAnniv" id="rdAnniv2" value="2" tabindex="<?php echo $tabIndex++; ?>" class="Case"<?php echo $rdAnniv2; ?>>&nbsp;<?php echo trad("COMMUN_LIB_RAPPEL"); ?></LABEL>&nbsp;<SELECT name="zlRappelAnniv" tabindex="<?php echo $tabIndex++; ?>" onFocus="document.frmProfil.rdRappelAnniv[1].checked='true';">
<?php
  if (!$rsProfil['util_rappel_anniv']) {
    $rsProfil['util_rappel_anniv'] = 1;
    $rsProfil['util_rappel_anniv_coeff'] = 1440;
    $rsProfil['util_rappel_anniv_email'] = 0;
  }
  for ($i=1;$i<60;$i++) {
    $selected = ($rsProfil['util_rappel_anniv']==$i) ? " selected" : "";
    echo "            <OPTION value=\"".$i."\"".$selected.">".$i."</OPTION>\n";
  }
?>
          </SELECT>
          <SELECT name="zlRappelAnnivCoeff" tabindex="<?php echo $tabIndex++; ?>" onFocus="document.frmProfil.rdRappelAnniv[1].checked='true';">
            <OPTION value="1"<?php if ($rsProfil['util_rappel_anniv_coeff']==1) echo " selected"; ?>><?php echo trad("COMMUN_MINUTE"); ?></OPTION>
            <OPTION value="60"<?php if ($rsProfil['util_rappel_anniv_coeff']==60) echo " selected"; ?>><?php echo trad("COMMUN_HEURE"); ?></OPTION>
            <OPTION value="1440"<?php if ($rsProfil['util_rappel_anniv_coeff']==1440) echo " selected"; ?>><?php echo trad("COMMUN_JOUR"); ?></OPTION>
          </SELECT>&nbsp;<?php echo trad("COMMUN_AVANCE"); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<LABEL for="annivEmail"><INPUT type="checkbox" name="ckAnnivEmail" value="1" class="Case" id="annivEmail" tabindex="<?php echo $tabIndex++; ?>"<?php if ($rsProfil['util_rappel_anniv_email']==1) echo " checked"; ?>>&nbsp;<?php echo trad("PROFIL_COPIE_MAIL"); ?></LABEL></TD>
        </TR>
      </TABLE></TD>
    </TR>
<?php
  if (($droit_PROFILS >= _DROIT_PROFIL_AUTRE_PARAM_PARTAGE) or (($droit_PROFILS >= _DROIT_PROFIL_PARAM_PARTAGE) and ($idUser==$USER_SUBSTITUE))) {
?>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>">
      <TD class="tabIntitule" nowrap><?php echo trad("PROFIL_LIB_CONSULTATION"); ?></TD>
      <TD style="padding-bottom:2px;" class="tabInput"><?php echo trad("PROFIL_AUTORISE_CONSULT"); ?><BR>
        <LABEL for="prive"><INPUT type="radio" name="rdPartage" value="0" class="Case" id="prive" tabindex="<?php echo $tabIndex++; ?>"<?php if ($rsProfil['util_partage_planning']=="0") echo " checked"; ?> onclick="javascript: vPartage=0; selectTous(document.frmProfil.zlPartage, document.frmProfil.zlUtilisateur);selectTous(document.frmProfil.zlPrtGroupe, document.frmProfil.zlGroupe);ModifAffect();">&nbsp;<?php echo trad("PROFIL_NON_PARTAGE"); ?></LABEL>&nbsp;&nbsp;&nbsp;&nbsp;<LABEL for="selectif"><INPUT type="radio" name="rdPartage" value="2" class="Case" id="selectif" tabindex="<?php echo $tabIndex++; ?>"<?php if ($rsProfil['util_partage_planning']=="2") echo " checked"; ?> onclick="javascript: vPartage=2;">&nbsp;<?php echo trad("PROFIL_AU_CHOIX"); ?></LABEL>&nbsp;&nbsp;&nbsp;&nbsp;<LABEL for="public"><INPUT type="radio" name="rdPartage" value="1" class="Case" id="public" tabindex="<?php echo $tabIndex++; ?>"<?php if ($rsProfil['util_partage_planning']=="1") echo " checked"; ?> onclick="javascript: vPartage=1; selectTous(document.frmProfil.zlUtilisateur, document.frmProfil.zlPartage); ModifAffect();">&nbsp;<?php echo trad("PROFIL_TOUT_MONDE"); ?></LABEL>
        <BR><TABLE cellspacing="0" cellpadding="0" width="100%" border="0" align="center">
        <TR>
          <TH><?php echo trad("PROFIL_PERS_POSSIBLE"); ?></TH>
          <TH>&nbsp;</TH>
          <TH><?php echo trad("PROFIL_PERS_SELECTIONNEE"); ?></TH>
        </TR>
        <TR>
          <TD><SELECT name="zlUtilisateur" id="zlUtilisateur" size="6" multiple tabindex="<?php echo $tabIndex++; ?>" style="width:200px;">
<?php
  $tabPartage = array();
  // On recupere la liste des personnes concernees par le partage sauf l'utilisateur courant
  if ($ztAction=="UPDATE") { // On n'est pas dans le cas d'une creation de compte
    $DB_CX->DbQuery("SELECT ppl_consultant_id FROM ${PREFIX_TABLE}planning_partage WHERE ppl_util_id=".$USER_SUBSTITUE." AND ppl_consultant_id!=".$USER_SUBSTITUE." AND ppl_gr='0'");
    while ($enr = $DB_CX->DbNextRow())
      $tabPartage[] = $enr['ppl_consultant_id'];
  } else {
    $tabPartage = explode("+", $ztPartage);
  }

  // On recupere la liste des utilisateurs sauf l'utilisateur courant
  $DB_CX->DbQuery("SELECT util_id, CONCAT(".$FORMAT_NOM_UTIL.") AS nomUtil FROM ${PREFIX_TABLE}utilisateur WHERE util_id!=".$USER_SUBSTITUE." ORDER BY nomUtil");
  while ($rsUtil = $DB_CX->DbNextRow()) {
    $selected = ($rsProfil['util_partage_planning'] == "1") ? " selected" : "";
    for ($i=0; $i<count($tabPartage) && empty($selected); $i++) {
      if ($tabPartage[$i] == $rsUtil['util_id'])
        $selected = " selected";
    }
    echo "            <OPTION value=\"".$rsUtil['util_id']."\"".$selected.">".$rsUtil['nomUtil']."</OPTION>\n";
  }
?>
          </SELECT></TD>
          <TD align="center" valign="middle"><TABLE border=0 cellpadding=0 cellspacing=0>
            <TR>
              <TD>&nbsp;<INPUT type="button" class="PickList" name="btSelect" id="btSelect" value="&#155;" title="<?php echo trad("PROFIL_AJOUT_SELECTION"); ?>" tabindex="<?php echo $tabIndex++; ?>" onClick="javascript: vPartage=2; selectUtil(document.frmProfil.zlUtilisateur, document.frmProfil.zlPartage); ModifAffect();">&nbsp;</TD>
            </TR>
            <TR>
              <TD height="6"></TD>
            </TR>
            <TR>
              <TD nowrap>&nbsp;<INPUT type="button" class="PickList" name="btDeselect" id="btDeselect" value="&#139;" title="<?php echo trad("PROFIL_ENLEV_SELECTION"); ?>" tabindex="<?php echo $tabIndex++; ?>" onClick="javascript: vPartage=2; selectUtil(document.frmProfil.zlPartage, document.frmProfil.zlUtilisateur); ModifAffect();">&nbsp;</TD>
            </TR>
          </TABLE></TD>
          <TD><SELECT name="zlPartage" id="zlPartage" size="6" multiple tabindex="<?php echo $tabIndex++; ?>" style="width:200px;"></SELECT></TD>
        </TR>
<?php
  if ($NoGroupe) {
?>
        <TR>
          <TH><?php echo trad("PROFIL_GROUPE_POSSIBLE"); ?></TH>
          <TH>&nbsp;</TH>
          <TH><?php echo trad("PROFIL_GROUPE_SELECTIONNEE"); ?></TH>
        </TR>
        <TR>
          <TD><SELECT name="zlGroupe" id="zlGroupe" size="3" multiple tabindex="<?php echo $tabIndex++; ?>" style="width:200px;">
<?php
  $tabPrtGroupe = array();
  // On recupere la liste des personnes concernees par le partage sauf l'utilisateur courant
  if ($ztAction=="UPDATE") { // On n'est pas dans le cas d'une creation de compte
    $DB_CX->DbQuery("SELECT DISTINCT ppl_gr FROM ${PREFIX_TABLE}planning_partage WHERE ppl_util_id=".$USER_SUBSTITUE." AND ppl_consultant_id!=".$USER_SUBSTITUE." AND ppl_gr!='0'");
    while ($enr = $DB_CX->DbNextRow())
      $tabPrtGroupe[] = $enr['ppl_gr'];
  } else {
    $tabPrtGroupe = explode("+", $ztPrtGroupe);
  }

  // On recupere la liste des utilisateurs sauf l'utilisateur courant
  $DB_CX->DbQuery("SELECT gr_util_id, gr_util_nom, gr_util_liste FROM ${PREFIX_TABLE}groupe_util");
  while ($rsUtil = $DB_CX->DbNextRow()) {
    $selected = "";
    for ($i=0; $i<count($tabPrtGroupe) && empty($selected); $i++) {
      if ($tabPrtGroupe[$i] == $rsUtil[0])
        $selected = " selected";
    }
    echo "            <OPTION value=\"".$rsUtil[0]."|".$rsUtil[2]."\"".$selected.">".$rsUtil[1]."</OPTION>\n";
  }
?>
          </SELECT></TD>
          <TD align="center" valign="middle"><TABLE border=0 cellpadding=0 cellspacing=0>
            <TR>

            <TD>&nbsp;<INPUT type="button" class="PickList" name="btSelectG" id="btSelectG" value="&#155;" title="<?php echo trad("PROFIL_AJOUT_SELECTION"); ?>" tabindex="<?php echo $tabIndex++; ?>" onClick="vPartage=2; javascript:selectUtil(document.frmProfil.zlGroupe, document.frmProfil.zlPrtGroupe); ModifAffect();">&nbsp;</TD>
            </TR>
            <TR>
              <TD height="6"></TD>
            </TR>
            <TR>
            <TD nowrap>&nbsp;<INPUT type="button" class="PickList" name="btDeselectG" id="btDeselectG" value="&#139;" title="<?php echo trad("PROFIL_ENLEV_SELECTION"); ?>" tabindex="<?php echo $tabIndex++; ?>" onClick="vPartage=2; javascript:selectUtil(document.frmProfil.zlPrtGroupe, document.frmProfil.zlGroupe); ModifAffect();">&nbsp;</TD>
            </TR>
          </TABLE></TD>
          <TD><SELECT name="zlPrtGroupe" id="zlPrtGroupe" size="3" multiple tabindex="<?php echo $tabIndex++; ?>" style="width:200px;"></SELECT></TD>
        </TR>
<?php
  } else {
    echo "<INPUT type=\"hidden\" name=\"zlPrtGroupe\" value=\"\"></TD>";
  }
?>

      </TABLE><INPUT type="hidden" name="ztPartage" value="">
      <INPUT type="hidden" name="ztPrtGroupe" value=""></TD>
    </TR>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>">
      <TD class="tabIntitule" nowrap><?php echo trad("PROFIL_LIB_MODIFICATION"); ?></TD>
<?php
  if (($droit_PROFILS >= _DROIT_PROFIL_AUTRE_PARAM_PARTAGE) or (($droit_PROFILS >= _DROIT_PROFIL_PARAM_PARTAGE) and ($idUser==$USER_SUBSTITUE))) {
?>
      <SCRIPT language="JavaScript">
      <!--
        function selectAffect(_list) {
          switch (_list) {
            case '1' : vAffecte=1; selectTous(document.frmProfil.zlUtilisateur2, document.frmProfil.zlAffecte); break;
            case '2' : vAffecte=2;
            selectTous(document.frmProfil.zlAffecte, document.frmProfil.zlUtilisateur2);
            copieTous(document.frmProfil.zlPartage, document.frmProfil.zlUtilisateur2, document.frmProfil.zlAffecte);
            selectTous(document.frmProfil.zlAffGroupe, document.frmProfil.zlGroupe2);
            copieTous(document.frmProfil.zlPrtGroupe, document.frmProfil.zlGroupe2, document.frmProfil.zlAffGroupe);break;
            case '3' : vAffecte=3; break;
            default : vAffecte=0; selectTous(document.frmProfil.zlAffecte, document.frmProfil.zlUtilisateur2); selectTous(document.frmProfil.zlAffGroupe, document.frmProfil.zlGroupe2);
          }
        }
        function ModifAffect() {
          if (vAffecte==2) {
            selectTous(document.frmProfil.zlAffecte, document.frmProfil.zlUtilisateur2);
            copieTous(document.frmProfil.zlPartage, document.frmProfil.zlUtilisateur2, document.frmProfil.zlAffecte);
            selectTous(document.frmProfil.zlAffGroupe, document.frmProfil.zlGroupe2);
            copieTous(document.frmProfil.zlPrtGroupe, document.frmProfil.zlGroupe2, document.frmProfil.zlAffGroupe);
          }
        }
      //-->
      </SCRIPT>
      <TD style="padding-bottom:1px;" class="tabInput"><?php echo trad("PROFIL_AUTORISE_AFFECT"); ?><BR><SELECT name="zlAffectation" size="1" tabindex="<?php echo $tabIndex++; ?>" onchange="selectAffect(this.value);">
          <OPTION value="0"<?php if ($rsProfil['util_autorise_affect']==0) echo " selected"; ?>><?php echo trad("PROFIL_AUCUNE"); ?></OPTION>
          <OPTION value="2"<?php if ($rsProfil['util_autorise_affect']==2) echo " selected"; ?>><?php echo trad("PROFIL_IDEM_CONSULT"); ?></OPTION>
          <OPTION value="3"<?php if ($rsProfil['util_autorise_affect']==3) echo " selected"; ?>><?php echo trad("PROFIL_AU_CHOIX"); ?></OPTION>
          <OPTION value="1"<?php if ($rsProfil['util_autorise_affect']==1) echo " selected"; ?>><?php echo trad("PROFIL_TOUT_MONDE"); ?></OPTION>
        </SELECT><BR>
        <TABLE cellspacing="0" cellpadding="0" width="100%" border="0" align="center">
        <TR>
          <TH><?php echo trad("PROFIL_PERS_POSSIBLE"); ?></TH>
          <TH>&nbsp;</TH>
          <TH><?php echo trad("PROFIL_PERS_SELECTIONNEE"); ?></TH>
        </TR>
        <TR>
          <TD><SELECT name="zlUtilisateur2" id="zlUtilisateur2" size="6" multiple tabindex="<?php echo $tabIndex++; ?>" style="width:200px;">
<?php
  $tabPartage = array();
  // On recupere la liste des personnes concernees par l'affectation sauf l'utilisateur courant
  if ($ztAction=="UPDATE") { // On n'est pas dans le cas d'une creation de compte
    $DB_CX->DbQuery("SELECT paf_consultant_id FROM ${PREFIX_TABLE}planning_affecte WHERE paf_util_id=".$USER_SUBSTITUE." AND paf_consultant_id!=".$USER_SUBSTITUE." AND paf_gr='0'");
    while ($enr = $DB_CX->DbNextRow())
      $tabPartage[] = $enr['paf_consultant_id'];
  } else {
    $tabPartage = explode("+", $ztAffecte);
  }

  // On recupere la liste des utilisateurs sauf l'utilisateur courant
  $DB_CX->DbQuery("SELECT util_id, CONCAT(".$FORMAT_NOM_UTIL.") AS nomUtil FROM ${PREFIX_TABLE}utilisateur WHERE util_id!=".$USER_SUBSTITUE." ORDER BY nomUtil");
  while ($rsUtil = $DB_CX->DbNextRow()) {
    $selected = ($rsProfil['util_autorise_affect'] == "1") ? " selected" : "";
    for ($i=0; $i<count($tabPartage) && empty($selected); $i++) {
      if ($tabPartage[$i] == $rsUtil['util_id'])
        $selected = " selected";
    }
    echo "            <OPTION value=\"".$rsUtil['util_id']."\"".$selected.">".$rsUtil['nomUtil']."</OPTION>\n";
  }
?>
          </SELECT></TD>
          <TD align="center" valign="middle"><TABLE border=0 cellpadding=0 cellspacing=0>
            <TR>
              <TD>&nbsp;<INPUT type="button" class="PickList" name="btSelect" id="btSelect" value="&#155;" title="<?php echo trad("PROFIL_AJOUT_SELECTION"); ?>" tabindex="<?php echo $tabIndex++; ?>" onClick="javascript: vAffecte=3; selectUtil(document.frmProfil.zlUtilisateur2, document.frmProfil.zlAffecte);">&nbsp;</TD>
            </TR>
            <TR>
              <TD height="6"></TD>
            </TR>
            <TR>
              <TD nowrap>&nbsp;<INPUT type="button" class="PickList" name="btDeselect" id="btDeselect" value="&#139;" title="<?php echo trad("PROFIL_ENLEV_SELECTION"); ?>" tabindex="<?php echo $tabIndex++; ?>" onClick="javascript: vAffecte=3; selectUtil(document.frmProfil.zlAffecte, document.frmProfil.zlUtilisateur2);">&nbsp;</TD>
            </TR>
          </TABLE></TD>
          <TD><SELECT name="zlAffecte" id="zlAffecte" size="6" multiple tabindex="<?php echo $tabIndex++; ?>" style="width:200px;"></SELECT></TD>
        </TR>

<?php
  if ($NoGroupe) {
?>
        <TR>
          <TH><?php echo trad("PROFIL_GROUPE_POSSIBLE"); ?></TH>
          <TH>&nbsp;</TH>
          <TH><?php echo trad("PROFIL_GROUPE_SELECTIONNEE"); ?></TH>
        </TR>
        <TR>
          <TD><SELECT name="zlGroupe2" id="zlGroupe2" size="3" multiple tabindex="<?php echo $tabIndex++; ?>" style="width:200px;">
<?php
  $tabAffGroupe = array();
  // On recupere la liste des personnes concernees par le partage sauf l'utilisateur courant
  if ($ztAction=="UPDATE") { // On n'est pas dans le cas d'une creation de compte
    $DB_CX->DbQuery("SELECT DISTINCT paf_gr FROM ${PREFIX_TABLE}planning_affecte WHERE paf_util_id=".$USER_SUBSTITUE." AND paf_consultant_id!=".$USER_SUBSTITUE." AND paf_gr!='0'");
    while ($enr = $DB_CX->DbNextRow())
      $tabAffGroupe[] = $enr['paf_gr'];
  } else {
    $tabAffGroupe = explode("+", $ztPrtGroupe);
  }

  // On recupere la liste des utilisateurs sauf l'utilisateur courant
  $DB_CX->DbQuery("SELECT gr_util_id, gr_util_nom, gr_util_liste FROM ${PREFIX_TABLE}groupe_util");
  while ($rsUtil = $DB_CX->DbNextRow()) {
    $selected = "";
    for ($i=0; $i<count($tabAffGroupe) && empty($selected); $i++) {
      if ($tabAffGroupe[$i] == $rsUtil[0])
        $selected = " selected";
    }
    echo "            <OPTION value=\"".$rsUtil[0]."|".$rsUtil[2]."\"".$selected.">".$rsUtil[1]."</OPTION>\n";
  }
?>
          </SELECT></TD>
          <TD align="center" valign="middle"><TABLE border=0 cellpadding=0 cellspacing=0>
            <TR>

            <TD>&nbsp;<INPUT type="button" class="PickList" name="btSelectG" id="btSelectG" value="&#155;" title="<?php echo trad("PROFIL_AJOUT_SELECTION"); ?>" tabindex="<?php echo $tabIndex++; ?>" onClick="vAffecte=3; javascript:selectUtil(document.frmProfil.zlGroupe2, document.frmProfil.zlAffGroupe);">&nbsp;</TD>
            </TR>
            <TR>
              <TD height="6"></TD>
            </TR>
            <TR>
            <TD nowrap>&nbsp;<INPUT type="button" class="PickList" name="btDeselectG" id="btDeselectG" value="&#139;" title="<?php echo trad("PROFIL_ENLEV_SELECTION"); ?>" tabindex="<?php echo $tabIndex++; ?>" onClick="vAffecte=3; javascript:selectUtil(document.frmProfil.zlAffGroupe, document.frmProfil.zlGroupe2);">&nbsp;</TD>
            </TR>
          </TABLE></TD>
          <TD><SELECT name="zlAffGroupe" id="zlAffGroupe" size="3" multiple tabindex="<?php echo $tabIndex++; ?>" style="width:200px;"></SELECT></TD>
        </TR>
<?php
  } else {
    echo "<INPUT type=\"hidden\" name=\"zlAffGroupe\" value=\"\"></TD>";
  }

?>

      </TABLE><INPUT type="hidden" name="ztAffecte" value="">
      <INPUT type="hidden" name="ztAffGroupe" value="">
<?php
  } else {
?>
      <TD style="padding-bottom:1px;" class="tabInput">
<?php
  }
?>
      <LABEL for="email"><INPUT type="checkbox" name="ckAlertEmail" value="O" class="Case" id="email" tabindex="<?php echo $tabIndex++; ?>"<?php if ($rsProfil['util_alert_affect']=="O") echo " checked"; ?>>&nbsp;<?php echo trad("PROFIL_INFO_MAIL"); ?></LABEL></TD>
    </TR>
<?php
    if ($ztAction=="UPDATE") { // On n'est pas dans le cas d'une creation de compte
?>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>">
      <TD class="tabIntitule" nowrap><B><?php echo trad("PROFIL_LIB_RECAP"); ?></B></TD>
      <TD style="padding-bottom:2px;" class="tabInput"><TABLE cellspacing="0" cellpadding="0" width="100%" border="0" align="center">
        <TR>
          <TH width="50%"><?php echo trad("PROFIL_RECAP_CONSULT"); ?></TH>
          <TH>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TH>
          <TH width="50%"><?php echo trad("PROFIL_RECAP_MODIF"); ?></TH>
        </TR>
        <TR>
          <TD><SELECT size="6" style="width:200px; border:<?php echo $FormulaireBordureInput; ?>; background-color:<?php echo $FormulaireFondInput; ?>;">
<?php
    // Liste des utilisateurs dont on peut consulter le planning
    $DB_CX->DbQuery("SELECT DISTINCT util_id, CONCAT(".$FORMAT_NOM_UTIL.") AS nomUtil FROM ${PREFIX_TABLE}utilisateur LEFT JOIN ${PREFIX_TABLE}planning_partage ON ppl_util_id=util_id WHERE util_id!=".$USER_SUBSTITUE." AND (util_partage_planning='1' OR (util_partage_planning='2' AND ppl_consultant_id=".$USER_SUBSTITUE.")) ORDER BY nomUtil");
    while ($enr=$DB_CX->DbNextRow()) {
      echo "            <OPTION value=\"".$enr['util_id']."\" disabled>".htmlspecialchars($enr['nomUtil'])."</OPTION>\n";
    }
?>
          </SELECT></TD>
          <TD>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</TD>
          <TD align="right"><SELECT size="6" style="width:200px; border:<?php echo $FormulaireBordureInput; ?>; background-color:<?php echo $FormulaireFondInput; ?>;">
<?php
    // Liste des utilisateurs a qui l'on peut affecter une note
    $DB_CX->DbQuery("SELECT DISTINCT util_id, CONCAT(".$FORMAT_NOM_UTIL.") AS nomUtil FROM ${PREFIX_TABLE}utilisateur LEFT JOIN ${PREFIX_TABLE}planning_affecte ON paf_util_id=util_id WHERE util_id!=".$USER_SUBSTITUE." AND (util_autorise_affect ='1' OR (util_autorise_affect IN ('2','3') AND paf_consultant_id=".$USER_SUBSTITUE.")) ORDER BY nomUtil");
    while ($enr=$DB_CX->DbNextRow()) {
      echo "            <OPTION value=\"".$enr['util_id']."\" disabled>".htmlspecialchars($enr['nomUtil'])."</OPTION>\n";
    }
?>
          </SELECT></TD>
        </TR>
      </TABLE></TD>
    </TR>
<?php
    } // FIN On n'est pas dans le cas d'une creation de compte
  }
?>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_EXPORT"); ?><BR><DIV style="text-align:center;"><INPUT type="button" class="Bouton" value="<?php echo trad("PROFIL_BT_AUTO"); ?>" name="btAutoCode" tabindex="<?php echo $tabIndex++; ?>" onclick="javascript: autoCodeURL();"></DIV></TD>
<?php
  if ($rsProfil['util_url_export']=="") {
    // Si le code n'a pas ete genere
    $idRand = md5(uniqid(rand()));
    if ($USER_SUBSTITUE) {
      // Si on n'est pas dans la creation d'un nouveau compte => on met a jour dans la bdd
      $DB_CX->DbQuery("UPDATE ${PREFIX_TABLE}utilisateur SET util_url_export='".$idRand."' WHERE util_id=".$USER_SUBSTITUE);
    }
    $rsProfil['util_url_export']=$idRand;
  }
  // URL generee a partir des variables d'environnement de PHP
  $urlExport = substr(getenv('HTTP_REFERER'),0,(strrpos(getenv('HTTP_REFERER'), '/') + 1))."agenda_note_export.php?zlTypeFichier=icsURL&id=".$rsProfil['util_url_export'];
?>
      <TD class="tabInput" style="text-align:center;"><TEXTAREA style="width:436px;" cols="80" rows="3" readonly wrap="soft" name="ztURLExport"><?php echo $urlExport; ?></TEXTAREA><BR>
      <INPUT type="hidden" class="Texte" name="ztCodeURL" maxlength="32" value="<?php echo $rsProfil['util_url_export']; ?>" readonly></TD>
    </TR>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_SYNCHRO"); ?><BR><DIV style="text-align:center;"><INPUT type="button" class="Bouton" value="<?php echo trad("PROFIL_BT_AUTO"); ?>" name="btAutoCode" tabindex="<?php echo $tabIndex++; ?>" onclick="javascript: autoCodeURL();"></DIV></TD>
<?php
  // URL generee a partir des variables d'environnement de PHP
  $urlExport = substr(getenv('HTTP_REFERER'),0,(strrpos(getenv('HTTP_REFERER'), '/') + 1))."agenda_synchro.php?id=".$rsProfil['util_url_export'];
?>
      <TD class="tabInput" style="text-align:center;"><TEXTAREA style="width:436px;" cols="80" rows="3" readonly wrap="soft" name="ztURLSynchro"><?php echo $urlExport; ?></TEXTAREA><BR>
    </TR>
    </TABLE>
  </DIV>
<?php
  if ($droit_PROFILS >= _DROIT_PROFIL_COMPLET or $idAdmin!=0) {
    $iColor = 0;
?>
  <DIV id="divAdmin" style="display: none">
    <TABLE cellspacing="0" cellpadding="0" width="<?php echo ($idUser) ? "585" : "565"; ?>" border="0">
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad('PROFIL_LIB_DROITS_AFFICHAGE'); ?></TD>
      <TD width="436" class="tabInput"><LABEL for="IeMdp1"><INPUT type="checkbox" name="droit_Aff_Login" value="1" class="Case" id="IeMdp1" tabindex="<?php echo $tabIndex++; ?>"<?php if ($dr_Aff_Login=="1") echo " checked"; ?>>&nbsp;<?php echo trad('PROFIL_ADMIN_LOGIN'); ?></LABEL>&nbsp;&nbsp;
        <LABEL for="IeMdp2"><INPUT type="checkbox" name="droit_Aff_MDP" value="1" class="Case" id="IeMdp2" tabindex="<?php echo $tabIndex++; ?>"<?php if ($dr_Aff_MDP=="1") echo " checked"; ?>>&nbsp;<?php echo trad('PROFIL_ADMIN_PWD'); ?></LABEL>&nbsp;&nbsp;
        <LABEL for="IeMdp3"><INPUT type="checkbox" name="droit_Aff_THEME" value="1" class="Case" id="IeMdp3" tabindex="<?php echo $tabIndex++; ?>"<?php if ($dr_Aff_THEME=="1") echo " checked"; ?>>&nbsp;<?php echo trad('PROFIL_ADMIN_THEME'); ?></LABEL></TD>
    </TR>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_DROITS_PROFILS"); ?></TD>
      <TD class="tabInput"><SELECT name="zlAMProfils" size="1" style="width: 350px;" tabindex="<?php echo $tabIndex++; ?>">
        <OPTION value="<?php echo _DROIT_PROFIL_RIEN; ?>"<?php if ($dr_PROFILS==_DROIT_PROFIL_RIEN) echo " selected"; ?>><?php echo trad('PROFIL_ADMIN_PROFILS_1'); ?></OPTION>
        <OPTION value="<?php echo _DROIT_PROFIL_PARAM_BASE; ?>"<?php if ($dr_PROFILS==_DROIT_PROFIL_PARAM_BASE) echo " selected"; ?>><?php echo trad('PROFIL_ADMIN_PROFILS_2'); ?></OPTION>
        <OPTION value="<?php echo _DROIT_PROFIL_PARAM_PARTAGE; ?>"<?php if ($dr_PROFILS==_DROIT_PROFIL_PARAM_PARTAGE) echo " selected"; ?>><?php echo trad('PROFIL_ADMIN_PROFILS_3'); ?></OPTION>
        <OPTION value="<?php echo _DROIT_PROFIL_AUTRE_PARAM_BASE; ?>"<?php if ($dr_PROFILS==_DROIT_PROFIL_AUTRE_PARAM_BASE) echo " selected"; ?>><?php echo trad('PROFIL_ADMIN_PROFILS_4'); ?></OPTION>
        <OPTION value="<?php echo _DROIT_PROFIL_AUTRE_PARAM_PARTAGE; ?>"<?php if ($dr_PROFILS>=_DROIT_PROFIL_AUTRE_PARAM_PARTAGE) echo " selected"; ?>><?php echo trad('PROFIL_ADMIN_PROFILS_5'); ?></OPTION>
      </SELECT></TD>
    </TR>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_DROITS_AGENDAS"); ?></TD>
      <TD class="tabInput"><SELECT name="zlAMAgendas" size="1" style="width: 350px;" tabindex="<?php echo $tabIndex++; ?>">
        <OPTION value="<?php echo _DROIT_AGENDA_SEUL; ?>"<?php if ($dr_AGENDAS==_DROIT_AGENDA_SEUL) echo " selected"; ?>><?php echo trad('PROFIL_ADMIN_AGENDAS_1'); ?></OPTION>
        <OPTION value="<?php echo _DROIT_AGENDA_PARTAGE; ?>"<?php if ($dr_AGENDAS==_DROIT_AGENDA_PARTAGE) echo " selected"; ?>><?php echo trad('PROFIL_ADMIN_AGENDAS_2'); ?></OPTION>
        <OPTION value="<?php echo _DROIT_AGENDA_TOUS; ?>"<?php if ($dr_AGENDAS==_DROIT_AGENDA_TOUS) echo " selected"; ?>><?php echo trad('PROFIL_ADMIN_AGENDAS_3'); ?></OPTION>
      </SELECT></TD>
    </TR>
    <TR bgcolor="<?php echo $bgColor[++$iColor%2]; ?>" height="21">
      <TD class="tabIntitule" height="20"><?php echo trad("PROFIL_LIB_DROITS_NOTES"); ?></TD>
      <TD class="tabInput"><SELECT name="zlAMNotes" size="1" style="width: 350px;" tabindex="<?php echo $tabIndex++; ?>">
        <OPTION value="<?php echo _DROIT_NOTE_CONSULT_SEUL; ?>"<?php if ($dr_NOTES==_DROIT_NOTE_CONSULT_SEUL) echo " selected"; ?>><?php echo trad('PROFIL_ADMIN_NOTES_1'); ?></OPTION>
        <OPTION value="<?php echo _DROIT_NOTE_CONSULT_RECHERCHE; ?>"<?php if ($dr_NOTES==_DROIT_NOTE_CONSULT_RECHERCHE) echo " selected"; ?>><?php echo trad('PROFIL_ADMIN_NOTES_2'); ?></OPTION>
        <OPTION value="<?php echo _DROIT_NOTE_STANDARD_SANS_APPR; ?>"<?php if ($dr_NOTES==_DROIT_NOTE_STANDARD_SANS_APPR) echo " selected"; ?>><?php echo trad('PROFIL_ADMIN_NOTES_3'); ?></OPTION>
        <OPTION value="<?php echo _DROIT_NOTE_STANDARD; ?>"<?php if ($dr_NOTES==_DROIT_NOTE_STANDARD) echo " selected"; ?>><?php echo trad('PROFIL_ADMIN_NOTES_4'); ?></OPTION>
        <OPTION value="<?php echo _DROIT_NOTE_MODIF_STATUT; ?>"<?php if ($dr_NOTES==_DROIT_NOTE_MODIF_STATUT) echo " selected"; ?>><?php echo trad('PROFIL_ADMIN_NOTES_5'); ?></OPTION>
        <OPTION value="<?php echo _DROIT_NOTE_MODIF_CREATION; ?>"<?php if ($dr_NOTES==_DROIT_NOTE_MODIF_CREATION) echo " selected"; ?>><?php echo trad('PROFIL_ADMIN_NOTES_6'); ?></OPTION>
        <OPTION value="<?php echo _DROIT_NOTE_COMPLET; ?>"<?php if ($dr_NOTES==_DROIT_NOTE_COMPLET) echo " selected"; ?>><?php echo trad('PROFIL_ADMIN_NOTES_7'); ?></OPTION>
      </SELECT></TD>
    </TR>
    </TABLE>
    <INPUT type="hidden" name="admin_PROFILS" value="<?php echo $admin_PROFILS; ?>">
  </DIV>
<?php
  }
?>
  <BR><INPUT type="button" name="btEnregistre" value="<?php echo $labelBouton; ?>" onClick="javascript: return saisieOK(document.frmProfil);" tabindex="<?php echo $tabIndex++; ?>" class="bouton">&nbsp;&nbsp;&nbsp;<INPUT type="button" name="btAnnule" value="<?php echo trad("PROFIL_BT_ANNULER"); ?>" onclick="javascript: <?php echo $btnAnnul ?>;" tabindex="<?php echo $tabIndex++; ?>" class="bouton">
  </FORM>
<?php
  // Si on provient d'une substitution, on active l'onglet precedement selectionne
  if (!empty($selOnglet)) {
?>
  <SCRIPT language="JavaScript" type="text/javascript">
  <!--
    affOnglet('<?php echo $selOnglet; ?>');
  //-->
  </SCRIPT>
<?php
  }
?>
<!-- FIN MODULE GESTION DU PROFIL -->
