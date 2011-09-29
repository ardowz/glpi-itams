<?php

/*
 * @version $Id: log.class.php 14750 2011-06-23 11:30:45Z remi $
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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

// Log class
class Log extends CommonDBTM {


   static function getTypeName() {
      global $LANG;

      return $LANG['title'][38];
   }


   /**
    * Construct  history for an item
    *
    * @param $item CommonDBTM object
    * @param $oldvalues array of old values updated
    * @param $values array of all values of the item
    *
    * @return boolean for success (at least 1 log entry added)
   **/
   static function constructHistory(CommonDBTM $item, & $oldvalues, & $values) {
      global $LANG;

      if (!count($oldvalues)) {
         return false;
      }
      // needed to have  $SEARCHOPTION
      if ($item->getType() == 'Infocom') {
         $real_type = $item->fields['itemtype'];
         $real_id   = $item->fields['items_id'];

      } else {
         $real_type = $item->getType();
         $real_id   = $item->fields['id'];
      }
      $searchopt = Search::getOptions($real_type);

      if (!is_array($searchopt)) {
         return false;
      }
      $result = 0;

      foreach ($oldvalues as $key => $oldval) {
         $changes = array();

         if ($real_type == 'Infocom') {
            // Parsing $SEARCHOPTION to find infocom
            foreach ($searchopt as $key2 => $val2) {
               if (($val2["field"] == $key && strpos($val2['table'], 'infocoms'))
                   || ($key == 'budgets_id' && $val2['table'] == 'glpi_budgets')
                   || ($key == 'suppliers_id' && $val2['table'] == 'glpi_suppliers')) {

                  $id_search_option = $key2; // Give ID of the $SEARCHOPTION
                  if ($val2["table"] == "glpi_infocoms") {
                     // 1st case : text field -> keep datas
                     $changes = array($id_search_option, addslashes($oldval), $values[$key]);

                  } else if ($val2["table"] == "glpi_suppliers") {
                     // 2nd case ; link field -> get data from glpi_suppliers
                     $changes = array($id_search_option,
                                      addslashes(Dropdown::getDropdownName("glpi_suppliers",
                                                                           $oldval)),
                                      addslashes(Dropdown::getDropdownName("glpi_suppliers",
                                                                           $values[$key])));

                  } else {
                     // 3rd case ; link field -> get data from dropdown (budget)
                     $changes = array($id_search_option,
                                      addslashes(Dropdown::getDropdownName($val2["table"],
                                                                           $oldval)),
                                      addslashes(Dropdown::getDropdownName($val2["table"],
                                                                           $values[$key])));
                  }
                  break; // foreach exit
               }
            }

         } else { // Not an Infocom
            // Parsing $SEARCHOPTION to find changed field
            foreach ($searchopt as $key2 => $val2) {
               // Linkfield or standard field not massive action enable
               if ($val2["linkfield"] == $key
                   || ($key == $val2["field"] && $val2["table"] == $item->getTable())) {
                  $id_search_option = $key2; // Give ID of the $SEARCHOPTION

                  // 1st case : Ticket specific dropdown case (without table)
                  if ($real_type=='Ticket' && in_array($key, array('status', 'urgency', 'impact',
                                                                   'priority', 'global_validation'))) {
                     switch ($key) {
                        case 'global_validation' :
                           $changes = array($id_search_option,
                                            addslashes(TicketValidation::getStatus($oldval)),
                                            addslashes(TicketValidation::getStatus($values[$key])));
                           break;

                        case 'status' :
                           $changes = array($id_search_option,
                                            addslashes(Ticket::getStatus($oldval)),
                                            addslashes(Ticket::getStatus($values[$key])));
                           break;

                        case 'urgency' :
                           $changes = array($id_search_option,
                                            addslashes(Ticket::getUrgencyName($oldval)),
                                            addslashes(Ticket::getUrgencyName($values[$key])));
                           break;

                        case 'impact' :
                           $changes = array($id_search_option,
                                            addslashes(Ticket::getImpactName($oldval)),
                                            addslashes(Ticket::getImpactName($values[$key])));
                           break;

                        case 'priority' :
                           $changes = array($id_search_option,
                                            addslashes(Ticket::getPriorityName($oldval)),
                                            addslashes(Ticket::getPriorityName($values[$key])));
                           break;
                     }

                  } else if ($val2["table"] == $item->getTable()) {
                     // 2nd case : text field -> keep datas
                     $changes = array($id_search_option, addslashes($oldval), $values[$key]);

                  } else {
//                      if ($val2['table'] == 'glpi_users_validation') {
//                         $val2['table'] = 'glpi_users';
//                      }

                     // other cases ; link field -> get data from dropdown
                     if ($val2["table"] != 'glpi_complete_entities') {
                        $changes = array($id_search_option,
                                         addslashes(Dropdown::getDropdownName($val2["table"],
                                                                              $oldval)),
                                         addslashes(Dropdown::getDropdownName($val2["table"],
                                                                              $values[$key])));
                     }
                  }
                  break;
               }
            }
         }

         if (count($changes)) {
            $result = self::history($real_id, $real_type, $changes);
         }
      }
      return $result;
   } // function construct_history


   /**
    * Log history
    *
    * @param $items_id
    * @param $itemtype
    * @param $changes
    * @param $itemtype_link
    * @param $linked_action
    *
    * @return boolean success
   **/
   static function history ($items_id, $itemtype, $changes, $itemtype_link='', $linked_action='0') {
      global $DB;

      $date_mod = $_SESSION["glpi_currenttime"];
      if (empty($changes)) {
         return false;
      }

      // create a query to insert history
      $id_search_option = $changes[0];
      $old_value        = $changes[1];
      $new_value        = $changes[2];

      if ($uid=getLoginUserID(false)) {
         if (is_numeric($uid)) {
            $username = getUserName($uid,$link=0);
         } else { // For cron management
            $username = $uid;
         }

      } else {
         $username = "";
      }

      $old_value = mysql_real_escape_string(utf8_substr(stripslashes($old_value), 0, 180));
      $new_value = mysql_real_escape_string(utf8_substr(stripslashes($new_value), 0, 180));

      // Security to be sure that values do not pass over the max length
      if (utf8_strlen($old_value)>255) {
         $old_value = utf8_substr($old_value,0,250);
      }
      if (utf8_strlen($new_value)>255) {
         $new_value = utf8_substr($new_value,0,250);
      }

      // Build query
      $query = "INSERT INTO `glpi_logs`
                       (`items_id`, `itemtype`, `itemtype_link`, `linked_action`, `user_name`,
                        `date_mod`, `id_search_option`, `old_value`, `new_value`)
                VALUES ('$items_id', '$itemtype', '$itemtype_link', '$linked_action',
                        '".addslashes($username)."', '$date_mod', '$id_search_option',
                        '$old_value', '$new_value')";

      if ($DB->query($query)) {
         return $DB->insert_id();
      }
      return false;
   }


   /**
    * Show History of an item
    *
    * @param $item CommonDBTM object
   **/
   static function showForItem(CommonDBTM $item) {
      global $DB,$LANG;

      $itemtype = $item->getType();
      $items_id = $item->getField('id');

      $SEARCHOPTION = Search::getOptions($itemtype);

      if (isset($_REQUEST["start"])) {
         $start = $_REQUEST["start"];
      } else {
         $start = 0;
      }

      // Total Number of events
      $number = countElementsInTable("glpi_logs",
                                     "`items_id`='$items_id' AND `itemtype`='$itemtype'");

      // No Events in database
      if ($number < 1) {
         echo "<div class='center'>";
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr><th>".$LANG['event'][20]."</th></tr>";
         echo "</table>";
         echo "</div><br>";
         return;
      }

      // Display the pager
      printAjaxPager($LANG['title'][38],$start,$number);

      // Output events
      echo "<div class='center'><table class='tab_cadre_fixe'>";
      echo "<tr><th>".$LANG['common'][2]."</th><th>".$LANG['common'][27]."</th>";
      echo "<th>".$LANG['common'][34]."</th><th>".$LANG['event'][18]."</th>";
      echo "<th>".$LANG['event'][19]."</th></tr>";

      foreach (self::getHistoryData($item,$start,$_SESSION['glpilist_limit']) as $data) {
         if ($data['display_history']) {
            // show line
            echo "<tr class='tab_bg_2'>";
            echo "<td>".$data['id']."</td><td>".$data['date_mod'].
                 "</td><td>".$data['user_name']."</td><td>".$data['field']."</td>";
            echo "<td width='60%'>".$data['change']."</td></tr>";
         }
      }
      echo "</table></div>";
   }


   /**
    * Retrieve last history Data for an item
    *
    * @param $item CommonDBTM object
    * @param $start interger first line to retrieve
    * @param $limit interfer max number of line to retrive (0 for all)
    * @param $sqlfilter string to add an SQL filter
    *
    * @return array of localized log entry (TEXT only, no HTML)
   **/
   static function getHistoryData(CommonDBTM $item, $start=0, $limit=0, $sqlfilter='') {
      global $DB, $LANG;

      $itemtype = $item->getType();
      $items_id = $item->getField('id');

      $SEARCHOPTION = Search::getOptions($itemtype);

      $query = "SELECT *
                FROM `glpi_logs`
                WHERE `items_id` = '$items_id'
                      AND `itemtype` = '$itemtype' ";
      if ($sqlfilter) {
         $query .= "AND ($sqlfilter) ";
      }
      $query .= "ORDER BY `id` DESC";

      if ($limit) {
         $query .= " LIMIT ".intval($start)."," . intval($limit);
      }

      $changes = array();
      foreach ($DB->request($query) as $data) {
         $tmp = array();
         $tmp['display_history'] = true;
         $tmp['id']              = $data["id"];
         $tmp['date_mod']        = convDateTime($data["date_mod"]);
         $tmp['user_name']       = $data["user_name"];
         $tmp['field']           = "";
         $tmp['change']          = "";
         $tmp['datatype']        = "";

         // This is an internal device ?
         if ($data["linked_action"]) {
            // Yes it is an internal device
            switch ($data["linked_action"]) {
               case HISTORY_CREATE_ITEM :
                  $tmp['change'] = $LANG['log'][20];
                  break;

               case HISTORY_DELETE_ITEM :
                  $tmp['change'] = $LANG['log'][22];
                  break;

               case HISTORY_RESTORE_ITEM :
                  $tmp['change'] = $LANG['log'][23];
                  break;

               case HISTORY_ADD_DEVICE :
                  $tmp['field'] = NOT_AVAILABLE;
                  if (class_exists($data["itemtype_link"])) {
                     $item         = new $data["itemtype_link"]();
                     $tmp['field'] = $item->getTypeName();
                  }
                  $tmp['change'] = $LANG['devices'][25]." : "."\"". $data["new_value"]."\"";
                  break;

               case HISTORY_UPDATE_DEVICE :
                  $tmp['field'] = NOT_AVAILABLE;
                  $change = '';
                  if (class_exists($data["itemtype_link"])) {
                     $item          = new $data["itemtype_link"]();
                     $tmp['field']  = $item->getTypeName();
                     $specif_fields = $item->getSpecifityLabel();
                     $tmp['change'] = $specif_fields['specificity']." : ";
                  }
                  $tmp['change'] .= $data[ "old_value"]." --> "."\"". $data[ "new_value"]."\"";
                  break;

               case HISTORY_DELETE_DEVICE :
                  $tmp['field']=NOT_AVAILABLE;
                  if (class_exists($data["itemtype_link"])) {
                     $item         = new $data["itemtype_link"]();
                     $tmp['field'] = $item->getTypeName();
                  }
                  $tmp['change'] = $LANG['devices'][26]." : "."\"". $data["old_value"]."\"";
                  break;

               case HISTORY_INSTALL_SOFTWARE :
                  $tmp['field']  = $LANG['help'][31];
                  $tmp['change'] = $LANG['software'][44]." : "."\"".$data["new_value"]."\"";
                  break;

               case HISTORY_UNINSTALL_SOFTWARE :
                  $tmp['field']  = $LANG['help'][31];
                  $tmp['change'] = $LANG['software'][45]." : "."\"". $data["old_value"]."\"";
                  break;

               case HISTORY_DISCONNECT_DEVICE :
                  $tmp['field'] = NOT_AVAILABLE;
                  if (class_exists($data["itemtype_link"])) {
                     $item         = new $data["itemtype_link"]();
                     $tmp['field'] = $item->getTypeName();
                  }
                  $tmp['change'] = $LANG['log'][26]." : "."\"". $data["old_value"]."\"";
                  break;

               case HISTORY_CONNECT_DEVICE :
                  $tmp['field'] = NOT_AVAILABLE;
                  if (class_exists($data["itemtype_link"])) {
                     $item         = new $data["itemtype_link"]();
                     $tmp['field'] = $item->getTypeName();
                  }
                  $tmp['change'] = $LANG['log'][27]." : "."\"". $data["new_value"]."\"";
                  break;

               case HISTORY_OCS_IMPORT :
                  if (haveRight("view_ocsng","r")) {
                     $tmp['field']  = "";
                     $tmp['change'] = $LANG['ocsng'][7]." ".$LANG['ocsng'][45]." :";
                     $tmp['change'].= " "."\"".$data["new_value"]."\"";
                  } else {
                     $tmp['display_history'] = false;
                  }
                  break;

               case HISTORY_OCS_DELETE :
                  if (haveRight("view_ocsng","r")) {
                     $tmp['field']  ="";
                     $tmp['change'] = $LANG['ocsng'][46]." ".$LANG['ocsng'][45]." :";
                     $tmp['change'].= " "."\"".$data["old_value"]."\"";
                  } else {
                     $tmp['display_history'] = false;
                  }
                  break;

               case HISTORY_OCS_LINK :
                  if (haveRight("view_ocsng","r")) {
                     $tmp['field'] = NOT_AVAILABLE;
                     if (class_exists($data["itemtype_link"])) {
                        $item         = new $data["itemtype_link"]();
                        $tmp['field'] = $item->getTypeName();
                     }
                     $tmp['change'] = $LANG['ocsng'][47]." ".$LANG['ocsng'][45]." :";
                     $tmp['change'].= " "."\"".$data["new_value"]."\"";

                  } else {
                     $tmp['display_history'] = false;
                  }
                  break;

               case HISTORY_OCS_IDCHANGED :
                  if (haveRight("view_ocsng","r")) {
                     $tmp['field']  = "";
                     $tmp['change'] = $LANG['ocsng'][48]." "." : "."\"".
                                      $data["old_value"]."\" -->  : "."\"".
                                      $data["new_value"]."\"";
                  } else {
                     $tmp['display_history'] = false;
                  }
                  break;

               case HISTORY_LOG_SIMPLE_MESSAGE :
                  $tmp['field']  = "";
                  $tmp['change'] = $data["new_value"];
                  break;

               case HISTORY_ADD_RELATION :
                  $tmp['field'] = NOT_AVAILABLE;
                  if (class_exists($data["itemtype_link"])) {
                     $item         = new $data["itemtype_link"]();
                     $tmp['field'] = $item->getTypeName();
                  }
                  $tmp['change'] = $LANG['log'][32]." : "."\"". $data["new_value"]."\"";
                  break;

               case HISTORY_DEL_RELATION :
                  $tmp['field'] = NOT_AVAILABLE;
                  if (class_exists($data["itemtype_link"])) {
                     $item         = new $data["itemtype_link"]();
                     $tmp['field'] = $item->getTypeName();
                  }
                  $tmp['change'] = $LANG['log'][33]." : "."\"". $data["old_value"]."\"";
                  break;

               case HISTORY_ADD_SUBITEM :
                  $tmp['field'] = '';
                  if (class_exists($data["itemtype_link"])) {
                     $item         = new $data["itemtype_link"]();
                     $tmp['field'] = $item->getTypeName();
                  }
                  $tmp['change'] = $LANG['log'][98]." : ".$tmp['field']." (".$data["new_value"].")";
                  break;

               case HISTORY_UPDATE_SUBITEM :
                  $tmp['field'] = '';
                  if (class_exists($data["itemtype_link"])) {
                     $item         = new $data["itemtype_link"]();
                     $tmp['field'] = $item->getTypeName();
                  }
                  $tmp['change'] = $LANG['log'][99]." : ".$tmp['field']." (".$data["new_value"].")";
                  break;

               case HISTORY_DELETE_SUBITEM :
                  $tmp['field'] = '';
                  if (class_exists($data["itemtype_link"])) {
                     $item         = new $data["itemtype_link"]();
                     $tmp['field'] = $item->getTypeName();
                  }
                  $tmp['change'] = $LANG['log'][100]." : ".$tmp['field']." (".$data["old_value"].")";
                  break;

            }

         } else {
            $fieldname = "";
            // It's not an internal device
            foreach ($SEARCHOPTION as $key2 => $val2) {
               if ($key2==$data["id_search_option"]) {
                  $tmp['field'] =  $val2["name"];
                  $fieldname    = $val2["field"];

                  if (isset($val2['datatype'])) {
                     $tmp['datatype'] = $val2["datatype"];
                  }
               }
            }

            switch ($tmp['datatype']) {
               case "bool" :
                  $data["old_value"] = Dropdown::getYesNo($data["old_value"]);
                  $data["new_value"] = Dropdown::getYesNo($data["new_value"]);
                  break;

               case "datetime" :
                  $data["old_value"] = convDateTime($data["old_value"]);
                  $data["new_value"] = convDateTime($data["new_value"]);
                  break;

               case "date" :
                  $data["old_value"] = convDate($data["old_value"]);
                  $data["new_value"] = convDate($data["new_value"]);
                  break;

               case "timestamp" :
                  $data["old_value"] = timestampToString($data["old_value"]);
                  $data["new_value"] = timestampToString($data["new_value"]);
                  break;

               case "actiontime" :
                  $data["old_value"] = Ticket::getActionTime($data["old_value"]);
                  $data["new_value"] = Ticket::getActionTime($data["new_value"]);
                  break;

               case "number" :
                  $data["old_value"] = formatNumber($data["old_value"],false,0);
                  $data["new_value"] = formatNumber($data["new_value"],false,0);
                  break;

               case "decimal" :
                  $data["old_value"] = formatNumber($data["old_value"]);
                  $data["new_value"] = formatNumber($data["new_value"]);
                  break;

               case "right" :
                  $data["old_value"] = Profile::getRightValue($data["old_value"]);
                  $data["new_value"] = Profile::getRightValue($data["new_value"]);
                  break;

               case "text" :
                  $tmp['change'] = $LANG['log'][64];
                  break;
            }
            if (empty($tmp['change'])) {
               $tmp['change'] = "\"".$data["old_value"]."\" --> \"". $data["new_value"]."\"";
            }
         }
         $changes[] = $tmp;
      }
      return $changes;
   }

}
?>