<?php
/*
 * @version $Id: ruleticket.class.php 14684 2011-06-11 06:32:40Z remi $
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
if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}


class RuleTicket extends Rule {

   // From Rule
   public $right    = 'entity_rule_ticket';
   public $can_sort = true;


   function getTitle() {
      global $LANG;

      return $LANG['rulesengine'][28];
   }


   function canCreate() {
      return haveRight('entity_rule_ticket', 'w');
   }


   function canView() {
      return haveRight('entity_rule_ticket', 'r');
   }


   function maybeRecursive() {
      return true;
   }


   function isEntityAssign() {
      return true;
   }


   function canUnrecurs() {
      return true;
   }


   function maxActionsCount() {
      return count($this->getActions());
   }


   function addSpecificParamsForPreview($params) {

      if (!isset($params["entities_id"])) {
         $params["entities_id"] = $_SESSION["glpiactive_entity"];
      }
      return $params;
   }


   /**
    * Function used to display type specific criterias during rule's preview
    *
    * @param $fields fields values
   **/
   function showSpecificCriteriasForPreview($fields) {

      $entity_as_criteria = false;
      foreach ($this->criterias as $criteria) {
         if ($criteria->fields['criteria'] == 'entities_id') {
            $entity_as_criteria = true;
            break;
         }
      }
      if (!$entity_as_criteria) {
         echo "<input type='hidden' name='entities_id' value='".$_SESSION["glpiactive_entity"]."'>";
      }
   }


   function executeActions($output,$params) {

      if (count($this->actions)) {
         foreach ($this->actions as $action) {
            switch ($action->fields["action_type"]) {
               case "send" :
                  $ticket = new Ticket();
                  if ($ticket->getFromDB($output['id'])) {
                     NotificationEvent::raiseEvent('recall', $ticket);
                  }
                  break;

               case "add_validation" :
                  $output['_add_validation'] = $action->fields["value"];
                  break;

               case "assign" :
                  $output[$action->fields["field"]] = $action->fields["value"];
                  break;

               case 'compute' :
                  // Value could be not set (from test)
                  $urgency = (isset($output['urgency'])?$output['urgency']:3);
                  $impact  = (isset($output['impact'])?$output['impact']:3);
                  // Apply priority_matrix from config
                  $output['priority'] = Ticket::computePriority($urgency, $impact);
                  break;

               case "affectbyip" :
               case "affectbyfqdn" :
               case "affectbymac" :
                  if (!isset($output["entities_id"])) {
                     $output["entities_id"] = $params["entities_id"];
                  }
                  $regexvalue = RuleAction::getRegexResultById($action->fields["value"],
                                                               $this->regex_results[0]);
                  switch ($action->fields["action_type"]) {
                     case "affectbyip" :
                        $result = NetworkPort::getUniqueObjectIDByIPAddressOrMac($regexvalue, "IP",
                                                                                 $output["entities_id"]);
                        break;

                     case "affectbyfqdn" :
                        $result= NetworkPort::getUniqueObjectIDByFQDN($regexvalue,
                                                                      $output["entities_id"]);
                        break;

                     case "affectbymac" :
                        $result = NetworkPort::getUniqueObjectIDByIPAddressOrMac($regexvalue,"MAC",
                                                                                 $output["entities_id"]);
                        break;

                     default:
                        $result = array();
                  }
                  if (!empty($result)) {
                     $output["itemtype"] = $result["itemtype"];
                     $output["items_id"] = $result["id"];
                  }
                  break;
            }
         }
      }
      return $output;
   }


   function preProcessPreviewResults($output) {
      return Ticket::showPreviewAssignAction($output);
   }


   function getCriterias() {
      global $LANG;

      $criterias = array();
      $criterias['name']['table']     = 'glpi_tickets';
      $criterias['name']['field']     = 'name';
      $criterias['name']['name']      = $LANG['common'][57];
      $criterias['name']['linkfield'] = 'name';

      $criterias['content']['table']     = 'glpi_tickets';
      $criterias['content']['field']     = 'content';
      $criterias['content']['name']      = $LANG['joblist'][6];
      $criterias['content']['linkfield'] = 'content';

      $criterias['ticketcategories_id']['table']     = 'glpi_ticketcategories';
      $criterias['ticketcategories_id']['field']     = 'name';
      $criterias['ticketcategories_id']['name']      = $LANG['common'][36];
      $criterias['ticketcategories_id']['linkfield'] = 'ticketcategories_id';
      $criterias['ticketcategories_id']['type']      = 'dropdown';

      $criterias['type']['table']     = 'glpi_tickets';
      $criterias['type']['field']     = 'type';
      $criterias['type']['name']      = $LANG['common'][17];
      $criterias['type']['linkfield'] = 'type';
      $criterias['type']['type']      = 'dropdown_tickettype';


      $criterias['_users_id_requester']['table']     = 'glpi_users';
      $criterias['_users_id_requester']['field']     = 'name';
      $criterias['_users_id_requester']['name']      = $LANG['job'][4]." - ".$LANG['common'][34];
      $criterias['_users_id_requester']['linkfield'] = '_users_id_requester';
      $criterias['_users_id_requester']['type']      = 'dropdown_users';

      $criterias['users_locations']['table']     = 'glpi_locations';
      $criterias['users_locations']['field']     = 'completename';
      $criterias['users_locations']['name']      = $LANG['job'][4]." - ".$LANG['common'][15];
      $criterias['users_locations']['linkfield'] = 'users_locations';
      $criterias['users_locations']['type']      = 'dropdown';

      $criterias['_groups_id_requester']['table']     = 'glpi_groups';
      $criterias['_groups_id_requester']['field']     = 'name';
      $criterias['_groups_id_requester']['name']      = $LANG['job'][4]." - ".$LANG['common'][35];
      $criterias['_groups_id_requester']['linkfield'] = '_groups_id_requester';
      $criterias['_groups_id_requester']['type']      = 'dropdown';

      $criterias['_users_id_assign']['table']     = 'glpi_users';
      $criterias['_users_id_assign']['field']     = 'name';
      $criterias['_users_id_assign']['name']      = $LANG['job'][5]." - ".$LANG['job'][6];
      $criterias['_users_id_assign']['linkfield'] = '_users_id_assign';
      $criterias['_users_id_assign']['type']      = 'dropdown_users';

      $criterias['_groups_id_assign']['table']     = 'glpi_groups';
      $criterias['_groups_id_assign']['field']     = 'name';
      $criterias['_groups_id_assign']['name']      = $LANG['job'][5]." - ".$LANG['common'][35];
      $criterias['_groups_id_assign']['linkfield'] = '_groups_id_assign';
      $criterias['_groups_id_assign']['type']      = 'dropdown';

      $criterias['suppliers_id_assign']['table']     = 'glpi_suppliers';
      $criterias['suppliers_id_assign']['field']     = 'name';
      $criterias['suppliers_id_assign']['name']      = $LANG['job'][5]." - ".$LANG['financial'][26];
      $criterias['suppliers_id_assign']['linkfield'] = 'suppliers_id_assign';
      $criterias['suppliers_id_assign']['type']      = 'dropdown';

      $criterias['requesttypes_id']['table']     = 'glpi_requesttypes';
      $criterias['requesttypes_id']['field']     = 'name';
      $criterias['requesttypes_id']['name']      = $LANG['job'][44];
      $criterias['requesttypes_id']['linkfield'] = 'requesttypes_id';
      $criterias['requesttypes_id']['type']      = 'dropdown';

      $criterias['itemtype']['table']     = 'glpi_tickets';
      $criterias['itemtype']['field']     = 'itemtype';
      $criterias['itemtype']['name']      = $LANG['state'][6];
      $criterias['itemtype']['linkfield'] = 'itemtype';
      $criterias['itemtype']['type']      = 'dropdown_tracking_itemtype';

      $criterias['entities_id']['table']     = 'glpi_entities';
      $criterias['entities_id']['field']     = 'name';
      $criterias['entities_id']['name']      = $LANG['entity'][0];
      $criterias['entities_id']['linkfield'] = 'entities_id';
      $criterias['entities_id']['type']      = 'dropdown';

      $criterias['urgency']['name'] = $LANG['joblist'][29];
      $criterias['urgency']['type'] = 'dropdown_urgency';

      $criterias['impact']['name'] = $LANG['joblist'][30];
      $criterias['impact']['type'] = 'dropdown_impact';

      $criterias['priority']['name'] = $LANG['joblist'][2];
      $criterias['priority']['type'] = 'dropdown_priority';

      $criterias['_mailgate']['table']     = 'glpi_mailcollectors';
      $criterias['_mailgate']['field']     = 'name';
      $criterias['_mailgate']['name']      = $LANG['mailgate'][0];
      $criterias['_mailgate']['linkfield'] = '_mailgate';
      $criterias['_mailgate']['type']      = 'dropdown';
      return $criterias;
   }


   function getActions() {
      global $LANG;

      $actions = array();
      $actions['ticketcategories_id']['name']  = $LANG['common'][36];
      $actions['ticketcategories_id']['type']  = 'dropdown';
      $actions['ticketcategories_id']['table'] = 'glpi_ticketcategories';

      $actions['_users_id_requester']['name'] = $LANG['job'][4]." - ".$LANG['common'][34];
      $actions['_users_id_requester']['type'] = 'dropdown_users';

      $actions['_groups_id_requester']['name']  = $LANG['job'][4]." - ".$LANG['common'][35];
      $actions['_groups_id_requester']['type']  = 'dropdown';
      $actions['_groups_id_requester']['table'] = 'glpi_groups';

      $actions['_users_id_assign']['name'] = $LANG['job'][5]." - ".$LANG['job'][6];
      $actions['_users_id_assign']['type'] = 'dropdown_assign';

      $actions['_groups_id_assign']['table'] = 'glpi_groups';
      $actions['_groups_id_assign']['name']  = $LANG['job'][5]." - ".$LANG['common'][35];
      $actions['_groups_id_assign']['type']  = 'dropdown';

      $actions['suppliers_id_assign']['table'] = 'glpi_suppliers';
      $actions['suppliers_id_assign']['name']  = $LANG['job'][5]." - ".$LANG['financial'][26];
      $actions['suppliers_id_assign']['type']  = 'dropdown';


      $actions['urgency']['name'] = $LANG['joblist'][29];
      $actions['urgency']['type'] = 'dropdown_urgency';

      $actions['impact']['name'] = $LANG['joblist'][30];
      $actions['impact']['type'] = 'dropdown_impact';

      $actions['priority']['name']          = $LANG['joblist'][2];
      $actions['priority']['type']          = 'dropdown_priority';
      $actions['priority']['force_actions'] = array('assign', 'compute');

      $actions['status']['name'] = $LANG['joblist'][0];
      $actions['status']['type'] = 'dropdown_status';

      $actions['affectobject']['name']          = $LANG['document'][14];
      $actions['affectobject']['type']          = 'text';
      $actions['affectobject']['force_actions'] = array('affectbyip', 'affectbyfqdn', 'affectbymac');

      $actions['slas_id']['table'] = 'glpi_slas';
      $actions['slas_id']['name']  = $LANG['sla'][1];
      $actions['slas_id']['type']  = 'dropdown';

      $actions['users_id_validate']['name']          = $LANG['validation'][1];
      $actions['users_id_validate']['type']          = 'dropdown_users_validate';
      $actions['users_id_validate']['force_actions'] = array('add_validation');

      return $actions;
   }

}

?>