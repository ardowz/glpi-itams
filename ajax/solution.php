<?php
/*
 * @version $Id: solution.php 14684 2011-06-11 06:32:40Z remi $
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

$AJAX_INCLUDE = 1;

define('GLPI_ROOT','..');
include (GLPI_ROOT."/inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
header_nocache();

checkLoginUser();

initEditorSystem("solution");

if (isset($_POST['value']) && $_POST['value'] > 0) {
   $template = new TicketSolutionTemplate();

   if ($template->getFromDB($_POST['value'])) {
      echo "<textarea id='solution' name='solution' rows='12' cols='80'>";
      echo $template->getField('content');
      echo "</textarea>\n";
      echo "<script type='text/javascript'>document.getElementById('".$_POST["type_id"]."').
             value = ".$template->getField('ticketsolutiontypes_id')."</script>";
   }

} else {
      echo "<textarea id='solution' name='solution' rows='12' cols='80'></textarea>";
}

?>