<?php
/*
 * @version $Id: ticket_ticket.class.php 14684 2011-06-11 06:32:40Z remi $
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
// Original Author of file: Remi Collet
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/// Class Ticket links
class Ticket_Ticket extends CommonDBRelation {


   // From CommonDBRelation
   public $itemtype_1 = 'Ticket';
   public $items_id_1 = 'tickets_id_1';
   public $itemtype_2 = 'Ticket';
   public $items_id_2 = 'tickets_id_2';

   public $check_entities = false;

   // Ticket links
   const LINK_TO        = 1;
   const DUPLICATE_WITH = 2;


   function canCreate() {

      return haveRight('create_ticket', 1) // Add on creation
             || haveRight('update_ticket', 1);
   }


   function canCreateItem() {

      $ticket = new Ticket();
      return $ticket->can($this->fields['tickets_id_1'], 'w')
             || $ticket->can($this->fields['tickets_id_2'], 'w');
   }


   /**
    * Get linked tickets to a ticket
    *
    * @param $ID ID of the ticket id
    *
    * @return array of linked tickets  array(id=>linktype)
   **/
   static function getLinkedTicketsTo ($ID) {
      global $DB;

      // Make new database object and fill variables
      if (empty($ID)) {
         return false;
      }

      $sql = "SELECT *
              FROM `glpi_tickets_tickets`
              WHERE `tickets_id_1` = '$ID'
                    OR `tickets_id_2` = '$ID'";

      $tickets = array();

      foreach ($DB->request($sql) as $data) {
         if ($data['tickets_id_1']!=$ID) {
            $tickets[$data['id']] = array('link'       => $data['link'],
                                          'tickets_id' => $data['tickets_id_1']);
         } else {
            $tickets[$data['id']] = array('link'       => $data['link'],
                                          'tickets_id' => $data['tickets_id_2']);
         }
      }

      ksort($tickets);
      return $tickets;
   }


   /**
    * Display linked tickets to a ticket
    *
    * @param $ID ID of the ticket id
    *
    * @return nothing display
   **/
   static function displayLinkedTicketsTo ($ID) {
      global $DB, $LANG, $CFG_GLPI;

      $tickets   = self::getLinkedTicketsTo($ID);
      $canupdate = haveRight('update_ticket','1');

      $ticket = new Ticket();
      if (is_array($tickets) && count($tickets)) {
         foreach ($tickets as $linkID => $data) {
            echo self::getLinkName($data['link'])."&nbsp;";
            if (!$_SESSION['glpiis_ids_visible']) {
               echo $LANG['common'][2]."&nbsp;".$data['tickets_id']."&nbsp;:&nbsp;";
            }

            if ($ticket->getFromDB($data['tickets_id'])) {
               echo $ticket->getLink();
               echo  "&nbsp;<img src='".$CFG_GLPI["root_doc"]."/pics/".$ticket->fields["status"].
                             ".png' alt=\"".Ticket::getStatus($ticket->fields["status"])."\"
                             title=\"". Ticket::getStatus($ticket->fields["status"])."\">";
               if ($canupdate) {
                  echo "&nbsp;<a href='".$CFG_GLPI["root_doc"].
                               "/front/ticket.form.php?delete_link=delete_link&amp;id=$linkID".
                               "&amp;tickets_id=$ID' title=\"".$LANG['reservation'][6]."\">
                               <img src='".$CFG_GLPI["root_doc"]."/pics/delete.png'
                                alt=\"".$LANG['buttons'][6]."\" title=\"".$LANG['buttons'][6]."\"></a>";
               }
            }
            echo '<br>';
         }
      }
   }


   /**
    * Dropdown for links between tickets
    *
    * @param $myname select name
    * @param $value default value
   **/
   static function dropdownLinks($myname, $value=self::LINK_TO) {
      global $LANG;

      $tmp[self::LINK_TO]        = $LANG['common'][97];
      $tmp[self::DUPLICATE_WITH] = $LANG['common'][98];
      Dropdown::showFromArray($myname, $tmp, array('value' => $value));
   }


   /**
    * Get Link Name
    *
    * @param $value default value
   **/
   static function getLinkName($value) {
      global $LANG;

      $tmp[self::LINK_TO]        = $LANG['common'][97];
      $tmp[self::DUPLICATE_WITH] = $LANG['common'][98];

      if (isset($tmp[$value])) {
         return $tmp[$value];
      }
      return NOT_AVAILABLE;
   }


   function prepareInputForAdd($input) {

      $ticket = new Ticket();
      if (!isset($input['tickets_id_1'])
          || !isset($input['tickets_id_2'])
          || $input['tickets_id_2'] == $input['tickets_id_1']
          || !$ticket->getFromDB($input['tickets_id_1'])
          || !$ticket->getFromDB($input['tickets_id_2'])) {
         return false;
      }

      if (!isset($input['link'])) {
         $input['link'] = self::LINK_TO;
      }

      // No multiple links
      $tickets = self::getLinkedTicketsTo($input['tickets_id_1']);
      if (count($tickets)) {
         foreach ($tickets as $key => $t) {
            if ($t['tickets_id']==$input['tickets_id_2']) {
               // Delete old simple link
               if ($input['link'] == self::DUPLICATE_WITH
                   && $t['link'] == self::LINK_TO) {
                  $tt = new Ticket_Ticket();
                  $tt->delete(array("id" => $key));
               } else { // No duplicate link
                  return false;
               }
            }
         }
      }

      return $input;
   }


   function post_deleteFromDB() {

      $t = new Ticket();
      $t->updateDateMod($this->fields['tickets_id_1']);
      $t->updateDateMod($this->fields['tickets_id_2']);
      parent::post_deleteFromDB();
   }


   function post_addItem() {

      $t = new Ticket();
      $t->updateDateMod($this->fields['tickets_id_1']);
      $t->updateDateMod($this->fields['tickets_id_2']);
      parent::post_addItem();
   }


  /**
    * Affect the same solution for duplicates tickets
    *
    * @param $ID ID of the ticket id
    *
    * @return nothing do the change
   **/
   static function manageLinkedTicketsOnSolved ($ID) {

      $ticket = new Ticket();

      if ($ticket->getfromDB($ID)) {
         $input['solution']               = addslashes($ticket->fields['solution']);
         $input['ticketsolutiontypes_id'] = addslashes($ticket->fields['ticketsolutiontypes_id']);

         $tickets = self::getLinkedTicketsTo($ID);
         if (count($tickets)) {
            foreach ($tickets as $data) {
               $input['id'] = $data['tickets_id'];
               if ($ticket->can($input['id'],'w')
                   && $data['link'] == self::DUPLICATE_WITH
                   && $ticket->fields['status'] != 'solved'
                   && $ticket->fields['status'] != 'closed') {
                  $ticket->update($input);
               }
            }
         }
      }
   }


}

?>
