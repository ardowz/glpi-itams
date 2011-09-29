<?php
/*
 * @version $Id: rule.class.php 14684 2011-06-11 06:32:40Z remi $
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


/**
 * Rule class store all informations about a GLPI rule :
 *   - description
 *   - criterias
 *   - actions
**/
class Rule extends CommonDBTM {

   public $dohistory = true;

   // Specific ones
   ///Actions affected to this rule
   var $actions = array();
   ///Criterias affected to this rule
   var $criterias = array();
   /// Right needed to use this rule
   var $right = 'config';
   /// Rules can be sorted ?
   var $can_sort = false;
   /// field used to order rules
   var $orderby = 'ranking';

   /// restrict matching to self::AND_MATCHING or self::OR_MATCHING : specify value to activate
   var $restrict_matching = false;

   protected $rules_id_field    = 'rules_id';
   protected $ruleactionclass   = 'RuleAction';
   protected $rulecriteriaclass = 'RuleCriteria';

   var $specific_parameters = false;

   var $regex_results     = array();
   var $criterias_results = array();

   const RULE_NOT_IN_CACHE = -1;
   const RULE_WILDCARD     = '*';

   //Generic rules engine
   const PATTERN_IS              = 0;
   const PATTERN_IS_NOT          = 1;
   const PATTERN_CONTAIN         = 2;
   const PATTERN_NOT_CONTAIN     = 3;
   const PATTERN_BEGIN           = 4;
   const PATTERN_END             = 5;
   const REGEX_MATCH             = 6;
   const REGEX_NOT_MATCH         = 7;
   const PATTERN_EXISTS          = 8;
   const PATTERN_DOES_NOT_EXISTS = 9;
   const PATTERN_FIND            = 10;

   const AND_MATCHING = "AND";
   const OR_MATCHING  = "OR";


   /**
    * Constructor
   **/
   function __construct() {
      // Temproray hack for this class
      $this->forceTable('glpi_rules');
   }


   function canCreate() {
      return haveRight($this->right, 'w');
   }


   function canView() {
      return haveRight($this->right, 'r');
   }


   function isEntityAssign() {
      return false;
   }


   function post_getEmpty () {
      $this->fields['is_active'] = 0;
   }


   /**
    * Get additional header for rule
    *
    * @param $target where to go if link needed
    *
    * @return nothing display
   **/
   function getTitleRule($target) {
   }


   /**
    * Get title used in rule
    *
    * @return Title of the rule
   **/
   function getTitle() {
      global $LANG;

      return $LANG['rulesengine'][8];
   }


   function getSearchOptions() {
      global $LANG;

      $tab = array();
      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         = 'name';
      $tab[1]['name']          = $LANG['common'][16];
      $tab[1]['datatype']      = 'itemlink';
      $tab[1]['itemlink_type'] = $this->getType();
      $tab[1]['massiveaction'] = false;

      $tab[3]['table']         = $this->getTable();
      $tab[3]['field']         = 'ranking';
      $tab[3]['name']          = $LANG['rulesengine'][10];
      $tab[3]['datatype']      = 'integer';
      $tab[3]['massiveaction'] = false;

      $tab[4]['table']         = $this->getTable();
      $tab[4]['field']         = 'description';
      $tab[4]['name']          = $LANG['joblist'][6];
      $tab[4]['datatype']      = 'string';
      $tab[4]['massiveaction'] = false;

      $tab[5]['table']         = $this->getTable();
      $tab[5]['field']         = 'match';
      $tab[5]['name']          = $LANG['rulesengine'][9];
      $tab[5]['datatype']      = 'string';
      $tab[5]['massiveaction'] = false;

      $tab[8]['table']     = $this->getTable();
      $tab[8]['field']     = 'is_active';
      $tab[8]['name']      = $LANG['common'][60];
      $tab[8]['datatype']  = 'bool';

      $tab[16]['table']     = $this->getTable();
      $tab[16]['field']     = 'comment';
      $tab[16]['name']      = $LANG['common'][25];
      $tab[16]['datatype']  = 'text';

      $tab[80]['table']         = 'glpi_entities';
      $tab[80]['field']         = 'completename';
      $tab[80]['name']          = $LANG['entity'][0];
      $tab[80]['massiveaction'] = false;

      $tab[86]['table']     = $this->getTable();
      $tab[86]['field']     = 'is_recursive';
      $tab[86]['name']      = $LANG['entity'][9];
      $tab[86]['datatype']  = 'bool';

      return $tab;
   }


   /**
    * Show the rule
    *
    * @param $ID ID of the rule
    * @param $options array
    *     - target filename : where to go when done.
    *     - withtemplate boolean : template or basic item
    *
    * @return nothing
   **/
   function showForm($ID, $options=array()) {
      global $CFG_GLPI, $LANG;

      if (!$this->isNewID($ID)) {
         $this->check($ID, 'r');
      } else {
         // Create item
         $this->checkGlobal('w');
      }

      $canedit = $this->can($this->right, "w");

      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][16]."&nbsp;:&nbsp;</td>";
      echo "<td>";
      autocompletionTextField($this, "name");
      echo "</td>";
      echo "<td>".$LANG['joblist'][6]."&nbsp;:&nbsp;</td>";
      echo "<td>";
      autocompletionTextField($this, "description");
      echo "</td></tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['rulesengine'][9]."&nbsp;:&nbsp;</td>";
      echo "<td>";
      $this->dropdownRulesMatch("match", $this->fields["match"], $this->restrict_matching);
      echo "</td>";
      echo "<td>".$LANG['common'][60]."&nbsp;:&nbsp;</td>";
      echo "<td>";
      Dropdown::showYesNo("is_active", $this->fields["is_active"]);
      echo"</td></tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][25]."&nbsp;:&nbsp;</td>";
      echo "<td class='middle' colspan='3'>";
      echo "<textarea cols='110' rows='3' name='comment' >".$this->fields["comment"]."</textarea>";

      if (!$this->isNewID($ID)) {
         echo "<br>".$LANG['common'][26]."&nbsp;:&nbsp;";
         echo ($this->fields["date_mod"] ? convDateTime($this->fields["date_mod"])
                                         : $LANG['setup'][307]);
      }
      echo"</td></tr>\n";

      if ($canedit) {
         echo "<input type='hidden' name='ranking' value='".$this->fields["ranking"]."'>";
         echo "<input type='hidden' name='sub_type' value='".get_class($this)."'>";

         if ($ID > 0) {
            if ($plugin = isPluginItemType($this->getType())) {
               $url = $CFG_GLPI["root_doc"]."/plugins/".strtolower($plugin['plugin']);
            } else {
               $url = $CFG_GLPI["root_doc"];
            }
            echo "<tr><td class='tab_bg_2 center' colspan='4'>";
            echo "<a href='#' onClick=\"var w=window.open('".$url.
                  "/front/popup.php?popup=test_rule&amp;sub_type=".$this->getType().
                  "&amp;rules_id=".$this->fields["id"]."' ,'glpipopup', 'height=400,".
                  "width=1000, top=100, left=100, scrollbars=yes' );w.focus();\">".
                  $LANG['buttons'][50]."</a>";
            echo "</td></tr>\n";
         }
      }

      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }


   /**
    * Display a dropdown with all the rule matching
    *
    * @param $name dropdown name
    * @param $value default value
    * @param $restrict may be self::AND_MATCHING or self::OR_MATCHING
    *                   to restrict to its type / false if both displayed
   **/
   function dropdownRulesMatch($name, $value='', $restrict=false) {
      global $LANG;

      if (!$restrict || $restrict == self::AND_MATCHING) {
         $elements[self::AND_MATCHING] = $LANG['choice'][3];
      }

      if (!$restrict || $restrict == self::OR_MATCHING) {
         $elements[self::OR_MATCHING]  = $LANG['choice'][2];
      }

      return Dropdown::showFromArray($name, $elements, array('value' => $value));
   }


   /**
    * Get all criterias for a given rule
    *
    * @param $ID the rule_description ID
    * @param $withcriterias 1 to retrieve all the criterias for a given rule
    * @param $withactions  1 to retrive all the actions for a given rule
   **/
   function getRuleWithCriteriasAndActions($ID, $withcriterias = 0, $withactions = 0) {

      if ($ID == "") {
         return $this->getEmpty();

      } else if ($ret=$this->getFromDB($ID)) {

         if ($withactions) {
            $RuleAction    = new $this->ruleactionclass;
            $this->actions = $RuleAction->getRuleActions($ID);
         }

         if ($withcriterias) {
            $RuleCriterias   = new $this->rulecriteriaclass;
            $this->criterias = $RuleCriterias->getRuleCriterias($ID);
         }

         return true;
      }

      return false;
   }


   /**
    * display title for action form
    *
    * @param $target where to go if action
   **/
   function getTitleAction($target) {
      global $LANG, $CFG_GLPI;

      foreach ($this->getActions() as $key => $val) {
         if (isset($val['force_actions'])
             && (in_array('regex_result',$val['force_actions'])
                 || in_array('append_regex_result',$val['force_actions']))) {

            echo "<table class='tab_cadre_fixe'>";
            echo "<tr class='tab_bg_2'><td>".$LANG['rulesengine'][83]."</td></tr>\n";
            echo "</table><br>";
            return;
         }
      }
   }


   /**
    * display title for criteria form
    *
    * @param $target where to go if action
   **/
   function getTitleCriteria($target) {
   }


   /**
    * Get maximum number of Actions of the Rule (0 = unlimited)
    *
    * @return the maximum number of actions
   **/
   function maxActionsCount() {
      // Unlimited
      return 0;
   }


   /**
    * Display all rules actions
    *
    * @param $rules_id  rule ID
    * @param $options array iof options : may be readonly
   **/
   function showActionsList($rules_id, $options=array()) {
      global $CFG_GLPI, $LANG;

      $p['readonly'] = false;

      if (is_array($options) && count($options)) {
         foreach ($options as $key => $val) {
            $p[$key] = $val;
         }
      }

      $canedit = $this->can($rules_id, "w");
      $style   = "class='tab_cadre_fixe'";

      if ($p['readonly']) {
         $canedit = false;
         $style   = "class='tab_cadre'";
      }
      $this->getTitleAction(getItemTypeFormURL(get_class($this)));

      if (($this->maxActionsCount()==0 || sizeof($this->actions) < $this->maxActionsCount())
          && $canedit) {

         echo "<form name='actionsaddform' method='post' action='".
                getItemTypeFormURL(get_class($this))."'>\n";
         $this->addActionForm($rules_id);
         echo "</form>";
      }

      if ($canedit) {
         echo "<form name='actionsform' id='actionsform' method='post' action='".
                getItemTypeFormURL(get_class($this))."'>\n";
      }
      echo "<div class='spaced'>";
      echo "<table $style>";
      echo "<tr><th colspan='".($canedit?" 4 ":"3")."'>" . $LANG['rulesengine'][7] . "</th></tr>";
      echo "<tr class='tab_bg_2'>";

      if ($canedit) {
         echo "<td>&nbsp;</td>";
      }

      echo "<td class='center b'>".$LANG['rulesengine'][12]."</td>";
      echo "<td class='center b'>".$LANG['rulesengine'][11]."</td>";
      echo "<td class='center b'>".$LANG['rulesengine'][13]."</td>";
      echo "</tr>\n";

      $nb = count($this->actions);

      foreach ($this->actions as $action) {
         $this->showMinimalActionForm($action->fields, $canedit);
      }
      echo "</table>\n";

      if ($canedit && $nb>0) {
         openArrowMassive("actionsform", true);
         echo "<input type='hidden' name='".$this->rules_id_field."' value='$rules_id'>";
         closeArrowMassive('delete_action', $LANG['buttons'][6]);
      }
      if ($canedit) {
         echo "</form>";
      }
      echo "</div>";
   }


   /**
    * Display the add action form
    *
    * @param $rules_id rule ID
   **/
   function addActionForm($rules_id) {
      global $LANG, $CFG_GLPI;

      $ra = new $this->ruleactionclass();

      echo "<div class='firstbloc'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr><th colspan='4'>" . $LANG['rulesengine'][30] . "</tr>";

      echo "<tr class='tab_bg_1 center'>";
      echo "<td>".$LANG['rulesengine'][30] . "&nbsp;:&nbsp;</td><td>";
      $val = $this->dropdownActions($ra->getAlreadyUsedForRuleID($rules_id, $this->getType()));
      echo "</td><td class='left'><span id='action_span'>\n";
      $_POST["sub_type"] = $this->getType();
      $_POST["field"]    = $val;
      include (GLPI_ROOT."/ajax/ruleaction.php");
      echo "</span></td>\n";
      echo "<td class='tab_bg_2 left' width='80px'>";
      echo "<input type='hidden' name='".$this->rules_id_field."' value='".$this->fields["id"]."'>";
      echo "<input type='submit' name='add_action' value=\"".$LANG['buttons'][8]."\" class='submit'>";
      echo "</td></tr>\n";
      echo "</table></div>";
   }


   /**
    * Display the add criteria form
    *
    * @param $rules_id rule ID
   **/
   function addCriteriaForm($rules_id) {
      global $LANG, $CFG_GLPI;

      echo "<div class='firstbloc'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr><th colspan='4'>" . $LANG['rulesengine'][16] . "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td class='center'>".$LANG['rulesengine'][16] . "&nbsp;:&nbsp;</td><td>";
      $val = $this->dropdownCriterias();
      echo "</td><td class='left'><span id='criteria_span'>\n";
      $_POST["sub_type"] = $this->getType();
      $_POST["criteria"] = $val;
      include (GLPI_ROOT."/ajax/rulecriteria.php");
      echo "</span></td>\n";
      echo "<td class='tab_bg_2' width='80px'>";
      echo "<input type='hidden' name='".$this->rules_id_field."' value='".$this->fields["id"]."'>";
      echo "<input type='submit' name='add_criteria' value=\"".$LANG['buttons'][8]."\" class='submit'>";
      echo "</td></tr>\n";
      echo "</table></div>";
   }


   /**
    * Get maximum number of criterias of the Rule (0 = unlimited)
    *
    * @return the maximum number of criterias
   **/
   function maxCriteriasCount() {
      // Unlimited
      return 0;
   }


   function maybeRecursive() {
      return false;
   }


   /**
    * Display all rules criterias
    *
    * @param $rules_id
   **/
   function showCriteriasList($rules_id) {
      global $CFG_GLPI, $LANG;

      $canedit = $this->can($rules_id, "w");
      $this->getTitleCriteria(getItemTypeFormURL(get_class($this)));

      if (($this->maxCriteriasCount()==0 || sizeof($this->criterias) < $this->maxCriteriasCount())
          && $canedit) {

         echo "<form name='criteriasaddform' method='post' action='".
                getItemTypeFormURL(get_class($this))."'>\n";
         $this->addCriteriaForm($rules_id);
         echo "</form>";
      }

      echo "<div class='spaced'>";

      echo "<form name='criteriasform' id='criteriasform' method='post' action='".
             getItemTypeFormURL(get_class($this))."'>\n";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr><th colspan='".($canedit?" 4 ":"3")."'>" . $LANG['rulesengine'][6] . "</th></tr>\n";

      echo "<tr class='tab_bg_2'>";
      if ($canedit) {
         echo "<td>&nbsp;</td>";
      }
      echo "<td class='center b'>".$LANG['rulesengine'][16]."</td>\n";
      echo "<td class='center b'>".$LANG['rulesengine'][14]."</td>\n";
      echo "<td class='center b'>".$LANG['rulesengine'][15]."</td>\n";
      echo "</tr>\n";

      $maxsize = sizeof($this->criterias);

      foreach ($this->criterias as $criteria) {
         $this->showMinimalCriteriaForm($criteria->fields, $canedit);
      }
      echo "</table>\n";

      if ($canedit && $maxsize>0) {
         openArrowMassive("criteriasform", true);
         echo "<input type='hidden' name='".$this->rules_id_field."' value='$rules_id'>";
         closeArrowMassive('delete_criteria', $LANG['buttons'][6]);
      }
      echo "</form></div>\n";
   }


   /**
    * Display the dropdown of the criterias for the rule
    *
    * @return the initial value (first)
   **/
   function dropdownCriterias() {
      global $CFG_GLPI, $LANG;

      $items = array();

      foreach ($this->getCriterias() as $ID => $crit) {
         $items[$ID] = $crit['name'];
      }
      asort($items);

      $rand   = Dropdown::showFromArray("criteria", $items);
      $params = array('criteria' => '__VALUE__',
                      'rand'     => $rand,
                      'sub_type' => $this->getType());

      ajaxUpdateItemOnSelectEvent("dropdown_criteria$rand", "criteria_span",
                                  $CFG_GLPI["root_doc"]."/ajax/rulecriteria.php", $params, false);

      if ($this->specific_parameters) {
         $itemtype = get_class($this).'Parameter';
         echo "<img alt='' title=\"".$LANG['rulesengine'][140]."\" src='".$CFG_GLPI["root_doc"].
                "/pics/add_dropdown.png' style='cursor:pointer; margin-left:2px;'
                onClick=\"var w = window.open('".getItemTypeFormURL($itemtype)."?popup=1&amp;rand=".
                $params['rand']."' ,'glpipopup', 'height=400, "."width=1000, top=100, left=100, ".
                "scrollbars=yes' );w.focus();\">";
      }

      return key($items);
   }


   /**
    * Get all ldap rules criterias from the DB and add them into the RULES_CRITERIAS
   **/
   function addSpecificCriteriasToArray(&$criterias) {
   }


   /**
    * Display the dropdown of the actions for the rule
    *
    * @param $used already used actions
    *
    * @return the initial value (first non used)
   **/
   function dropdownActions($used=array()) {
      global $CFG_GLPI;

      $actions = $this->getActions();

      // Complete used array with duplicate items
      // add duplicates of used items
      foreach ($used as $ID) {
         if (isset($actions[$ID]['duplicatewith'])) {
            $used[$actions[$ID]['duplicatewith']] = $actions[$ID]['duplicatewith'];
         }
      }

      // Parse for duplicates of already used items
      foreach ($actions as $ID => $act) {
         if (isset($actions[$ID]['duplicatewith']) && in_array($actions[$ID]['duplicatewith'],
                                                               $used)) {
            $used[$ID] = $ID;
         }
      }

      $items = array();
      $value = '';

      foreach ($actions as $ID => $act) {
         $items[$ID] = $act['name'];

         if (empty($value) && !isset($used[$ID])) {
            $value = $ID;
         }
      }
      asort($items);

      $rand   = Dropdown::showFromArray("field", $items, array('value' => $value,
                                                               'used'  => $used));
      $params = array('field'    => '__VALUE__',
                      'sub_type' => $this->getType());

      ajaxUpdateItemOnSelectEvent("dropdown_field$rand", "action_span",
                                  $CFG_GLPI["root_doc"]."/ajax/ruleaction.php", $params, false);

      return $value;
   }


   /**
    * Filter actions if needed
    *
    * @param $actions the actions array
    *
    * @return the filtered actions array
   **/
   function filterActions($actions) {
      return $actions;
   }


   /**
    * Get a criteria description by his ID
    *
    * @param $ID the criteria's ID
    *
    * @return the criteria array
   **/
   function getCriteria($ID) {

      $criterias = $this->getCriterias();
      if (isset($criterias[$ID])) {
         return $criterias[$ID];
      }
      return array();
   }


   /**
    * Get a action description by his ID
    *
    * @param $ID the action's ID
    *
    * @return the action array
   **/
   function getAction($ID) {

      $actions = $this->getActions();
      if (isset($actions[$ID])) {
         return $actions[$ID];
      }
      return array();
   }


   /**
    * Get a criteria description by his ID
    *
    * @param $ID the criteria's ID
    *
    * @return the criteria's description
   **/

   function getCriteriaName($ID) {

      $criteria = $this->getCriteria($ID);
      if (isset($criteria['name'])) {
         return $criteria['name'];
      }
      return "&nbsp;";
   }


   /**
    * Get a action description by his ID
    *
    * @param $ID the action's ID
    *
    * @return the action's description
   **/
   function getActionName($ID) {

      $action = $this->getAction($ID);
      if (isset($action['name'])) {
         return $action['name'];
      }
      return "&nbsp;";
   }


   /**
    * Process the rule
    *
    * @param $input the input data used to check criterias
    * @param $output the initial ouput array used to be manipulate by actions
    * @param $params parameters for all internal functions
    *
    * @return the output array updated by actions. If rule matched add field _rule_process to return value
   **/
   function process(&$input, &$output, &$params) {

      if (count($this->criterias)) {
         $this->regex_results     = array();
         $this->criterias_results = array();
         $input = $this->prepareInputDataForProcess($input, $params);

         if ($this->checkCriterias($input)) {
            $output = $this->executeActions($output, $params);

            //Hook
            $hook_params["sub_type"] = $this->getType();
            $hook_params["ruleid"]   = $this->fields["id"];
            $hook_params["input"]    = $input;
            $hook_params["output"]   = $output;
            doHook("rule_matched", $hook_params);
            $output["_rule_process"] = true;
            unset($output["_no_rule_matches"]);
         }
      }
   }


   /**
    * Check criterias
    *
    * @param $input the input data used to check criterias
    *
    * @return boolean if criterias match
   **/
   function checkCriterias($input) {

      reset($this->criterias);

      if ($this->fields["match"]==self::AND_MATCHING) {
         $doactions = true;

         foreach ($this->criterias as $criteria) {
            $definition_criteria = $this->getCriteria($criteria->fields['criteria']);
            if (!isset($definition_criteria['is_global']) || !$definition_criteria['is_global']) {
               $doactions &= $this->checkCriteria($criteria, $input);
               if (!$doactions) {
                  break;
               }
             }
         }

      } else { // OR MATCHING
         $doactions           = false;
         foreach ($this->criterias as $criteria) {
            $definition_criteria = $this->getCriteria($criteria->fields['criteria']);

            if (!isset($definition_criteria['is_global'])
                || !$definition_criteria['is_global']) {
               $doactions |= $this->checkCriteria($criteria,$input);
               if ($doactions) {
                  break;
               }
            }
         }
      }

      //If all simple criteria match, and if necessary, check complex criteria
      if ($doactions) {
         return $this->findWithGlobalCriteria($input);
      } else {
         return false;
      }
   }


   /**
    * Check criterias
    *
    * @param $input the input data used to check criterias
    * @param $check_results
    *
    * @return boolean if criterias match
   **/
   function testCriterias($input, &$check_results) {

      reset($this->criterias);

      foreach ($this->criterias as $criteria) {
         $result = $this->checkCriteria($criteria,$input);
         $check_results[$criteria->fields["id"]]["name"]   = $criteria->fields["criteria"];
         $check_results[$criteria->fields["id"]]["value"]  = $criteria->fields["pattern"];
         $check_results[$criteria->fields["id"]]["result"] = ((!$result)?0:1);
         $check_results[$criteria->fields["id"]]["id"]     = $criteria->fields["id"];
      }
   }


   /**
    * Process a criteria of a rule
    *
    * @param $criteria criteria to check
    * @param $input the input data used to check criterias
   **/
   function checkCriteria(&$criteria, &$input) {

      $partial_regex_result = array();

      // Undefine criteria field : set to blank
      if (!isset($input[$criteria->fields["criteria"]])) {
         $input[$criteria->fields["criteria"]] = '';
      }

      //If the value is not an array
      if (!is_array($input[$criteria->fields["criteria"]])) {
         $value = $this->getCriteriaValue($criteria->fields["criteria"],
                                          $criteria->fields["condition"],
                                          $input[$criteria->fields["criteria"]]);

         $res = RuleCriteria::match($criteria, $value, $this->criterias_results,
                                    $partial_regex_result);
      } else {
         //If the value if, in fact, an array of values
         // Negative condition : Need to match all condition (never be)
         if (in_array($criteria->fields["condition"], array(self::PATTERN_IS_NOT,
                                                            self::PATTERN_NOT_CONTAIN,
                                                            self::REGEX_NOT_MATCH,
                                                            self::PATTERN_DOES_NOT_EXISTS))) {
            $res = true;
            foreach ($input[$criteria->fields["criteria"]] as $tmp) {
               $value = $this->getCriteriaValue($criteria->fields["criteria"],
                                                $criteria->fields["condition"], $tmp);

               $res &= RuleCriteria::match($criteria, $value, $this->criterias_results,
                                           $partial_regex_result);
               if (!$res) {
                  break;
               }
            }

         // Positive condition : Need to match one
         } else {
            $res = false;
            foreach ($input[$criteria->fields["criteria"]] as $crit) {
               $value = $this->getCriteriaValue($criteria->fields["criteria"],
                                                $criteria->fields["condition"], $crit);

               $res |= RuleCriteria::match($criteria, $value, $this->criterias_results,
                                           $partial_regex_result);
            }
         }
      }

      // Found regex on this criteria
      if (count($partial_regex_result)) {
         // No regex existing : put found
         if (!count($this->regex_results)) {
            $this->regex_results = $partial_regex_result;

         } else { // Already existing regex : append found values
            $temp_result = array();
            foreach ($partial_regex_result as $new) {

               foreach ($this->regex_results as $old) {
                  $temp_result[] = array_merge($old,$new);
               }
            }
            $this->regex_results=$temp_result;
         }
      }

      return $res;
   }

   function findWithGlobalCriteria($input) {
      return true;
   }

   /**
    * Specific prepare input datas for the rule
    *
    * @param $input the input data used to check criterias
    * @param $params parameters
    *
    * @return the updated input datas
   **/
   function prepareInputDataForProcess($input, $params) {
      return $input;
   }


   /**
    * Execute the actions as defined in the rule
    *
    * @param $output the fields to manipulate
    * @param $params parameters
    *
    * @return the $output array modified
   **/
   function executeActions($output, $params) {

      if (count($this->actions)) {
         foreach ($this->actions as $action) {

            switch ($action->fields["action_type"]) {
               case "assign" :
                  $output[$action->fields["field"]] = $action->fields["value"];
                  break;

               case "regex_result" :
               case "append_regex_result" :
                  //Regex result : assign value from the regex
                  //Append regex result : append result from a regex
                  if ($action->fields["action_type"] == "append_regex_result") {
                     $res = (isset($params[$action->fields["field"]])
                             ?$params[$action->fields["field"]]:"");
                  } else {
                     $res = "";
                  }
                  $res .= RuleAction::getRegexResultById($action->fields["value"],
                                                         $this->regex_results[0]);
                  $output[$action->fields["field"]] = $res;
                  break;

               default :
                  //Each type can add his own actions
                  $output = $this->executeSpecificActions($output,$params);
                  break;
            }
         }
      }
      return $output;
   }


   function cleanDBonPurge() {
      global $DB;

      // Delete a rule and all associated criterias and actions
      $sql = "DELETE
              FROM `glpi_ruleactions`
              WHERE `".$this->rules_id_field."` = '".$this->fields['id']."'";
      $DB->query($sql);

      $sql = "DELETE
              FROM `glpi_rulecriterias`
              WHERE `".$this->rules_id_field."` = '".$this->fields['id']."'";
      $DB->query($sql);
   }


   /**
    * Show the minimal form for the rule
    *
    * @param $target link to the form page
    * @param $first is it the first rule ?
    * @param $last is it the last rule ?
    * @param $display_entities display entities / make it read only display
   **/
   function showMinimalForm($target, $first=false, $last=false, $display_entities=false) {
      global $LANG, $CFG_GLPI;

      $canedit = haveRight($this->right, "w") && !$display_entities;
      echo "<tr class='tab_bg_1'>";

      if ($canedit) {
         echo "<td width='10'>";
         $sel = "";

         if (isset ($_GET["select"]) && $_GET["select"] == "all") {
            $sel = "checked";
         }

         echo "<input type='checkbox' name='item[" . $this->fields["id"] . "]' value='1' $sel>";
         echo "</td>";

      } else {
         echo "<td>&nbsp;</td>";
      }

      echo "<td><a id='rules".$this->fields["id"]."' href=\"".str_replace(".php",".form.php",$target).
                 "?id=".$this->fields["id"]."&amp;onglet=1\">" . $this->fields["name"] . "</a> ";

      if (!empty($this->fields["comment"])) {
         showToolTip($this->fields["comment"], array('applyto' => "rules".$this->fields["id"]));
      }
      echo "</td>";
      echo "<td>".$this->fields["description"]."</td>";
      echo "<td>".Dropdown::getYesNo($this->fields["is_active"])."</td>";

      if ($display_entities) {
         echo "<td>".Dropdown::getDropdownName('glpi_entities', $this->fields['entities_id'])."</td>";
      }

      if (!$display_entities) {
         if ($this->can_sort && !$first && $canedit) {
            echo "<td><a href='".$target."?type=".$this->fields["sub_type"]."&amp;action=up&amp;id=".
                       $this->fields["id"]."'>";
            echo "<img src='".$CFG_GLPI["root_doc"]."/pics/deplier_up.png' alt=''></a></td>";

         } else {
            echo "<td>&nbsp;</td>";
         }
      }

      if (!$display_entities) {
         if ($this->can_sort && !$last && $canedit) {
            echo "<td><a href='".$target."?type=".$this->fields["sub_type"]."&amp;action=down&amp;id=".
                       $this->fields["id"]."'>";
            echo "<img src='".$CFG_GLPI["root_doc"]."/pics/deplier_down.png' alt=''></a></td>";

         } else {
            echo "<td>&nbsp;</td>";
         }
      }
      echo "</tr>\n";
   }


   function prepareInputForAdd($input) {

      // Before adding, add the ranking of the new rule
      $input["ranking"] = $this->getNextRanking();
      return $input;
   }


   /**
    * Get the next ranking for a specified rule
   **/
   function getNextRanking() {
      global $DB;

      $sql = "SELECT max(`ranking`) AS rank
              FROM `glpi_rules`
              WHERE `sub_type` = '".$this->getType()."'";
      $result = $DB->query($sql);

      if ($DB->numrows($result) > 0) {
         $datas = $DB->fetch_assoc($result);
         return $datas["rank"] + 1;
      }
      return 0;
   }


   /**
    * Show the minimal form for the action rule
    *
    * @param $fields datas used to display the action
    * @param $canedit can edit the actions rule ?
   **/
   function showMinimalActionForm($fields, $canedit) {

      echo "<tr class='tab_bg_1'>";
      if ($canedit) {
         echo "<td width='10'>";
         $sel = "";

         if (isset ($_GET["select"]) && $_GET["select"] == "all") {
            $sel = "checked";
         }

         echo "<input type='checkbox' name='item[" . $fields["id"] . "]' value='1' $sel>";
         echo "</td>";
      }
      $this->showMinimalAction($fields);
      echo "</tr>\n";
   }


   function preProcessResults($results) {
      return $results;
   }


   /**
    * Show preview result of a rule
    *
    * @param $target where to go if action
    * @param $input input data array
    * @param $params params used (see addSpecificParamsForPreview)
   **/
   function showRulePreviewResultsForm($target, $input, $params) {
      global $LANG;

      $actions       = $this->getActions();
      $check_results = array();
      $output        = array();

      //Test all criterias, without stopping at the first good one
      $this->testCriterias($input, $check_results);

      //Process the rule
      $this->process($input, $output, $params, false);

      $criteria = new $this->rulecriteriaclass;

      echo "<div class='spaced'>";
      echo "<table class='tab_cadrehov'>";
      echo "<tr><th colspan='4'>" . $LANG['rulesengine'][82] . "</th></tr>";

      echo "<tr class='tab_bg_2'>";
      echo "<td class='center b'>".$LANG['rulesengine'][16]."</td>";
      echo "<td class='center b'>".$LANG['rulesengine'][14]."</td>";
      echo "<td class='center b'>".$LANG['rulesengine'][15]."</td>";
      echo "<td class='center b'>".$LANG['rulesengine'][41]."</td>";
      echo "</tr>\n";

      foreach ($check_results as $ID=>$criteria_result) {
         echo "<tr class='tab_bg_1'>";
         $criteria->getFromDB($criteria_result["id"]);
         $this->showMinimalCriteria($criteria->fields);
         if ($criteria->fields['condition'] != self::PATTERN_FIND) {
            echo "<td class='b'>".Dropdown::getYesNo($criteria_result["result"])."</td></tr>\n";
         } else {
            echo "<td class='b'>".DROPDOWN_EMPTY_VALUE."</td></tr>\n";
         }
      }
      echo "</table></div>";

      $global_result = (isset($output["_rule_process"])?1:0);

      echo "<div class='spaced'>";
      echo "<table class='tab_cadrehov'>";
      echo "<tr><th colspan='2'>" . $LANG['rulesengine'][81] . "</th></tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td class='center b' colspan='2'>".$LANG['rulesengine'][41]."&nbsp;:&nbsp;";
      echo Dropdown::getYesNo($global_result)."</td>";

      $output = $this->preProcessPreviewResults($output);

      foreach ($output as $criteria => $value) {
         if (isset($actions[$criteria])) {
            echo "<tr class='tab_bg_2'>";
            echo "<td>".$actions[$criteria]["name"]."</td>";
            echo "<td>".$this->getActionValue($criteria,$value)."</td></tr>\n";
         }
      }

      //If a regular expression was used, and matched, display the results
      if (count($this->regex_results)) {
         echo "<tr class='tab_bg_2'>";
         echo "<td>".$LANG['rulesengine'][85]."</td>";
         echo "<td>";
         printCleanArray($this->regex_results[0]);
         echo "</td></tr>\n";
      }
      echo "</tr>\n";
      echo "</table></div>";
   }


   /**
    * Show the minimal form for the criteria rule
    *
    * @param $fields datas used to display the criteria
    * @param $canedit can edit the criterias rule ?
   **/
   function showMinimalCriteriaForm($fields, $canedit) {

      echo "<tr class='tab_bg_1'>";
      if ($canedit) {
         echo "<td width='10'>";
         $sel = "";

         if (isset ($_GET["select"]) && $_GET["select"] == "all") {
            $sel = "checked";
         }

         echo "<input type='checkbox' name='item[" . $fields["id"] . "]' value='1' $sel>";
         echo "</td>";
      }

      $this->showMinimalCriteria($fields);
      echo "</tr>\n";
   }


   /**
    * Show the minimal infos for the criteria rule
    *
    * @param $fields datas used to display the criteria
   **/
   function showMinimalCriteria($fields) {
      echo $this->getMinimalCriteriaText($fields);
   }


   function getMinimalCriteriaText($fields) {

      $text  = "<td>" . $this->getCriteriaName($fields["criteria"]) . "</td>";
      $text .= "<td>" . RuleCriteria::getConditionByID($fields["condition"], get_class($this))."</td>";
      $text .= "<td>" . $this->getCriteriaDisplayPattern($fields["criteria"], $fields["condition"],
                                                         $fields["pattern"]) . "</td>";
      return $text;
   }


   /**
    * Show the minimal infos for the action rule
    *
    * @param $fields datas used to display the action
   **/
   function showMinimalAction($fields) {
      echo $this->getMinimalActionText($fields);
   }


   function getMinimalActionText($fields) {

      $text  = "<td>" . $this->getActionName($fields["field"]) . "</td>";
      $text .= "<td>" . RuleAction::getActionByID($fields["action_type"]) . "</td>";
      $text .= "<td>" . stripslashes($this->getActionValue($fields["field"],
                                                           $fields["value"])) . "</td>";
      return $text;
   }


   /**
    * Return a value associated with a pattern associated to a criteria to display it
    *
    * @param $ID the given criteria
    * @param $condition condition used
    * @param $pattern the pattern
   **/
   function getCriteriaDisplayPattern($ID, $condition, $pattern) {
      global $LANG;

      if ($condition == self::PATTERN_EXISTS
          || $condition == self::PATTERN_DOES_NOT_EXISTS
          || $condition == self::PATTERN_FIND) {
          return $LANG['choice'][1];

      } else if ($condition==self::PATTERN_IS || $condition==self::PATTERN_IS_NOT) {
         $crit = $this->getCriteria($ID);

         if (isset($crit['type'])) {
            switch ($crit['type']) {
               case "yesonly" :
               case "yesno" :
                  return Dropdown::getYesNo($pattern);

               case "dropdown" :
                  $addentity = "";
                  if ($this->isEntityAssign()) {
                     $itemtype = getItemTypeForTable($crit["table"]);
                     $item     = new $itemtype();
                     if ($item->isEntityAssign() && $item->getFromDB($pattern)) {
                        $addentity = '&nbsp;('.Dropdown::getDropdownName('glpi_entities',
                                                                         $item->getEntityID()).')';
                     }
                  }
                  return Dropdown::getDropdownName($crit["table"], $pattern).$addentity;

               case "dropdown_users" :
                  return getUserName($pattern);

               case "dropdown_tracking_itemtype" :
                  if (class_exists($pattern)) {
                     $item = new $pattern();
                     return $item->getTypeName();
                  }
                  if (empty($pattern)) {
                     return $LANG['help'][30];
                  }
                  break;

               case "dropdown_priority" :
                  return Ticket::getPriorityName($pattern);

               case "dropdown_urgency" :
                  return Ticket::getUrgencyName($pattern);

               case "dropdown_impact" :
                  return Ticket::getImpactName($pattern);

               case "dropdown_tickettype" :
                  return Ticket::getTicketTypeName($pattern);
            }
         }
      }
      if ($result = $this->getAdditionalCriteriaDisplayPattern($ID, $condition, $pattern)) {
         return $result;
      }
      return $pattern;
   }


   /**
    * Used to get specific criteria patterns
    * @param $ID the given criteria
    * @param $condition condition used
    * @param $pattern the pattern
    *
    * @return a value associated with the criteria, or false otherwise
   **/
   function getAdditionalCriteriaDisplayPattern($ID, $condition, $pattern) {
      return false;
   }


   /**
    * Display item used to select a pattern for a criteria
    *
    * @param $name criteria name
    * @param $ID the given criteria
    * @param $condition condition used
    * @param $value the pattern
    * @param $test Is to test rule ?
   **/
   function displayCriteriaSelectPattern($name, $ID, $condition, $value="", $test=false) {

      $crit    = $this->getCriteria($ID);
      $display = false;
      $tested  = false;

      if (isset($crit['type'])
          && ($test || $condition == self::PATTERN_IS || $condition == self::PATTERN_IS_NOT)) {

         switch ($crit['type']) {
            case "yesonly" :
               Dropdown::showYesNo($name, $crit['table'], 0);
               $display = true;
               break;

            case "yesno" :
               Dropdown::showYesNo($name, $crit['table']);
               $display = true;
               break;

            case "dropdown" :
               Dropdown::show(getItemTypeForTable($crit['table']), array('name'  => $name,
                                                                         'value' => $value));
               $display = true;
               break;

            case "dropdown_users" :
               User::dropdown(array('value'  => $value,
                                    'name'   => $name,
                                    'right'  => 'all'));
               $display = true;
               break;

            case "dropdown_tracking_itemtype" :
               Dropdown::dropdownTypes($name, 0 ,array_keys(Ticket::getAllTypesForHelpdesk()));
               $display = true;
               break;

            case "dropdown_urgency" :
               Ticket::dropdownUrgency($name, $value);
               $display = true;
               break;

            case "dropdown_impact" :
               Ticket::dropdownImpact($name, $value);
               $display = true;
               break;

            case "dropdown_priority" :
               Ticket::dropdownPriority($name, $value);
               $display = true;
               break;

            case "dropdown_tickettype" :
               Ticket::dropdownType($name, $value);
               $display = true;
               break;
         }
         $tested = true;
      }
      //Not a standard condition
      if (!$tested) {
        $display = $this->displayAdditionalRuleCondition($condition, $crit, $name, $value, $test);
      }

      if ($condition == self::PATTERN_EXISTS || $condition == self::PATTERN_DOES_NOT_EXISTS) {
         echo "<input type='hidden' name='$name' value='1'>";
         $display = true;
      }

      if (!$display) {
         $rc = new $this->rulecriteriaclass();
         autocompletionTextField($rc, "pattern", array('name'  => $name,
                                                       'value' => $value,
                                                       'size'  => 70));
      }
   }


   /**
    * Return a value associated with a pattern associated to a criteria
    *
    * @param $ID the given action
    * @param $value the value
   **/
   function getActionValue($ID, $value) {
      global $LANG;

      $action = $this->getAction($ID);
      if (isset($action['type'])) {

         switch ($action['type']) {
            case "dropdown" :
               return Dropdown::getDropdownName($action["table"], $value);

            case "dropdown_status" :
               return Ticket::getStatus($value);

            case "dropdown_assign" :
            case "dropdown_users" :
            case "dropdown_users_validate" :
               return getUserName($value);

            case "yesonly" :
            case "yesno" :
               if ($value) {
                  return $LANG['choice'][1];
               }
               return $LANG['choice'][0];

            case "dropdown_urgency" :
               return Ticket::getUrgencyName($value);

            case "dropdown_impact" :
               return Ticket::getImpactName($value);

            case "dropdown_priority" :
               return Ticket::getPriorityName($value);

            case "dropdown_management" :
               return Dropdown::getGlobalSwitch($value);

            default :
               return $this->displayAdditionRuleActionValue($value);
         }
      }

      return $value;
   }


   /**
    * Return a value associated with a pattern associated to a criteria to display it
    *
    * @param $ID the given criteria
    * @param $condition condition used
    * @param $value the pattern
   **/
   function getCriteriaValue($ID, $condition, $value) {
      global $LANG;

      if ($condition!=self::PATTERN_IS && $condition!=self::PATTERN_IS_NOT) {
         $crit = $this->getCriteria($ID);
         if (isset($crit['type'])) {

            switch ($crit['type']) {
               case "dropdown" :
                  return Dropdown::getDropdownName($crit["table"], $value);

               case "dropdown_assign" :
               case "dropdown_users" :
                  return getUserName($value);

               case "yesonly" :
               case "yesonly" :
               case "yesno"  :
                  if ($value) {
                     return $LANG['choice'][1];
                  }
                  return $LANG['choice'][0];

               case "dropdown_impact" :
                  return Ticket::getImpactName($value);

               case "dropdown_urgency" :
                  return Ticket::getUrgencyName($value);

               case "dropdown_priority" :
                  return Ticket::getPriorityName($value);
            }
         }
      }
      return $value;
   }


   /**
    * Function used to display type specific criterias during rule's preview
    *
    * @param $fields fields values
   **/
   function showSpecificCriteriasForPreview($fields) {
   }


   /**
    * Function used to add specific params before rule processing
    *
    * @param $params parameters
   **/
   function addSpecificParamsForPreview($params) {
      return $params;
   }


   /**
    * Criteria form used to preview rule
    *
    * @param $target target of the form
    * @param $rules_id ID of the rule
   **/
   function showRulePreviewCriteriasForm($target, $rules_id) {
      global $DB, $LANG;

      $criterias = $this->getCriterias();

      if ($this->getRuleWithCriteriasAndActions($rules_id,1,0)) {
         echo "<form name='testrule_form' id='testrule_form' method='post' action='$target'>\n";
         echo "<div class='spaced'>";
         echo "<table class='tab_cadre'>";
         echo "<tr><th colspan='3'>" . $LANG['rulesengine'][6] . "</th></tr>";

         $type_match = ($this->fields["match"]==self::AND_MATCHING
                        ?$LANG['choice'][3]:$LANG['choice'][2]);
         $already_displayed = array();
         $first = true;

         //Brower all criterias
         foreach ($this->criterias as $criteria) {

            //Look for the criteria in the field of already displayed criteria :
            //if present, don't display it again
            if (!in_array($criteria->fields["criteria"],$already_displayed)) {
               $already_displayed[] = $criteria->fields["criteria"];
               echo "<tr class='tab_bg_1'>";
               echo "<td>";

               if ($first) {
                  echo "&nbsp;";
                  $first = false;
               } else {
                  echo $type_match;
               }

               echo "</td>";
               $criteria_constants = $criterias[$criteria->fields["criteria"]];
               echo "<td>".$criteria_constants["name"]."&nbsp;:&nbsp;</td>";
               echo "<td>";
               $value = "";
               if (isset($_POST[$criteria->fields["criteria"]])) {
                  $value = $_POST[$criteria->fields["criteria"]];
               }

               $this->displayCriteriaSelectPattern($criteria->fields['criteria'],
                                                   $criteria->fields['criteria'],
                                                   $criteria->fields['condition'], $value, true);
               echo "</td></tr>\n";
            }
         }
         $this->showSpecificCriteriasForPreview($_POST);

         echo "<tr><td class='tab_bg_2 center' colspan='3'>";
         echo "<input type='submit' name='test_rule' value=\"".$LANG['buttons'][50]."\"
                class='submit'>";
         echo "<input type='hidden' name='".$this->rules_id_field."' value='$rules_id'>";
         echo "<input type='hidden' name='sub_type' value='" . $this->getType() . "'>";
         echo "</td></tr>\n";
         echo "</table></div></form>\n";
      }
   }


   function preProcessPreviewResults($output) {
      return $output;
   }


   /**
    * Dropdown rules for a defined sub_type of rule
    *
    * Parameters which could be used in options array :
    *    - name : string / name of the select (default is depending itemtype)
    *    - sub_type : integer / sub_type of rule
    *
    * @param $options possible options
   **/
   static function dropdown($options=array()) {
      global $DB, $CFG_GLPI, $LANG;

      $p['sub_type']        = '';
      $p['name']            = 'rules_id';
      $p['entity_restrict'] = '';

      if (is_array($options) && count($options)) {
         foreach ($options as $key => $val) {
            $p[$key] = $val;
         }
      }

      if ($p['sub_type'] == '') {
         return false;
      }

      $rand         = mt_rand();
      $limit_length = $_SESSION["glpidropdown_chars_limit"];
      $use_ajax     = false;

      if ($CFG_GLPI["use_ajax"]) {
         $nb = countElementsInTable("glpi_rules", "`sub_type`='".$p['sub_type']."'");

         if ($nb>$CFG_GLPI["ajax_limit_count"]) {
            $use_ajax = true;
         }
      }

      $params = array('searchText'      => '__VALUE__',
                      'myname'          => $p['name'],
                      'limit'           => $limit_length,
                      'rand'            => $rand,
                      'type'            => $p['sub_type'],
                      'entity_restrict' => $p['entity_restrict']);

      $default  = "<select name='".$p['name']."' id='dropdown_".$p['name'].$rand."'>";
      $default .= "<option value='0'>".DROPDOWN_EMPTY_VALUE."</option></select>";
      ajaxDropdown($use_ajax, "/ajax/dropdownRules.php", $params, $default, $rand);

      return $rand;
   }


   function getCriterias() {
      return array();
   }


   function getActions() {
      return array();
   }


   static function getActionsByType($sub_type) {

      if (class_exists($sub_type)) {
         $rule = new $sub_type();
         return $rule->getActions();
      }
      return array();
   }


   /**
    * Return all rules from database
    *
    * @param $crit array of criteria (at least, 'field' and 'value')
    *
    * @return array of Rule objects
   **/
   function getRulesForCriteria($crit) {
      global $DB;

      $rules = array();

      //Get all the rules whose sub_type is $sub_type and entity is $ID
      $query = "SELECT `glpi_rules`.`id`
                FROM `glpi_ruleactions`,
                     `glpi_rules`
                WHERE `glpi_ruleactions`.".$this->rules_id_field." = `glpi_rules`.`id`
                      AND `glpi_rules`.`sub_type` = '".get_class($this)."'";

      foreach ($crit as $field => $value) {
         $query .= " AND `glpi_ruleactions`.`$field` = '$value'";
      }

      foreach ($DB->request($query) as $rule) {
         $affect_rule = new Rule;
         $affect_rule->getRuleWithCriteriasAndActions($rule["id"], 0, 1);
         $rules[] = $affect_rule;
      }
      return $rules;
   }


   function showNewRuleForm($ID) {
      global $LANG;

      echo "<form method='post' action='".getItemTypeFormURL('Entity')."'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr><th colspan='2'>" . $this->getTitle() . "</th></tr>\n";
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][16] . "&nbsp;:&nbsp;";
      autocompletionTextField($this, "name", array('value' => '',
                                                   'size'  => 33));
      echo "&nbsp;&nbsp;&nbsp;".$LANG['joblist'][6] . "&nbsp;:&nbsp;";
      autocompletionTextField($this, "description", array('value' => '',
                                                          'size'  => 33));
      echo "&nbsp;&nbsp;&nbsp;".$LANG['rulesengine'][9] . "&nbsp;:&nbsp;";
      $this->dropdownRulesMatch("match", self::AND_MATCHING);
      echo "</td><td class='tab_bg_2 center'>";
      echo "<input type=hidden name='sub_type' value='".get_class($this)."'>";
      echo "<input type=hidden name='entities_id' value='-1'>";
      echo "<input type=hidden name='affectentity' value='$ID'>";
      echo "<input type=hidden name='_method' value='addRule'>";
      echo "<input type='submit' name='execute' value=\"".$LANG['buttons'][8]."\" class='submit'>";
      echo "</td></tr>\n";
      echo "</table></form>";
   }


   function showAndAddRuleForm($item) {
      global $LANG;

      $canedit = haveRight($this->right, "w");

      if ($canedit && $item->getType()=='Entity') {
         $this->showNewRuleForm($item->getField('id'));
      }

         //Get all rules and actions
      $crit = array('field' => getForeignKeyFieldForTable($item->getTable()),
                    'value' => $item->getField('id'));

      $rules = $this->getRulesForCriteria($crit);

      echo "<div class='spaced'>";

      if (empty ($rules)) {
         echo "<table class='tab_cadre_fixehov'>";
         echo "<tr><th>" . $LANG['search'][15] . "</th>";
         echo "</tr>\n";
         echo "</table>\n";

      } else {
         if ($canedit) {
            $formname = $item->getType()."_".$this->getType()."_form";
            echo "\n<form name='$formname' id='$formname' method='post' ".
                   "action='".getItemTypeSearchURL(get_class($this))."'>";
         }
         echo "<table class='tab_cadre_fixehov'><tr>";

         if ($canedit) {
            echo "<th></th>";
         }
         echo "<th>" . $this->getTitle() . "</th>";
         echo "<th>" . $LANG['joblist'][6] . "</th>";
         echo "<th>" . $LANG['common'][60] . "</th>";
         echo "</tr>\n";
         initNavigateListItems(get_class($this), $item->getTypeName()."=".$item->getName());

         foreach ($rules as $rule) {
            addToNavigateListItems(get_class($this), $rule->fields["id"]);
            echo "<tr class='tab_bg_1'>";

            if ($canedit) {
               echo "<td width='10'>";
               echo "<input type='checkbox' name='item[" . $rule->fields["id"] . "]' value='1'>";
               echo "</td>";
               echo "<td><a href='".getItemTypeFormURL(get_class($this))."?id=" .
                      $rule->fields["id"] . "&amp;onglet=1'>" .$rule->fields["name"] ."</a></td>";

            } else {
               echo "<td>" . $rule->fields["name"] . "</td>";
            }

            echo "<td>" . $rule->fields["description"] . "</td>";
            echo "<td>" . Dropdown::getYesNo($rule->fields["is_active"]) . "</td>";
            echo "</tr>\n";
         }
         echo "</table>\n";

         if ($canedit) {
            openArrowMassive($formname, true);
            echo "<input type='hidden' name='action' value='delete'>";
            closeArrowMassive('massiveaction', $LANG['buttons'][6]);
            echo "</form>";
         }
      }
      echo "</div>";
   }


   function defineTabs($options=array()) {
      global $LANG;

      $ong[1] = $LANG['title'][26];

      if ($this->fields['id'] > 0) {
         $ong[12] = $LANG['title'][38];
      }
      return $ong;
   }


   /**
    * Add more criteria specific to this type of rule
   **/
   static function addMoreCriteria($criterion = '') {
      return array();
   }


   /**
    * Add more actions specific to this type of rule
   **/
   function displayAdditionRuleActionValue($value) {
      return $value;
   }


   /**
    * Method for each type to manage his own actions
    *
    * @param output the rule's execution actions
    * @param params additional parameters that may be used
    *
    * @return the rule's execution array modified
   **/
   function executeSpecificActions($output, $params) {
      return $output;
   }


   /**
    *
   **/
   function displayAdditionalRuleCondition($condition, $criteria, $name, $value, $test=false) {
      return false;
   }


   function displayAdditionalRuleAction($action,$params = array()) {
      return true;
   }


   /**
    * Clean Rule with Action is assign to an item
    *
    * @param $item Object
    */
   static function cleanForItemAction($item) {
      global $DB, $LANG;

      $query = "SELECT `rules_id`
                FROM `glpi_ruleactions`
                WHERE `value` = '".$item->getField('id')."'
                      AND `field` = '".getForeignKeyFieldForTable($item->getTable())."'";

      if ($result = $DB->query($query)) {
         if ($DB->numrows($result)>0) {
            $rule = new self();
            $input['is_active'] = 0;

            while ($data = $DB->fetch_array($result)) {
               $input['id'] = $data['rules_id'];
               $rule->update($input);
            }
            addMessageAfterRedirect($LANG['rulesengine'][150]);
         }
      }
   }

}

?>
