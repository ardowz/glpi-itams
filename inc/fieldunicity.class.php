<?php
/*
 * @version $Id: fieldunicity.class.php 14684 2011-06-11 06:32:40Z remi $
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

/// Class FieldUnicity
class FieldUnicity extends CommonDropdown {

   // From CommonDBTM
   public $dohistory = true;

   var $second_level_menu = "control";


   static function getTypeName() {
      global $LANG;

      return $LANG['setup'][811];
   }


   function canCreate() {
      return haveRight('config', 'w');
   }


   function canView() {
      return haveRight('config', 'r');
   }


   function getAdditionalFields() {
      global $LANG;

      return array(array('name'  => 'is_active',
                         'label' => $LANG['common'][60],
                         'type'  => 'bool'),
                   array('name'  => 'itemtype',
                         'label' => $LANG['common'][17],
                         'type'  => 'unicity_itemtype'),
                   array('name'  => 'fields',
                         'label' => $LANG['setup'][815],
                         'type'  => 'unicity_fields'),
                   array('name'  => 'action_refuse',
                         'label' => $LANG['setup'][821],
                         'type'  => 'bool'),
                   array('name'  => 'action_notify',
                         'label' => $LANG['setup'][822],
                         'type'  => 'bool'));
   }


   /**
    * Add more tabs to display
    *
    * @param $options array
   **/
   function defineMoreTabs($options=array()) {
      global $LANG;

      $ong = array();
      $ong[12] = $LANG['title'][38];
      $ong[2]  = $LANG['setup'][826];
      return $ong;
   }


   /**
    * Display more tabs
    *
    * @param $tab
   **/
   function displayMoreTabs($tab) {

      switch ($tab) {
         case 2:
            self::showDoubles($this);
            break;

         case 12 :
            Log::showForItem($this);
            break;

         case -1 :
            self::showDoubles($this);
            Log::showForItem($this);
            break;
      }
   }


   /**
    * Display specific fields for FieldUnicity
    *
    * @param $ID
    * @param $field array
   **/
   function displaySpecificTypeField($ID, $field=array()) {

      switch ($field['type']) {
         case 'unicity_itemtype' :
            $this->showItemtype($ID, $this->fields['itemtype']);
            break;

         case 'unicity_fields' :
            self::selectCriterias($this);
            break;
      }
   }


   /**
    * Display a dropdown which contains all the available itemtypes
    *
    * @param ID the field unicity item id
    * @param value the selected value
    *
    * @return nothing
   **/
   function showItemtype($ID, $value=0) {
      global $CFG_GLPI;

      //Criteria already added : only display the selected itemtype
      if ($ID > 0) {
          $item = new $this->fields['itemtype'];
          echo $item->getTypeName();
          echo "<input type='hidden' name='itemtype' value='".$this->fields['itemtype']."'";

      } else {
         //Add criteria : display dropdown
         $options[0] = DROPDOWN_EMPTY_VALUE;
         foreach ($CFG_GLPI['unicity_types'] as $itemtype) {
            if (class_exists($itemtype)) {
               $item = new $itemtype();
               if ($item->can(-1,'r')) {
                  $options[$itemtype] = $item->getTypeName($itemtype);
               }
            }
         }
         asort($options);
         $rand = Dropdown::showFromArray('itemtype', $options);

         $params = array('itemtype' => '__VALUE__',
                         'id'       => $ID);
         ajaxUpdateItemOnSelectEvent("dropdown_itemtype$rand", "span_fields",
                                     $CFG_GLPI["root_doc"]."/ajax/dropdownUnicityFields.php",
                                     $params);
      }

   }


   /**
    * Return criteria unicity for an itemtype, in an entity
    *
    * @param itemtype the itemtype for which unicity must be checked
    * @param entities_id the entity for which configuration must be retrivied
    * @param $check_active
    *
    * @return an array of fields to check, or an empty array if no
   **/
   public static function getUnicityFieldsConfig($itemtype, $entities_id=0, $check_active=true) {
      global $DB;

      //Get the first active configuration for this itemtype
      $query = "SELECT *
                FROM `glpi_fieldunicities`
                WHERE `itemtype` = '$itemtype' ".
                      getEntitiesRestrictRequest("AND", 'glpi_fieldunicities', "", $entities_id,
                                                 true);

      if ($check_active) {
         $query .= " AND `is_active` = '1' ";
      }

      $query .= "ORDER BY `entities_id` DESC";

      $current_entity = false;
      $return         = array();
      foreach ($DB->request($query) as $data) {
         //First row processed
         if (!$current_entity) {
            $current_entity = $data['entities_id'];
         }
         //Process only for one entity, not more
         if ($current_entity != $data['entities_id']) {
            break;
         }
         $return[] = $data;
      }
      return $return;
   }


   /**
    * Display a list of available fields for unicity checks
    *
    * @param $unicity an instance of CommonDBTM class
    *
    * @return nothing
   **/
   static function selectCriterias(CommonDBTM $unicity) {
      global $DB;

      //Do not check unicity on fields in DB with theses types
      $blacklisted_types = array('longtext', 'text');

      echo "<span id='span_fields' name='span_fields'>";

      if (!isset($unicity->fields['itemtype']) || !$unicity->fields['itemtype']) {
         echo  "</span>";
         return;
      }

      if (!isset($unicity->fields['entities_id'])) {
         $unicity->fields['entities_id'] = $_SESSION['glpiactive_entity'];
      }

      $unicity_fields = explode(',', $unicity->fields['fields']);
      //Search option for this type
      $target = new $unicity->fields['itemtype'];

      //Construct list
      echo "<span id='span_fields' name='span_fields'>";
      echo "<select name='_fields[]' multiple size='15' style='width:400px'>";

      foreach ($DB->list_fields(getTableForItemType($unicity->fields['itemtype'])) as $field) {
         $searchOption = $target->getSearchOptionByField('field', $field['Field']);

         if (empty($searchOption)) {
            if ($table = getTableNameForForeignKeyField($field['Field'])) {
               $searchOption = $target->getSearchOptionByField('field', 'name', $table);
            }
         }

         if (!empty($searchOption)
             && !in_array($field['Type'],$blacklisted_types)
             && !in_array($field['Field'],$target->getUnallowedFieldsForUnicity())) {

            echo "<option value='".$field['Field']."'";
            if (isset($unicity_fields) && in_array($field['Field'],$unicity_fields)) {
               echo " selected ";
            }
            echo  ">".$searchOption['name']."</option>";
         }
      }

      echo "</select></span>";
   }


   function getSearchOptions() {
      global $LANG;

      $tab = array();
      $tab['common'] = $LANG['setup'][811];

      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         = 'name';
      $tab[1]['name']          = $LANG['common'][16];
      $tab[1]['datatype']      = 'itemlink';
      $tab[1]['itemlink_type'] = $this->getType();
      $tab[1]['massiveaction'] = false;

      $tab[2]['table']         = $this->getTable();
      $tab[2]['field']         = 'id';
      $tab[2]['name']          = $LANG['common'][2];
      $tab[2]['massiveaction'] = false;

      $tab[3]['table']         = $this->getTable();
      $tab[3]['field']         = 'fields';
      $tab[3]['name']          = $LANG['setup'][815];
      $tab[3]['massiveaction'] = false;

      $tab[4]['table']         = $this->getTable();
      $tab[4]['field']         = 'itemtype';
      $tab[4]['name']          = $LANG['common'][17];
      $tab[4]['massiveaction'] = false;
      $tab[4]['datatype']      = 'itemtypename';
      $tab[4]['forcegroupby']  = true;

      $tab[5]['table']         = $this->getTable();
      $tab[5]['field']         = 'action_refuse';
      $tab[5]['name']          = $LANG['setup'][821];
      $tab[5]['datatype']      = 'bool';

      $tab[6]['table']         = $this->getTable();
      $tab[6]['field']         = 'action_notify';
      $tab[6]['name']          = $LANG['setup'][822];
      $tab[6]['datatype']      = 'bool';

      $tab[86]['table']    = $this->getTable();
      $tab[86]['field']    = 'is_recursive';
      $tab[86]['name']     = $LANG['entity'][9];
      $tab[86]['datatype'] = 'bool';

      $tab[16]['table']    = $this->getTable();
      $tab[16]['field']    = 'comment';
      $tab[16]['name']     = $LANG['common'][25];
      $tab[16]['datatype'] = 'text';

      $tab[30]['table']          = $this->getTable();
      $tab[30]['field']          = 'is_active';
      $tab[30]['name']           = $LANG['common'][60];
      $tab[30]['datatype']       = 'bool';
      $tab[30]['massiveaction']  = false;

      $tab[80]['table']        = 'glpi_entities';
      $tab[80]['field']        = 'completename';
      $tab[80]['name']         = $LANG['entity'][0];
      $tab[80]['forcegroupby'] = true;

      return $tab;
   }


   /**
    * Perform checks to be sure that an itemtype and at least a field are selected
    *
    * @param input the values to insert in DB
    *
    * @return input the values to insert, but modified
   **/
   static function checkBeforeInsert($input) {
      global $LANG;

      if (!$input['itemtype'] || empty($input['_fields'])) {
         addMessageAfterRedirect($LANG['setup'][817], true, ERROR);
         $input = array();

      } else {
         $input['fields'] = implode(',',$input['_fields']);
         unset($input['_fields']);
      }
      return $input;
   }


   function prepareInputForAdd($input) {
      return self::checkBeforeInsert($input);
   }


   function prepareInputForUpdate($input) {
      return $input;
   }


   /**
    * Delete all criterias for an itemtype
    *
    * @param itemtype
    *
    * @return nothing
   **/
   static function deleteForItemtype($itemtype) {
      global $DB;

      $query = "DELETE
                FROM `glpi_fieldunicities`
                WHERE `itemtype` LIKE '%Plugin$itemtype%'";
      $DB->query($query);
   }


   /**
    * List doubles
    *
    * @param $unicity an instance of FieldUnicity class
   **/
   static function showDoubles(FieldUnicity $unicity) {
      global $LANG, $DB;


      $fields       = array();
      $where_fields = array();
      $item         = new $unicity->fields['itemtype'];
      foreach (explode(',',$unicity->fields['fields']) as $field) {
         $fields[]       = $field;
         $where_fields[] = $field;
      }

      if (!empty($fields)) {
         $colspan = count($fields) + 1;
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr><th colspan='".$colspan."'>".$LANG['setup'][826]."</th></tr>";

         $entities = array($unicity->fields['entities_id']);
         if ($unicity->fields['is_recursive']) {
            $entities = getSonsOf('glpi_entities', $unicity->fields['entities_id']);
         }
         $fields_string = implode(',', $fields);

         if ($item->maybeTemplate()) {
            $where_template = " AND `".$item->getTable()."`.`is_template` = '0'";
         } else {
            $where_template = "";
         }

         $where_fields_string = implode(',', $where_fields);
         $query = "SELECT $fields_string,
                          COUNT(*) AS cpt
                   FROM `".$item->getTable()."`
                   WHERE `".$item->getTable()."`.`entities_id` IN (".implode(',',$entities).")
                        $where_template 
                   GROUP BY $fields_string
                   ORDER BY cpt DESC";
         $results = array();
         foreach ($DB->request($query) as $data) {
            if ($data['cpt'] > 1) {
               $results[] = $data;

            }
         }

         if (empty($results)) {
            echo "<tr><td class='center' colspan='$colspan'>".$LANG['stats'][2]."</td></tr>";
         } else {
            echo "<tr>";
            foreach ($fields as $field) {
               $searchOption = $item->getSearchOptionByField('field',$field);
               echo "<th>".$searchOption["name"]."</th>";
            }
            echo "<th>".$LANG['tracking'][29]."</th></tr>";

            foreach ($results as $result) {
               echo "<tr>";
               foreach ($fields as $field) {
                  echo "<td>".$result[$field]."</td>";
               }
               echo "<td>".$result['cpt']."</td></tr>";
            }
         }

      } else {
         echo "<tr><td class='center' colspan='$colspan'>".$LANG['stats'][2]."</td></tr>";
      }
      echo "</table>";
   }


   /**
    * Display debug information for current object
   **/
   function showDebug() {

      $params = array('message'     => '',
                      'action_type' => true,
                      'action_user' => getUserName(getLoginUserID()),
                      'entities_id' => $_SESSION['glpiactive_entity'],
                      'itemtype'    => get_class($this),
                      'date'        => $_SESSION['glpi_currenttime'],
                      'refuse'      => true);

      NotificationEvent::debugEvent($this, $params);
   }

}
?>
