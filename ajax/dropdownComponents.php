<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

define('GLPI_ROOT','..');
include (GLPI_ROOT."/inc/includes.php");

header("Content-Type: text/html; charset=UTF-8");
header_nocache();

if($_POST['type']){
    if(strpos($_POST['type'], 'Printer') !== false){
//        echo "printer";
        $pos = strpos($_POST['type'],'_');
        $printer = substr($_POST['type'],$pos+1);
//        echo $_POST['type'];

        //echo $test;
//         Cartridge::showInstalled($printer,1);
//        Cartridge::showForCartridgeItem($printer);
//        Link::showForItem('CartridgeItem', $printer);
        CartridgeItem::dropdownForPrinterCustom($printer);
    }else{
        $optgroup = Dropdown::getDeviceItemTypesNoAuthorization();
        $pass = '';
        $name = 'component_request';
        Dropdown::showItemTypeMenuPlain($optgroup,$pass,$name);
    }
   
}

//switch($_POST['type']){
//    case 
//}
?>
