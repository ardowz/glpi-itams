<?php
/*
 * @version $Id: printer.class.php 14684 2011-06-11 06:32:40Z remi $
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
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

// CLASSES Printers

class Printer  extends CommonDBTM {

   // From CommonDBTM
   public $dohistory=true;
   protected $forward_entity_to = array('Infocom', 'NetworkPort', 'ReservationItem');


/**
 * Name of the type
 *
 * @param $nb : number of item in the type
 *
 * @return $LANG
 */
   static function getTypeName($nb=0) {
      global $LANG;

      if ($nb>1) {
         return $LANG['Menu'][2];
      }
      return $LANG['help'][27];
   }


   function canCreate() {
      return haveRight('printer', 'w');
   }


   function canView() {
      return haveRight('printer', 'r');
   }


   function defineTabs($options=array()) {
      global $LANG, $CFG_GLPI;

      $ong = array();
      if ($this->fields['id'] > 0) {
         if (haveRight("cartridge","r")) {
            $ong[1] = $LANG['Menu'][21];
         }

         if (haveRight("networking","r") || haveRight("computer","r")) {
            $ong[3] = $LANG['title'][27];
         }

         if (haveRight("contract","r") || haveRight("infocom","r")) {
            $ong[4] = $LANG['Menu'][26];
         }

         if (haveRight("document","r")) {
            $ong[5] = $LANG['Menu'][27];
         }

         if (!isset($options['withtemplate']) || empty($options['withtemplate'])) {
            if (haveRight("show_all_ticket","1")) {
               $ong[6] = $LANG['title'][28];
            }

            if (haveRight("link","r")) {
               $ong[7] = $LANG['title'][34];
            }

            if (haveRight("notes","r")) {
               $ong[10] = $LANG['title'][37];
            }

            if (haveRight("reservation_central","r")) {
               $ong[11] = $LANG['Menu'][17];
            }

            $ong[12] = $LANG['title'][38];
         }

      } else { // New item
         $ong[1] = $LANG['title'][26];
      }

      return $ong;
   }


   /**
    * Can I change recusvive flag to false
    * check if there is "linked" object in another entity
    *
    * Overloaded from CommonDBTM
    *
    * @return booleen
    **/
   function canUnrecurs () {
      global $DB, $CFG_GLPI;

      $ID = $this->fields['id'];

      if ($ID<0 || !$this->fields['is_recursive']) {
         return true;
      }

      if (!parent::canUnrecurs()) {
         return false;
      }

      $entities = "(".$this->fields['entities_id'];

      foreach (getAncestorsOf("glpi_entities",$this->fields['entities_id']) as $papa) {
         $entities .= ",$papa";
      }

      $entities .= ")";

      // RELATION : printers -> _port -> _wire -> _port -> device

      // Evaluate connection in the 2 ways
      for ($tabend = array("networkports_id_1" => "networkports_id_2",
                           "networkports_id_2" => "networkports_id_1");
           list($enda, $endb) = each($tabend);) {

         $sql = "SELECT `itemtype`,
                        GROUP_CONCAT(DISTINCT `items_id`) AS ids
                 FROM `glpi_networkports_networkports`,
                      `glpi_networkports`
                 WHERE `glpi_networkports_networkports`.`$endb` = `glpi_networkports`.`id`
                       AND `glpi_networkports_networkports`.`$enda`
                            IN (SELECT `id`
                                FROM `glpi_networkports`
                                WHERE `itemtype` = '".$this->getType()."'
                                      AND `items_id` = '$ID')
                 GROUP BY `itemtype`";
         $res = $DB->query($sql);

         if ($res) {
            while ($data = $DB->fetch_assoc($res)) {
               $itemtable = getTableForItemType($data["itemtype"]);
               $item      = new $data["itemtype"]();
               // For each itemtype which are entity dependant
               if ($item->isEntityAssign()) {

                  if (countElementsInTable($itemtable, "`id` IN (".$data["ids"].")
                                           AND `entities_id` NOT IN $entities")>0) {
                     return false;
                  }
               }
            }
         }
      }
      return true;
   }


   function prepareInputForAdd($input) {

      if (isset($input["id"]) && $input["id"]>0) {
         $input["_oldID"]=$input["id"];
      }
      unset($input['id']);
      unset($input['withtemplate']);

      if (isset($input['init_pages_counter'])) {
         $input['init_pages_counter'] = intval($input['init_pages_counter']);
      } else {
         $input['init_pages_counter'] = 0;
      }

      return $input;
   }


   function prepareInputForUpdate($input) {

      if (isset($input['init_pages_counter'])) {
         $input['init_pages_counter'] = intval($input['init_pages_counter']);
      }

      return $input;
   }


   function post_addItem() {
      global $DB, $CFG_GLPI;

      // Manage add from template
      if (isset($this->input["_oldID"])) {
         // ADD Infocoms
         $ic = new Infocom();
         $ic->cloneItem($this->getType(), $this->input["_oldID"], $this->fields['id']);

         // ADD Ports
         $query = "SELECT `id`
                   FROM `glpi_networkports`
                   WHERE `items_id` = '".$this->input["_oldID"]."'
                         AND `itemtype` = '".$this->getType()."'";
         $result = $DB->query($query);

         if ($DB->numrows($result)>0) {
            while ($data=$DB->fetch_array($result)) {
               $np  = new NetworkPort();
               $npv = new NetworkPort_Vlan();
               $np->getFromDB($data["id"]);
               unset($np->fields["id"]);
               unset($np->fields["ip"]);
               unset($np->fields["mac"]);
               unset($np->fields["netpoints_id"]);
               $np->fields["items_id"]     = $this->fields['id'];
               $np->fields["entities_id"]  = $this->fields['entities_id'];
               $np->fields["is_recursive"] = $this->fields['is_recursive'];

               $portid = $np->addToDB();
               foreach ($DB->request('glpi_networkports_vlans',
                                     array('networkports_id' => $data["id"])) as $vlan) {
                  $npv->assignVlan($portid, $vlan['vlans_id']);
               }
            }
         }

         // ADD Contract
         $query = "SELECT `contracts_id`
                   FROM `glpi_contracts_items`
                   WHERE `items_id` = '".$this->input["_oldID"]."'
                         AND `itemtype` = '".$this->getType()."'";
         $result = $DB->query($query);

         if ($DB->numrows($result)>0) {
            $contractitem = new Contract_Item();

            while ($data=$DB->fetch_array($result)) {
               $contractitem->add(array('contracts_id' => $data["contracts_id"],
                                        'itemtype'     => $this->getType(),
                                        'items_id'     => $this->fields['id']));
            }
         }

         // ADD Documents
         $query = "SELECT `documents_id`
                   FROM `glpi_documents_items`
                   WHERE `items_id` = '".$this->input["_oldID"]."'
                         AND `itemtype` = '".$this->getType()."'";
         $result = $DB->query($query);

         if ($DB->numrows($result)>0) {
            $docitem = new Document_Item();

            while ($data=$DB->fetch_array($result)) {
               $docitem->add(array('documents_id' => $data["documents_id"],
                                   'itemtype'     => $this->getType(),
                                   'items_id'     => $this->fields['id']));
            }
         }
      }
   }


   function cleanDBonPurge() {
      global $DB;

      $query = "SELECT `id`
                FROM `glpi_computers_items`
                WHERE `itemtype` = '".$this->getType()."'
                      AND `items_id` = '".$this->fields['id']."'";

      if ($result = $DB->query($query)) {
         if ($DB->numrows($result)>0) {
            $conn = new Computer_Item();

            while ($data = $DB->fetch_array($result)) {
               $data['_no_auto_action'] = true;
               $conn->delete($data);
            }
         }
      }

      $query = "UPDATE `glpi_cartridges`
                SET `printers_id` = NULL
                WHERE `printers_id` = '".$this->fields['id']."'";
      $result = $DB->query($query);
   }


   /**
    * Print the printer form
    *
    * @param $ID integer ID of the item
    * @param $options array
    *     - target filename : where to go when done.
    *     - withtemplate boolean : template or basic item
    *
     *@return boolean item found
    **/
   function showForm ($ID, $options=array()) {
      global $CFG_GLPI, $LANG, $DB;

      $target       = $this->getFormURL();
      $withtemplate = '';

      if (isset($options['target'])) {
        $target = $options['target'];
      }

      if (isset($options['withtemplate'])) {
         $withtemplate = $options['withtemplate'];
      }

      if (!haveRight("printer","r")) {
         return false;
      }

      if ($ID > 0) {
         $this->check($ID,'r');
      } else {
         // Create item
         $this->check(-1,'w');
      }

      if (isset($options['withtemplate']) && $options['withtemplate'] == 2) {
         $template   = "newcomp";
         $datestring = $LANG['computers'][14]."&nbsp;: ";
         $date       = convDateTime($_SESSION["glpi_currenttime"]);

      } else if (isset($options['withtemplate']) && $options['withtemplate'] == 1) {
         $template   = "newtemplate";
         $datestring = $LANG['computers'][14]."&nbsp;: ";
         $date       = convDateTime($_SESSION["glpi_currenttime"]);

      } else {
         $datestring = $LANG['common'][26]."&nbsp;: ";
         $date       = convDateTime($this->fields["date_mod"]);
         $template   = false;
      }

      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][16].($template?"*":"")."&nbsp;:</td>\n";
      echo "<td>";
      $objectName = autoName($this->fields["name"], "name", ($template === "newcomp"),
                             $this->getType(), $this->fields["entities_id"]);
      autocompletionTextField($this, 'name', array('value' => $objectName));
      echo "</td>\n";
         
      echo "<td> Useful Life </td>";
      //useful life
      echo "<td>";
      
      if($ID > 0){
          echo $this->getUsefulLife($ID);
      }else{
          echo "<input type='text' name='life' id='life'></input>";
      }
      
      echo "</td>";
      
      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
      /*echo "<td>".$LANG['common'][18]."&nbsp;:</td>\n";
      echo "<td>";
      autocompletionTextField($this, "contact");
      echo "</td>\n";*/
      echo "<td>".$LANG['common'][19]."&nbsp;:</td>\n";
      echo "<td>";
      autocompletionTextField($this, "serial");
      echo "</td>";
      echo "<td>".$LANG['common'][20].($template?"*":"")."&nbsp;:</td>\n";
      echo "<td>";
      $objectName = autoName($this->fields["otherserial"], "otherserial", ($template === "newcomp"),
                             $this->getType(), $this->fields["entities_id"]);
      autocompletionTextField($this, 'otherserial', array('value' => $objectName));
      echo "</td>";

      echo "</tr>\n";


      echo "<tr class='tab_bg_1'>";
      

      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
     /* echo "<td>".$LANG['common'][10]."&nbsp;:</td>\n";
      echo "<td>";
      User::dropdown(array('name'   => 'users_id_tech',
                           'value'  => $this->fields["users_id_tech"],
                           'right'  => 'interface',
                           'entity' => $this->fields["entities_id"]));
      echo "</td>\n";*/
      echo "<td>".$LANG['common'][17]."&nbsp;:</td>\n";
      echo "<td>";
      Dropdown::show('PrinterType', array('value' => $this->fields["printertypes_id"]));
      echo "</td>";

      echo "<td>".$LANG['common'][5]."&nbsp;:</td>\n";
      echo "<td>";
      Dropdown::show('Manufacturer', array('value' => $this->fields["manufacturers_id"]));
      echo "</td></tr>\n";

      echo "<tr class='tab_bg_1'>";
      /*echo "<td>".$LANG['common'][21]."&nbsp;:</td>\n";
      echo "<td>";
      autocompletionTextField($this, "contact_num");
      echo "</td>\n";*/
      echo "<td>".$LANG['common'][22]."&nbsp;:</td>\n";
      echo "<td>";
      Dropdown::show('PrinterModel', array('value' => $this->fields["printermodels_id"]));
      echo "</td>";
      echo "<td>";
      echo "Repair Count: ";
      echo "</td>";
      echo "<td>";
      
       $repairCount = $this->getRepairCount($ID, $this->getTypeName());
      
      $state = $this->getRepairTreshold($this->getTypeName(),$repairCount,$ID);
//      $db = new CommonDBTM();
//      $query = "SELECT COUNT(*) as count
//FROM glpi_tickets
//WHERE itemtype = 'Printer' and items_id = '".$ID."' AND ticketsolutiontypes_id = '11'";
//        $result = $DB->query($query);
////      $resultid = $DB->query($queryid);
//      if ($DB->query($query)) {
//           while ($data=$DB->fetch_array($result)) {
//              $count = $data["count"];
//           }
//      }
//      
//      //repair count here
//      echo $count;
//      if ($count >= 3){
//          echo " - Decommission";
//      }
      echo "</td>";
      echo "</tr>\n";

      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][109]."&nbsp;:</td>\n";
      echo "<td>";
      User::dropdown(array('value'  => $this->fields["users_id"],
                           'entity' => $this->fields["entities_id"],
                           'right'  => 'all'),$state);
      echo "</td>";

      echo "<td>".$LANG['common'][15]."&nbsp;: </td>\n";
      echo "<td>";
      Dropdown::show('Location', array('value'  => $this->fields["locations_id"],
                                       'entity' => $this->fields["entities_id"]));
      echo "</td>\n";


      echo "</tr>\n";
 //status
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['state'][0]."&nbsp;:</td>";
      echo "<td>";
      Dropdown::show('State', array('value' => $this->fields["states_id"]));
      echo "</td>";
      echo "</tr>";



/*
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][35]."&nbsp;:</td>\n";
      echo "<td>";
      Dropdown::show('Group', array('value'  => $this->fields["groups_id"],
                                    'entity' => $this->fields["entities_id"]));
      echo "</td>\n";
      echo "<td>".$LANG['peripherals'][33]."&nbsp;:</td>";
      echo "<td>";
      if ($this->can($ID,'w')) {
         Dropdown::showGlobalSwitch($this->fields["id"],
                                    array('withtemplate' => $withtemplate,
                                          'value'        => $this->fields["is_global"],
                                          'management_restrict'
                                                         => $CFG_GLPI["printers_management_restrict"],
                                          'target'       => $target));
      } else {
         Dropdown::showGlobalSwitch($this->fields["id"],
                                    array('withtemplate' => $withtemplate,
                                          'value'        => $this->fields["is_global"],
                                          'target'       => $target));
      }
      echo "</td></tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['setup'][89]."&nbsp;:</td>\n";
      echo "<td>";
      Dropdown::show('Domain', array('value' => $this->fields["domains_id"]));
      echo "</td>";
      echo "<td>".$LANG['setup'][88]."&nbsp;:</td>\n";
      echo "<td>";
      Dropdown::show('Network', array('value' => $this->fields["networks_id"]));
      echo "</td></tr>\n";
*/
     /* echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['devices'][6]."&nbsp;:</td>\n";
      echo "<td>";
      autocompletionTextField($this, "memory_size");
      echo "</td>";
      /*echo "<td rowspan='4'>";
      echo $LANG['common'][25]."&nbsp;:</td>\n";
      echo "<td rowspan='4'><textarea cols='45' rows='8' name='comment' >".
            $this->fields["comment"]."</textarea>";
      echo "</td></tr>\n";*/
echo "</tr>\n";

/*
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['printers'][30]."&nbsp;:</td>\n";
      echo "<td>";
      autocompletionTextField($this, "init_pages_counter");
      echo "</td></tr>\n";
*/
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['printers'][18]."&nbsp;: </td>";
      echo "<td>\n<table>";

      // serial interface
      echo "<tr><td>".$LANG['printers'][14]."</td><td>";
      Dropdown::showYesNo("have_serial", $this->fields["have_serial"]);
      echo "</td></tr>";
      // parallel interface?
      echo "<tr><td>".$LANG['printers'][15]."</td><td>";
      Dropdown::showYesNo("have_parallel", $this->fields["have_parallel"]);
      echo "</td></tr>";
      // USB interface?
      echo "<tr><td>".$LANG['printers'][27]."</td><td>";
      Dropdown::showYesNo("have_usb", $this->fields["have_usb"]);
      echo "</td></tr>";
      // ethernet interface?
      echo "<tr><td>".$LANG['printers'][28]."</td><td>";
      Dropdown::showYesNo("have_ethernet",$this->fields["have_ethernet"]);
      echo "</td></tr>";
      // wifi ?
      echo "<tr><td>".$LANG['printers'][29]."</td><td>";
      Dropdown::showYesNo("have_wifi", $this->fields["have_wifi"]);
      echo "</td></tr></table>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2' class='center' height='30'>".$datestring."&nbsp;".$date;
      if (!$template && !empty($this->fields['template_name'])) {
         echo "<span class='small_space'>";
         echo "(".$LANG['common'][13]."&nbsp;: ".$this->fields['template_name'].")</span>";
      }
      echo "</td></tr>\n";

      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }


   /**
    * Return the SQL command to retrieve linked object
    *
    * @return a SQL command which return a set of (itemtype, items_id)
    **/
   function getSelectLinkedItem () {

      return "SELECT 'Computer', `computers_id`
              FROM `glpi_computers_items`
              WHERE `itemtype` = '".$this->getType()."'
                    AND `items_id` = '" . $this->fields['id']."'";
   }


   function getSearchOptions() {
      global $LANG;

      $tab = array();
      $tab['common']           = $LANG['common'][32];

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

      $tab+=Location::getSearchOptionsToAdd();

      $tab[4]['table'] = 'glpi_printertypes';
      $tab[4]['field'] = 'name';
      $tab[4]['name']  = $LANG['common'][17];

      $tab[40]['table'] = 'glpi_printermodels';
      $tab[40]['field'] = 'name';
      $tab[40]['name']  = $LANG['common'][22];

      $tab[31]['table'] = 'glpi_states';
      $tab[31]['field'] = 'name';
      $tab[31]['name']  = $LANG['state'][0];

      $tab[5]['table']     = $this->getTable();
      $tab[5]['field']     = 'serial';
      $tab[5]['name']      = $LANG['common'][19];
      $tab[5]['datatype']  = 'string';

      $tab[6]['table']     = $this->getTable();
      $tab[6]['field']     = 'otherserial';
      $tab[6]['name']      = $LANG['common'][20];
      $tab[6]['datatype']  = 'string';

      $tab[7]['table']     = $this->getTable();
      $tab[7]['field']     = 'contact';
      $tab[7]['name']      = $LANG['common'][18];
      $tab[7]['datatype']  = 'string';

      $tab[8]['table']     = $this->getTable();
      $tab[8]['field']     = 'contact_num';
      $tab[8]['name']      = $LANG['common'][21];
      $tab[8]['datatype']  = 'string';

      $tab[70]['table'] = 'glpi_users';
      $tab[70]['field'] = 'name';
      $tab[70]['name']  = $LANG['common'][34];

      $tab[71]['table'] = 'glpi_groups';
      $tab[71]['field'] = 'name';
      $tab[71]['name']  = $LANG['common'][35];

      $tab[19]['table']         = $this->getTable();
      $tab[19]['field']         = 'date_mod';
      $tab[19]['name']          = $LANG['common'][26];
      $tab[19]['datatype']      = 'datetime';
      $tab[19]['massiveaction'] = false;

      $tab[16]['table']    = $this->getTable();
      $tab[16]['field']    = 'comment';
      $tab[16]['name']     = $LANG['common'][25];
      $tab[16]['datatype'] = 'text';

      $tab[42]['table']    = $this->getTable();
      $tab[42]['field']    = 'have_serial';
      $tab[42]['name']     = $LANG['printers'][14];
      $tab[42]['datatype'] = 'bool';

      $tab[43]['table']    = $this->getTable();
      $tab[43]['field']    = 'have_parallel';
      $tab[43]['name']     = $LANG['printers'][15];
      $tab[43]['datatype'] = 'bool';

      $tab[44]['table']    = $this->getTable();
      $tab[44]['field']    = 'have_usb';
      $tab[44]['name']     = $LANG['printers'][27];
      $tab[44]['datatype'] = 'bool';

      $tab[45]['table']    = $this->getTable();
      $tab[45]['field']    = 'have_ethernet';
      $tab[45]['name']     = $LANG['printers'][28];
      $tab[45]['datatype'] = 'bool';

      $tab[46]['table']    = $this->getTable();
      $tab[46]['field']    = 'have_wifi';
      $tab[46]['name']     = $LANG['printers'][29];
      $tab[46]['datatype'] = 'bool';

      $tab[90]['table']         = $this->getTable();
      $tab[90]['field']         = 'notepad';
      $tab[90]['name']          = $LANG['title'][37];
      $tab[90]['massiveaction'] = false;

      $tab[32]['table'] = 'glpi_networks';
      $tab[32]['field'] = 'name';
      $tab[32]['name']  = $LANG['setup'][88];

      $tab[33]['table'] = 'glpi_domains';
      $tab[33]['field'] = 'name';
      $tab[33]['name']  = $LANG['setup'][89];

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

      $tab[82]['table']         = $this->getTable();
      $tab[82]['field']         = 'is_global';
      $tab[82]['name']          = $LANG['peripherals'][31];
      $tab[82]['datatype']      = 'bool';
      $tab[82]['massiveaction'] = false;

      $tab[86]['table']    = $this->getTable();
      $tab[86]['field']    = 'is_recursive';
      $tab[86]['name']     = $LANG['entity'][9];
      $tab[86]['datatype'] = 'bool';

      return $tab;
   }
      /**
    * Add a printer. If already exist in trash restore it
    *
    * @param name the printer's name
    * @param manufacturer the software's manufacturer
    * @param entity the entity in which the software must be added
    * @param comment comment
   */
   function addOrRestoreFromTrash($name, $manufacturer, $entity, $comment='') {
      global $DB;

      //Look for the software by his name in GLPI for a specific entity
      $query_search = "SELECT `glpi_printers`.`id`, `glpi_printers`.`is_deleted`
                       FROM `glpi_printers`
                       WHERE `name` = '$name'
                             AND `is_template` = '0'
                             AND `entities_id` = '$entity'";

      $result_search = $DB->query($query_search);

      if ($DB->numrows($result_search) > 0) {
         //Printer already exists for this entity, get his ID
         $data = $DB->fetch_array($result_search);
         $ID   = $data["id"];

         // restore software
         if ($data['is_deleted']) {
            $this->removeFromTrash($ID);
         }

      } else {
         $ID = 0;
      }

      if (!$ID) {
         $ID = $this->addPrinter($name, $manufacturer, $entity, $comment);
      }
      return $ID;
   }


   /**
    * Create a new printer
    *
    * @param name the printer's name
    * @param manufacturer the printer's manufacturer
    * @param entity the entity in which the printer must be added
    * @param comment
    *
    * @return the printer's ID
   **/
   function addPrinter($name, $manufacturer, $entity, $comment = '') {
      global $DB, $CFG_GLPI;

      $manufacturer_id = 0;
      if ($manufacturer != '') {
         $manufacturer_id = Dropdown::importExternal('Manufacturer', $manufacturer);
      }

      //If there's a printer in a parent entity with the same name and manufacturer
      $sql = "SELECT `id`
              FROM `glpi_printers`
              WHERE `manufacturers_id` = '$manufacturer_id'
                    AND `name` = '$name' " .
                    getEntitiesRestrictRequest('AND', 'glpi_printers', 'entities_id', $entity,
                                               true);

      $res_printer = $DB->query($sql);
      if ($printer = $DB->fetch_array($res_printer)) {
         $id = $printer["id"];
      } else {
         $input["name"]             = $name;
         $input["manufacturers_id"] = $manufacturer_id;
         $input["entities_id"]      = $entity;

         $id = $this->add($input);
      }
      return $id;
   }


   /**
    * Restore a software from trash
    *
    * @param $ID  the ID of the software to put in trash
    *
    * @return boolean (success)
   **/
   function removeFromTrash($ID) {
      return $this->restore(array("id" => $ID));
   }
   
   function getUsefulLife($id){
    global $DB;
    
    $queryid = "SELECT dateadd,life FROM sideb_usefullife where asset_id = '$id' and type = 'printer'";
    $result = $DB->query($queryid);
//      $resultid = $DB->query($queryid);
    if ($DB->query($queryid)) {
           while ($data=$DB->fetch_array($result)) {
              $dateadd = strtotime($data["dateadd"]);
              $assetlife = $data["life"];
             }
    }
    //echo $id;
    $dateaddConverted = date("Y-m-d H:i:s", $dateadd);
    
    $comput = "+".$assetlife." year";
    $lifeLeft = strtotime ( $comput , $dateadd);
    $lifeDate = date ('Y-m-d', $lifeLeft);
//    echo "the asset is added on: ".$dateaddConverted;
//    echo "<br/>";
//    echo "the asset's useful life is on: ".$assetlife;
//    echo "<br/>";
//    echo "the asset is useful until".$lifeDate;
//    
    return $lifeDate;
}
   
}

?>