<?php


/*
 * @version $Id: update_071_0712.php 14684 2011-06-11 06:32:40Z remi $
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

/// Update from 0.71 to 0.71.2
function update071to0712() {
	global $DB, $CFG_GLPI, $LANG;


	$query="UPDATE glpi_display SET num=120 WHERE num=121"	;
	$DB->query($query) or die("0.71.2 Update display index in view item ".$LANG['update'][90].$DB->error());

	$query="UPDATE glpi_rules_actions SET field='_ignore_ocs_import' WHERE action_type='ignore'"	;
	$DB->query($query) or die("0.71.2 Update ignore field for soft dict ".$LANG['update'][90].$DB->error());

} // fin 0.71 #####################################################################################
?>
