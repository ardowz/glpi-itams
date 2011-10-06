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
    
    $optgroup = Dropdown::getDeviceItemTypesNoAuthorization();
    $pass = '';
    $name = 'component_request';
    Dropdown::showItemTypeMenuPlain($optgroup,$pass,$name);
    
}

?>
