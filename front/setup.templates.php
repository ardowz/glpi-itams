<?php
/*
 * @version $Id: setup.templates.php 14684 2011-06-11 06:32:40Z remi $
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

// Based on:
// IRMA, Information Resource-Management and Administration
// Christian Bauer
// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '..');
include (GLPI_ROOT . "/inc/includes.php");

if (isset($_GET["itemtype"])) {

   $link=getItemTypeFormURL($_GET["itemtype"]);
   
   $item = str_replace(".form.php","",$link);
   $item = str_replace("front/","",$item);
   //removed templated selection
   $t = "?id=-1&withtemplate=2";
   //echo $link . $t;
   $test = $link.$t;
   //header($test);
   header('Location: '.$test);
   commonHeader($LANG['common'][12],$_SERVER['PHP_SELF'],"inventory",$item);
   
  
   listTemplates($_GET["itemtype"],$link,$_GET["add"]);
   
   
   //commonFooter();
}

?>
