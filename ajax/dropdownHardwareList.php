<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * this file is for the new dropdowns depending on hardware
 * $_POST["type"]
 */
define('GLPI_ROOT','..');
include (GLPI_ROOT."/inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
header_nocache();


//if($_POST['type']=='networks'){
//    $table = "networkequipments";
//}else{
//    $table = $_POST['type'];
//}

switch($_POST['type']){
    case "networks":
        $table = "networkequipments";
        break;
    case "cartridges":
        $table = "cartridgeitems";
        break;
    case "consumables":
        $table = "consumableitems";
        break;
    case "devices":
        $table = "peripherals";
        break;
    default :
        $table = $_POST['type'];
        break;
}

$query = "SELECT DISTINCT `name`
          FROM `glpi_".$table."`";
        
        $result = $DB->query($query);
        
        $rand = mt_rand();
        $id = "hardware_dropbox_".$rand;
        echo "<select name='softwares_id' id='$id'>\n";
        echo "<option value='0'>".DROPDOWN_EMPTY_VALUE."</option>\n";

        if ($DB->numrows($result)) {
           while ($data=$DB->fetch_array($result)) {
              $output       = $data["name"];
              echo "<option value='computer' title=\"".cleanInputText($output)."\">".
                     utf8_substr($output, 0, $_SESSION["glpidropdown_chars_limit"])."</option>";
           }
           
        }
        
        echo "<option value='-1'>New Asset</option>";
        echo "</select>\n";
        echo "<br/>";
        $params = array('type'            => '__VALUE__');
     
           ajaxUpdateItemOnSelectEvent($id,
                                  "newHardware_".$rand,
                                  $CFG_GLPI["root_doc"]."/ajax/newAssetRequest.php", $params);
         
           echo "<span id='newHardware_$rand'>&nbsp;</span>";
        

//switch ($_POST["type"]){
//    case "computers" :
//
//        $type = "computers";
//        databaseQuery($type);
////        $result = $DB->hardwareQuery($_POST["type"]);
//        break;
//}


//
//function databaseQuery($type){
//
//
//        $query = "SELECT DISTINCT `name`
//          FROM `glpi_computers`";
//        
//        $result = $DB->query($query);
//        
//        
//        echo "<select name='softwares_id' id='item_type$rand'>\n";
//        echo "<option value='0'>".DROPDOWN_EMPTY_VALUE."</option>\n";
//
//        if ($DB->numrows($result)) {
//           while ($data=$DB->fetch_array($result)) {
//              $output       = $data["name"];
//              echo "<option value='computer' title=\"".cleanInputText($output)."\">".
//                     utf8_substr($output, 0, $_SESSION["glpidropdown_chars_limit"])."</option>";
//           }
//        }
//        echo "</select>\n";
//    
//}
?>

