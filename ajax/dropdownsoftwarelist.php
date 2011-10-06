<?php
/*
 * @version $Id: dropdownTicketActors.php 14762 2011-06-24 12:36:26Z remi $
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

define('GLPI_ROOT','..');
include (GLPI_ROOT."/inc/includes.php");

header("Content-Type: text/html; charset=UTF-8");
header_nocache();


// Make a select box
   $rand = mt_rand();
   switch ($_POST["type"]) {
      case "software" :
         $right = 'all';
//          echo "test software";
          //$entity_restrict = ;
          Software::dropdownSoftwareList($rand, $entity_restrict);
         // Only steal or own ticket whit empty assign

//         $options = array('name'        => '_ticket_'.$_POST["actortype"].'[users_id]',
//                          'entity'      => $_POST['entity_restrict'],
//                          'right'       => $right,
//                          'ldap_import' => true);
//
//         $rand = User::dropdown($options);
//         if ($CFG_GLPI["use_mailing"]) {
//            echo "<br><span id='notif_user_$rand'>";
//            if ($withemail) {
//               echo $LANG['job'][19].'&nbsp;:&nbsp;';
//               $rand = Dropdown::showYesNo('_ticket_'.$_POST["actortype"].'[use_notification]', 1);
//               echo '<br>'.$LANG['mailing'][118].'&nbsp;:&nbsp;';
//               echo "<input type='text' size='25' name='_ticket_".$_POST["actortype"]."[alternative_email]'>";
//            }
//            echo "</span>";
//         }
         break;

      case "hardware" :
          
          
          $name = "hardware";
          $rand = Dropdown::dropdownInventoryTypes($name);
          echo "<br/>";
          
          
          $params = array('type'            => '__VALUE__');
      
      
           ajaxUpdateItemOnSelectEvent($rand,
                                  "hardware_".$rand,
                                  $CFG_GLPI["root_doc"]."/ajax/dropdownHardwareList.php", $params);
           
           echo "<span id='hardware_$rand'>&nbsp;</span>";
          //$opt = Dropdown::getStandardDropdownItemTypes();
          
//          echo "test hardware";
//         Dropdown::show('Group', array('name'   => '_ticket_'.$_POST["actortype"].'[groups_id]',
//                                       'entity' => $_POST['entity_restrict']));
         break;
     case "component" :
//         echo "component";
         break;
   }

?>
