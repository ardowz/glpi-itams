<?php

/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2005 by the INDEPNET Development Team.

   http://indepnet.net/   http://glpi-project.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of GLPI.

   GLPI is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with GLPI; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
   ------------------------------------------------------------------------
 */

// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}
include (GLPI_ROOT . "/inc/includes.php");

if (!isset($_GET["id"])) $_GET["id"] = "";
if (!isset($_GET["withtemplate"])) $_GET["withtemplate"] = "";

$PluginGeninventorynumberConfig = new PluginGeninventorynumberConfig;
$_GET = array_merge($_GET,$_POST);

if (isset ($_GET["update"])) {
	$PluginGeninventorynumberConfig->update($_GET);
}
if (isset ($_GET["update_fields"])) {
	foreach ($_GET["ids"] as $type => $datas) {
		$field = new PluginGeninventorynumberConfigField;
		$field->update($datas);
	}
}

//(r�)Affichage du formulaire
commonHeader($LANG["plugin_geninventorynumber"]["title"][1],'',"config","plugins");
$PluginGeninventorynumberConfig->showForm($_GET['id']);
commonFooter();

?>