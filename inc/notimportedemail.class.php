<?php

/*
 * @version $Id: notimportedemail.class.php 14684 2011-06-11 06:32:40Z remi $
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
// Original Author of file: Olivier Andreotti
// Purpose of file:
// ----------------------------------------------------------------------
if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/// LDAP criteria class
class NotImportedEmail extends CommonDBTM {

   const MATCH_NO_RULE = 0;
   const USER_UNKNOWN  = 1;

   function canCreate() {
      return haveRight('config', 'w');
   }


   function canView() {
      return haveRight('config', 'w');
   }


   static function getTypeName() {
      global $LANG;

      return $LANG['mailgate'][10];
   }


   function getSearchOptions() {
      global $LANG;

      $tab = array();
      $tab[1]['table']         = 'glpi_notimportedemails';
      $tab[1]['field']         = 'from';
      $tab[1]['name']          = $LANG['mailing'][132].' : '.$LANG['mailing'][130];
      $tab[1]['massiveaction'] = false;

      $tab[2]['table']         = 'glpi_notimportedemails';
      $tab[2]['field']         = 'to';
      $tab[2]['name']          = $LANG['mailing'][132].' : '.$LANG['mailing'][131];
      $tab[2]['massiveaction'] = false;

      $tab[3]['table']         = 'glpi_notimportedemails';
      $tab[3]['field']         = 'subject';
      $tab[3]['name']          = $LANG['mailing'][132].' : '.$LANG['common'][57];
      $tab[3]['massiveaction'] = false;

      $tab[4]['table']         = 'glpi_mailcollectors';
      $tab[4]['field']         = 'name';
      $tab[4]['name']          = $LANG['mailgate'][0];
      $tab[4]['datatype']      = 'itemlink';
      $tab[4]['itemlink_type'] = 'MailCollector';

      $tab[5]['table']         = 'glpi_notimportedemails';
      $tab[5]['field']         = 'messageid';
      $tab[5]['name']          = $LANG['mailing'][132].' : messageid';
      $tab[5]['massiveaction'] = false;

      $tab[6]['table'] = 'glpi_users';
      $tab[6]['field'] = 'name';
      $tab[6]['name']  = $LANG['job'][4];

      $tab[16]['table']         = 'glpi_notimportedemails';
      $tab[16]['field']         = 'reason';
      $tab[16]['name']          = $LANG['mailgate'][13];
      $tab[16]['datatype']      = 'text';
      $tab[16]['massiveaction'] = false;

      $tab[19]['table']         = 'glpi_notimportedemails';
      $tab[19]['field']         = 'date';
      $tab[19]['name']          = $LANG['common'][27];
      $tab[19]['datatype']      = 'datetime';
      $tab[19]['massiveaction'] = false;

      return $tab;
   }


   static function deleteLog() {
      global $DB;

      $query = "TRUNCATE `glpi_notimportedemails`";
      $DB->query($query);
   }


   static function getReason($reason_id) {
      global $LANG;

      switch ($reason_id) {
         case self::MATCH_NO_RULE :
            return $LANG['mailgate'][12];

         case self::USER_UNKNOWN :
            return $LANG['login'][14];

         default :
            return '';
      }
   }

}

?>
