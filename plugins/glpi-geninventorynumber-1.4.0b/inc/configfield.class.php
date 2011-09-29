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

// ----------------------------------------------------------------------
// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginGeninventorynumberConfigField extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_geninventorynumber_configfields";
	}
	
	function getFromDBbyConfigAndType($config_id,$itemtype) {
		global $DB;
		
		$query = "SELECT * FROM '".$this->getTable()."' " .
			"WHERE 'config_id' = '" . $config_id . "' 
			AND 'device_type' = '" . $itemtype . "'";
		if ($result = $DB->query($query)) {
			if ($DB->numrows($result) != 1) {
				return false;
			}
			$this->fields = $DB->fetch_assoc($result);
			if (is_array($this->fields) && count($this->fields)) {
				return true;
			} else {
				return false;
			}
		}
		return false;
	}
	
	function canCreate() {
      return haveRight("config", "w");
	}

	function canView() {
      return haveRight("config", "r");
	}
   
	function canDelete() {
	  return haveRight("config", "w");
	}
	
	static function getTypeName() {
		global $LANG;
		return $LANG['plugin_geninventorynumber']['types'][1];
	}
}
?>