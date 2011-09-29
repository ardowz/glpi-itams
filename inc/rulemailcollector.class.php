<?php
/*
 * @version $Id: rulemailcollector.class.php 14788 2011-06-28 11:01:55Z remi $
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

/// Rule class for Rights management
class RuleMailCollector extends Rule {

   // From Rule
   public $right    = 'rule_mailcollector';
   public $orderby  = "name";
   public $can_sort = true;

   /**
    * Constructor
   **/
   function __construct() {

      // Temproray hack for this class
      $this->forceTable('glpi_rules');
   }


   function canCreate() {
      return haveRight('rule_mailcollector', 'w');
   }


   function canView() {
      return haveRight('rule_mailcollector', 'r');
   }


   function preProcessPreviewResults($output) {
      return $output;
   }


   function maxActionsCount() {
      return 1;
   }

   function getTitleRule($target) {
   }


   function getTitle() {
      global $LANG;

      return $LANG['rulesengine'][70];
   }


   function getCriterias() {
      global $LANG;

      $criterias = array();

      $criterias['mailcollector']['field'] = 'name';
      $criterias['mailcollector']['name']  = $LANG['mailgate'][0];
      $criterias['mailcollector']['table'] = 'glpi_mailcollectors';
      $criterias['mailcollector']['type']  = 'dropdown';

      $criterias['_users_id_requester']['field'] = 'name';
      $criterias['_users_id_requester']['name']  = $LANG['common'][34].' : '.$LANG['common'][16];
      $criterias['_users_id_requester']['table'] = 'glpi_users';
      $criterias['_users_id_requester']['type']  = 'dropdown';

      $criterias['subject']['name']  = $LANG['mailing'][118].' : '.$LANG['common'][90];
      $criterias['subject']['field'] = 'subject';
      $criterias['subject']['table'] = '';
      $criterias['subject']['type']  = 'text';

      $criterias['content']['name']  = $LANG['mailing'][118].' : '.$LANG['mailing'][114];
      $criterias['content']['table'] = '';
      $criterias['content']['type']  = 'text';

      $criterias['from']['name']  = $LANG['mailing'][132].' : from';
      $criterias['from']['table'] = '';
      $criterias['from']['type']  = 'text';

      $criterias['to']['name']  = $LANG['mailing'][132].' : to';
      $criterias['to']['table'] = '';
      $criterias['to']['type']  = 'text';

      $criterias['in_reply_to']['name']  = $LANG['mailing'][132].' : in_reply_to';
      $criterias['in_reply_to']['table'] = '';
      $criterias['in_reply_to']['type']  = 'text';

      $criterias['x-priority']['name']  = $LANG['mailing'][132].' : X-Priority';
      $criterias['x-priority']['table'] = '';
      $criterias['x-priority']['type']  = 'text';

      $criterias['x-auto-response-suppress']['name']  = $LANG['mailing'][132].' : X-Auto-Response-Suppress';
      $criterias['x-auto-response-suppress']['table'] = '';
      $criterias['x-auto-response-suppress']['type']  = 'text';

      $criterias['auto-submitted']['name']  = $LANG['mailing'][132].' : Auto-Submitted';
      $criterias['auto-submitted']['table'] = '';
      $criterias['auto-submitted']['type']  = 'text';

      $criterias['received']['name']  = $LANG['mailing'][132].' : Received';
      $criterias['received']['table'] = '';
      $criterias['received']['type']  = 'text';

      $criterias['GROUPS']['table']     = 'glpi_groups';
      $criterias['GROUPS']['field']     = 'name';
      $criterias['GROUPS']['name']      = $LANG['common'][34].' : '.$LANG['common'][35];
      $criterias['GROUPS']['linkfield'] = '';
      $criterias['GROUPS']['type']      = 'dropdown';
      $criterias['GROUPS']['virtual']   = true;
      $criterias['GROUPS']['id']        = 'groups';

      $criterias['KNOWN_DOMAIN']['field']           = 'name';
      $criterias['KNOWN_DOMAIN']['name']            = $LANG['rulesengine'][148];
      $criterias['KNOWN_DOMAIN']['table']           = 'glpi_entitydatas';
      $criterias['KNOWN_DOMAIN']['type']            = 'yesno';
      $criterias['KNOWN_DOMAIN']['virtual']         = true;
      $criterias['KNOWN_DOMAIN']['id']              = 'entitydatas';
      $criterias['KNOWN_DOMAIN']['allow_condition'] = array(Rule::PATTERN_IS);

      $criterias['PROFILES']['field']           = 'name';
      $criterias['PROFILES']['name']            = $LANG['rulesengine'][146];
      $criterias['PROFILES']['table']           = 'glpi_profiles';
      $criterias['PROFILES']['type']            = 'dropdown';
      $criterias['PROFILES']['virtual']         = true;
      $criterias['PROFILES']['id']              = 'profiles';
      $criterias['PROFILES']['allow_condition'] = array(Rule::PATTERN_IS);

      if (isMultiEntitiesMode()) {
         $criterias['UNIQUE_PROFILE']['field']           = 'name';
         $criterias['UNIQUE_PROFILE']['name']            = $LANG['rulesengine'][147];
         $criterias['UNIQUE_PROFILE']['table']           = 'glpi_profiles';
         $criterias['UNIQUE_PROFILE']['type']            = 'dropdown';
         $criterias['UNIQUE_PROFILE']['virtual']         = true;
         $criterias['UNIQUE_PROFILE']['id']              = 'profiles';
         $criterias['UNIQUE_PROFILE']['allow_condition'] = array(Rule::PATTERN_IS);
      }

      $criterias['ONE_PROFILE']['field']           = 'name';
      $criterias['ONE_PROFILE']['name']            = $LANG['rulesengine'][145];
      $criterias['ONE_PROFILE']['table']           = '';
      $criterias['ONE_PROFILE']['type']            = 'yesonly';
      $criterias['ONE_PROFILE']['virtual']         = true;
      $criterias['ONE_PROFILE']['id']              = 'profiles';
      $criterias['ONE_PROFILE']['allow_condition'] = array(Rule::PATTERN_IS);

      return $criterias;
   }


   function getActions() {
      global $LANG;

      $actions = array();

      $actions['entities_id']['name']  = $LANG['entity'][0];
      $actions['entities_id']['type']  = 'dropdown';
      $actions['entities_id']['table'] = 'glpi_entities';

      $actions['_affect_entity_by_domain']['name']          = $LANG['rulesengine'][133];
      $actions['_affect_entity_by_domain']['type']          = 'text';
      $actions['_affect_entity_by_domain']['force_actions'] = array('regex_result');

      $actions['_affect_entity_by_tag']['name']          = $LANG['rulesengine'][131];
      $actions['_affect_entity_by_tag']['type']          = 'text';
      $actions['_affect_entity_by_tag']['force_actions'] = array('regex_result');

      $actions['_affect_entity_by_user_entity']['name']  = $LANG['rulesengine'][144];
      $actions['_affect_entity_by_user_entity']['type']  = 'yesonly';
      $actions['_affect_entity_by_user_entity']['table'] = '';

      $actions['_refuse_email_no_response']['name']  = $LANG['rulesengine'][134];
      $actions['_refuse_email_no_response']['type']  = 'yesonly';
      $actions['_refuse_email_no_response']['table'] = '';

      $actions['_refuse_email_with_response']['name']  = $LANG['rulesengine'][135];
      $actions['_refuse_email_with_response']['type']  = 'yesonly';
      $actions['_refuse_email_with_response']['table'] = '';

      return $actions;
   }


   function executeActions($output,$params) {

      if (count($this->actions)) {

         foreach ($this->actions as $action) {

            switch ($action->fields["action_type"]) {
               case "assign" :
                  switch ($action->fields["field"]) {
                     default :
                        $output[$action->fields["field"]] = $action->fields["value"];
                        break;

                     case "_affect_entity_by_user_entity":
                        //3 cases :
                        //1 - rule contains a criteria like : Profil is XXXX
                        //    -> in this case, profiles_id is stored in
                        //       $this->criterias_results['PROFILES'] (one value possible)
                        //2-   rule contains criteria "User has only one profile"
                        //    -> in this case, profiles_id is stored in
                        //       $this->criterias_results['PROFILES'] (one value possible) (same as 1)
                        //3   -> rule contains only one profile
                        $profile = 0;

                        //Case 2:
                        if (isset($this->criterias_results['ONE_PROFILE'])) {
                           $profile = $this->criterias_results['ONE_PROFILE'];
                        //Case 3
                        } else if (isset($this->criterias_results['UNIQUE_PROFILE'])) {
                           $profile = $this->criterias_results['UNIQUE_PROFILE'];
                        //Case 1
                        } else if (isset($this->criterias_results['PROFILES'])) {
                           $profile = $this->criterias_results['PROFILES'];
                        }

                        if ($profile) {
                           $entities = array();
                           if (isset($params['_users_id_requester'])) { // Not set when testing
                              $entities = Profile_User::getEntitiesForProfileByUser($params['_users_id_requester'],
                                                                                    $profile);
                           }

                           //Case 2 : check if there's only one profile for this user
                           if ((isset($this->criterias_results['ONE_PROFILE'])
                                && count($entities) == 1)
                               || !isset($this->criterias_results['ONE_PROFILE'])) {

                              if (count($entities) == 1) {
                                 //User has right on only one entity
                                 $output['entities_id'] = array_pop($entities);
                              } else if (isset($this->criterias_results['UNIQUE_PROFILE'])) {
                                 $output['entities_id'] = array_pop($entities);
                              } else {

                                 //Rights on more than one entity : get the user's prefered entity
                                 if (isset($params['_users_id_requester'])) { // Not set when testing
                                    $user = new User;
                                    $user->getFromDB($params['_users_id_requester']);

                                    //If an entity is defined in user's preferences, use this one
                                    //else do not set the rule as matched
                                    if (is_integer($user->getField('entities_id'))) {
                                       $output['entities_id'] = $user->fields['entities_id'];
                                    }
                                 }
                              }
                           }
                        }
                  }
                  break;

               case "regex_result" :
                  foreach ($this->regex_results as $regex_result) {
                     $entity_found = -1;
                     $res = RuleAction::getRegexResultById($action->fields["value"],
                                                           $regex_result);
                     if ($res != null) {
                        switch ($action->fields["field"]) {
                           case "_affect_entity_by_domain" :
                              $entity_found = EntityData::getEntityIDByDomain($res);
                              break;

                           case "_affect_entity_by_tag" :
                              $entity_found = EntityData::getEntityIDByTag($res);
                              break;
                        }

                        //If an entity was found
                        if ($entity_found > -1) {
                           $output['entities_id'] = $entity_found;
                           break;
                         }
                      }
                  } // switch (field)
               break;
            }
         }
      }
      return $output;
   }

}

?>
