<?php
/*
 * @version $Id: commondbtm.class.php 14705 2011-06-17 18:13:23Z walid $
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
 *  Common DataBase Table Manager Class - Persistent Object
 */
class CommonDBTM extends CommonGLPI {

   /// Data of the Item
   var $fields = array();
   /// Make an history of the changes
   var $dohistory = false;
   /// Black list fields for history log or date mod update
   var $history_blacklist = array();
   /// Set false to desactivate automatic message on action
   var $auto_message_on_action = true;

   /// Set true to desactivate link generation because form page do not permit show/edit item
   var $no_form_page = false;

   /// Set true to desactivate auto compute table name
   var $notable = false;

   //Additional foeidls for dictionnary processing
   var $additional_fields_for_dictionnary = array();

   /// Forward entity datas to linked items
   protected $forward_entity_to = array();
   /// Table name cache : set dynamically calling getTable
   protected $table = "";
   /// Foreign key field cache : set dynamically calling getForeignKeyField
   protected $fkfield = "";


   const SUCCESS                    = 0; //Process is OK
   const TYPE_MISMATCH              = 1; //Type is not good, value cannot be inserted
   const ERROR_FIELDSIZE_EXCEEDED   = 2; //Value is bigger than the field's size
   const HAS_DUPLICATE              = 3; //Can insert or update because it's duplicating another item
   const NOTHING_TO_DO              = 4; //Nothing to insert or update


   /**
    * Constructor
   **/
   function __construct () {
   }


   /**
    * Return the table used to stor this object
    *
    * @return string
   **/
   function getTable() {

      if (empty($this->table) && !$this->notable) {
         $this->table = getTableForItemType($this->getType());
      }

      return $this->table;
   }


   /**
    * force table value (used for config management for old versions)
    *
    * @return nothing
   **/
   function forceTable($table) {
      $this->table = $table;
   }


   function getForeignKeyField() {

      if (empty($this->fkfield)) {
         $this->fkfield = getForeignKeyFieldForTable($this->getTable());
      }

      return $this->fkfield;
   }


   /**
    * Retrieve an item from the database
    *
    * @param $ID ID of the item to get
    *
    * @return true if succeed else false
   **/
   function getFromDB ($ID) {
      global $DB;
      // Make new database object and fill variables

      // != 0 because 0 is consider as empty
      if (strlen($ID)==0) {
         return false;
      }

      $query = "SELECT *
                FROM `".$this->getTable()."`
                WHERE `".$this->getIndexName()."` = '$ID'";

      if ($result = $DB->query($query)) {
         if ($DB->numrows($result)==1) {
            $this->fields = $DB->fetch_assoc($result);
            $this->post_getFromDB();

            return true;
         }
      }

      return false;
   }


   /**
    * Get the identifier of the current item
    *
    * @return ID
   **/
   function getID() {

      if (isset($this->fields[$this->getIndexName()])) {
         return $this->fields[$this->getIndexName()];
      }
      return -1;
   }


   /**
    * Actions done at the end of the getFromDB function
    *
    * @return nothing
   **/
   function post_getFromDB () {
   }


   /**
    * Retrieve all items from the database
    *
    * @param $condition condition used to search if needed (empty get all)
    * @param $order order field if needed
    * @param $limit limit retrieved datas if needed
    *
    * @return true if succeed else false
   **/
   function find ($condition="", $order="", $limit="") {
      global $DB;
      // Make new database object and fill variables

      $query = "SELECT *
                FROM `".$this->getTable()."`";

      if (!empty($condition)) {
         $query .= " WHERE $condition";
      }

      if (!empty($order)) {
         $query .= " ORDER BY $order";
      }

      if (!empty($limit)) {
         $query .= " LIMIT ".intval($limit);
      }

      $data = array();
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result)) {
            while ($line = $DB->fetch_assoc($result)) {
               $data[$line['id']] = $line;
            }
         }
      }

      return $data;
   }


   /**
    * Get the name of the index field
    *
    * @return name of the index field
   **/
   function getIndexName() {
      return "id";
   }


   /**
    * Get an empty item
    *
    *@return true if succeed else false
   **/
   function getEmpty () {
      global $DB;

      //make an empty database object
      $table = $this->getTable();

      if (!empty($table) && $fields = $DB->list_fields($table)) {
         foreach ($fields as $key => $val) {
            $this->fields[$key] = "";
         }
      } else {
         return false;
      }

      if (array_key_exists('entities_id',$this->fields) && isset($_SESSION["glpiactive_entity"])) {
         $this->fields['entities_id'] = $_SESSION["glpiactive_entity"];
      }

      $this->post_getEmpty();

      // Call the plugin hook - $this->fields can be altered
      doHook("item_empty", $this);
      return true;
   }


   /**
    * Actions done at the end of the getEmpty function
    *
    * @return nothing
   **/
   function post_getEmpty () {
   }


   /**
    * Update the item in the database
    *
    * @param $updates fields to update
    * @param $oldvalues old values of the updated fields
    *
    * @return nothing
   **/
   function updateInDB($updates, $oldvalues=array()) {
      global $DB, $CFG_GLPI;

      foreach ($updates as $field) {
         if (isset($this->fields[$field])) {
            $query  = "UPDATE `".$this->getTable()."`
                       SET `".$field."`";

            if ($this->fields[$field]=="NULL") {
               $query .= " = ".$this->fields[$field];

            } else {
               $query .= " = '".$this->fields[$field]."'";
            }

            $query .= " WHERE `id` ='".$this->fields["id"]."'";

            if (!$DB->query($query)) {
               if (isset($oldvalues[$field])) {
                  unset($oldvalues[$field]);
               }
            }

         } else {
            // Clean oldvalues
            if (isset($oldvalues[$field])) {
               unset($oldvalues[$field]);
            }
         }

      }

      if (count($oldvalues)) {
         Log::constructHistory($this, $oldvalues, $this->fields);
      }

      return true;
   }


   /**
    * Add an item to the database
    *
    * @return new ID of the item is insert successfull else false
   **/
   function addToDB() {
      global $DB;

      //unset($this->fields["id"]);
      $nb_fields = count($this->fields);
      if ($nb_fields>0) {
         // Build query
         $query = "INSERT
                   INTO `".$this->getTable()."` (";

         $i = 0;
         foreach ($this->fields as $key => $val) {
            $fields[$i] = $key;
            $values[$i] = $val;
            $i++;
         }

         for ($i=0 ; $i<$nb_fields; $i++) {
            $query .= "`".$fields[$i]."`";
            if ($i!=$nb_fields-1) {
               $query .= ",";
            }
         }

         $query .= ") VALUES (";
         for ($i=0 ; $i<$nb_fields ; $i++) {

            if ($values[$i]=='NULL') {
               $query .= $values[$i];
            } else {
               $query .= "'".$values[$i]."'";
            }

            if ($i!=$nb_fields-1) {
               $query .= ",";
            }

         }
         $query .= ")";

         if ($result=$DB->query($query)) {
            $this->fields['id'] = $DB->insert_id();
            return $this->fields['id'];
         }

      }
      return false;
   }


   /**
    * Restore item = set deleted flag to 0
    *
    * @return true if succeed else false
   **/
   function restoreInDB() {
      global $DB,$CFG_GLPI;

      if ($this->maybeDeleted()) {
         // Auto set date_mod if exsist
         $toadd = '';
         if (isset($this->fields['date_mod'])) {
            $toadd = ", `date_mod` ='".$_SESSION["glpi_currenttime"]."' ";
         }

         $query = "UPDATE `".$this->getTable()."`
                   SET `is_deleted`='0' $toadd
                   WHERE `id` = '".$this->fields['id']."'";

         if ($result = $DB->query($query)) {
            return true;
         }

      }
      return false;
   }


   /**
    * Mark deleted or purge an item in the database
    *
    * @param $force force the purge of the item (not used if the table do not have a deleted field)
    *
    * @return true if succeed else false
   **/
   function deleteFromDB($force=0) {
      global $DB, $CFG_GLPI;

      if ($force==1 || !$this->maybeDeleted()) {
         $this->cleanDBonPurge();
         $this->cleanHistory();
         $this->cleanRelationData();
         $this->cleanRelationTable();

         $query = "DELETE
                   FROM `".$this->getTable()."`
                   WHERE `id` = '".$this->fields['id']."'";

         if ($result = $DB->query($query)) {
            $this->post_deleteFromDB();
            return true;
         }

      } else {
         // Auto set date_mod if exsist
         $toadd = '';
         if (isset($this->fields['date_mod'])) {
            $toadd = ", `date_mod` ='".$_SESSION["glpi_currenttime"]."' ";
         }

         $query = "UPDATE `".$this->getTable()."`
                   SET `is_deleted`='1' $toadd
                   WHERE `id` = '".$this->fields['id']."'";
         $this->cleanDBonMarkDeleted();

         if ($result = $DB->query($query)) {
            return true;
         }

      }

      return false;
   }


   /**
    * Clean data in the tables which have linked the deleted item
    *
    * @return nothing
   **/
   function cleanHistory() {
      global $DB;

      if ($this->dohistory) {
         $query = "DELETE
                   FROM `glpi_logs`
                   WHERE (`itemtype` = '".$this->getType()."'
                          AND `items_id` = '".$this->fields['id']."')";
         $DB->query($query);
      }
   }


   /**
    * Clean data in the tables which have linked the deleted item
    * Clear 1/N Relation
    *
    * @return nothing
   **/
   function cleanRelationData() {
      global $DB, $CFG_GLPI;

      $RELATION = getDbRelations();
      if (isset($RELATION[$this->getTable()])) {
         $newval = (isset($this->input['_replace_by']) ? $this->input['_replace_by'] : 0);

         foreach ($RELATION[$this->getTable()] as $tablename => $field) {
            if ($tablename[0]!='_') {

               if (!is_array($field)) {
                  $query = "UPDATE `$tablename`
                            SET `$field` = '$newval'
                            WHERE `$field` = '".$this->fields['id']."'";
                  $DB->query($query);

               } else {
                  foreach ($field as $f) {
                     $query = "UPDATE `$tablename`
                               SET `$f` = '$newval'
                               WHERE `$f` = '".$this->fields['id']."'";
                     $DB->query($query);
                  }
               }

            }
         }

      }

      // Clean ticket open against the item
      if (in_array($this->getType(),$CFG_GLPI["ticket_types"])) {
         $job = new Ticket;

         $query = "SELECT *
                   FROM `glpi_tickets`
                   WHERE `items_id` = '".$this->fields['id']."'
                         AND `itemtype`='".$this->getType()."'";
         $result = $DB->query($query);

         if ($DB->numrows($result)) {
            while ($data=$DB->fetch_array($result)) {

               if ($CFG_GLPI["keep_tickets_on_delete"]==1) {
                  $job->update(array('id'       => $data["id"],
                                     'items_id' => 0,
                                     'itemtype' => ''));
               } else {
                  $job->delete(array("id" => $data["id"]));
               }

            }
         }

      }
   }


   /**
    * Actions done after the DELETE of the item in the database
    *
    * @return nothing
   **/
   function post_deleteFromDB() {
   }


   /**
    * Actions done when item is deleted from the database
    *
    * @return nothing
   **/
   function cleanDBonPurge() {
   }


   /**
    * Clean the date in the relation tables for the deleted item
    * Clear N/N Relation
   **/
   function cleanRelationTable () {
      global $CFG_GLPI, $DB;

      // If this type have INFOCOM, clean one associated to purged item
      if (in_array($this->getType(),$CFG_GLPI['infocom_types'])) {
         $infocom = new Infocom();

         if ($infocom->getFromDBforDevice($this->getType(), $this->fields['id'])) {
             $infocom->delete(array('id' => $infocom->fields['id']));
         }
      }

      // If this type have NETPORT, clean one associated to purged item
      if (in_array($this->getType(),$CFG_GLPI['networkport_types'])) {
         $query = "SELECT `id`
                   FROM `glpi_networkports`
                   WHERE (`items_id` = '".$this->fields['id']."'
                          AND `itemtype` = '".$this->getType()."')";
         $result = $DB->query($query);

         while ($data = $DB->fetch_array($result)) {
            $q = "DELETE
                  FROM `glpi_networkports_networkports`
                  WHERE (`networkports_id_1` = '".$data["id"]."'
                         OR `networkports_id_2` = '".$data["id"]."')";
            $result2 = $DB->query($q);
         }

         $query = "DELETE
                   FROM `glpi_networkports`
                   WHERE (`items_id` = '".$this->fields['id']."'
                          AND `itemtype` = '".$this->getType()."')";
         $result = $DB->query($query);
      }

      // If this type is RESERVABLE clean one associated to purged item
      if (in_array($this->getType(),$CFG_GLPI['reservation_types'])) {
         $rr = new ReservationItem();

         if ($rr->getFromDBbyItem($this->getType(), $this->fields['id'])) {
             $rr->delete(array('id' => $infocom->fields['id']));
         }
      }

      // If this type have CONTRACT, clean one associated to purged item
      if (in_array($this->getType(),$CFG_GLPI['contract_types'])) {
         $ci = new Contract_Item();
         $ci->cleanDBonItemDelete($this->getType(), $this->fields['id']);
      }

      // If this type have DOCUMENT, clean one associated to purged item
      if (in_array($this->getType(),$CFG_GLPI["document_types"])) {
         $di = new Document_Item();
         $di->cleanDBonItemDelete($this->getType(), $this->fields['id']);
      }
   }


   /**
    * Actions done when item flag deleted is set to an item
    *
    * @return nothing
   **/
   function cleanDBonMarkDeleted() {
   }


   // Common functions
   /**
    * Add an item in the database with all it's items.
    *
    * @param $input array : the _POST vars returned by the item form when press add
    * @param options an array with the insert options
    *   - unicity_message : do not display message if item it a duplicate (default is yes)
    * @param $history boolean : do history log ?
    *
    * @return integer the new ID of the added item (or false if fail)
   **/
   function add($input, $options=array(), $history=true) {
      global $DB, $CFG_GLPI;

      if ($DB->isSlave()) {
         return false;
      }

      // Store input in the object to be available in all sub-method / hook
      $this->input = $input;

      // Call the plugin hook - $this->input can be altered
      doHook("pre_item_add", $this);

      if ($this->input && is_array($this->input)) {

         if (isset($this->input['add'])) {
            $this->input['_add'] = $this->input['add'];
            unset($this->input['add']);
         }

         $this->input = $this->prepareInputForAdd($this->input);
         //Check values to inject
         $this->filterValues();
      }

      if ($this->input && is_array($this->input)) {
         $this->fields = array();
         $table_fields = $DB->list_fields($this->getTable());

         // fill array for add
         foreach ($this->input as $key => $val) {
            if ($key[0]!='_' && isset($table_fields[$key])) {
               $this->fields[$key] = $this->input[$key];
            }
         }

         // Auto set date_mod if exsist
         if (isset($table_fields['date_mod'])) {
            $this->fields['date_mod'] = $_SESSION["glpi_currenttime"];
         }

         if ($this->checkUnicity(true,$options)) {

            if ($this->addToDB()) {
               $this->addMessageOnAddAction();
               $this->post_addItem();

               if ($this->dohistory && $history) {
                  $changes[0] = 0;
                  $changes[1] = $changes[2] = "";

                  Log::history($this->fields["id"], $this->getType(), $changes, 0,
                               HISTORY_CREATE_ITEM);
               }

                // Auto create infocoms
               if ($CFG_GLPI["auto_create_infocoms"]
                   && in_array($this->getType(), $CFG_GLPI["infocom_types"])) {

                  $ic = new Infocom();
                  if (!$ic->getFromDBforDevice($this->getType(), $this->fields['id'])) {
                     $ic->add(array('itemtype' => $this->getType(),
                                    'items_id' => $this->fields['id']));
                  }
               }

               // If itemtype is in infocomtype and if states_id field is filled
               // and item is not a template
               if (in_array($this->getType(),$CFG_GLPI["infocom_types"])
                   && isset($this->input['states_id'])
                            && (!isset($this->input['is_template'])
                                || !$this->input['is_template'])) {

                  //Check if we have to automaticall fill dates
                  Infocom::manageDateOnStatusChange($this);
               }
               doHook("item_add", $this);
               return $this->fields['id'];
            }
         }

      }
      $this->last_status = self::NOTHING_TO_DO;
      return false;
   }


   /**
    * Get the link to an item
    *
    * @param $with_comment Display comments
    *
    * @return string : HTML link
   **/
   function getLink($with_comment=0) {
      global $CFG_GLPI;

      if (!isset($this->fields['id'])) {
         return '';
      }

      if ($this->no_form_page) {
         return $this->getNameID($with_comment);

      } else {
         $link_item = $this->getFormURL();

         if (!$this->can($this->fields['id'],'r')) {
            return $this->getNameID($with_comment);
         }

         $link  = $link_item;
         $link .= (strpos($link,'?') ? '&amp;':'?').'id=' . $this->fields['id'];
         $link .= ($this->isTemplate() ? "&amp;withtemplate=1" : "");

         return "<a href='$link'>".$this->getNameID($with_comment)."</a>";
      }

   }


   /**
    * Get the link url to an item
    *
    * @return string : HTML link
   **/
   function getLinkURL() {
      global $CFG_GLPI;

      if (!isset($this->fields['id'])) {
         return '';
      }

      $link_item = $this->getFormURL();

      $link  = $link_item;
      $link .= (strpos($link,'?') ? '&amp;':'?').'id=' . $this->fields['id'];
      $link .= ($this->isTemplate() ? "&amp;withtemplate=1" : "");

      return $link;
   }


   /**
    * Add a message on add action
   **/
   function addMessageOnAddAction() {
      global $CFG_GLPI, $LANG;

      $link = $this->getFormURL();
      if (!isset($link)) {
         return;
      }

      $addMessAfterRedirect = false;
      if (isset($this->input['_add'])) {
         $addMessAfterRedirect = true;
      }

      if (isset($this->input['_no_message']) || !$this->auto_message_on_action) {
         $addMessAfterRedirect = false;
      }

      if ($addMessAfterRedirect) {
         if (($name=$this->getName()) == NOT_AVAILABLE) {
            $this->fields['name'] = $this->getTypeName()." : ".$LANG['common'][2]
                                    ." ".$this->fields['id'];
         }
         $display = (isset($this->input['_no_message_link'])?$this->getNameID()
                                                            :$this->getLink());

         // Do not display quotes
         addMessageAfterRedirect($LANG['common'][70]."&nbsp;: ".stripslashes($display));

      }
   }


   /**
    * Prepare input datas for adding the item
    *
    * @param $input datas used to add the item
    *
    * @return the modified $input array
   **/
   function prepareInputForAdd($input) {
      return $input;
   }


   /**
    * Actions done after the ADD of the item in the database
    *
    * @return nothing
   **/
   function post_addItem() {
   }


   /**
    * Update some elements of an item in the database.
    *
    * @param $input array : the _POST vars returned by the item form when press update
    * @param $history boolean : do history log ?
    * @param options an array with the insert options
    *
    * @return boolean : true on success
   **/
   function update($input, $history=1, $options=array()) {
      global $DB, $CFG_GLPI;

      if ($DB->isSlave()) {
         return false;
      }

      if (!$this->getFromDB($input[$this->getIndexName()])) {
         return false;
      }

      // Store input in the object to be available in all sub-method / hook
      $this->input = $input;

      // Plugin hook - $this->input can be altered
      doHook("pre_item_update", $this);

      if ($this->input && is_array($this->input)) {
         $this->input = $this->prepareInputForUpdate($this->input);

         if (isset($this->input['update'])) {
            $this->input['_update'] = $this->input['update'];
            unset($this->input['update']);
         }

         $this->filterValues();
      }

      // Valid input for update
      if ($this->checkUnicity(false,$options)) {
         if ($this->input && is_array($this->input)) {
            // Fill the update-array with changes
            $x = 0;
            $this->updates   = array();
            $this->oldvalues = array();

            foreach ($this->input as $key => $val) {
               if (array_key_exists($key,$this->fields)) {

                  // Prevent history for date statement (for date for example)
                  if (is_null($this->fields[$key]) && $this->input[$key]=='NULL') {
                     $this->fields[$key] = 'NULL';
                  }

                  if (mysql_real_escape_string($this->fields[$key]) != $this->input[$key]) {
                     if ($key!="id") {

                        // Store old values
                        if (!in_array($key,$this->history_blacklist)) {
                           $this->oldvalues[$key] = $this->fields[$key];
                        }

                        $this->fields[$key] = $this->input[$key];
                        $this->updates[$x]  = $key;
                        $x++;
                     }
                  }

               }
            }

            if (count($this->updates)) {
               if (array_key_exists('date_mod',$this->fields)) {
                  // is a non blacklist field exists
                  if (count(array_diff($this->updates, $this->history_blacklist)) > 0) {
                     $this->fields['date_mod'] = $_SESSION["glpi_currenttime"];
                     $this->updates[$x++]      = 'date_mod';
                  }
               }
               $this->pre_updateInDB();

               if (count($this->updates)) {
                  if ($this->updateInDB($this->updates,
                                        ($this->dohistory && $history ? $this->oldvalues
                                                                      : array()))) {
                     $this->addMessageOnUpdateAction();
                     doHook("item_update", $this);

                     // forward entity information if needed
                     if (count($this->forward_entity_to)
                         && (in_array("entities_id",$this->updates)
                             || in_array("is_recursive",$this->updates)) ) {
                        $this->forwardEntityInformations();
                     }

                     // If itemtype is in infocomtype and if states_id field is filled
                     // and item not a template
                     if (in_array($this->getType(),$CFG_GLPI["infocom_types"])
                         && in_array('states_id',$this->updates)
                         && ($this->getField('is_template') != NOT_AVAILABLE)) {
                        //Check if we have to automatical fill dates
                        Infocom::manageDateOnStatusChange($this, false);
                     }
                  }
               }
            }
            $this->post_updateItem($history);
            return true;
         }
      }

      return false;
   }


   /**
    * Forward entity informations to linked items
   **/
   protected function forwardEntityInformations() {
      global $DB;

      if (!isset($this->fields['id']) || !($this->fields['id']>=0)) {
         return false;
      }

      if (count($this->forward_entity_to)) {
         foreach ($this->forward_entity_to as $type) {
            $item  = new $type();
            $query = "SELECT `id`
                      FROM `".$item->getTable()."`
                      WHERE ";

            if ($item->isField('itemtype')) {
               $query .= " `itemtype` = '".$this->getType()."'
                          AND `items_id` = '".$this->fields['id']."'";
            } else {
               $query .= " `".$this->getForeignKeyField()."` = '".$this->fields['id']."'";
            }

            $input = array('entities_id' => $this->getEntityID());

            if ($this->maybeRecursive()) {
               $input['is_recursive'] = $this->isRecursive();
            }

            foreach ($DB->request($query) as $data) {
               $input['id'] = $data['id'];
               $item->update($input);
            }
         }
      }
   }


   /**
    * Add a message on update action
   **/
   function addMessageOnUpdateAction() {
      global $CFG_GLPI, $LANG;

      $link = $this->getFormURL();
      if (!isset($link)) {
         return;
      }

      $addMessAfterRedirect = false;

      if (isset($this->input['_update'])) {
         $addMessAfterRedirect = true;
      }

      if (isset($this->input['_no_message']) || !$this->auto_message_on_action) {
         $addMessAfterRedirect = false;
      }

      if ($addMessAfterRedirect) {

         // Do not display quotes
         if (isset($this->fields['name'])) {
            $this->fields['name'] = stripslashes($this->fields['name']);
         } else {
            $this->fields['name'] = $this->getTypeName()." : ".$LANG['common'][2]." ".
                                    $this->fields['id'];
         }

         addMessageAfterRedirect($LANG['common'][71] . "&nbsp;: " .
                                 (isset($this->input['_no_message_link'])?$this->getNameID()
                                                                         :$this->getLink()));
      }

   }


   /**
    * Prepare input datas for updating the item
    *
    * @param $input datas used to update the item
    *
    * @return the modified $input array
   **/
   function prepareInputForUpdate($input) {
      return $input;
   }


   /**
    * Actions done after the UPDATE of the item in the database
    *
    * @param $history store changes history ?
    *
    * @return nothing
   **/
   function post_updateItem($history=1) {
   }


   /**
    * Actions done before the UPDATE of the item in the database
    *
    * @return nothing
   **/
   function pre_updateInDB() {
   }


   /**
    * Delete an item in the database.
    *
    * @param $input array : the _POST vars returned by the item form when press delete
    * @param $force boolean : force deletion
    * @param $history boolean : do history log ?
    *
    * @return boolean : true on success
   **/
   function delete($input, $force=0, $history=1) {
      global $DB;

      if ($DB->isSlave()) {
         return false;
      }

      if (!$this->getFromDB($input[$this->getIndexName()])) {
         return false;
      }

      if ($this->isTemplate() || !$this->maybeDeleted()) {
         $force = 1;
      }
      // Store input in the object to be available in all sub-method / hook
      $this->input = $input;

      if (isset($this->input['purge'])) {
         $this->input['_purge'] = $this->input['purge'];
         unset($this->input['purge']);
      }

      if (isset($this->input['delete'])) {
         $this->input['_delete'] = $this->input['delete'];
         unset($input['delete']);
      }

      // Purge
      if ($force) {
         doHook("pre_item_purge", $this);
      } else {
         doHook("pre_item_delete", $this);
      }


      if (!is_array($this->input)) {
         // $input clear by a hook to cancel delete
         return false;
      }

      if ($this->pre_deleteItem()) {
         if ($this->deleteFromDB($force)) {

            if ($force) {
               $this->addMessageOnPurgeAction();
               $this->post_purgeItem();
               doHook("item_purge", $this);

            } else {
               $this->addMessageOnDeleteAction();

               if ($this->dohistory && $history) {
                  $changes[0] = 0;
                  $changes[1] = $changes[2] = "";

                  Log::history($this->fields["id"], $this->getType(), $changes, 0,
                               HISTORY_DELETE_ITEM);
               }

               $this->post_deleteItem();
               doHook("item_delete",$this);
            }
            return true;
         }

      }
      return false;
   }


   /**
    * Actions done after the DELETE (mark as deleted) of the item in the database
    *
    * @return nothing
   **/
   function post_deleteItem () {
   }


   /**
    * Actions done after the PURGE of the item in the database
    *
    * @return nothing
   **/
   function post_purgeItem () {
   }


   /**
    * Add a message on delete action
   **/
   function addMessageOnDeleteAction() {
      global $CFG_GLPI, $LANG;

      $link = $this->getFormURL();
      if (!isset($link)) {
         return;
      }

      if (!$this->maybeDeleted()) {
         return;
      }

      $addMessAfterRedirect=false;
      if (isset($this->input['_delete'])) {
         $addMessAfterRedirect = true;
      }

      if (isset($this->input['_no_message']) || !$this->auto_message_on_action) {
         $addMessAfterRedirect = false;
      }

      if ($addMessAfterRedirect) {
         addMessageAfterRedirect($LANG['common'][72] . "&nbsp;: " .
                                 (isset($this->input['_no_message_link'])?$this->getNameID()
                                                                         :$this->getLink()));
      }
   }


   /**
    * Add a message on purge action
   **/
   function addMessageOnPurgeAction() {
      global $CFG_GLPI, $LANG;

      $link = $this->getFormURL();
      if (!isset($link)) {
         return;
      }

      $addMessAfterRedirect = false;

      if (isset($this->input['_purge']) || isset($this->input['_delete'])) {
         $addMessAfterRedirect = true;
      }

      if (isset($this->input['_no_message']) || !$this->auto_message_on_action) {
         $addMessAfterRedirect = false;
      }

      if ($addMessAfterRedirect) {
         addMessageAfterRedirect($LANG['common'][73]);
      }
   }


   /**
    * Actions done before the DELETE of the item in the database /
    * Maybe used to add another check for deletion
    *
    * @return bool : true if item need to be deleted else false
   **/
   function pre_deleteItem() {
      return true;
   }


   /**
    * Restore an item trashed in the database.
    *
    * @param $input array : the _POST vars returned by the item form when press restore
    * @param $history boolean : do history log ?
    *
    * @return boolean : true on success
   **/
   function restore($input, $history=1) {

      if (!$this->getFromDB($input[$this->getIndexName()])) {
         return false;
      }

      if (isset($input['restore'])) {
         $input['_restore'] = $input['restore'];
         unset($input['restore']);
      }

      // Store input in the object to be available in all sub-method / hook
      $this->input = $input;
      doHook("pre_item_restore", $this);

      if ($this->restoreInDB()) {
         $this->addMessageOnRestoreAction();

         if ($this->dohistory && $history) {
            $changes[0] = 0;
            $changes[1] = $changes[2] = "";
            Log::history($this->input["id"], $this->getType(), $changes, 0, HISTORY_RESTORE_ITEM);
         }

         $this->post_restoreItem();
         doHook("item_restore", $this);
         return true;
      }

      return false;
   }


   /**
    * Actions done after the restore of the item
    *
    * @return nothing
   **/
   function post_restoreItem () {
   }


   /**
    * Add a message on restore action
   **/
   function addMessageOnRestoreAction() {
      global $CFG_GLPI, $LANG;

      $link = $this->getFormURL();
      if (!isset($link)) {
         return;
      }

      $addMessAfterRedirect = false;
      if (isset($this->input['_restore'])) {
         $addMessAfterRedirect = true;
      }

      if (isset($this->input['_no_message']) || !$this->auto_message_on_action) {
         $addMessAfterRedirect = false;
      }

      if ($addMessAfterRedirect) {
         addMessageAfterRedirect($LANG['common'][74] . "&nbsp;: " .
                                 (isset($this->input['_no_message_link'])?$this->getNameID()
                                                                         :$this->getLink()));
      }
   }


   /**
    * Reset fields of the item
   **/
   function reset() {
      $this->fields = array();
   }


   /**
    * Have I the global right to "create" the Object
    *
    * May be overloaded if needed (ex KnowbaseItem)
    *
    * @return booleen
   **/
   function canCreate() {
      return false;
   }


   /**
    * Have I the global right to "delete" the Object
    *
    * Default is calling canCreate
    * May be overloaded if needed
    *
    * @return booleen
    * @see canCreate
   **/
   function canDelete() {
      return $this->canCreate();
   }


   /**
    * Have I the global right to "update" the Object
    *
    * Default is calling canCreate
    * May be overloaded if needed
    *
    * @return booleen
    * @see canCreate
   **/
   function canUpdate() {
      return $this->canCreate();
   }


   /**
    * Have I the right to "create" the Object
    *
    * Default is true and check entity if the objet is entity assign
    *
    * May be overloaded if needed
    *
    * @return booleen
   **/
   function canCreateItem() {

      // Is an item assign to an entity
      if ($this->isEntityAssign()) {
         // Have access to entity
         return haveAccessToEntity($this->getEntityID());
      }
      // else : Global item
      return true;
   }


   /**
    * Have I the right to "update" the Object
    *
    * Default is calling canCreateItem
    * May be overloaded if needed
    *
    * @return booleen
    * @see canCreate
   **/
   function canUpdateItem() {
      return $this->canCreateItem();
   }


   /**
    * Have I the right to "delete" the Object
    *
    * Default is calling canCreateItem
    * May be overloaded if needed
    *
    * @return booleen
    * @see canCreate
   **/
   function canDeleteItem() {
      global $CFG_GLPI;

      if (!$this->canCreateItem()) {
         return false;
      }

      // Can delete an object with Infocom only if can delete Infocom
      if (in_array($this->getType(), $CFG_GLPI['infocom_types'])) {
         $infocom = new Infocom();

         if ($infocom->getFromDBforDevice($this->getType(), $this->fields['id'])) {
            return $infocom->canDelete();
         }

      }

      return true;
   }


   /**
    * Have I the global right to "view" the Object
    *
    * Default is true and check entity if the objet is entity assign
    *
    * May be overloaded if needed
    *
    * @return booleen
   **/
   function canView() {
      return false;
   }


   /**
    * Have I the right to "view" the Object
    *
    * May be overloaded if needed
    *
    * @return booleen
   **/
   function canViewItem() {

      // Is an item assign to an entity
      if ($this->isEntityAssign()) {
         // Can be recursive check
         if ($this->maybeRecursive()) {
            return haveAccessToEntity($this->getEntityID(),$this->isRecursive());
         }
         //  else : No recursive item
         return haveAccessToEntity($this->getEntityID());
      }
      //  else : Global item
      return true;
   }


   /**
    * Can I change recusvive flag to false
    * check if there is "linked" object in another entity
    *
    * May be overloaded if needed
    *
    * @return booleen
   **/
   function canUnrecurs() {
      global $DB, $CFG_GLPI;

      $ID  = $this->fields['id'];
      if ($ID<0 || !$this->fields['is_recursive']) {
         return true;
      }

      $entities = "('".$this->fields['entities_id']."'";
      foreach (getAncestorsOf("glpi_entities",$this->fields['entities_id']) as $papa) {
         $entities .= ",'$papa'";
      }

      $entities .= ")";
      $RELATION  = getDbRelations();

      if ($this instanceof CommonTreeDropdown) {
         $f = getForeignKeyFieldForTable($this->getTable());

         if (countElementsInTable($this->getTable(),
                                  "`$f`='$ID' AND entities_id NOT IN $entities")>0) {
            return false;
         }
      }

      if (isset($RELATION[$this->getTable()])) {
         foreach ($RELATION[$this->getTable()] as $tablename => $field) {
            $itemtype = getItemTypeForTable($tablename);
            $item     = new $itemtype();

            if ($item->isEntityAssign()) {

               // 1->N Relation
               if (is_array($field)) {
                  foreach ($field as $f) {
                     if (countElementsInTable($tablename,
                                              "`$f`='$ID' AND entities_id NOT IN $entities")>0) {
                        return false;
                     }
                  }

               } else {
                  if (countElementsInTable($tablename,
                                           "`$field`='$ID' AND entities_id NOT IN $entities")>0) {
                     return false;
                  }
               }

            } else {
               foreach ($RELATION as $othertable => $rel) {
                  // Search for a N->N Relation with devices
                  if ($othertable == "_virtual_device" && isset($rel[$tablename])) {
                     $devfield  = $rel[$tablename][0]; // items_id...
                     $typefield = $rel[$tablename][1]; // itemtype...

                     $sql = "SELECT DISTINCT `$typefield` AS itemtype
                             FROM `$tablename`
                             WHERE `$field`='$ID'";
                     $res = $DB->query($sql);

                     // Search linked device of each type
                     if ($res) {
                        while ($data = $DB->fetch_assoc($res)) {
                           $itemtype  = $data["itemtype"];
                           $itemtable = getTableForItemType($itemtype);
                           $item      = new $itemtype();

                           if ($item->isEntityAssign()) {
                              if (countElementsInTable(array($tablename, $itemtable),
                                                       "`$tablename`.`$field`='$ID'
                                                        AND `$tablename`.`$typefield`='$itemtype'
                                                        AND `$tablename`.`$devfield`=`$itemtable`.id
                                                        AND `$itemtable`.`entities_id`
                                                             NOT IN $entities")>'0') {
                                 return false;
                              }
                           }

                        }
                     }

                  // Search for another N->N Relation
                  } else if ($othertable != $this->getTable() && isset($rel[$tablename])) {
                     $itemtype = getItemTypeForTable($othertable);
                     $item     = new $itemtype();

                     if ($item->isEntityAssign()) {
                        if (is_array($rel[$tablename])) {
                           foreach ($rel[$tablename] as $otherfield) {
                              if (countElementsInTable(array($tablename, $othertable),
                                                       "`$tablename`.`$field`='$ID'
                                                        AND `$tablename`.`$otherfield`
                                                                  =`$othertable`.id
                                                        AND `$othertable`.`entities_id`
                                                                  NOT IN $entities")>'0') {
                                 return false;
                              }
                           }

                        } else {
                           $otherfield = $rel[$tablename];
                           if (countElementsInTable(array($tablename, $othertable),
                                                    "`$tablename`.`$field`=$ID
                                                     AND `$tablename`.`$otherfield`=`$othertable`.id
                                                     AND `$othertable`.`entities_id`
                                                               NOT IN $entities")>'0') {
                              return false;
                           }
                        }

                     }
                  }
               }
            }
         }
      }

      // Doc links to this item
      if ($this->getType() > 0
          && countElementsInTable(array('glpi_documents_items', 'glpi_documents'),
                                  "`glpi_documents_items`.`items_id`='$ID'
                                   AND `glpi_documents_items`.`itemtype`=".$this->getType()."
                                   AND `glpi_documents_items`.`documents_id`=`glpi_documents`.`id`
                                   AND `glpi_documents`.`entities_id` NOT IN $entities")>'0') {
         return false;
      }
      // TODO : do we need to check all relations in $RELATION["_virtual_device"] for this item

      return true;
   }


   /**
    * Display a 2 columns Footer for Form buttons
    * Close the form is user can edit
    *
    * @param $options array
    *     - withtemplate : 1 for newtemplate, 2 for newobject from template
    *     - colspan for each column (default 2)
    *     - candel : set to false to hide "delete" button
    *     - canedit : set to false to hide all buttons
    *
   **/
   function showFormButtons ($options=array()) {
      global $LANG, $CFG_GLPI;

      // for single object like config
      if (isset($this->fields['id'])) {
         $ID = $this->fields['id'];
      } else {
        $ID = 1;
      }

      $params['colspan']      = 2;
      $params['withtemplate'] = '';
      $params['candel']       = true;
      $params['canedit']      = true;

      if (is_array($options) && count($options)) {
         foreach ($options as $key => $val) {
            $params[$key] = $val;
         }
      }

      if (!$params['canedit']
          || (!$this->can($ID,'w') && !$this->can($ID,'d'))) {
         echo "</table></div>";
         // Form Header always open form
         if (!$params['canedit']) {
            echo "</form>";
         }
         return false;
      }
      echo "<tr>";

      if ($params['withtemplate'] ||$this->isNewID($ID)) {
         echo "<td class='tab_bg_2 center' colspan='".($params['colspan']*2)."'>";

         if ($ID<=0 || $params['withtemplate']==2) {
            echo "<input type='submit' name='add' value=\"".$LANG['buttons'][8]."\" class='submit'>";
         } else {
            echo "<input type='submit' name='update' value=\"".$LANG['buttons'][7]."\" class='submit'>";
         }

      } else {
         if ($params['candel'] && !$this->can($ID,'d')) {
            $params['candel'] = false;
         }

         if ($params['candel']) {
            echo "<td class='tab_bg_2 center' colspan='".$params['colspan']."'>\n";
            echo "<input type='submit' name='update' value=\"".$LANG['buttons'][7]."\" class='submit'>";
            echo "</td>\n";
            echo "<td class='tab_bg_2 center' colspan='".$params['colspan']."' >\n";

            if ($this->isDeleted()) {
               echo "<input type='submit' name='restore' value=\"".$LANG['buttons'][21]."\"
                      class='submit'>";
               echo "<span class='small_space'>
                     <input type='submit' name='purge' value=\"".$LANG['buttons'][22]."\"
                      class='submit'>
                     </span>";

            } else {
               if (!$this->maybeDeleted()) {
                  echo "<input type='submit' name='delete' value=\"".$LANG['buttons'][22]."\"
                         class='submit' OnClick=\"return window.confirm('".$LANG['common'][50]."');\">";
               } else {
                  echo "<input type='submit' name='delete' value='" . $LANG['buttons'][6] ."'
                         class='submit'>";
               }
            }

         } else {
            echo "<td class='tab_bg_2 center' colspan='".($params['colspan']*2)."'>\n";
            echo "<input type='submit' name='update' value=\"".$LANG['buttons'][7]."\"
                   class='submit'>";
         }
      }

      if ($ID>0) {
         echo "<input type='hidden' name='id' value='$ID'>";
      }
      echo "</td>";
      echo "</tr>\n";

      // Close for Form
      echo "</table></div></form>";
   }


   /**
    *
    * Display a 2 columns Header 1 for ID, 1 for recursivity menu
    * Open the form is user can edit
    *
    * @param $options array
    *     - target for the Form
    *     - withtemplate : 1 for newtemplate, 2 for newobject from template
    *     - colspan for each column (default 2)
    *     - formoptions string (javascript p.e.)
    *     - canedit boolean edit mode of form ?
    *
   **/
   function showFormHeader ($options=array()) {
      global $LANG, $CFG_GLPI;

      $ID = $this->fields['id'];
      $params['target']       = $this->getFormURL();
      $params['colspan']      = 2;
      $params['withtemplate'] = '';
      $params['formoptions']  = '';
      $params['canedit']      = true;

      if (is_array($options) && count($options)) {
         foreach ($options as $key => $val) {
            $params[$key] = $val;
         }
      }

      // Template case : clean entities data
      if ($params['withtemplate'] == 2 && $this->isEntityAssign()) {
         $this->fields['entities_id']  = $_SESSION['glpiactive_entity'];
         if ($this->maybeRecursive()) {
            $this->fields["is_recursive"] = 0;
         }
      }

      if ($this->can($ID,'w')) {
         echo "<form name='form' method='post' action='".$params['target']."' ".
                $params['formoptions'].">";

         //Should add an hidden entities_id field ?
         //If the table has an entities_id field
         if ($this->isField("entities_id")) {
            //The object type can be assigned to an entity
            if ($this->isEntityAssign()) {
               // TODO CommonDBChild must not use current entity, but parent entity

               if (isset($params['entities_id'])) {
                  $entity = $this->fields['entities_id'] = $params['entities_id'];

               } else if ($this->isNewID($ID) || $params['withtemplate'] == 2) {
                  //It's a new object to be added
                  $entity = $_SESSION['glpiactive_entity'];

               } else {
                  //It's an existing object to be displayed
                  $entity = $this->fields['entities_id'];
               }

               echo "<input type='hidden' name='entities_id' value='$entity'>";

            // For Rules except ruleticket and slalevel
            } else if ($this->getType() != 'User') {
               echo "<input type='hidden' name='entities_id' value='0'>";

            }
         }

         // No link on popup window
         if (isset($_GET['popup']) && $_GET['popup']) {
            echo "<input type='hidden' name='_no_message_link' value='1'>";
         }
      }

      echo "<div class='spaced' id='tabsbody'>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr><th colspan='".$params['colspan']."'>";

      if (!empty($params['withtemplate'])
          && $params['withtemplate'] == 2
          && !$this->isNewID($ID)) {

         echo "<input type='hidden' name='template_name' value='".$this->fields["template_name"]."'>";
         echo $LANG['buttons'][8]." - ".$LANG['common'][13]."&nbsp;: ".$this->fields["template_name"];

      } else if (!empty($params['withtemplate']) && $params['withtemplate'] == 1) {
         echo "<input type='hidden' name='is_template' value='1'>\n";
         echo $LANG['common'][6]."&nbsp;: ";
         autocompletionTextField($this, "template_name", array('size' => 25));

      } else if ($this->isNewID($ID)) {
         echo $this->getTypeName()." - ".$LANG['common'][87];

      } else {
         echo $this->getTypeName()." - ".$LANG['common'][2]." $ID";
      }

      if (isset($this->fields["entities_id"])
          && isMultiEntitiesMode()
          && $this->isEntityAssign()) {

         echo "&nbsp;(".Dropdown::getDropdownName("glpi_entities", $this->fields["entities_id"]).")";
      }

      echo "</th><th colspan='".$params['colspan']."'>";

      if (get_class($this)=='Entity') {
         // is recursive but cannot be change

      } else {
         if ($this->maybeRecursive()) {
            if (isMultiEntitiesMode()) {
               echo $LANG['entity'][9]."&nbsp;:&nbsp;";

               if ($params['canedit']) {
                  if (!$this->can($ID,'recursive')) {
                     echo Dropdown::getYesNo($this->fields["is_recursive"]);
                     $comment = $LANG['common'][86];
                     // CommonDBChild : entity data is get or copy from parent

                  } else if ( $this instanceof CommonDBChild) {
                     echo Dropdown::getYesNo($this->isRecursive());
                     $comment = $LANG['common'][91];

                  } else if ( !$this->canUnrecurs()) {
                     echo Dropdown::getYesNo($this->fields["is_recursive"]);
                     $comment = $LANG['common'][84];

                  } else {
                     Dropdown::showYesNo("is_recursive", $this->fields["is_recursive"]);
                     $comment = $LANG['common'][85];
                  }
                  echo "&nbsp;";
                  showToolTip($comment);
               } else {
                  echo Dropdown::getYesNo($this->fields["is_recursive"]);
               }
            } else {
               echo "<input type='hidden' name='is_recursive' value='0'>";
            }
         } else {
            echo "&nbsp;";
         }
      }
      echo "</th></tr>\n";
   }


   /**
    * is the parameter ID must be considered as new one ?
    * Default is empty of <0 may be overriden (for entity for example)
    *
    * @param $ID ID of the item (-1 if new item)
    *
    * @return boolean
   **/
   function isNewID($ID) {
      return (empty($ID) || $ID<=0);
   }


   /**
    * Check right on an item
    *
    * @param $ID ID of the item (-1 if new item)
    * @param $right Right to check : r / w / recursive
    * @param $input array of input data (used for adding item)
    *
    * @return boolean
   **/
   function can($ID, $right, &$input=NULL) {

      // Create process
      if ($this->isNewID($ID)) {
         if (!isset($this->fields['id'])) {
            // Only once
            $this->getEmpty();
         }

         if (is_array($input)) {
            // Copy input field to allow getEntityID() to work
            // from entites_id field or from parent item ref
            foreach ($input as $key => $val) {
               if (isset($this->fields[$key])) {
                  $this->fields[$key] = $val;
               }
            }
         }

         if ($this->isPrivate() && $this->fields['users_id']===getLoginUserID()) {
            return true;
         }
         return ($this->canCreate() && $this->canCreateItem());

      }
      // else : Get item if not already loaded
      if (!isset($this->fields['id']) || $this->fields['id']!=$ID) {
         // Item not found : no right
         if (!$this->getFromDB($ID)) {
            return false;
         }
      }

      switch ($right) {
         case 'r' :
            // Personnal item
            if ($this->isPrivate() && $this->fields['users_id']===getLoginUserID()) {
               return true;
            }
            return ($this->canView() && $this->canViewItem());

         case 'w' :
            // Personnal item
            if ($this->isPrivate() && $this->fields['users_id']===getLoginUserID()) {
               return true;
            }
            return ($this->canUpdate() && $this->canUpdateItem());

         case 'd' :
            // Personnal item
            if ($this->isPrivate() && $this->fields['users_id']===getLoginUserID()) {
               return true;
            }
            return ($this->canDelete() && $this->canDeleteItem());

         case 'recursive' :
            if ($this->isEntityAssign() && $this->maybeRecursive()) {
               if ($this->canCreate() && haveAccessToEntity($this->getEntityID())) {
                  // Can make recursive if recursive access to entity
                  return haveRecursiveAccessToEntity($this->getEntityID());
               }
            }
            break;

      }
      return false;
   }


   /**
    * Check right on an item with block
    *
    * @param $ID ID of the item (-1 if new item)
    * @param $right Right to check : r / w / recursive
    * @param $input array of input data (used for adding item)
    *
    * @return nothing
   **/
   function check($ID,$right,&$input=NULL) {
      global $CFG_GLPI;

      // Check item exists
      if (!$this->isNewID($ID) && !$this->getFromDB($ID)) {
         // Gestion timeout session
         if (!getLoginUserID()) {
            glpi_header($CFG_GLPI["root_doc"] . "/index.php");
            exit ();
         }
         displayNotFoundError();

      } else {
         if (!$this->can($ID,$right,$input)) {
            // Gestion timeout session
            if (!getLoginUserID()) {
               glpi_header($CFG_GLPI["root_doc"] . "/index.php");
               exit ();
            }
            displayRightError();
         }
      }
   }


   /**
    * Check global right on an object
    *
    * @param $right Right to check : c / r / w / d
    *
    * @return nothing
   **/
   function checkGlobal($right) {
      global $CFG_GLPI;

      if (!$this->canGlobal($right)) {
         // Gestion timeout session
         if (!getLoginUserID()) {
            glpi_header($CFG_GLPI["root_doc"] . "/index.php");
            exit ();
         }
         displayRightError();
      }
   }


   /**
    * Get global right on an object
    *
    * @param $right Right to check : c / r / w / d
    *
    * @return nothing
   **/
   function canGlobal($right) {

      switch ($right) {
         case 'r' :
            return $this->canView();

         case 'w' :
            return $this->canUpdate();

         case 'c' :
            return $this->canCreate();

         case 'd' :
            return $this->canDelete();
      }

      return false;
   }


   /**
    * Get the ID of entity assigned to the object
    *
    * Can be overloaded (ex : infocom)
    *
    * @return ID of the entity
   **/
   function getEntityID() {

      if ($this->isEntityAssign()) {
         return $this->fields["entities_id"];
      }
      return  -1;
   }


   /**
    * Is the object assigned to an entity
    *
    * Can be overloaded (ex : infocom)
    *
    * @return boolean
   **/
   function isEntityAssign() {

      if (!array_key_exists('id',$this->fields)) {
         $this->getEmpty();
      }
      return array_key_exists('entities_id', $this->fields);
   }


   /**
    * Is the object may be recursive
    *
    * Can be overloaded (ex : infocom)
    *
    * @return boolean
   **/
   function maybeRecursive() {
      if (!array_key_exists('id',$this->fields)) {
         $this->getEmpty();
      }
      return array_key_exists('is_recursive', $this->fields);
   }


   /**
    * Is the object recursive
    *
    * Can be overloaded (ex : infocom)
    *
    * @return boolean
   **/
   function isRecursive() {

      if ($this->maybeRecursive()) {
         return $this->fields["is_recursive"];
      }
      // Return integer value to be used to fill is_recursive field
      return 0;
   }


   /**
    * Is the object may be deleted
    *
    * @return boolean
   **/
   function maybeDeleted() {

      if (!isset($this->fields['id'])) {
         $this->getEmpty();
      }
      return array_key_exists('is_deleted', $this->fields);
   }


   /**
    * Is the object deleted
    *
    * @return boolean
   **/
   function isDeleted() {

      if ($this->maybeDeleted()) {
         return $this->fields["is_deleted"];
      }
      // Return integer value to be used to fill is_deleted field
      return 0;

   }


   /**
    * Is the object may be a template
    *
    * @return boolean
   **/
   function maybeTemplate() {

      if (!isset($this->fields['id'])) {
         $this->getEmpty();
      }
      return isset($this->fields['is_template']);
   }


   /**
    * Is the object a template
    *
    * @return boolean
   **/
   function isTemplate() {

      if ($this->maybeTemplate()) {
         return $this->fields["is_template"];
      }
      // Return integer value to be used to fill is_template field
      return 0;
   }


   /**
    * Is the object may be recursive
    *
    * @return boolean
   **/
   function maybePrivate() {

      if (!isset($this->fields['id'])) {
         $this->getEmpty();
      }
      return (array_key_exists('is_private', $this->fields)
              && array_key_exists('users_id', $this->fields));
   }


   /**
    * Is the object private
    *
    * @return boolean
   **/
   function isPrivate() {

      if ($this->maybePrivate()) {
         return $this->fields["is_private"];
      }
      return false;
   }


   /**
    * Return the SQL command to retrieve linked object
    *
    * @return a SQL command which return a set of (itemtype, items_id)
   **/
   function getSelectLinkedItem() {
      return '';
   }


   /**
    * Return a field Value if exists
    *
    * @param $field field name
    *
    * @return value of the field / false if not exists
   **/
   function getField ($field) {

      if (array_key_exists($field,$this->fields)) {
         return $this->fields[$field];
      }
      return NOT_AVAILABLE;
   }


   /**
    * Determine if a field exists
    *
    * @param $field field name
    *
    * @return boolean
   **/
   function isField ($field) {

      if (!isset($this->fields['id'])) {
         $this->getEmpty();
      }
       return array_key_exists($field, $this->fields);
   }


   /**
    * Get comments of the Object
    *
    * @return String: comments of the object in the current language (HTML)
   **/
   function getComments() {
      global $LANG,$CFG_GLPI;

      $comment = "";
      if ($this->isField('serial')) {
         $comment .= "<strong>".$LANG['common'][19]."&nbsp;: </strong>".
                     $this->getField('serial')."<br>";
      }

      if ($this->isField('otherserial')) {
         $comment .= "<strong>".$LANG['common'][20]."&nbsp;: </strong>".
                     $this->getField('otherserial')."<br>";
      }

      if ($this->isField('locations_id')) {
         $tmp = Dropdown::getDropdownName("glpi_locations", $this->getField('locations_id'));
         if (strlen($tmp)!=0 && $tmp!='&nbsp;') {
            $comment .= "<strong>".$LANG['common'][15]."&nbsp;: "."</strong>".$tmp."<br>";
         }
      }

      if ($this->isField('users_id')) {
         $tmp = getUserName($this->getField('users_id'));
         if (strlen($tmp)!=0 && $tmp!='&nbsp;') {
            $comment .= "<strong>".$LANG['common'][34]."&nbsp;: "."</strong>".$tmp."<br>";
         }
      }

      if ($this->isField('groups_id')) {
         $tmp = Dropdown::getDropdownName("glpi_groups",$this->getField('groups_id'));
         if (strlen($tmp)!=0 && $tmp!='&nbsp;') {
            $comment .= "<strong>".$LANG['common'][35]."&nbsp;: "."</strong>".$tmp."<br>";
         }
      }

      if ($this->isField('users_id_tech')) {
         $tmp = getUserName($this->getField('users_id_tech'));
         if (strlen($tmp)!=0 && $tmp!='&nbsp;') {
            $comment .= "<strong>".$LANG['common'][10]."&nbsp;: "."</strong>".$tmp."<br>";
         }
      }

      if ($this->isField('contact')) {
         $comment .= "<strong>".$LANG['common'][18]."&nbsp;: </strong>".$this->getField('contact').
                     "<br>";
      }

      if ($this->isField('contact_num')) {
         $comment .= "<strong>".$LANG['common'][21]."&nbsp;: </strong>".
                     $this->getField('contact_num')."<br>";
      }

      if (!empty($comment)) {
         return showToolTip($comment, array('display' => false));
      }

      return $comment;
   }


   /**
    * Get The Name of the Object
    *
    * @param $with_comment add comments to name
    *
    * @return String: name of the object in the current language
   **/
   function getName($with_comment=0) {

      $toadd = "";
      if ($with_comment) {
         $toadd = "&nbsp;".$this->getComments();
      }

      if (isset($this->fields["name"]) && strlen($this->fields["name"])!=0) {
         return $this->fields["name"].$toadd;
      }
      return NOT_AVAILABLE;
   }


   /**
    * Get The Name of the Object with the ID if the config is set
    *
    * @param $with_comment add comments to name
    *
    * @return String: name of the object in the current language
   **/
   function getNameID($with_comment=0) {
      global $CFG_GLPI;

      $toadd = "";
      if ($with_comment) {
         $toadd = "&nbsp;".$this->getComments();
      }

      if ($_SESSION['glpiis_ids_visible']) {
         return $this->getName()." (".$this->getField('id').")".$toadd;
      }
      return $this->getName().$toadd;
   }


   /**
    * Get the Search options for the givem Type
    *
    * This should be overloaded in Class
    *
    * @return an array of seach options
    * More information on https://forge.indepnet.net/wiki/glpi/SearchEngine
   **/
   function getSearchOptions() {
      global $LANG;

      $tab = array();
      $tab['common'] = $LANG['common'][32];

      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         = 'name';
      $tab[1]['name']          = $LANG['common'][16];
      $tab[1]['datatype']      = 'itemlink';
      $tab[1]['itemlink_link'] = $this->getType();
      $tab[1]['massiveaction'] = false;

      return $tab;
   }


   /**
    * Print out an HTML "<select>" for a dropdown
    *
    * This should be overloaded in Class
    * Parameters which could be used in options array :
    *    - name : string / name of the select (default is depending itemtype)
    *    - value : integer / preselected value (default 0)
    *    - comments : boolean / is the comments displayed near the dropdown (default true)
    *    - entity : integer or array / restrict to a defined entity or array of entities
    *                   (default -1 : no restriction)
    *    - toupdate : array / Update a specific item on select change on dropdown
    *                   (need value_fieldname, to_update, url (see ajaxUpdateItemOnSelectEvent for informations)
    *                   and may have moreparams)
    *    - used : array / Already used items ID: not to display in dropdown (default empty)
    *    - auto_submit : boolean / preselected value (default 0)
    *
    * @param $options possible options
    * @return nothing display the dropdown
   **/
   static function dropdown($options=array()) {
      echo "This function cannot be used for the moment. Use Dropdown::show instead.";
      /*
      Dropdown::show(get_called_class(),$options);
      */
   }


   /**
    * Add div to display form's tabs
   **/
   function addDivForTabs() {

      echo "<div id='tabcontent'>&nbsp;</div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";
   }


   /**
    * Return a search option by looking for a value of a specific field and maybe a specific table
    *
    * @param field the field in which looking for the value (for exampe : table, name, etc)
    * @param value the value to look for in the field
    * @param table the table
    *
    * @return then search option array, or an empty array if not found
   **/
   function getSearchOptionByField($field, $value, $table='') {

      foreach (Search::getOptions(get_class($this)) as $searchOption) {
         if ((isset($searchOption['linkfield']) && $searchOption['linkfield'] == $value)
             || (isset($searchOption[$field]) && $searchOption[$field] == $value)) {
            if (($table == '') || ($table != '' && $searchOption['table'] == $table)) {
               return $searchOption;
            }
         }
      }
      return array();
   }


   /**
    * Check float and decimal values
    *
    * @param display or not messages in and addAfterRedirect
    *
    * @return input the data checked
   **/
   function filterValues($display=true) {
      global $LANG;

      if (in_array('CommonDBRelation',class_parents($this))) {
         return true;
      }
      //Type mismatched fields
      $fails = array();
      if (isset($this->input) && is_array($this->input) && count($this->input)) {

         foreach ($this->input as $key => $value) {
            $unset        = false;
            $regs         = array();
            $searchOption = $this->getSearchOptionByField('field', $key);

            if (isset($searchOption['datatype'])
                && !is_null($value)
                && $value != ''
                && $value != 'NULL') {

               switch ($searchOption['datatype']) {
                  case 'integer' :
                  case 'number' :
                  case 'decimal' :
                     $value = str_replace(',','.',$value);
                     if ($searchOption['datatype'] == 'decimal') {
                        $this->input[$key] = floatval($value);
                     } else {
                        $this->input[$key] = intval($value);
                     }
                     if (!is_numeric($value)) {
                        $unset = true;
                     }
                     break;

                  case 'bool' :
                     if (!in_array($value,array(0,1))) {
                        $unset = true;
                     }
                     break;

                  case 'ip' :
                     $pattern  = "\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.";
                     $pattern .= "(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.";
                     $pattern .= "(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.";
                     $pattern .= "(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b";
                     if (!preg_match("/$pattern/", $value, $regs)) {
                        $unset = true;
                     }
                     break;

                  case 'mac' :
                     preg_match("/([0-9a-fA-F]{1,2}([:-]|$)){6}$/",$value,$regs);
                     if (empty($regs)) {
                        $unset = true;
                     }
                     break;

                  case 'date' :
                  case 'datetime' :
                     // Date is already "reformat" according to getDateFormat()
                     $pattern  = "/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})";
                     $pattern .= "([_][01][0-9]|2[0-3]:[0-5][0-9]:[0-5]?[0-9])?/";
                     preg_match($pattern, $value, $regs);
                     if (empty($regs)) {
                        $unset = true;
                     }
                     break;

                  case 'itemtype' :
                     //Want to insert an itemtype, but the associated class doesn't exists
                     if (!class_exists($value)) {
                        $unset = true;
                     }

                  case 'email' :
                  case 'string' :
                     if (strlen($value)>255) {
                        $this->input[$key] = substr($value, 0, 254);
                     }
                     break;

                  default :
                     //Plugins can implement their own checks
                     if (!$this->checkSpecificValues($searchOption['datatype'],$value)) {
                        $unset = true;
                     }
                     break;
                }
            }

            if ($unset) {
               $fails[] = $searchOption['name'];
               unset($this->input[$key]);
            }
         }
      }
      if ($display && count($fails)) {
         //Display a message to indicate that one or more value where filtered
         $message = $LANG['common'][106].' : '.implode(',',$fails);
         addMessageAfterRedirect($message, INFO, true);
      }
   }


   /**
    * Add more check for values
    *
    * @param datatype datatype of the value
    * @param value value to check (pass by reference)
    *
    * @return true if value is ok, false if not
   **/
   function checkSpecificValues($datatype, &$value) {
      return true;
   }


   /**
    * Get fields to display in the unicity error message
    *
    * @return an aray which contains field => label
   **/
   function getUnicityFieldsToDisplayInErrorMessage() {
      global $LANG;

      return array('id'          => $LANG['common'][2],
                   'serial'      => $LANG['common'][19],
                   'entities_id' => $LANG['entity'][0]);
   }


   function getUnallowedFieldsForUnicity() {
      return array('alert', 'comment', 'date_mod', 'id', 'is_recursive', 'items_id', 'notepad');
   }

   /**
    * Build an unicity error message
    *
    * @param $message the string to be display on the screen, or to be sent in a notification
    * @param $unicity the unicity criterion that failed to match
    * @param $doubles the items that are already present in DB
   **/
   function getUncityErrorMessage($message, $unicity, $doubles) {
      global $LANG;

      if ($unicity['action_refuse']) {
         $message_text = $LANG['setup'][813];
      } else {
         $message_text = $LANG['setup'][823];
      }
      $message_text .= " ".implode('&nbsp;&&nbsp;',$message);
      $message_text .= $LANG['setup'][818];

      foreach ($doubles as $double) {
         $doubles_text = array();

         if (in_array('CommonDBChild',class_parents($this))) {
            if ($this->getField($this->itemtype)) {
               $item = new $double['itemtype'];
            } else {
               $item = new $this->itemtype;
            }

            $item->getFromDB($double['items_id']);
         } else {
            $item = new CommonDBTM();
            $item->fields = $double;
         }

         foreach ($this->getUnicityFieldsToDisplayInErrorMessage() as $key => $value) {
            $field_value = $item->getField($key);
            if ($field_value != NOT_AVAILABLE) {
               if (getTableNameForForeignKeyField($key) != '') {
                  $field_value = Dropdown::getDropdownName(getTableNameForForeignKeyField($key),
                                                           $field_value);
               }
               $doubles_text[] =  $value.": ".$field_value;
            }
         }
         $message_text .= "<br>[".implode(', ',$doubles_text)."]";
      }
      return $message_text;
   }


   /**
    * Check field unicity before insert or update
    *
    * @param add true for insert, false for update
    * @param $options array
    *
    * @return true if item can be written in DB, false if not
   **/
   function checkUnicity($add=false, $options=array()) {
      global $LANG, $DB, $CFG_GLPI;

      $p['unicity_error_message']  = true;
      $p['add_event_on_duplicate'] = true;
      $p['disable_unicity_check']  = false;

      if (is_array($options) && count($options)) {
         foreach ($options as $key => $value) {
            $p[$key] = $value;
         }
      }

      $result = true;

      //Do not check unicity when creating infocoms or if checking is expliclty disabled
      if ($p['disable_unicity_check']) {
         return $result;
      }

      //Get all checks for this itemtype and this entity
      if (in_array(get_class($this), $CFG_GLPI["unicity_types"])) {
         // Get input entities if set / else get object one
         if (isset($this->input['entities_id'])) {
            $entities_id = $this->input['entities_id'];
         } else {
            $entities_id = $this->fields['entities_id'];
         }

         $all_fields =  FieldUnicity::getUnicityFieldsConfig(get_class($this), $entities_id);
         foreach ($all_fields  as $key => $fields) {

            //If there's fields to check
            if (!empty($fields) && !empty($fields['fields'])) {
               $where    = "";
               $continue = true;
               foreach (explode(',',$fields['fields']) as $field) {
                  if (isset($this->input[$field]) //Field is set
                      //Standard field not null
                      && ((getTableNameForForeignKeyField($field) == '' && $this->input[$field] != '')
                          //Foreign key and value is not 0
                          || (getTableNameForForeignKeyField($field) != ''
                              && $this->input[$field] > 0))
                      && !Fieldblacklist::isFieldBlacklisted(get_class($this), $entities_id, $field,
                                                             $this->input[$field])) {
                     $where .= " AND `".$this->getTable()."`.`$field` = '".$this->input[$field]."'";
                  } else {
                     $continue = false;
                  }
               }

               if ($continue && $where != '') {
                  $entities = $fields['entities_id'];
                  if ($fields['is_recursive']) {
                     $entities = getSonsOf('glpi_entities', $fields['entities_id']);
                  }
                  $where_global = getEntitiesRestrictRequest(" AND", $this->getTable(), '',
                                                             $entities);

                  //If update, exclude ID of the current object
                  if (!$add) {
                     $where .= " AND `".$this->getTable()."`.`id` NOT IN (".$this->input['id'].") ";
                  }

                  if (countElementsInTable($this->table,"1 $where $where_global") > 0) {
                     if ($p['unicity_error_message'] || $p['add_event_on_duplicate']) {
                        $message = array();
                        foreach (explode(',',$fields['fields']) as $field) {
                           $table = getTableNameForForeignKeyField($field);
                           if ($table != '') {
                              $searchOption = $this->getSearchOptionByField('field', 'name',
                                                                            $table);
                           } else {
                              $searchOption = $this->getSearchOptionByField('field', $field);
                           }
                           $message[] = $searchOption['name'].'='.$this->input[$field];
                        }

                        $doubles      = getAllDatasFromTable($this->table, "1 $where $where_global");
                        $message_text = $this->getUncityErrorMessage($message, $fields, $doubles);
                        if ($p['unicity_error_message']) {
                           if (!$fields['action_refuse']) {
                           $show_other_messages = ($fields['action_refuse']?true:false);
                           } else {
                              $show_other_messages = true;
                           }
                           addMessageAfterRedirect($message_text, true, $show_other_messages,
                                                   $show_other_messages);
                        }
                        if ($p['add_event_on_duplicate']) {
                           Event::log ((!$add?$this->fields['id']:0), get_class($this), 4,
                                       'inventory',
                                       $_SESSION["glpiname"]." ".$LANG['log'][123].' : '.
                                          $message_text);
                        }
                     }
                     if($fields['action_refuse']) {
                        $result = false;
                     }
                     if($fields['action_notify']) {
                        $params = array('message'     => html_clean($message_text),
                                        'action_type' => $add,
                                        'action_user' => getUserName(getLoginUserID()),
                                        'entities_id' => $entities_id,
                                        'itemtype'    => get_class($this),
                                        'date'        => $_SESSION['glpi_currenttime'],
                                        'refuse'      => $fields['action_refuse']);
                        NotificationEvent::raiseEvent('refuse', new FieldUnicity(), $params);
                     }
                  }
               }
            }
         }

      }

      return $result;
   }


   /**
    * Clean all infos which match some criteria
    *
    * @param $crit array of criteria (ex array('is_active'=>'1'))
    * @param $force boolean force purge not on put in trash
   **/
   function deleteByCriteria($crit=array(), $force=0) {
      global $DB;

      if (is_array($crit) && count($crit)>0) {
         $crit['FIELDS'] = 'id';

         foreach ($DB->request($this->getTable(), $crit) as $row) {
            $this->delete($row, $force);
         }

      }
   }
}

?>
