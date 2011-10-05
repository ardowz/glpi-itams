<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
define('GLPI_ROOT','..');
include (GLPI_ROOT."/inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
header_nocache();

//echo $_POST['type'];

switch ($_POST['type']){
    case "-1":
        
        echo "<input type='text' name='newAsset' id='newAsset'></input>";
        
    break;
    default:
        
    break;
    
}


?>
