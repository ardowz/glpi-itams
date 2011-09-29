<?php
/*
 * @version $Id: authldapreplicate.class.php 14684 2011-06-11 06:32:40Z remi $
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

/**
 *  Class used to manage LDAP replicate config
**/
class AuthLdapReplicate extends CommonDBTM {

   function prepareInputForAdd($input) {
      if (isset($input["port"]) && intval($input["port"]) == 0) {
         $input["port"] = 389;
      }
      return $input;
   }

   function prepareInputForUpdate($input) {
      if (isset($input["port"]) && intval($input["port"]) == 0) {
         $input["port"] = 389;
      }
      return $input;
   }

   /**
    * Form to add a replicate to a ldap server
    *
    * @param $target : target page for add new replicate
    * @param $master_id : master ldap server ID
   **/
   static function addNewReplicateForm($target, $master_id) {
      global $LANG;

      echo "<form action='$target' method='post' name='add_replicate_form' id='add_replicate_form'>";
      echo "<div class='center'>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr><th colspan='4'>" .$LANG['ldap'][20] . "</th></tr>";
      echo "<tr class='tab_bg_1'><td class='center'>".$LANG['common'][16]."</td>";
      echo "<td class='center'>".$LANG['common'][52]."</td>";
      echo "<td class='center'>".$LANG['setup'][175]."</td><td></td></tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td class='center'><input type='text' name='name'></td>";
      echo "<td class='center'><input type='text' name='host'></td>";
      echo "<td class='center'><input type='text' name='port'></td>";
      echo "<td class='center'><input type='hidden' name='next' value=\"extauth_ldap\">";
      echo "<input type='hidden' name='authldaps_id' value='$master_id'>";
      echo "<input type='submit' name='add_replicate' value=\"" .
            $LANG['buttons'][2] . "\" class='submit'></td>";
      echo "</tr></table></div></form>";
   }

}


?>