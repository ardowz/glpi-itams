<?php
/*
 * @version $Id: reservationitem.class.php 14684 2011-06-11 06:32:40Z remi $
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

/// Reservation item class
class ReservationItem extends CommonDBTM {

   static function getTypeName() {
      global $LANG;

      return $LANG['Menu'][17];
   }


   // From CommonDBTM
   /**
    * Retrieve an item from the database for a specific item
    *
    *@param $ID ID of the item
    *@param $itemtype type of the item
    *
    *@return true if succeed else false
   **/
   function getFromDBbyItem($itemtype, $ID) {
      global $DB;

      $query = "SELECT *
                FROM `".$this->getTable()."`
                WHERE (`itemtype` = '$itemtype'
                       AND `items_id` = '$ID')";

      if ($result = $DB->query($query)) {
         if ($DB->numrows($result)==1) {
            $this->fields = $DB->fetch_assoc($result);
            return true;
         }
      }
      return false;
   }


   function cleanDBonPurge() {
      global $DB;

      $query2 = "DELETE
                 FROM `glpi_reservations`
                 WHERE `reservationitems_id` = '".$this->fields['id']."'";
      $result2 = $DB->query($query2);
   }


   function prepareInputForAdd($input) {

      if (!$this->getFromDBbyItem($input['itemtype'], $input['items_id'])) {
         if (!isset($input['is_active'])) {
            $input['is_active'] = 1;
         }
         return $input;
      }
      return false;
   }


   function getSearchOptions() {
      global $LANG;

      $tab = array();

      $tab[4]['table']    = $this->getTable();
      $tab[4]['field']    = 'comment';
      $tab[4]['name']     = $LANG['common'][25];
      $tab[4]['datatype'] = 'text';

      $tab['common'] = $LANG['common'][32];

      $tab[1]['table']         = 'reservation_types';
      $tab[1]['field']         = 'name';
      $tab[1]['name']          = $LANG['common'][16];
      $tab[1]['datatype']      = 'itemlink';
      $tab[1]['massiveaction'] = false;

      $tab[2]['table']         = 'reservation_types';
      $tab[2]['field']         = 'id';
      $tab[2]['name']          = $LANG['common'][2];
      $tab[2]['massiveaction'] = false;

      $tab += Location::getSearchOptionsToAdd();

      $tab[16]['table']    = 'reservation_types';
      $tab[16]['field']    = 'comment';
      $tab[16]['name']     = $LANG['common'][25];
      $tab[16]['datatype'] = 'text';

      $tab[70]['table'] = 'glpi_users';
      $tab[70]['field'] = 'name';
      $tab[70]['name']  = $LANG['common'][34];

      $tab[71]['table'] = 'glpi_groups';
      $tab[71]['field'] = 'name';
      $tab[71]['name']  = $LANG['common'][35];

      $tab[19]['table']         = 'reservation_types';
      $tab[19]['field']         = 'date_mod';
      $tab[19]['name']          = $LANG['common'][26];
      $tab[19]['datatype']      = 'datetime';
      $tab[19]['massiveaction'] = false;

      $tab[23]['table'] = 'glpi_manufacturers';
      $tab[23]['field'] = 'name';
      $tab[23]['name']  = $LANG['common'][5];

      $tab[24]['table']     = 'glpi_users';
      $tab[24]['field']     = 'name';
      $tab[24]['linkfield'] = 'users_id_tech';
      $tab[24]['name']      = $LANG['common'][10];

      $tab[80]['table']         = 'glpi_entities';
      $tab[80]['field']         = 'completename';
      $tab[80]['name']          = $LANG['entity'][0];
      $tab[80]['massiveaction'] = false;

      return $tab;
   }


   static function showActivationFormForItem($itemtype, $items_id) {
      global $CFG_GLPI, $LANG;

      if (!haveRight("reservation_central","w")) {
         return false;
      }
      if (class_exists($itemtype)) {
         $item = new $itemtype();
         if (!$item->getFromDB($items_id)) {
            return false;
         }
         // Recursive type case => need entity right
         if ($item->isRecursive()) {
            if (!haveAccessToEntity($item->fields["entities_id"])) {
               return false;
            }
         }

      } else {
         return false;
      }

      $ri = new ReservationItem;

      echo "<div><form method='post' name=form action='".getItemTypeFormURL('ReservationItem')."'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr><th>".$LANG['reservation'][9]."</th></tr>";
      echo "<tr class='tab_bg_1'>";
      if ($ri->getFromDBbyItem($itemtype,$items_id)) {
         echo "<td class='center'>";
         //Switch reservation state
         echo "<input type='hidden' name='id' value='".$ri->fields['id']."'>";
         if ($ri->fields["is_active"]) {
            echo "<input type='hidden' name='is_active' value='0'>";
            echo "<input type='submit' name='update' value=\"".$LANG['reservation'][3]."\"
                   class='submit'>";
         } else {
            echo "<input type='hidden' name='is_active' value='1'>";
            echo "<input type='submit' name='update' value=\"".$LANG['reservation'][5]."\"
                   class='submit'>";
         }
         echo "<span class='small_space'>";
         echo "<input type='submit' name='delete' value=\"".$LANG['reservation'][6]."\"
               class='submit' OnClick='return window.confirm(\"".$LANG['reservation'][38]. "\")'>";
         echo "</span></td>";
      } else {
         echo "<td class='center'>";
         echo "<input type='hidden' name='items_id' value='$items_id'>";
         echo "<input type='hidden' name='itemtype' value='$itemtype'>";
         echo "<input type='hidden' name='entities_id' value='".$item->getEntityID()."'>";
         echo "<input type='hidden' name='is_recursive' value='".$item->isRecursive()."'>";
         echo "<input type='submit' name='add' value=\"".$LANG['reservation'][7]."\" class='submit'>";
         echo "</td>";
      }
      echo "</tr></table></form></div>";
   }


   function showForm($ID, $options=array()) {
      global $LANG;

      if (!haveRight("reservation_central","w")) {
         return false;
      }

      $r = new ReservationItem;

      if ($r->getFromDB($ID)) {
         $type = $r->fields["itemtype"];
         $name = NOT_AVAILABLE;
         if (class_exists($r->fields["itemtype"])) {
            $item = new $r->fields["itemtype"]();
            $type = $item->getTypeName();
            if ($item->getFromDB($r->fields["items_id"])) {
               $name = $item->getName();
            }
         }

         echo "<div class='center'><form method='post' name=form action='".$this->getFormURL()."'>";
         echo "<input type='hidden' name='id' value='$ID'>";
         echo "<table class='tab_cadre'>";
         echo "<tr><th colspan='2'>".$LANG['reservation'][22]."</th></tr>";

         // Ajouter le nom du materiel
         echo "<tr class='tab_bg_1'><td>".$LANG['common'][1]."&nbsp;:</td>";
         echo "<td class='b'>$type - $name</td></tr>\n";

         echo "<tr class='tab_bg_1'><td>".$LANG['common'][25]."&nbsp;:</td>";
         echo "<td><textarea name='comment' cols='30' rows='10' >".$r->fields["comment"];
         echo "</textarea></td></tr>\n";

         echo "<tr class='tab_bg_2'><td colspan='2' class='top center'>";
         echo "<input type='submit' name='update' value=\"".$LANG['buttons'][14]."\" class='submit'>";
         echo "</td></tr>\n";

         echo "</table></form></div>";
         return true;

      } else {
         return false;
      }
   }


   static function showListSimple() {
      global $DB, $LANG, $CFG_GLPI;

      if (!haveRight("reservation_helpdesk","1")) {
         return false;
      }

      $ri = new ReservationItem;
      $ok = false;
      $showentity = isMultiEntitiesMode();

      echo "<div class='center'><form name='form' method='get' action='reservation.form.php'>";
      echo "<table class='tab_cadre'>";
      echo "<tr><th colspan='".($showentity?"5":"4")."'>".$LANG['reservation'][1]."</th></tr>\n";

      foreach ($CFG_GLPI["reservation_types"] as $itemtype) {
         if (!class_exists($itemtype)) {
            continue;
         }
         $item = new $itemtype();
         $itemtable = getTableForItemType($itemtype);
         $query = "SELECT `glpi_reservationitems`.`id`,
                          `glpi_reservationitems`.`comment`,
                          `$itemtable`.`name` AS name,
                          `$itemtable`.`entities_id` AS entities_id,
                          `glpi_locations`.`completename` AS location,
                          `glpi_reservationitems`.`items_id` AS items_id
                   FROM `glpi_reservationitems`
                   INNER JOIN `$itemtable`
                        ON (`glpi_reservationitems`.`itemtype` = '$itemtype'
                            AND `glpi_reservationitems`.`items_id` = `$itemtable`.`id`)
                   LEFT JOIN `glpi_locations`
                        ON (`$itemtable`.`locations_id` = `glpi_locations`.`id`)
                   WHERE `glpi_reservationitems`.`is_active` = '1'
                         AND `$itemtable`.`is_deleted` = '0'".
                         getEntitiesRestrictRequest(" AND", $itemtable, '',
                                                    $_SESSION['glpiactiveentities'],
                                                    $item->maybeRecursive())."
                   ORDER BY `$itemtable`.`entities_id`,
                            `$itemtable`.`name`";

         if ($result=$DB->query($query)) {
            while ($row=$DB->fetch_array($result)) {
               echo "<tr class='tab_bg_2'><td>";
               echo "<input type='checkbox' name='item[".$row["id"]."]' value='".$row["id"]."'></td>";
               $typename = $item->getTypeName();
               if ($itemtype == 'Peripheral') {
                  $item->getFromDB($row['items_id']);
                  if (isset($item->fields["peripheraltypes_id"])
                     && $item->fields["peripheraltypes_id"]!=0) {

                     $typename = Dropdown::getDropdownName("glpi_peripheraltypes",
                                                           $item->fields["peripheraltypes_id"]);
                  }
               }
               echo "<td><a href='reservation.php?reservationitems_id=".$row['id']."'>$typename - ".
                     $row["name"]."</a></td>";
               echo "<td>".$row["location"]."</td>";
               echo "<td>".nl2br($row["comment"])."</td>";
               if ($showentity) {
                  echo "<td>".Dropdown::getDropdownName("glpi_entities", $row["entities_id"])."</td>";
               }
               echo "</tr>\n";
               $ok = true;
            }
         }
      }
      if ($ok) {
         echo "<tr class='tab_bg_1 center'><td colspan='".($showentity?"5":"4")."'>";
         echo "<input type='submit' value=\"".$LANG['buttons'][8]."\" class='submit'></td></tr>\n";
      }
      echo "</table>\n";
      echo "<input type='hidden' name='id' value=''>";
      echo "</form></div>\n";
   }


   static function cronInfo($name) {
      global $LANG;

      return array('description' => $LANG['setup'][707]);
   }


   /**
    * Cron action on reservation : alert on end of reservations
    *
    * @param $task to log, if NULL use display
    *
    * @return 0 : nothing to do 1 : done with success
    **/
   static function cronReservation($task=NULL) {
      global $DB, $CFG_GLPI, $LANG;

      if (!$CFG_GLPI["use_mailing"]) {
         return 0;
      }

      $message        = array();
      $cron_status    = 0;
      $items_infos    = array();
      $items_messages = array();

      foreach (Entity::getEntitiesToNotify('use_reservations_alert') as $entity => $value) {
         $secs = $value * HOUR_TIMESTAMP;


         // Reservation already begin and reservation ended in $value hours
         $query_end = "SELECT `glpi_reservationitems`.*,
                              `glpi_reservations`.`end` AS `end`,
                              `glpi_reservations`.`id` AS `resaid`
                       FROM `glpi_reservations`
                       LEFT JOIN `glpi_alerts`
                           ON (`glpi_reservations`.`id` = `glpi_alerts`.`items_id`
                               AND `glpi_alerts`.`itemtype` = 'Reservation'
                               AND `glpi_alerts`.`type` = '".Alert::END."')
                       LEFT JOIN `glpi_reservationitems`
                           ON (`glpi_reservations`.`reservationitems_id` = `glpi_reservationitems`.`id`)
                       WHERE `glpi_reservationitems`.`entities_id` = '$entity'
                             AND (UNIX_TIMESTAMP(`glpi_reservations`.`end`) - $secs) < UNIX_TIMESTAMP()
                             AND `glpi_reservations`.`begin` < NOW()
                             AND `glpi_alerts`.`date` IS NULL";

         foreach ($DB->request($query_end) as $data) {
            $item_resa = new $data["itemtype"]();
            if ($item_resa->getFromDB($data["items_id"])) {
               $message .= $LANG['reservation'][40]." ". $item_resa->getTypeName()." - ".
                           $item_resa->getName()."<br />";
               $data['item_name'] = $item_resa->getName();
               $data['entity'] = $entity;
               $items_infos[$entity][$data['resaid']] = $data;

               if (!isset($items_messages[$entity])) {
                  $items_messages[$entity] = $LANG['reservation'][40]."<br />";
               }
               $items_messages[$entity] .= $message;
            }
         }
      }

      foreach ($items_infos as $entity => $items) {
         $resitem = new ReservationItem;
         if (NotificationEvent::raiseEvent("alert", new Reservation(),
                                           array('entities_id' => $entity,
                                                 'items'       => $items))) {
            $message = $items_messages[$entity];
            $cron_status = 1;
            if ($task) {
               $task->addVolume(1);
               $task->log(Dropdown::getDropdownName("glpi_entities",
                                                    $entity).":  $message\n");
            } else {
               addMessageAfterRedirect(Dropdown::getDropdownName("glpi_entities",
                                                                 $entity).":  $message");
            }

            $alert = new Alert();
            $input["itemtype"] = 'Reservation';
            $input["type"] = Alert::END;
            foreach ($items as $resaid => $item) {
               $input["items_id"] = $resaid;
               $alert->add($input);
               unset($alert->fields['id']);
            }

         } else {
            if ($task) {
               $task->log(Dropdown::getDropdownName("glpi_entities", $entity).
                          ":  Send reservationitem alert failed\n");
            } else {
               addMessageAfterRedirect(Dropdown::getDropdownName("glpi_entities", $entity).
                                       ":  Send reservationitem alert failed", false, ERROR);
            }
         }
      }
      return $cron_status;
   }


   /**
    * Display debug information for reservation of current object
   **/
   function showDebug() {

      $resa = new Reservation();
      $resa->fields['id']                  = '1';
      $resa->fields['reservationitems_id'] = $this->getField('id');
      $resa->fields['begin']               = $_SESSION['glpi_currenttime'];
      $resa->fields['end']                 = $_SESSION['glpi_currenttime'];
      $resa->fields['users_id']            = getLoginUserID();
      $resa->fields['comment']             = '';

      NotificationEvent::debugEvent($resa);
   }

}

?>