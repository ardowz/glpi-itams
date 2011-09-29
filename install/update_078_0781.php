<?php


/*
 * @version $Id: update_078_0781.php 14684 2011-06-11 06:32:40Z remi $
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

/**
 * Update from 0.78 to 0.78.1
 *
 * @param $output string for format
 *       HTML (default) for standard upgrade
 *       empty = no ouput for PHPUnit
 *
 * @return bool for success (will die for most error)
 */
function update078to0781($output='HTML') {
   global $DB, $LANG;

   $updateresult = true;

   if ($output) {
      echo "<h3>".$LANG['install'][4]." -&gt; 0.78.1</h3>";
   }
   displayMigrationMessage("0781"); // Start

   displayMigrationMessage("0781", $LANG['update'][142] . ' - Clean reservation entity link'); // Updating schema

   $entities=getAllDatasFromTable('glpi_entities');
   $entities[0]="Root";

   $query = "SELECT DISTINCT `itemtype` FROM `glpi_reservationitems`";
   if ($result=$DB->query($query)) {
      if ($DB->numrows($result)>0) {
         while ($data = $DB->fetch_assoc($result)) {
            $itemtable=getTableForItemType($data['itemtype']);
            // ajout d'un contrôle pour voir si la table existe ( cas migration plugin non fait)
            if (!TableExists($itemtable)) {
               if ($output) {
                  echo "<p class='red'>*** Skip : no table $itemtable ***</p>";
               }
               continue;
            }
            $do_recursive=false;
            if (FieldExists($itemtable,'is_recursive')) {
               $do_recursive=true;
            }
            foreach ($entities as $entID => $val) {
               if ($do_recursive) {
                  // Non recursive ones
                  $query3="UPDATE `glpi_reservationitems`
                           SET `entities_id`=$entID, `is_recursive`=0
                           WHERE `itemtype`='".$data['itemtype']."'
                              AND `items_id` IN (SELECT `id` FROM `$itemtable`
                              WHERE `entities_id`=$entID AND `is_recursive`=0)";
                  $DB->query($query3) or die("0.78.1 update entities_id and is_recursive=0
                        in glpi_reservationitems for ".$data['itemtype']." ". $LANG['update'][90] . $DB->error());

                  // Recursive ones
                  $query3="UPDATE `glpi_reservationitems`
                           SET `entities_id`=$entID, `is_recursive`=1
                           WHERE `itemtype`='".$data['itemtype']."'
                              AND `items_id` IN (SELECT `id` FROM `$itemtable`
                              WHERE `entities_id`=$entID AND `is_recursive`=1)";
                  $DB->query($query3) or die("0.78.1 update entities_id and is_recursive=1
                        in glpi_reservationitems for ".$data['itemtype']." ". $LANG['update'][90] . $DB->error());
               } else {
                  $query3="UPDATE `glpi_reservationitems`
                           SET `entities_id`=$entID
                           WHERE `itemtype`='".$data['itemtype']."'
                              AND `items_id` IN (SELECT `id` FROM `$itemtable`
                              WHERE `entities_id`=$entID)";
                  $DB->query($query3) or die("0.78.1 update entities_id in glpi_reservationitems
                        for ".$data['itemtype']." ". $LANG['update'][90] . $DB->error());
               }
            }
         }
      }
   }

   $query = "ALTER TABLE `glpi_tickets`
             CHANGE `global_validation` `global_validation` VARCHAR(255) DEFAULT 'none'";
   $DB->query($query) or die("0.78.1 change ticket global_validation default state");

   $query = "UPDATE `glpi_tickets`
             SET `global_validation`='none'
             WHERE `id` NOT IN (SELECT DISTINCT `tickets_id`
                                FROM `glpi_ticketvalidations`)";
   $DB->query($query) or die("0.78.1 update ticket global_validation state");


   if (!FieldExists('glpi_knowbaseitemcategories','entities_id')) {
      $query = "ALTER TABLE `glpi_knowbaseitemcategories`
                    ADD `entities_id` INT NOT NULL DEFAULT '0' AFTER `id`,
                    ADD `is_recursive` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `entities_id`,
                    ADD INDEX `entities_id` (`entities_id`),ADD INDEX `is_recursive` (`is_recursive`)";
      $DB->query($query) or die("0.78.1 add entities_id,is_recursive in glpi_knowbaseitemcategories" .
                                 $LANG['update'][90] . $DB->error());

      // Set existing categories recursive global
      $query = "UPDATE `glpi_knowbaseitemcategories` SET `is_recursive` = '1'";
      $DB->query($query) or die("0.78.1 set value of is_recursive in glpi_knowbaseitemcategories" .
                                $LANG['update'][90] . $DB->error());

      $query = "ALTER TABLE `glpi_knowbaseitemcategories` DROP INDEX `unicity` ,
               ADD UNIQUE `unicity` ( `entities_id`, `knowbaseitemcategories_id` , `name` ) ";
      $DB->query($query) or die("0.78.1 update unicity index on glpi_knowbaseitemcategories" .
                                $LANG['update'][90] . $DB->error());
   }

   // Display "Work ended." message - Keep this as the last action.
   displayMigrationMessage("0781"); // End

   return $updateresult;
}
?>
