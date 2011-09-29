<?php
/*
 * @version $Id: ldap.php 14684 2011-06-11 06:32:40Z remi $
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
// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '..');
include (GLPI_ROOT . "/inc/includes.php");

checkRight("import_externalauth_users",'w');

commonHeader($LANG['setup'][3],$_SERVER['PHP_SELF'],"admin","user","ldap");

if (isset($_SESSION["ldap_sortorder"])) {
   unset($_SESSION["ldap_sortorder"]);
}

AuthLdap::manageValuesInSession(array(),true);
echo "<div class='center'><table class='tab_cadre'>";
echo "<tr><th>&nbsp;".$LANG['ldap'][0]."&nbsp;</th></tr>";

echo "<tr class='tab_bg_1'><td class='center b'><a href='ldap.import.php?mode=1&action=show'>".
      $LANG['ldap'][1]."</a></td></tr>";

echo "<tr class='tab_bg_1'><td class='center b'><a href='ldap.import.php?mode=0&action=show'>".
      $LANG['ldap'][2]."</a></td> </tr>";

echo "</table></div>";

commonFooter();

?>