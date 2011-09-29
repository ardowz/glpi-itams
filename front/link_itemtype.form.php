<?php
/*
 * @version $Id: link_itemtype.form.php 14684 2011-06-11 06:32:40Z remi $
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
// Original Author of file: Julien Dombre
// Purpose of file:
// ----------------------------------------------------------------------


define('GLPI_ROOT', '..');
include (GLPI_ROOT . "/inc/includes.php");

if (empty($_GET["id"])) {
   $_GET["id"] = "";
}

$link = new Link;
$link_itemtype = new Link_ItemType;

if (isset($_POST["add"])) {
   $link->check($_GET["id"],'w');

   if ($link_itemtype->add($_POST)) {
    Event::log($_POST["links_id"], "links", 4, "setup", $_SESSION["glpiname"]." ".$LANG['log'][32]);
   }
   glpi_header($CFG_GLPI["root_doc"]."/front/link.form.php?id=".$_POST["links_id"]);
}
else if (isset($_GET["delete"])) {
   $link->check($_GET["links_id"],'w');

   $link_itemtype->delete($_GET);
   Event::log($_GET["links_id"], "links", 4, "setup", $_SESSION["glpiname"]." ".$LANG['log'][33]);
   glpi_header($_SERVER['HTTP_REFERER']);
}
?>
