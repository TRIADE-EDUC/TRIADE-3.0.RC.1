<?php
// +-------------------------------------------------+
// © 2002-2004 PMB Services / www.sigb.net pmb@sigb.net et contributeurs (voir www.sigb.net)
// +-------------------------------------------------+
// $Id: ana_explnum_update.inc.php,v 1.16 2017-08-10 09:19:18 dgoron Exp $

if (stristr($_SERVER['REQUEST_URI'], ".inc.php")) die("no access");

require_once($class_path."/entities/entities_analysis_explnum_controller.class.php");

// mise à jour de l'entête de page
echo str_replace('!!page_title!!', $msg[4000].$msg[1003].$msg['explnum_doc_associe'], $serial_header);

$entities_analysis_explnum_controller = new entities_analysis_explnum_controller($f_explnum_id);
$entities_analysis_explnum_controller->set_bulletin_id($bul_id);
$entities_analysis_explnum_controller->set_action('explnum_update');
$entities_analysis_explnum_controller->proceed();

?>