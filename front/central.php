<?php
/*
 * @version $Id: central.php 14684 2011-06-11 06:32:40Z remi $
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
include (GLPI_ROOT."/inc/includes.php");

checkCentralAccess();

// Change profile system
if (isset ($_POST['newprofile'])) {
   if (isset ($_SESSION["glpiprofiles"][$_POST['newprofile']])) {
      changeProfile($_POST['newprofile']);
      if ($_SESSION["glpiactiveprofile"]["interface"] == "helpdesk") {
         glpi_header($CFG_GLPI['root_doc']."/front/helpdesk.public.php");
      }
      glpi_header($_SERVER['PHP_SELF']);
   }
   glpi_header(preg_replace("/entities_id.*/","",$_SERVER['HTTP_REFERER']));
}

// Manage entity change
if (isset($_GET["active_entity"])) {
   if (!isset($_GET["is_recursive"])) {
      $_GET["is_recursive"] = 0;
   }
   if (changeActiveEntities($_GET["active_entity"],$_GET["is_recursive"])) {
      if (($_GET["active_entity"] == $_SESSION["glpiactive_entity"])
          && isset($_SERVER['HTTP_REFERER'])) {
         glpi_header(preg_replace("/entities_id.*/","",$_SERVER['HTTP_REFERER']));
      }
   }
}

commonHeader($LANG['common'][56],$_SERVER['PHP_SELF']);

// Redirect management
if (isset($_GET["redirect"])) {
   manageRedirect($_GET["redirect"]);
}

$central = new Central();
$central->show();

commonFooter();

?>
