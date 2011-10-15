<?php
/*
 * @version $Id: computer_device.class.php 14684 2011-06-11 06:32:40Z remi $
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


/**
 * Relation between Computer and devices
**/
class Computer_Device extends CommonDBTM {

   public $auto_message_on_action = false;


   function __construct($itemtype='') {

      if (!empty($itemtype)) {
         $linktable = getTableForItemType('Computer_'.$itemtype);
         $this->forceTable($linktable);
      }
   }


   /**
    * Get itemtype of devices : key is ocs identifier
   **/
   static function getDeviceTypes() {

      return array(1 => 'DeviceMotherboard', 2 => 'DeviceProcessor',   3 => 'DeviceMemory',
                   4 => 'DeviceHardDrive',   5 => 'DeviceNetworkCard', 6 => 'DeviceDrive',
                   7 => 'DeviceControl',     8 => 'DeviceGraphicCard', 9 => 'DeviceSoundCard',
                  10 => 'DevicePci',        11 => 'DeviceCase',       12 => 'DevicePowerSupply');
   }


   function getEmpty() {

      $this->fields['id'] = '';
      $this->fields['computers_id'] = '';
   }


   function canCreate() {
      return haveRight('computer', 'w');
   }


   function canView() {
      return haveRight('computer', 'r');
   }


   function prepareInputForAdd($input) {

      // For add from interface
      if (isset($input['itemtype'])) {
         $input['_itemtype'] = $input['itemtype'];
         unset($input['itemtype']);
      }

      if (empty($input['_itemtype']) || !$input['computers_id']) {
         return false;
      }

      $dev = new $input['_itemtype']();
      // For add from interface
      if (isset($input['items_id'])) {
         $input[$dev->getForeignKeyField()] = $input['items_id'];
         unset($input['items_id']);
      }

      if (!$input[$dev->getForeignKeyField()]) {
         return false;
      }

      $linktable = getTableForItemType('Computer_'.$input['_itemtype']);
      $this->forceTable($linktable);

      if (count($dev->getSpecifityLabel()) > 0
          && (!isset($input['specificity']) || empty($input['specificity']))) {

         $dev = new $input['_itemtype'];
         $dev->getFromDB($input[$dev->getForeignKeyField()]);
         $input['specificity'] = $dev->getField('specif_default');
      }
      return $input;
   }


   /**
    * overload to log HISTORY_ADD_DEVICE instead of HISTORY_ADD_RELATION
   **/
   function post_addItem() {

      if (isset($this->input['_no_history']) && $this->input['_no_history']) {
         return false;
      }
      $dev = new $this->input['_itemtype']();

      $dev->getFromDB($this->fields[$dev->getForeignKeyField()]);
      $changes[0] = 0;
      $changes[1] = '';
      $changes[2] = addslashes($dev->getName());
      Log::history($this->fields['computers_id'], 'Computer', $changes, get_class($dev),
                   HISTORY_ADD_DEVICE);
   }


   /**
    * overload to log HISTORY_DELETE_DEVICE instead of HISTORY_DEL_RELATION
   **/
   function post_deleteFromDB() {

      if (isset($this->input['_no_history']) && $this->input['_no_history']) {
         return false;
      }
      $dev = new $this->input['_itemtype']();

      $dev->getFromDB($this->fields[$dev->getForeignKeyField()]);
      $changes[0] = 0;
      $changes[1] = addslashes($dev->getName());
      $changes[2] = '';
      Log::history($this->fields['computers_id'], 'Computer', $changes, get_class($dev),
                   HISTORY_DELETE_DEVICE);
   }


   function post_updateItem($history=1) {

      if (!$history
          || (isset($this->input['_no_history']) &&  $this->input['_no_history'])
          || !in_array('specificity',$this->updates)) {
         return false;
      }

      $changes[0] = 0;
      $changes[1] = addslashes($this->oldvalues['specificity']);
      $changes[2] = $this->fields['specificity'];
      // history log
      Log::history($this->fields['computers_id'], 'Computer', $changes, $this->input['_itemtype'],
                   HISTORY_UPDATE_DEVICE);
   }


   /**
    * Print the form for devices linked to a computer or a template
    *
    * @param $computer Computer object
    * @param $withtemplate='' boolean : template or basic computer
    *
    * @return Nothing (display)
   **/
   static function showForComputer(Computer $computer, $withtemplate='') {
      global $DB, $LANG;

      $devtypes = self::getDeviceTypes();

      $ID = $computer->getField('id');
      if (!$computer->can($ID, 'r')) {
         return false;
      }
      $canedit = ($withtemplate!=2 && $computer->can($ID, 'w'));

      echo "<div class='spaced'>";
      if ($canedit) {
         echo "<form name='form_device_action' action='".getItemTypeFormURL(__CLASS__).
                "' method='post'>";
         echo "<input type='hidden' name='computers_id' value='$ID'>";
      }
      echo "<table class='tab_cadre_fixe' >";
      echo "<tr><th colspan='63'>".$LANG['title'][30]."</th></tr>";
      $nb = 0;

      $specificity_units = array('DeviceProcessor'   => $LANG['setup'][35],
                                 'DeviceMemory'      => $LANG['common'][82],
                                 'DeviceHardDrive'   => $LANG['common'][82],
                                 'DeviceGraphicCard' => $LANG['common'][82]);

      foreach ($devtypes as $itemtype) {
         initNavigateListItems($itemtype, $computer->getTypeName()." = ".$computer->getName());

         $device        = new $itemtype;
         $specificities = $device->getSpecifityLabel();
         $specif_fields = array_keys($specificities);
         $specif_text   = implode(',',$specif_fields);
         if (!empty($specif_text)) {
            $specif_text=" ,".$specif_text." ";
         }

         $linktable = getTableForItemType('Computer_'.$itemtype);
         $fk        = getForeignKeyFieldForTable(getTableForItemType($itemtype));

         $query = "SELECT COUNT(*) AS NB,
                          `id`,
                          `$fk`
                          $specif_text
                   FROM `$linktable`
                   WHERE `computers_id` = '$ID'
                   GROUP BY `$fk` $specif_text";

         $prev = '';
         foreach ($DB->request($query) as $data) {
            addToNavigateListItems($itemtype, $data[$fk]);

            if ($device->getFromDB($data[$fk])) {
               echo "<tr class='tab_bg_2'>";
               echo "<td class='center'>";
               Dropdown::showInteger("quantity_".$itemtype."_".$data['id'], $data['NB']);
               echo "</td><td>";
               //Component type
               if ($device->canCreate()) {
                  echo "<a href='".$device->getSearchURL()."'>".$device->getTypeName()."</a>";
               } else {
                  echo $device->getTypeName();
               }
               echo "</td><td>".$device->getLinkSideb($ID,$device->getTypeName())."</td>";

               $spec = $device->getFormData();
               if (isset($spec['label']) && count($spec['label'])) {
                  $colspan = (60/count($spec['label']));
                  foreach ($spec['label'] as $i => $label) {

                     if (isset($spec['value'][$i])) {
                        echo "<td colspan='$colspan'>".$spec['label'][$i]."&nbsp;: ";
                        echo $spec['value'][$i]."</td>";

                     } else if ($canedit) {
                        // Specificity
                        echo "<td class='right' colspan='$colspan'>".$spec['label'][$i]."&nbsp;: ";
                        echo "<input type='text' name='value_".$itemtype."_".$data['id']."' value='".
                               $data['specificity']."' size='".$spec['size']."'>";
                        if (isset($specificity_units[$device->getType()])) {
                           echo '&nbsp;'.$specificity_units[$device->getType()];
                        }
                        echo "</td>";

                     } else {
                        echo "<td colspan='$colspan'>".$spec['label'][$i]."&nbsp;: ";
                        echo $data['specificity'];
                        if (isset($specificity_units[$device->getType()])) {
                           echo '&nbsp;'.$specificity_units[$device->getType()];
                        }
                        echo "</td>";
                     }
                  }
               } else {
                  echo "<td colspan='60'>&nbsp;</td>";
               }
               echo "</tr>";
               $nb++;
            }
         }
      }

      if ($canedit) {
         if ($nb > 0) {
            echo "<tr><td colspan='63' class='tab_bg_1 center'>";
            echo "<input type='submit' class='submit' name='updateall' value='".
                   $LANG['buttons'][7]."'></td></tr>";
         }

         echo "<tr><td colspan='63' class='tab_bg_1 center'>";
         echo $LANG['devices'][0]."&nbsp;: ";
         echo "<input type='hidden' name='computerID' value='".$ID."'/>";
//         Dropdown::showAllItems('items_id', '', 0, -1, $devtypes);
         Dropdown::showAllItemsSideb('items_id', '', 0, -1, $devtypes);
         echo "<input type='submit' name='add' value=\"".$LANG['buttons'][8]."\" class='submit'>";
         echo "</tr></table></form>";

      } else {
         echo "</table>";
      }
      echo "</div>";
   }
   
   /**
    * commenting out this function because of errors
    * sideb thesis adjustment
    * 
    * remade the showform comptuer to accomodate the individual components
    * 
    * 
    * Print the form for devices linked to a computer or a template
    *
    * @param $computer Computer object
    * @param $withtemplate='' boolean : template or basic computer
    *
    * @return Nothing (display)
   **/
   
   static function showForComputerSideb(Computer $computer, $withtemplate='') {
      global $DB, $LANG;

      $devtypes = self::getDeviceTypes();

      $ID = $computer->getField('id');
      if (!$computer->can($ID, 'r')) {
         return false;
      }
      $canedit = ($withtemplate!=2 && $computer->can($ID, 'w'));

      echo "<div class='spaced'>";
      if ($canedit) {
         echo "<form name='form_device_action' action='".getItemTypeFormURL(__CLASS__).
                "' method='post'>";
         echo "<input type='hidden' name='computers_id' value='$ID'>";
      }
      echo "<table class='tab_cadre_fixe' >";
      echo "<tr><th colspan='63'>".$LANG['title'][30]."</th></tr>";
      $nb = 0;

      $specificity_units = array('DeviceProcessor'   => $LANG['setup'][35],
                                 'DeviceMemory'      => $LANG['common'][82],
                                 'DeviceHardDrive'   => $LANG['common'][82],
                                 'DeviceGraphicCard' => $LANG['common'][82]);

      foreach ($devtypes as $itemtype) {
         initNavigateListItems($itemtype, $computer->getTypeName()." = ".$computer->getName());

         $device        = new $itemtype;
         $specificities = $device->getSpecifityLabel();
         $specif_fields = array_keys($specificities);
         $specif_text   = implode(',',$specif_fields);
         if (!empty($specif_text)) {
            $specif_text=" ,".$specif_text." ";
         }

         $linktable = getTableForItemType('Computer_'.$itemtype);
         $fk        = getForeignKeyFieldForTable(getTableForItemType($itemtype));

         $query = "SELECT COUNT(*) AS NB,
                          `id`,
                          `$fk`
                          $specif_text
                   FROM `$linktable`
                   WHERE `computers_id` = '$ID'
                   GROUP BY `$fk` $specif_text";
//echo $fk;
$component =  substr($fk, 6,-4);
if($component == "memorie"){
    $component = "memory";
}else if($component == "powersupplie"){
    $component = "powersupply";
}

//         $query = "SELECT serialnumber, idsideb_".$component."_list,componentID FROM `sideb_".$component."_list`
//where componentID in (SELECT b.device".$component."s_id
//FROM `glpi_computers_device".$component."s` b
//WHERE b.`computers_id` = '".$ID."') and idsideb_".$component."_list in (select ".$component."id from sideb_".$component."_deploy)";

switch ($component){
    
    case 'processor':
        //$query = "SELECT * FROM `glpi_deviceprocessors`;";
        $query = "SELECT serialnumber, idsideb_processor_list,componentID FROM `sideb_processor_list`
        where componentID in (SELECT b.deviceprocessors_id
        FROM `glpi_computers_deviceprocessors` b
        WHERE b.`computers_id` = '".$ID."' ) and idsideb_processor_list in (select processorID from sideb_processor_deploy)";
        $glpicomponent = 'deviceprocessors';
        break;
    
    case 'case':
        //$query = "SELECT * FROM `glpi_devicecases`;";
        $query ="SELECT serialnumber, idsideb_case_list,componentID FROM `sideb_case_list`
        where componentID in (SELECT b.devicecases_id
        FROM `glpi_computers_devicecases` b
        WHERE b.`computers_id` = '".$ID."' ) and idsideb_case_list in (select caseID from sideb_case_deploy)";
        $glpicomponent = 'devicecases';
        break;
    
    case 'control':
        //$query = "SELECT * FROM `glpi_devicecontrols`;";
        $query = "SELECT serialnumber, idsideb_control_list,componentID FROM `sideb_control_list`
        where componentID in (SELECT b.devicecontrols_id
        FROM `glpi_computers_devicecontrols` b
        WHERE b.`computers_id` = '".$ID."' ) and idsideb_control_list in (select controlID from sideb_control_deploy)";
        $glpicomponent = 'devicecontrols';
        break;
    
    case 'drive':
        //$query = "SELECT * FROM `glpi_devicedrives`;";
        $query = "SELECT serialnumber, idsideb_drive_list,componentID FROM `sideb_drive_list`
        where componentID in (SELECT b.devicedrives_id
        FROM `glpi_computers_devicedrives` b
        WHERE b.`computers_id` = '".$ID."' ) and idsideb_drive_list in (select driveID from sideb_drive_deploy)";
        $glpicomponent = 'devicedrives';
        break;
    
    case 'graphiccard':
        //$query = "SELECT * FROM `glpi_devicegraphiccards`;";
        $query = "SELECT serialnumber, idsideb_graphiccard_list,componentID FROM `sideb_graphiccard_list`
        where componentID in (SELECT b.devicegraphiccards_id
        FROM `glpi_computers_devicegraphiccards` b
        WHERE b.`computers_id` = '".$ID."' ) and idsideb_graphiccard_list in (select graphiccardID from sideb_graphiccard_deploy)";
        $glpicomponent = 'devicegraphiccards';
        break;
    
    case 'harddrive':
        //$query = "SELECT * FROM `glpi_deviceharddrives`;";
        $query = "SELECT serialnumber, idsideb_harddrive_list,componentID FROM `sideb_harddrive_list`
        where componentID in (SELECT b.deviceharddrives_id
        FROM `glpi_computers_deviceharddrives` b
        WHERE b.`computers_id` = '".$ID."' ) and idsideb_harddrive_list in (select harddriveID from sideb_harddrive_deploy)";
        $glpicomponent = 'deviceharddrives';
        break;
        
    case 'memorie':
        $component = "memory";
        //$query = "SELECT * FROM `glpi_devicememories`;";
        $query = "SELECT serialnumber, idsideb_memory_list,componentID FROM `sideb_memory_list`
        where componentID in (SELECT b.devicememories_id
        FROM `glpi_computers_devicememories` b
        WHERE b.`computers_id` = '".$ID."' ) and idsideb_memory_list in (select memoryID from sideb_memory_deploy)";
        $glpicomponent = 'devicememories';
        break;
        
    case 'networkcard':
        //$query = "SELECT * FROM `glpi_devicenetworkcards`;";
        $query = "SELECT serialnumber, idsideb_networkcard_list,componentID FROM `sideb_networkcard_list`
        where componentID in (SELECT b.devicenetworkcards_id
        FROM `glpi_computers_devicenetworkcards` b
        WHERE b.`computers_id` = '".$ID."' ) and idsideb_networkcard_list in (select networkcardID from sideb_networkcard_deploy)";
        $glpicomponent = 'devicenetworkcards';
        break;
        
    case 'pci':
        //$query = "SELECT * FROM `glpi_devicepcis`;";
        $query = "SELECT serialnumber, idsideb_pci_list,componentID FROM `sideb_pci_list`
        where componentID in (SELECT b.devicepcis_id
        FROM `glpi_computers_devicepcis` b
        WHERE b.`computers_id` = '".$ID."' ) and idsideb_pci_list in (select pciID from sideb_pci_deploy)";
        $glpicomponent = 'devicepcis';
        break;
        
    case 'powersupply':
        //$query = "SELECT * FROM `glpi_devicepowersupplies`;";
        $query = "SELECT serialnumber, idsideb_powersupply_list,componentID FROM `sideb_powersupply_list`
        where componentID in (SELECT b.devicepowersupplies_id
        FROM `glpi_computers_devicepowersupplies` b
        WHERE b.`computers_id` = '".$ID."' ) and idsideb_powersupply_list in (select powersupplyID from sideb_powersupply_deploy)";
        $glpicomponent = 'devicepowersupplies';
        break;
        
    case 'soundcard':
        //$query = "SELECT * FROM `glpi_devicesoundcards`;";
        $query = "SELECT serialnumber, idsideb_soundcard_list,componentID FROM `sideb_soundcard_list`
        where componentID in (SELECT b.devicesoundcards_id
        FROM `glpi_computers_devicesoundcards` b
        WHERE b.`computers_id` = '".$ID."' ) and idsideb_soundcard_list in (select soundcardID from sideb_soundcard_deploy)";
        $glpicomponent = 'devicesoundcards';
        break;
        
    case 'motherboard':
        //$query = "SELECT * FROM `glpi_devicemotherboards`;";
        $query = "SELECT serialnumber, idsideb_motherboard_list,componentID FROM `sideb_motherboard_list`
        where componentID in (SELECT b.devicemotherboards_id
        FROM `glpi_computers_devicemotherboards` b
        WHERE b.`computers_id` = '".$ID."' ) and idsideb_motherboard_list in (select motherboardID from sideb_motherboard_deploy)";
        $glpicomponent = 'devicemotherboards';
        break;
    
    default:
        $query = '';
        break;
}
$sidebcomponent = $component;
         
//         echo $query;
//         echo "<br/>";
         $prev = '';
         
         
         
          $result = $DB->query($query);
      if ($DB->query($query)) {
          $ctr=0;
           while ($data=$DB->fetch_array($result)) {
              $lastid = $data["serialnumber"];
              $idcomp = $data["idsideb_".$component."_list"];
              $idcomponent = $data['componentID'];
//              echo $lastid;
//              echo "<br/>";
//              echo $component;
              echo "<tr class='tab_bg_2'>";
              echo "<td class='center'>";
//              echo "<input type='checkbox' name='".$component."_".$idcomp."' value='".$component."_".$idcomp."'/>";
//              echo "<form action='removeComponent.php' method='post'>";
//                      echo "<input type='hidden' name='compserial' value='".$lastid."'/>";
//                      echo "<input type='hidden' name='compid' value='".$idcomp."'/>";
//                      echo "<input type='hidden' name='idcomp' value='".$idcomponent."'/>";
//                      echo "<input type='submit' value='remove'/>";
//                      echo "</form>";
              echo "<a href='removeComponent.php?compserial=".$lastid.";".$idcomponent."|$ID:$component'>link</a>";
              echo "</td><td>";
              if ($device->canCreate()) {
                  echo "<a href='".$device->getSearchURL()."'>".$device->getTypeName()."</a>";
               } else {
                  echo $device->getTypeName();
               }
              echo "</td><td>".$device->getLinkSideb($ID,$device->getTypeName(),$idcomponent,$ctr)."</td>";
              $ctr++;
              
              $query2 = "SELECT sbgs.serialNumber, COUNT(*) AS Repaired, dg.designation, dg.id
                FROM sideb_".$sidebcomponent."_solution sbgs, sideb_".$sidebcomponent."_list sbgl, glpi_".$glpicomponent." dg
                WHERE sbgs.serialNumber = sbgl.serialNumber AND sbgl.componentID = dg.id AND sbgl.serialNumber = '".$lastid."' AND dg.id = '".$idcomponent."';";
                 $result2 = $DB->query($query2);
                 if ($DB->query($query2)) {
                     
                      while ($data2=$DB->fetch_array($result2)) {
                          $count = $data2['Repaired'];
                      }
                     
                 }
              echo "<td>Repair Count: ".$count." ";
              if($count >= 3){
                  echo "Replace";
              }
              echo "</td>";
              //input repair count
              
              
              
           }
      }

      }

      if ($canedit) {
            echo "<tr><td colspan='63' class='tab_bg_1 center'>";
            echo "<input type='submit' class='submit' name='updateall' value='".
                   $LANG['buttons'][7]."'></td></tr>";
         

         echo "<tr><td colspan='63' class='tab_bg_1 center'>";
         echo $LANG['devices'][0]."&nbsp;: ";
         echo "<input type='hidden' name='computerID' value='".$ID."'/>";
//         Dropdown::showAllItems('items_id', '', 0, -1, $devtypes);
         Dropdown::showAllItemsSideb('items_id', '', 0, -1, $devtypes);
         echo "<input type='submit' name='add' value=\"".$LANG['buttons'][8]."\" class='submit'>";
         echo "</tr></table></form>";

      } else {
         echo "</table>";
      }
      echo "</div>";

      
   }


   /**
    * Update an internal device quantity
    *
    * @param $newNumber new quantity value
    * @param $itemtype itemtype of device
    * @param $compDevID computer device ID
   **/
   private function updateQuantity($newNumber, $itemtype,$compDevID) {
      global $DB;

      $linktable = getTableForItemType('Computer_'.$itemtype);
      $this->forceTable($linktable);
      $fk        = getForeignKeyFieldForTable(getTableForItemType($itemtype));
      // Force table for link
      $item          = new $itemtype();
      $specif_fields = $item->getSpecifityLabel();

      if (!$this->getFromDB($compDevID)) {
         return false;
      }

      $query2 = "SELECT `id`
                 FROM `$linktable`
                 WHERE `computers_id` = '".$this->fields["computers_id"]."'
                       AND `$fk` = '".$this->fields[$fk]."'";

      if (count($specif_fields)) {
         foreach ($specif_fields as $field => $name) {
            $query2 .= " AND `$field` = '".addslashes($this->fields[$field])."' ";
         }
      }

      if ($result2 = $DB->query($query2)) {
         // Delete devices
         $number = $DB->numrows($result2);
         if ($number > $newNumber) {
            for ($i=$newNumber ; $i<$number ; $i++) {
               $data2              = $DB->fetch_array($result2);
               $data2['_itemtype'] = $itemtype;
               $this->delete($data2);
            }
         // Add devices
         } else if ($number < $newNumber) {
            $input = array('computers_id' => $this->fields["computers_id"],
                           '_itemtype'    => $itemtype,
                           $fk            => $this->fields[$fk]);
            if (count($specif_fields)) {
               foreach ($specif_fields as $field => $name) {
                  $input[$field] = addslashes($this->fields["specificity"]);
               }
            }
            for ($i=$number ; $i<$newNumber ; $i++) {
               $this->add($input);
            }
         }
      }
   }


   /**
    * Update an internal device specificity
    *
    * @param $newValue new specifity value
    * @param $itemtype itemtype of device
    * @param $compDevID computer device ID
   **/
   private function updateSpecificity($newValue, $itemtype,$compDevID) {
      global $DB;

      $item          = new $itemtype();
      $specif_fields = $item->getSpecifityLabel();

      // No specificity for this device type
      if (count($specif_fields) == 0) {
         return false;
      }

      $linktable = getTableForItemType('Computer_'.$itemtype);
      $this->forceTable($linktable);
      $fk        = getForeignKeyFieldForTable(getTableForItemType($itemtype));

      if (!$this->getFromDB($compDevID)) {
         return false;
      }

      // Is it a real change ?
      if (addslashes($this->fields['specificity']) == $newValue) {
         return false;
      }

      // Update specificity
      $query = "SELECT `id`
                FROM `$linktable`
                WHERE `computers_id` = '".$this->fields["computers_id"]."'
                      AND `$fk` = '".$this->fields[$fk]."'
                      AND `specificity` = '".addslashes($this->fields["specificity"])."'";

      $first = true;
      foreach ($DB->request($query) as $data) {
         $data['specificity'] = $newValue;
         $data['_itemtype']   = $itemtype;
         $this->update($data, $first);
         $first = false;
      }
   }


   /**
    * Update the device attached to a computer
    *
    * @param $input array of data from the input form
   **/
   function updateAll($input) {

      // Update quantity
      foreach ($input as $key => $val) {
         $data = explode("_",$key);
         if (count($data) == 3 && $data[0] == "quantity") {
            $this->updateQuantity($val, $data[1],$data[2]);
         }
      }

      // Update specificity
      foreach ($_POST as $key => $val) {
         $data = explode("_",$key);
         if (count($data) == 3 && $data[0] == "value") {
            $this->updateSpecificity($val,$data[1],$data[2]);
         }
      }
   }
   
   /*
    * sideb thesis adjustment
    * update the components by deleting from entries
    */
   
   function updateSideb($serial,$compid,$computerID,$component) {
       global $DB;
      // Update quantity
      
switch ($component){
    
    case 'processor':
        //$query = "SELECT * FROM `glpi_deviceprocessors`;";
        $query = "select id from glpi_computers_deviceprocessors where computers_id = '".$computerID."' and deviceprocessors_id='".$compid."' limit 1";
        break;
    
    case 'case':
        //$query = "SELECT * FROM `glpi_devicecases`;";
        $query = "select id from glpi_computers_devicecases where computers_id = '".$computerID."' and devicecases_id='".$compid."' limit 1";
        break;
    
    case 'control':
        //$query = "SELECT * FROM `glpi_devicecontrols`;";
        $query = "select id from glpi_computers_devicecontrols where computers_id = '".$computerID."' and devicecontrols_id='".$compid."' limit 1";
        break;
    
    case 'drive':
        //$query = "SELECT * FROM `glpi_devicedrives`;";
        $query = "select id from glpi_computers_devicedrives where computers_id = '".$computerID."' and devicedrives_id='".$compid."' limit 1";
        break;
    
    case 'graphiccard':
        //$query = "SELECT * FROM `glpi_devicegraphiccards`;";
        $query = "select id from glpi_computers_devicegraphiccards where computers_id = '".$computerID."' and devicegraphiccards_id='".$compid."' limit 1";
        break;
    
    case 'harddrive':
        //$query = "SELECT * FROM `glpi_deviceharddrives`;";
        $query = "select id from glpi_computers_deviceharddrives where computers_id = '".$computerID."' and deviceharddrives_id='".$compid."' limit 1";
        break;
        
    case 'memory':
        //$query = "SELECT * FROM `glpi_devicememories`;";
        $query = "select id from glpi_computers_devicememories where computers_id = '".$computerID."' and devicememories_id='".$compid."' limit 1";
        break;
        
    case 'networkcard':
        //$query = "SELECT * FROM `glpi_devicenetworkcards`;";
        $query = "select id from glpi_computers_devicenetworkcards where computers_id = '".$computerID."' and devicenetworkcards_id='".$compid."' limit 1";
        break;
        
    case 'pci':
        //$query = "SELECT * FROM `glpi_devicepcis`;";
        $query = "select id from glpi_computers_devicepcis where computers_id = '".$computerID."' and devicepcis_id='".$compid."' limit 1";
        break;
        
    case 'powersupply':
        //$query = "SELECT * FROM `glpi_devicepowersupplies`;";
        $query = "select id from glpi_computers_devicepowersupplies where computers_id = '".$computerID."' and devicepowersupplies_id='".$compid."' limit 1";
        break;
        
    case 'soundcard':
        //$query = "SELECT * FROM `glpi_devicesoundcards`;";
        $query = "select id from glpi_computers_devicesoundcards where computers_id = '".$computerID."' and devicesoundcards_id='".$compid."' limit 1";
        break;
        
    case 'motherboard':
        //$query = "SELECT * FROM `glpi_devicemotherboards`;";
        $query = "select id from glpi_computers_devicemotherboards where computers_id = '".$computerID."' and devicemotherboards_id='".$compid."' limit 1";
        break;
    
    default:
        $query = '';
        break;
}
       
        
        $result = $DB->query($query);
        if ($DB->query($query)) {
           while ($data=$DB->fetch_array($result)) {
              $lastid = $data["id"];
           }
           
           switch ($component){
    
                case 'processor':
                    //$query = "SELECT * FROM `glpi_deviceprocessors`;";
                    $queryDelete = "DELETE FROM sideb_processor_deploy WHERE processorID = (SELECT idsideb_processor_list FROM sideb_processor_list WHERE serialNumber = '".$serial."')";
                    $queryDelete2 = "DELETE FROM glpi_computers_deviceprocessors WHERE id = '".$lastid."'";
                    break;

                case 'case':
                    //$query = "SELECT * FROM `glpi_devicecases`;";
                    $queryDelete = "DELETE FROM sideb_case_deploy WHERE caseID = (SELECT idsideb_case_list FROM sideb_case_list WHERE serialNumber = '".$serial."')";
                    $queryDelete2 = "DELETE FROM glpi_computers_devicecases WHERE id = '".$lastid."'";

                    break;

                case 'control':
                    //$query = "SELECT * FROM `glpi_devicecontrols`;";
                    $queryDelete = "DELETE FROM sideb_control_deploy WHERE controlID = (SELECT idsideb_control_list FROM sideb_control_list WHERE serialNumber = '".$serial."')";
                    $queryDelete2 = "DELETE FROM glpi_computers_devicecontrols WHERE id = '".$lastid."'";

                    break;

                case 'drive':
                    //$query = "SELECT * FROM `glpi_devicedrives`;";
                    $queryDelete = "DELETE FROM sideb_drive_deploy WHERE driveID = (SELECT idsideb_drive_list FROM sideb_drive_list WHERE serialNumber = '".$serial."')";
                    $queryDelete2 = "DELETE FROM glpi_computers_devicedrives WHERE id = '".$lastid."'";

                    break;

                case 'graphiccard':
                    //$query = "SELECT * FROM `glpi_devicegraphiccards`;";
                    $queryDelete = "DELETE FROM sideb_graphiccard_deploy WHERE graphiccardID = (SELECT idsideb_graphiccard_list FROM sideb_graphiccard_list WHERE serialNumber = '".$serial."')";
                    $queryDelete2 = "DELETE FROM glpi_computers_devicegraphiccards WHERE id = '".$lastid."'";

                    break;

                case 'harddrive':
                    //$query = "SELECT * FROM `glpi_deviceharddrives`;";
                    $queryDelete = "DELETE FROM sideb_harddrive_deploy WHERE harddriveID = (SELECT idsideb_harddrive_list FROM sideb_harddrive_list WHERE serialNumber = '".$serial."')";
                    $queryDelete2 = "DELETE FROM glpi_computers_deviceharddrives WHERE id = '".$lastid."'";

                    break;

                case 'memory':
                    //$query = "SELECT * FROM `glpi_devicememories`;";
                    $queryDelete = "DELETE FROM sideb_memory_deploy WHERE memoryID = (SELECT idsideb_memory_list FROM sideb_memory_list WHERE serialNumber = '".$serial."')";
                    $queryDelete2 = "DELETE FROM glpi_computers_devicememories WHERE id = '".$lastid."'";

                    break;

                case 'networkcard':
                    //$query = "SELECT * FROM `glpi_devicenetworkcards`;";
                    $queryDelete = "DELETE FROM sideb_networkcard_deploy WHERE networkcardID = (SELECT idsideb_networkcard_list FROM sideb_networkcard_list WHERE serialNumber = '".$serial."')";
                    $queryDelete2 = "DELETE FROM glpi_computers_devicenetworkcards WHERE id = '".$lastid."'";

                    break;

                case 'pci':
                    //$query = "SELECT * FROM `glpi_devicepcis`;";
                    $queryDelete = "DELETE FROM sideb_pci_deploy WHERE pciID = (SELECT idsideb_pci_list FROM sideb_pci_list WHERE serialNumber = '".$serial."')";
                    $queryDelete2 = "DELETE FROM glpi_computers_devicepcis WHERE id = '".$lastid."'";

                    break;

                case 'powersupply':
                    //$query = "SELECT * FROM `glpi_devicepowersupplies`;";
                    $queryDelete = "DELETE FROM sideb_powersupply_deploy WHERE powersupplyID = (SELECT idsideb_powersupply_list FROM sideb_powersupply_list WHERE serialNumber = '".$serial."')";
                    $queryDelete2 = "DELETE FROM glpi_computers_devicepowersupplies WHERE id = '".$lastid."'";

                    break;

                case 'soundcard':
                    //$query = "SELECT * FROM `glpi_devicesoundcards`;";
                    $queryDelete = "DELETE FROM sideb_soundcard_deploy WHERE soundcardID = (SELECT idsideb_soundcard_list FROM sideb_soundcard_list WHERE serialNumber = '".$serial."')";
                    $queryDelete2 = "DELETE FROM glpi_computers_devicesoundcards WHERE id = '".$lastid."'";

                    break;

                case 'motherboard':
                    //$query = "SELECT * FROM `glpi_devicemotherboards`;";
                    $queryDelete = "DELETE FROM sideb_motherboard_deploy WHERE motherboardID = (SELECT idsideb_motherboard_list FROM sideb_motherboard_list WHERE serialNumber = '".$serial."')";
                    $queryDelete2 = "DELETE FROM glpi_computers_devicemotherboards WHERE id = '".$lastid."'";

                    break;

                default:
                    $query = '';
                    break;
            }
   
           $DB->query($queryDelete);
           $DB->query($queryDelete2);
           
      }
       
       
   }


   function cleanDBonItemDelete ($itemtype, $item_id) {
      global $DB;

      if ($itemtype == 'Computer') {
         $devtypes = self::getDeviceTypes();
         foreach ($devtypes as $type) {
            $linktable = getTableForItemType('Computer_'.$type);
            $this->forceTable($linktable);

            $query = "SELECT `id`
                      FROM `$linktable`
                      WHERE `computers_id` = '$item_id'";

            $result = $DB->query($query);
            while ($data = $DB->fetch_assoc($result)) {
               $data['_no_history'] = true; // Parent is deleted
               $data['_itemtype']   = $type;
               $this->delete($data);
            }
         }

      } else {
         $linktable = getTableForItemType('Computer_'.$itemtype);
         $fk        = getForeignKeyFieldForTable(getTableForItemType($itemtype));
         $this->forceTable($linktable);

         $query = "SELECT `id`
                   FROM `$linktable`
                   WHERE `$fk` = '$item_id'";

         $result = $DB->query($query);
         while ($data = $DB->fetch_assoc($result)) {
            $data['_itemtype'] = $itemtype;
            $this->delete($data);
         }
      }
   }


   /**
    * Duplicate all device from a computer template to his clone
   **/
   function cloneComputer ($oldid, $newid) {
      global $DB;

      $devtypes = self::getDeviceTypes();
      foreach ($devtypes as $itemtype) {
         $linktable = getTableForItemType('Computer_'.$itemtype);
         $fk        = getForeignKeyFieldForTable(getTableForItemType($itemtype));

         $query = "SELECT *
                   FROM `$linktable`
                   WHERE `computers_id` = '$oldid'";

         foreach ($DB->request($query) as $data) {
            unset($data['id']);
            $data['computers_id'] = $newid;
            $data['_itemtype']    = $itemtype;
            $data['_no_history']  = true;

            $this->add($data);
         }
      }
   }


   function prepareInputForUpdate($input) {

      if (isset($input['itemtype'])) {
         $input['_itemtype'] = $input['itemtype'];
         unset($input['itemtype']);
      }

      if ($input['_itemtype'] == 'DeviceGraphicCard') { // && isset($this->input['_from_ocs'])) {
         if (!$this->input['specificity']) {
            // memory can't be 0 (but sometime OCS report such value)
            return false;
         }
      }

      if ($input['_itemtype'] == 'DeviceProcessor') { // && isset($this->input['_from_ocs'])) {
         if (!$this->input['specificity']) {
            // frequency can't be 0 (but sometime OCS report such value)
            return false;
         }

         if ($this->fields['specificity']) { // old value
            $diff = ($this->input['specificity'] > $this->fields['specificity']
                      ? $this->input['specificity'] - $this->fields['specificity']
                      : $this->fields['specificity'] - $this->input['specificity']);
            if (($diff*100/$this->fields['specificity']) < 5) {
               $this->input['_no_history'] = true;
            }
         }
      }

      if (isset($this->fields['specificity'])
          && $this->fields['specificity'] == $this->input['specificity']) {
         // No change
         return false;
      }

      $linktable = getTableForItemType('Computer_'.$input['_itemtype']);
      $this->forceTable($linktable);

      return $this->input;
   }


   /**
    * get the Mac Addresses for a computer
    *
    * @param $comp object
    *
    * @return array of Mac Addresses
   **/
   static function getMacAddr (Computer $comp) {
      global $DB;

      $query = "SELECT DISTINCT `specificity`
                FROM `glpi_computers_devicenetworkcards`
                WHERE `computers_id`='".$comp->getField('id')."'";

      $mac = array();
      foreach ($DB->request($query) as $data) {
         $mac[] = $data['specificity'];
      }
      return $mac;
   }


   /**
    * Delete old devices settings
    *
    * @param $glpi_computers_id integer : glpi computer id.
    * @param $itemtype integer : device type identifier.
    *
    * @return nothing.
   **/
   static function resetDevices($glpi_computers_id, $itemtype) {
      global $DB;

      $linktable = getTableForItemType('Computer_'.$itemtype);

      $query = "DELETE
                FROM `$linktable`
                WHERE `computers_id` = '$glpi_computers_id'";
      $DB->query($query);
   }


}
?>