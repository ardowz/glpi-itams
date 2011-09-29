<?php

/*
 * @version $Id: ticketvalidation.class.php 14684 2011-06-11 06:32:40Z remi $
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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * TicketValidation class
 */
class TicketValidation  extends CommonDBChild {

   // From CommonDBTM
   public $auto_message_on_action = false;

   // From CommonDBChild
   public $itemtype = 'Ticket';
   public $items_id = 'tickets_id';


   static function getTypeName() {
      global $LANG;

      return $LANG['validation'][0];
   }


   function canCreate() {
      return haveRight('create_validation', 1);
   }


   function canView() {
      return (haveRight('create_validation', 1)
              || haveRight('validate_ticket', 1));
   }


   function canUpdate() {
      return (haveRight('validate_ticket', 1) || haveRight('create_validation', 1));
   }


   function canDelete() {
      return haveRight('create_validation', 1);
   }


   /**
    * Is the current user have right to update the current validation ?
    *
    * @return boolean
    */
   function canUpdateItem() {

      if (!haveRight('create_validation', 1)
          && ($this->fields["users_id_validate"] != getLoginUserID())) {
         return false;
      }

      return true;
   }


   static function canValidate($tickets_id) {
      global $DB;

      $query = "SELECT `users_id_validate`
                FROM `glpi_ticketvalidations`
                WHERE `tickets_id` = '$tickets_id'
                      AND users_id_validate='".getLoginUserID()."'";
      $result = $DB->query($query);
      if ($DB->numrows($result)) {
         return true;
      }

      return false;
   }


   function post_getEmpty() {

      $this->fields["users_id"] = getLoginUserID();
      $this->fields["status"]   = 'waiting';
   }


   function defineTabs($options=array()) {
      global $LANG;

      $ong = array();
      $ong[1] = $LANG['title'][26];

      return $ong;
   }


   function prepareInputForAdd($input) {

      // Not attached to tickets -> not added
      if (!isset($input['tickets_id']) || $input['tickets_id'] <= 0) {
         return false;
      } else {

/*       if (strstr($job->fields["status"],"solved")
                  || strstr($job->fields["status"],"closed")) {
            return false;
         }*/

         if (!isset($input['entities_id'])) { // Massive modif case
            $job = new Ticket;
            $job->getFromDB($input["tickets_id"]);

            $input['entities_id'] = $job->fields["entities_id"];
         }
         $input["users_id"] = 0;
         if (!isset($input['_auto_update'])) {
            $input["users_id"] = getLoginUserID();
         }
         $input["submission_date"] = $_SESSION["glpi_currenttime"];
         $input["status"] = 'waiting';

      }
      return $input;
   }


   function post_addItem() {
      global $LANG,$CFG_GLPI;

      $job = new Ticket;
      $mailsend = false;
      if ($job->getFromDB($this->fields["tickets_id"])) {
         // Set global validation to waiting
         if ($job->fields['global_validation'] == 'accepted'
             || $job->fields['global_validation'] == 'none') {
            $input['id'] = $this->fields["tickets_id"];
            $input['global_validation'] = 'waiting';

            // to fix lastupdater
            if (isset($this->input['_auto_update'])) {
               $input['_auto_update'] = $this->input['_auto_update'];
            }

            $job->update($input);
         }
         if ($CFG_GLPI["use_mailing"]) {
            $options = array('validation_id'     => $this->fields["id"],
                             'validation_status' => $this->fields["status"]);
            $mailsend = NotificationEvent::raiseEvent('validation',$job,$options);
         }
         if ($mailsend) {
            $user = new User();
            $user->getFromDB($this->fields["users_id_validate"]);
            if (!empty($user->fields["email"])) {
               addMessageAfterRedirect($LANG['validation'][13]." ".$user->getName());
            } else {
               addMessageAfterRedirect($LANG['validation'][23],false,ERROR);
            }
         }
         // Add log entry in the ticket
         $changes[0] = 0;
         $changes[1] = '';
         $changes[2] = addslashes($LANG['validation'][13]." ".
                                  getUserName($this->fields["users_id_validate"]));
         Log::history($this->getField('tickets_id'), 'Ticket', $changes, $this->getType(),
                      HISTORY_LOG_SIMPLE_MESSAGE);
      }
   }


   function prepareInputForUpdate($input) {
      global $LANG;

      $job = new Ticket;
      $forbid_fields = array();

      if ($this->fields["users_id_validate"] == getLoginUserID()) {
         if ($input["status"] == "rejected"
             && (!isset($input["comment_validation"]) || $input["comment_validation"] == '')) {
            addMessageAfterRedirect($LANG['validation'][29],false,ERROR);
            return false;
         }
         if ($input["status"] == "waiting") {
//             $input["comment_validation"] = '';
            $input["validation_date"] = 'NULL';
         } else {
            $input["validation_date"] = $_SESSION["glpi_currenttime"];
         }
         $forbid_fields = array('entities_id', 'users_id', 'tickets_id', 'users_id_validate',
                                'comment_submission', 'submission_date');

      } else if (haveRight('create_validation',1)) { // Update validation request
         $forbid_fields = array('entities_id', 'tickets_id', 'status', 'comment_validation',
                                'validation_date');
      }

      if (count($forbid_fields)) {
         foreach ($forbid_fields as $key => $val) {
            if (isset($input[$key])) {
               unset($input[$key]);
            }
         }
      }

      return $input;
   }


   function post_updateItem($history=1) {
      global $LANG,$CFG_GLPI;

      $job = new Ticket;
      $mailsend = false;

      if ($job->getFromDB($this->fields["tickets_id"])) {
         if (count($this->updates) && $CFG_GLPI["use_mailing"]) {
            $options = array('validation_id'     => $this->fields["id"],
                             'validation_status' => $this->fields["status"]);
            $mailsend = NotificationEvent::raiseEvent('validation',$job,$options);
         }
         // Add log entry in the ticket
         $changes[0] = 0;
         $changes[1] = '';

         switch ($this->fields["status"]) {
            case 'accepted' :
               $changes[2] = getUserName($this->fields["users_id_validate"]). " : ".
                                         $LANG['validation'][19];
               break;

            case 'rejected' :
            default :
               $changes[2] = $LANG['validation'][31]." ".
                             getUserName($this->fields["users_id_validate"]);
               break;

         }
         Log::history($this->getField('tickets_id'), 'Ticket',  $changes, $this->getType(),
                      HISTORY_LOG_SIMPLE_MESSAGE);

         // Set global validation to accepted to define one
         if ($job->fields['global_validation'] == 'waiting'
             || (countElementsInTable('glpi_ticketvalidations',
                                      "`tickets_id` = '".$this->fields["tickets_id"]."'") == 1)) {

            $input['id']                = $this->fields["tickets_id"];
            $input['global_validation'] = $this->fields["status"];
            $job->update($input);
         }
      }
   }


   function post_deleteFromDB() {
      global $LANG;

      // Add log entry in the ticket
      $changes[0] = 0;
      $changes[1] = '';
      $changes[2] = addslashes($LANG['validation'][30]." ".
                    getUserName($this->fields["users_id_validate"]));
      Log::history($this->getField('tickets_id'), 'Ticket', $changes, $this->getType(),
                   HISTORY_LOG_SIMPLE_MESSAGE);
   }


   /**
    * get the Ticket validation status list
    *
    * @param $withmetaforsearch boolean
    * @param $global boolean (true for global status, with "no validation" option)
    *
    * @return an array
    */
   static function getAllStatusArray($withmetaforsearch=false, $global=false) {
      global $LANG;

      $tab = array('waiting'  => $LANG['validation'][9],
                   'rejected' => $LANG['validation'][10],
                   'accepted' => $LANG['validation'][11]);
      if ($global) {
         $tab['none'] = $LANG['validation'][12];

         if ($withmetaforsearch) {
            $tab['can'] = $LANG['validation'][11]." + ".$LANG['validation'][12];
         }
      }

      if ($withmetaforsearch) {
         $tab['all'] = $LANG['common'][66];
      }
      return $tab;
   }


   /**
   * Dropdown of validation status
   *
   * @param $name select name
   * @param $options array options
   *   - possible values :
   *      - value : default value (default waiting)
   *      - all : display all (default false)
   *
   * @return nothing (display)
   */
   static function dropdownStatus($name, $options=array()) {

      $value  = 'waiting';
      $global = false;
      $all    = false;
      if (isset($options['value'])) {
         $value = $options['value'];
      }
      if (isset($options['all'])) {
         $all = $options['all'];
      }
      if (isset($options['global'])) {
         $global = $options['global'];
      }
      $tab = self::getAllStatusArray($all, $global);

      echo "<select name='$name'>";
      foreach ($tab as $key => $val) {
         echo "<option value='$key' ".($value==$key?" selected ":"").">$val</option>";
      }
      echo "</select>";
   }


   /**
    * Get Ticket validation status Name
    *
    * @param $value status ID
    */
   static function getStatus($value) {

      $tab = self::getAllStatusArray(true, true);
      return (isset($tab[$value]) ? $tab[$value] : '');
   }


   /**
    * Get Ticket validation status Color
    *
    * @param $value status ID
    */
   static function getStatusColor($value) {

      switch ($value) {
         case "waiting" :
            $style = "#FFC65D";
            break;

         case "rejected" :
            $style = "#cf9b9b";
            break;

         case "accepted" :
            $style = "#9BA563";
            break;

         default :
            $style = "#cf9b9b";
      }
      return $style;
   }


   /**
    * Get Ticket validation demands count
    *
    * @param $tickets_id ticket ID
    */
   static function getNumberValidationForTicket($tickets_id) {
      global $DB;

      $query = "SELECT COUNT(`id`) AS 'total'
                FROM `glpi_ticketvalidations`
                WHERE `tickets_id` = '$tickets_id'";

      $result = $DB->query($query);
      if ($DB->numrows($result)) {
         return $DB->result($result,0,"total");
      }
      return false;
   }

   /**
    * Get Ticket validation demands count for a user
    *
    * @param $users_id User ID
    */
   static function getNumberTicketsToValidate($users_id) {
      global $DB;

      $query = "SELECT COUNT(`id`) AS 'total'
                FROM `glpi_ticketvalidations`
                WHERE `users_id_validate` = '$users_id' AND `status` = 'waiting'";

      $result = $DB->query($query);
      if ($DB->numrows($result)) {
         return $DB->result($result,0,"total");
      }
      return false;
   }

   /**
    * Get the number of validations attached to a ticket having a specified status
    *
    * @param $tickets_id ticket ID
    * @param $status status
    */
   static function getTicketStatusNumber($tickets_id, $status) {
      global $DB;

      $query = "SELECT COUNT(`status`) AS 'total'
                FROM `glpi_ticketvalidations`
                WHERE `tickets_id` = '$tickets_id'
                      AND `status` = '".$status."'";

      $result = $DB->query($query);
      if ($DB->numrows($result)) {
         return $DB->result($result,0,"total");
      }
      return false;
   }


   /**
    * Form for Followup on Massive action
    */
   static function showFormMassiveAction() {
      global $LANG;

      echo "&nbsp;".$LANG['validation'][21]."&nbsp;: ";
      User::dropdown(array('name'   => 'users_id_validate',
                           'entity' => $_SESSION["glpiactive_entity"],
                           'right'  => 'validate_ticket'));

      echo "<br>".$LANG['common'][25]."&nbsp;: ";
      echo "<textarea name='comment_submission' cols='50' rows='6'></textarea>&nbsp;";

      echo "<input type='submit' name='add' value=\"".$LANG['buttons'][8]."\" class='submit'>";
   }


   /**
    * Print the validation list into ticket
    *
    * @param $ticket class
   **/
   function showSummary($ticket) {
      global $DB, $LANG, $CFG_GLPI;

      if (!haveRight('validate_ticket',1) && !haveRight('create_validation',1)) {
         return false;
      }
      $tID = $ticket->fields['id'];
      //$canadd = haveRight("create_validation", "1");

      $tmp    = array('tickets_id' => $tID);
      $canadd = $this->can(-1,'w',$tmp);
      $rand   = mt_rand();

      echo "<div id='viewfollowup" . $tID . "$rand'></div>\n";

      if ($canadd) {
         echo "<script type='text/javascript' >\n";
         echo "function viewAddValidation" . $tID . "$rand() {\n";
         $params = array('type'       => __CLASS__,
                         'tickets_id' => $tID,
                         'id'         => -1);
         ajaxUpdateItemJsCode("viewfollowup" . $tID . "$rand",
                              $CFG_GLPI["root_doc"]."/ajax/viewfollowup.php", $params, false);
         echo "};";
         echo "</script>\n";
         if ($ticket->fields["status"] != 'solved' && $ticket->fields["status"] != 'closed') {
            echo "<div class='center'><a href='javascript:viewAddValidation".$tID."$rand();'>";
            echo $LANG['validation'][1]."</a></div><br>\n";
         }
      }

      $query = "SELECT *
                FROM `".$this->getTable()."`
                WHERE `tickets_id` = '".$ticket->getField('id')."'";
      if (!$canadd) {
         $query .= " AND `users_id_validate` = '".getLoginUserID()."' ";
      }
      $query .= " ORDER BY submission_date DESC";
      $result = $DB->query($query);
      $number = $DB->numrows($result);

      if ($number) {
         $colonnes = array($LANG['validation'][2],
                           $LANG['validation'][3],
                           $LANG['validation'][18],
                           $LANG['validation'][5],
                           $LANG['validation'][4],
                           $LANG['validation'][21],
                           $LANG['validation'][6]);
         $nb_colonnes = count($colonnes);

         echo "<table class='tab_cadre_fixehov'>";
         echo "<tr><th colspan='".$nb_colonnes."'>".$LANG['validation'][7]."</th></tr>";

         echo "<tr>";
         foreach ($colonnes as $colonne) {
            echo "<th>".$colonne."</th>";
         }
         echo "</tr>";

         initNavigateListItems('TicketValidation',
                               $LANG['validation'][26]." = ".$ticket->fields['name']);

         while ($row = $DB->fetch_assoc($result)) {
            $canedit = $this->can($row["id"],'w');
            addToNavigateListItems('TicketValidation',$row["id"]);
            $bgcolor = $this->getStatusColor($row['status']);
            $status = $this->getStatus($row['status']);

            echo "<tr class='tab_bg_1' ".($canedit
                  ? "style='cursor:pointer' onClick=\"viewEditValidation".$ticket->fields['id'].
                     $row["id"]."$rand();\""
                  : '') ." id='viewfollowup" . $this->fields['tickets_id'] . $row["id"] . "$rand'>";
            echo "<td>";
            if ($canedit) {
               echo "\n<script type='text/javascript' >\n";
               echo "function viewEditValidation" . $ticket->fields['id'] . $row["id"] . "$rand() {\n";
               $params = array('type'       => __CLASS__,
                               'tickets_id' => $this->fields["tickets_id"],
                               'id'         => $row["id"]);
               ajaxUpdateItemJsCode("viewfollowup" . $ticket->fields['id'] . "$rand",
                                    $CFG_GLPI["root_doc"]."/ajax/viewfollowup.php", $params, false);
               echo "};";
               echo "</script>\n";
            }

            echo "<div style='background-color:".$bgcolor.";'>".$status."</div></td>";

            if ($ticket->can($ticket->fields['id'], 'r')
                && !strstr($ticket->fields["status"], "solved")
                && !strstr($ticket->fields["status"],"closed")) {

               $link_validation = getItemTypeFormURL('TicketValidation');
               echo "<td>". convDateTime($row["submission_date"])."</td>";
            } else {
               echo "<td>".convDateTime($row["submission_date"])."</a></td>";
            }

            echo "<td>".getUserName($row["users_id"])."</td>";
            echo "<td>".$row["comment_submission"]."</td>";
            echo "<td>".convDateTime($row["validation_date"])."</td>";
            echo "<td>".getUserName($row["users_id_validate"])."</td>";
            echo "<td>".$row["comment_validation"]."</td>";
            echo "</tr>";
         }
         echo "</table>";
      } else {
         echo "<div class='center b'>".$LANG['search'][15]."</div>";
      }
   }


   /**
    * Print the validation form
    *
    * @param $ID integer ID of the item
    * @param $options array options used
    *
    **/
   function showForm($ID, $options=array()) {
      global $LANG;

      $this->check($ID,'w');

      if ($ID>0) {
         $tickets_id = $this->fields["tickets_id"];
      } else {
         $tickets_id = $options['ticket']->fields["id"];
      }
      $ticket = new Ticket();
      if (!$ticket->getFromDB($tickets_id)) {
         return false;
      }
      // No update validation is answer set
      $validation_admin = ($this->fields["users_id"] == getLoginUserID())
                          && $this->canCreate()
                          && $this->fields['status'] == 'waiting';
      $validator = ($this->fields["users_id_validate"] == getLoginUserID());

      $options['colspan'] = 1;

      $this->showFormHeader($options);
      if ($validation_admin) {
         echo "<tr class='tab_bg_1'>";
         echo "<td>".$LANG['validation'][18]."&nbsp;:&nbsp;</td>";
         echo "<td>";
         echo "<input type='hidden' name='tickets_id' value='".$ticket->fields['id']."'>";
         echo "<input type='hidden' name='entities_id' value='".$ticket->fields['entities_id']."'>";
         echo getUserName($this->fields["users_id"]);
         echo "</td></tr>";

         echo "<tr class='tab_bg_1'><td>".$LANG['validation'][21]."&nbsp;:&nbsp;</td>";
         echo "<td>";
         echo "<input type='hidden' name='tickets_id' value='".$ticket->fields['id']."'>";
         echo "<input type='hidden' name='entities_id' value='".$ticket->fields['entities_id']."'>";
         User::dropdown(array('name'   => "users_id_validate",
                              'entity' => $ticket->fields['entities_id'],
                              'right'  => 'validate_ticket',
                              'value'  => $this->fields["users_id_validate"]));
         echo "</td></tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>".$LANG['common'][25]."&nbsp;:&nbsp;</td>";
         echo "<td><textarea cols='60' rows='3' name='comment_submission'>".
               $this->fields["comment_submission"]."</textarea></td></tr>";

      } else {
         echo "<tr class='tab_bg_1'>";
         echo "<td>".$LANG['validation'][18]."&nbsp;:&nbsp;</td>";
         echo "<td>".getUserName($this->fields["users_id"])."</td></tr>";

         echo "<tr class='tab_bg_1'><td>".$LANG['validation'][21]."&nbsp;:&nbsp;</td>";
         echo "<td>".getUserName($this->fields["users_id_validate"])."</td></tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>".$LANG['common'][25]."&nbsp;:&nbsp;</td>";
         echo "<td>". $this->fields["comment_submission"]. "</td></tr>";
      }

      if ($ID>0) {
         echo "<tr class='tab_bg_2'><td colspan='2'>&nbsp;</td></tr>";

         if ($validator) {
            echo "<tr class='tab_bg_1'>";
            echo "<td>".$LANG['validation'][28]."&nbsp;:&nbsp;</td>";
            echo "<td>";
            self::dropdownStatus("status", array('value' => $this->fields["status"]));
            echo "</td></tr>";

            echo "<tr class='tab_bg_1'>";
            echo "<td>".$LANG['validation'][6]." (".$LANG['validation'][16].")&nbsp;:&nbsp;</td>";
            echo "<td><textarea cols='60' rows='3' name='comment_validation'>".
                        $this->fields["comment_validation"]."</textarea>";
            echo "</td></tr>";

         } else {
            echo "<tr class='tab_bg_1'>";
            echo "<td>".$LANG['validation'][28]."&nbsp;:&nbsp;</td>";
            echo "<td>". self::getStatus($this->fields["status"])."</td></tr>";

            echo "<tr class='tab_bg_1'>";
            echo "<td>".$LANG['common'][25]."&nbsp;:&nbsp;</td>";
            echo "<td>".$this->fields["comment_validation"]."</td></tr>";
         }
      }

      $this->showFormButtons($options);

      return true;
   }


   function getSearchOptions() {
      global $LANG;

      $tab = array();
      $tab['common'] = $LANG['validation'][0];

      $tab[1]['table']        = $this->getTable();
      $tab[1]['field']        = 'comment_submission';
      $tab[1]['name']         = $LANG['validation'][0]." - ".$LANG['validation'][5];
      $tab[1]['datatype']     = 'text';
      $tab[1]['forcegroupby'] = true;

      $tab[2]['table']    = $this->getTable();
      $tab[2]['field']    = 'comment_validation';
      $tab[2]['name']     = $LANG['validation'][0]." - ".$LANG['validation'][6];
      $tab[2]['datatype'] = 'text';

      $tab[3]['table']      = $this->getTable();
      $tab[3]['field']      = 'status';
      $tab[3]['name']       = $LANG['validation'][0]." - ".$LANG['joblist'][0];
      $tab[3]['searchtype'] = 'equals';

      $tab[4]['table']    = $this->getTable();
      $tab[4]['field']    = 'submission_date';
      $tab[4]['name']     = $LANG['validation'][0]." - ".$LANG['validation'][3];
      $tab[4]['datatype'] = 'datetime';

      $tab[5]['table']    = $this->getTable();
      $tab[5]['field']    = 'validation_date';
      $tab[5]['name']     = $LANG['validation'][0]." - ".$LANG['validation'][4];
      $tab[5]['datatype'] = 'datetime';

      $tab[6]['table']         = 'glpi_users';
      $tab[6]['field']         = 'name';
      $tab[6]['name']          = $LANG['validation'][0]." - ".$LANG['job'][4];
      $tab[6]['datatype']      = 'itemlink';
      $tab[6]['itemlink_type'] = 'User';

      $tab[7]['table']         = 'glpi_users';
      $tab[7]['field']         = 'name';
      $tab[7]['linkfield']     = 'users_id_validate';
      $tab[7]['name']          = $LANG['validation'][0]." - ".$LANG['validation'][21];
      $tab[7]['datatype']      = 'itemlink';
      $tab[7]['itemlink_type'] = 'User';

      return $tab;
   }

}

?>