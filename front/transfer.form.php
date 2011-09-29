<?php
/*
 * @version $Id: transfer.form.php 14684 2011-06-11 06:32:40Z remi $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2011 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

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
 along with GLPI; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '..');
include (GLPI_ROOT . "/inc/includes.php");

checkRight("transfer", "r");

if (empty($_GET["id"])) {
   $_GET["id"] = "";
}

$transfer = new Transfer();

if (isset($_POST["add"])) {
   $transfer->check(-1,'w',$_POST);

   $newID = $transfer->add($_POST);
   Event::log($newID, "transfers", 4, "setup",
              $_SESSION["glpiname"]." ".$LANG['log'][20]." ".$_POST["name"].".");
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["delete"])) {
   $transfer->check($_POST["id"],'w');

   $transfer->delete($_POST);
   Event::log($_POST["id"], "transfers", 4, "setup", $_SESSION["glpiname"]." ".$LANG['log'][22]);
   glpi_header($CFG_GLPI["root_doc"]."/front/transfer.php");

} else if (isset($_POST["update"])) {
   $transfer->check($_POST["id"],'w');

   $transfer->update($_POST);
   Event::log($_POST["id"], "transfers", 4, "setup", $_SESSION["glpiname"]." ".$LANG['log'][21]);
   glpi_header($_SERVER['HTTP_REFERER']);
}

commonHeader($LANG['transfer'][1], '', 'admin', 'rule', 'transfer');

$transfer->showForm($_GET["id"], array('target' => $transfer->getFormURL()));

commonFooter();

?>
