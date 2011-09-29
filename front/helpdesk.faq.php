<?php
/*
 * @version $Id: helpdesk.faq.php 14684 2011-06-11 06:32:40Z remi $
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

// Redirect management
if (isset($_GET["redirect"])) {
   manageRedirect($_GET["redirect"]);
}

//*******************
// Affichage Module FAQ
//******************

$name = "";
checkFaqAccess();
if (getLoginUserID()) {
   helpHeader($LANG['Menu'][20],$_SERVER['PHP_SELF'],$_SESSION["glpiname"]);
} else {
   $_SESSION["glpilanguage"] = $CFG_GLPI['language'];
   // Anonymous FAQ
   simpleHeader($LANG['Menu'][20],array($LANG['Menu'][20] => $_SERVER['PHP_SELF']));
}

if (!isset($_GET["contains"])) {
   $_GET["contains"] = "";
}
if (!isset($_GET["knowbaseitemcategories_id"])) {
   $_GET["knowbaseitemcategories_id"] = 0;
}

if (isset($_GET["id"])) {
   $kb = new KnowbaseItem;
   if ($kb->getFromDB($_GET["id"])) {
      $kb->showFull(false);
   }

} else {
   KnowbaseItem::searchForm($_GET,1);
   KnowbaseItemCategory::showFirstLevel($_GET,1);
   KnowbaseItem::showList($_GET,1);
   if (!$_GET["knowbaseitemcategories_id"] && strlen($_GET["contains"]) == 0) {
      KnowbaseItem::showViewGlobal($_SERVER['PHP_SELF'],1) ;
   }
}

helpFooter();

?>
