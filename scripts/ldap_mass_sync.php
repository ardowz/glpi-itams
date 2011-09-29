<?php
/*
 * @version $Id: ldap_mass_sync.php 14684 2011-06-11 06:32:40Z remi $
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

if ($argv) {
   for ($i=1 ; $i<count($argv) ; $i++) {
      //To be able to use = in search filters, enter \= instead in command line
      //Replace the \= by ° not to match the split function
      $arg   = str_replace('\=','°',$argv[$i]);
      $it    = explode("=",$arg);
      $it[0] = preg_replace('/^--/','',$it[0]);

      //Replace the ° by = the find the good filter
      $it           = str_replace('°','=',$it);
      $_GET[$it[0]] = $it[1];
   }
}

if ((isset($argv) && in_array('help',$argv))
    || isset($_GET['help'])) {
   echo "Usage : php -q -f ldap_mass_sync.php [action=<option>]  [ldapservers_id=ID]\n";
   echo "Options values :\n";
   echo "0 : import users only\n";
   echo "1 : synchronize existing users only\n";
   echo "2 : import & synchronize users\n";
   echo "before-days : restrict user import or synchronization to the last x days\n";
   echo "after-days : restrict user import or synchronization until the last x days\n";
   echo "ldap_filter : ldap filter to use for the search. Value must be surrounded by \"\"\n";
   exit (0);
}

define('GLPI_ROOT', '..');
include (GLPI_ROOT . "/inc/includes.php");

// Default action : synchro
// - possible option :
//  - 0 : import new users
//  - 1 : synchronize users
//  - 2 : force synchronization of all the users (even if ldap timestamp wasn't modified)
$options['action']         = AuthLDAP::ACTION_SYNCHRONIZE;
$options['ldapservers_id'] = NOT_AVAILABLE;
$options['ldap_filter']    = '';
$options['before-days']    = 0;
$options['after-days']     = 0;
$options['script']         = 1;

foreach ($_GET as $key => $value) {
   $options[$key] = $value;
}

if ($options['before-days'] && $options['after-days']) {
   echo "You cannot use options before-days and after-days at the same time.";
   exit(1);
}

if ($options['before-days']) {
   $options['days']     = $options['before-days'];
   $options['operator'] = '>';
   unset($options['before-days']);
}
if ($options['after-days']) {
   $options['days']     = $options['after-days'];
   $options['operator'] = '<';
   unset($options['after-days']);
}

if (!canUseLdap() || !countElementsInTable('glpi_authldaps')) {
   echo "LDAP extension is not active or no LDAP directory defined";
}

$sql = "SELECT `id`, `name`
        FROM `glpi_authldaps`";

//Get the ldap server's id by his name
if ($options['ldapservers_id'] != NOT_AVAILABLE) {
   $sql .= " WHERE `id` = '" . $options['ldapservers_id']."'";
}

$result = $DB->query($sql);

if ($DB->numrows($result) == 0 && $_GET["ldapservers_id"] != NOT_AVAILABLE) {
   echo "LDAP Server not found";
} else {
   foreach($DB->request($sql) as $data) {
      echo "Processing LDAP Server: ".$data['name'].", ID : ".$data['id']." \n";
      $options['ldapservers_id'] = $data['id'];
      import ($options);
   }
}


/**
 * Function to import or synchronise all the users from an ldap directory
 *
 * @param action the action to perform (add/sync)
 * @param datas the ldap connection's datas
**/
function import($options) {
   global $CFG_GLPI;

   $results = array(AuthLDAP::USER_IMPORTED     => 0,
                    AuthLDAP::USER_SYNCHRONIZED => 0,
                    AuthLDAP::USER_DELETED_LDAP => 0);
   //The ldap server id is passed in the script url (parameter server_id)
   $limitexceeded = false;
   $actions_to_do = array();

   switch ($options['action']) {
      case AuthLDAP::ACTION_IMPORT :
         $actions_to_do = array(AuthLDAP::ACTION_IMPORT);
        break;

      case AuthLDAP::ACTION_SYNCHRONIZE :
         $actions_to_do = array(AuthLDAP::ACTION_SYNCHRONIZE);
        break;

      case AuthLDAP::ACTION_ALL :
         $actions_to_do = array(AuthLDAP::ACTION_IMPORT,AuthLDAP::ACTION_ALL);
        break;
   }

   foreach ($actions_to_do as $action_to_do) {
      $options['mode']         = $action_to_do;
      $options['authldaps_id'] = $options['ldapservers_id'];
      $users                   = AuthLdap::getAllUsers($options, $results, $limitexceeded);

      if (is_array($users)) {
         foreach ($users as $user) {
            $result = AuthLdap::ldapImportUserByServerId(array('method' => AuthLDAP::IDENTIFIER_LOGIN,
                                                               'value'  => $user["user"]),
                                                         $action_to_do,
                                                         $options['ldapservers_id']);
            if ($result) {
               $results[$result['action']] += 1;
            }
            echo ".";
         }
      }
   }

   if ($limitexceeded) {
      echo "\nLDAP Server size limit exceeded";
      if ($CFG_GLPI['user_deleted_ldap']) {
         echo " : user deletion disabled\n";
      }
      echo "\n";
   }
   echo "\nImported : ".$results[AuthLDAP::USER_IMPORTED]."\n";
   echo "Synchronized : ".$results[AuthLDAP::USER_SYNCHRONIZED]."\n";
   echo "Deleted from LDAP : ".$results[AuthLDAP::USER_DELETED_LDAP]."\n";
   echo "\n\n";
}

?>
