<?php
/*
 * @version $Id: stat.class.php 14684 2011-06-11 06:32:40Z remi $
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

/**
 *  Computer class
**/
class Stat {

   static function getItems($date1, $date2, $type) {
      global $CFG_GLPI, $DB;

      $val = array();

      switch ($type) {
         case "technicien" :
            $val = Ticket::getUsedTechBetween($date1, $date2);
            break;

         case "technicien_followup" :
            $val = Ticket::getUsedTechTaskBetween($date1, $date2);
            break;

         case "enterprise" :
            $val = Ticket::getUsedSupplierBetween($date1, $date2);
            break;

         case "user" :
            $val = Ticket::getUsedAuthorBetween($date1, $date2);
            break;

         case "users_id_recipient" :
            $val = Ticket::getUsedRecipientBetween($date1, $date2);
            break;

         case "ticketcategories_id" :
            // Get all ticket categories for tree merge management
            $query = "SELECT DISTINCT `glpi_ticketcategories`.`id`,
                             `glpi_ticketcategories`.`completename` AS category
                      FROM `glpi_ticketcategories`".
                      getEntitiesRestrictRequest(" WHERE", "glpi_ticketcategories", '', '', true)."
                      ORDER BY category";

            $result = $DB->query($query);
            $val    = array();
            if ($DB->numrows($result) >=1) {
               while ($line = $DB->fetch_assoc($result)) {
                  $tmp['id']   = $line["id"];
                  $tmp['link'] = $line["category"];
                  $val[]       = $tmp;
               }
            }
            break;

         case "type" :
            $types = Ticket::getTypes();
            $val   = array();
            foreach ($types as $id => $v) {
               $tmp['id']   = $id;
               $tmp['link'] = $v;
               $val[]       = $tmp;
            }
            break;

         case "group" :
            $val = Ticket::getUsedGroupBetween($date1, $date2);
            break;

         case "groups_id_assign" :
            $val = Ticket::getUsedAssignGroupBetween($date1, $date2);
            break;

         case "priority" :
            $val = Ticket::getUsedPriorityBetween($date1, $date2);
            break;

         case "urgency" :
            $val = Ticket::getUsedUrgencyBetween($date1, $date2);
            break;

         case "impact" :
            $val = Ticket::getUsedImpactBetween($date1, $date2);
            break;

         case "requesttypes_id" :
            $val = Ticket::getUsedRequestTypeBetween($date1, $date2);
            break;

         case "ticketsolutiontypes_id" :
            $val = Ticket::getUsedSolutionTypeBetween($date1, $date2);
            break;

         case "usertitles_id" :
            $val = Ticket::getUsedUserTitleOrTypeBetween($date1, $date2, true);
            break;

         case "usercategories_id" :
            $val = Ticket::getUsedUserTitleOrTypeBetween($date1, $date2, false);
            break;

         // DEVICE CASE
         default :
            $item = new $type();
            if ($item instanceof CommonDevice) {
               $device_table = $item->getTable();

               //select devices IDs (table row)
               $query = "SELECT `id`, `designation`
                         FROM `".$device_table."`
                         ORDER BY `designation`";
               $result = $DB->query($query);

               if ($DB->numrows($result) >=1) {
                  $i = 0;
                  while ($line = $DB->fetch_assoc($result)) {
                     $val[$i]['id']   = $line['id'];
                     $val[$i]['link'] = $line['designation'];
                     $i++;
                  }
               }

            } else {
               // Dropdown case for computers
               $field = "name";
               $table = getTableFOrItemType($type);
               $item  = new $type();
               if ($item instanceof CommonTreeDropdown) {
                  $field = "completename";
               }
               $where = '';
               $order = " ORDER BY `$field`";
               if ($item->isEntityAssign()) {
                  $where = getEntitiesRestrictRequest(" WHERE",$table);
                  $order = " ORDER BY `entities_id`, `$field`";
               }

               $query = "SELECT *
                         FROM `$table`
                         $where
                         $order";

               $val    = array();
               $result = $DB->query($query);
               if ($DB->numrows($result) >0) {
                  while ($line = $DB->fetch_assoc($result)) {
                     $tmp['id']   = $line["id"];
                     $tmp['link'] = $line[$field];
                     $val[]       = $tmp;
                  }
               }
            }
      }
      return $val;
   }


   static function getDatas($type, $date1, $date2, $start, $value, $value2="") {

      $export_data = array();

      if (is_array($value)) {
         $end_display = $start+$_SESSION['glpilist_limit'];
         $numrows     = count($value);

         for ($i=$start ; $i< $numrows && $i<($end_display) ; $i++) {
            //le nombre d'intervention - the number of intervention
            $opened    = self::constructEntryValues("inter_total", $date1, $date2, $type,
                                                    $value[$i]["id"], $value2);
            $nb_opened = array_sum($opened);
            $export_data['opened'][$value[$i]['link']] = $nb_opened;

            //le nombre d'intervention resolues - the number of resolved intervention
            $solved    = self::constructEntryValues("inter_solved", $date1, $date2, $type,
                                                    $value[$i]["id"], $value2);
            $nb_solved = array_sum($solved);
            $export_data['solved'][$value[$i]['link']] = $nb_solved;

            //le nombre d'intervention resolues - the number of resolved intervention
            $late    = self::constructEntryValues("inter_solved_late", $date1, $date2, $type,
                                                  $value[$i]["id"], $value2);
            $nb_late = array_sum($late);
            $export_data['late'][$value[$i]['link']] = $nb_late;

            //le nombre d'intervention closes - the number of closed intervention
            $closed    = self::constructEntryValues("inter_closed", $date1, $date2, $type,
                                                    $value[$i]["id"], $value2);
            $nb_closed = array_sum($closed);
            $export_data['closed'][$value[$i]['link']] = $nb_closed;

            //open satisfaction
            $opensatisfaction    = self::constructEntryValues("inter_opensatisfaction", $date1,
                                                              $date2, $type, $value[$i]["id"],
                                                              $value2);
            $nb_opensatisfaction = array_sum($opensatisfaction);
            $export_data['opensatisfaction'][$value[$i]['link']] = $nb_opensatisfaction;

            //answer satisfaction
//             $answersatisfaction    = self::constructEntryValues("inter_answersatisfaction", $date1, $date2, $type,
//                                                     $value[$i]["id"], $value2);
//             $nb_answersatisfaction = array_sum($answersatisfaction);
//             $export_data['opensatisfaction'][$value[$i]['link']] = $nb_answersatisfaction;

         }
      }
      return $export_data;
   }


   static function show($type, $date1, $date2, $start, $value, $value2="") {
      global $LANG, $CFG_GLPI;

      // Set display type for export if define
      $output_type = HTML_OUTPUT;
      if (isset($_GET["display_type"])) {
         $output_type = $_GET["display_type"];
      }

      if ($output_type==HTML_OUTPUT) { // HTML display
         echo "<div class ='center'>";
      }

      if (is_array($value)) {
         $end_display = $start+$_SESSION['glpilist_limit'];
         $numrows     = count($value);

         if (isset($_GET['export_all'])) {
            $start       = 0;
            $end_display = $numrows;
         }

         $nbcols = 8;
         if ($output_type!=HTML_OUTPUT) { // not HTML display
            $nbcols--;
         }

         echo Search::showHeader($output_type, $end_display-$start+1, $nbcols);

         if ($output_type==HTML_OUTPUT) { // HTML display
            echo Search::showNewLine($output_type);
            $header_num = 1;

            echo Search::showHeaderItem($output_type, "&nbsp;", $header_num);
            echo Search::showHeaderItem($output_type, "", $header_num);

            echo Search::showHeaderItem($output_type, $LANG['stats'][13], $header_num, '', 0, '',
                                        "colspan=4");
            echo Search::showHeaderItem($output_type, $LANG['satisfaction'][0], $header_num, '', 0,
                                        '', "colspan=3");
            echo Search::showHeaderItem($output_type, $LANG['stats'][8], $header_num, '', 0, '',
                                        "colspan=3");
            echo Search::showHeaderItem($output_type, $LANG['stats'][26], $header_num, '', 0, '',
                                        "colspan=2");
         }

         echo Search::showNewLine($output_type);
         $header_num    = 1;
         $header_to_add = '';
         echo Search::showHeaderItem($output_type, "&nbsp;", $header_num);

         if ($output_type==HTML_OUTPUT) { // HTML display
            echo Search::showHeaderItem($output_type, "", $header_num);
         }
         if ($output_type!=HTML_OUTPUT) {
            $header_to_add = $LANG['stats'][13].' - ';
         }
         echo Search::showHeaderItem($output_type, $header_to_add.$LANG['job'][14], $header_num);
         echo Search::showHeaderItem($output_type, $header_to_add.$LANG['job'][15], $header_num);
         echo Search::showHeaderItem($output_type, $header_to_add.$LANG['job'][17], $header_num);
         echo Search::showHeaderItem($output_type, $header_to_add.$LANG['job'][16], $header_num);

         if ($output_type!=HTML_OUTPUT) {
            $header_to_add = $LANG['satisfaction'][0].' - ';
         }
         echo Search::showHeaderItem($output_type, $header_to_add.$LANG['satisfaction'][13], $header_num);
         echo Search::showHeaderItem($output_type, $header_to_add.$LANG['satisfaction'][14], $header_num);
         echo Search::showHeaderItem($output_type, $header_to_add.$LANG['common'][107], $header_num);

         if ($output_type!=HTML_OUTPUT) {
            $header_to_add = $LANG['stats'][8].' - ';
         }
         echo Search::showHeaderItem($output_type, $header_to_add.$LANG['stats'][12], $header_num);
         echo Search::showHeaderItem($output_type, $header_to_add.$LANG['stats'][9], $header_num);
         echo Search::showHeaderItem($output_type, $header_to_add.$LANG['stats'][10], $header_num);

         if ($output_type!=HTML_OUTPUT) {
            $header_to_add = $LANG['stats'][26].' - ';
         }
         echo Search::showHeaderItem($output_type, $header_to_add.$LANG['common'][107], $header_num);
         echo Search::showHeaderItem($output_type, $header_to_add.$LANG['common'][33], $header_num);
         // End Line for column headers
         echo Search::showEndLine($output_type);
         $row_num = 1;

         for ($i=$start ; $i< $numrows && $i<($end_display) ; $i++) {
            $row_num++;
            $item_num = 1;
            echo Search::showNewLine($output_type, $i%2);
            echo Search::showItem($output_type, $value[$i]['link'], $item_num, $row_num);

            if ($output_type==HTML_OUTPUT) { // HTML display
               $link = "";
               if ($value[$i]['id']>0) {
                  $link = "<a href='stat.graph.php?id=".$value[$i]['id'].
                            "&amp;date1=$date1&amp;date2=$date2&amp;type=$type".
                            (!empty($value2)?"&amp;champ=$value2":"")."'>".
                          "<img src='".$CFG_GLPI["root_doc"]."/pics/stats_item.png' alt='' title=''>".
                          "</a>";
               }
               echo Search::showItem($output_type, $link, $item_num, $row_num);
            }

            //le nombre d'intervention - the number of intervention
            $opened    = self::constructEntryValues("inter_total", $date1, $date2, $type,
                                                    $value[$i]["id"], $value2);
            $nb_opened = array_sum($opened);
            echo Search::showItem($output_type, $nb_opened, $item_num, $row_num);

            //le nombre d'intervention resolues - the number of resolved intervention
            $solved    = self::constructEntryValues("inter_solved", $date1, $date2, $type,
                                                    $value[$i]["id"], $value2);
            $nb_solved = array_sum($solved);
            if ($nb_opened>0 && $nb_solved >0) {
               $nb_solved .= ' ('.round($nb_solved*100/$nb_opened).'%)';
            }
            echo Search::showItem($output_type, $nb_solved, $item_num, $row_num);

            //le nombre d'intervention resolues - the number of resolved intervention
            $solved_late    = self::constructEntryValues("inter_solved_late", $date1, $date2, $type,
                                                         $value[$i]["id"], $value2);
            $nb_solved_late = array_sum($solved_late);
            if ($nb_solved>0 && $nb_solved_late >0) {
               $nb_solved_late .= ' ('.round($nb_solved_late*100/$nb_solved).'%)';
            }
            echo Search::showItem($output_type, $nb_solved_late, $item_num, $row_num);

            //le nombre d'intervention closes - the number of closed intervention
            $closed    = self::constructEntryValues("inter_closed", $date1, $date2, $type,
                                                    $value[$i]["id"], $value2);
            $nb_closed = array_sum($closed);

            if ($nb_opened>0 && $nb_closed >0) {
               $nb_closed .= ' ('.round($nb_closed*100/$nb_opened).'%)';
            }

            echo Search::showItem($output_type, $nb_closed, $item_num, $row_num);


            //Satisfaction open
            $opensatisfaction    = self::constructEntryValues("inter_opensatisfaction", $date1,
                                                              $date2, $type, $value[$i]["id"],
                                                              $value2);
            $nb_opensatisfaction = array_sum($opensatisfaction);
            if ($nb_opensatisfaction>0) {
               $nb_opensatisfaction .= ' ('.round($nb_opensatisfaction*100/$nb_closed).'%)';
            }

            echo Search::showItem($output_type, $nb_opensatisfaction, $item_num, $row_num);

            //Satisfaction answer
            $answersatisfaction    = self::constructEntryValues("inter_answersatisfaction", $date1,
                                                                $date2, $type, $value[$i]["id"],
                                                                $value2);
            $nb_answersatisfaction = array_sum($answersatisfaction);
            if ($nb_answersatisfaction>0) {
               $nb_answersatisfaction .= ' ('.round($nb_answersatisfaction*100/$nb_opensatisfaction).'%)';
            }

            echo Search::showItem($output_type, $nb_answersatisfaction, $item_num, $row_num);

            //Satisfaction rate
            $satisfaction = self::constructEntryValues("inter_avgsatisfaction", $date1, $date2,
                                                       $type, $value[$i]["id"], $value2);
            foreach ($satisfaction as $key2 => $val2) {
               $satisfaction[$key2] *= $answersatisfaction[$key2];
            }
            if ($nb_answersatisfaction>0) {
               $avgsatisfaction = round(array_sum($satisfaction)/$nb_answersatisfaction,1);
               $avgsatisfaction = TicketSatisfaction::displaySatisfaction($avgsatisfaction);
            } else {
               $avgsatisfaction = '&nbsp;';
            }
            echo Search::showItem($output_type, $avgsatisfaction, $item_num, $row_num);


            //Le temps moyen de prise en compte du ticket - The average time to take a ticket into account
            $data = self::constructEntryValues("inter_avgtakeaccount", $date1, $date2, $type,
                                               $value[$i]["id"], $value2);
            foreach ($data as $key2 => $val2) {
               $data[$key2] *= $solved[$key2];
            }

            if ($nb_solved>0) {
               $timedisplay = array_sum($data)/$nb_solved;
            } else {
               $timedisplay = 0;
            }

            if ($output_type==HTML_OUTPUT
                || $output_type==PDF_OUTPUT_LANDSCAPE
                || $output_type==PDF_OUTPUT_PORTRAIT) {
               $timedisplay = timestampToString($timedisplay, 0);
            }
            echo Search::showItem($output_type, $timedisplay, $item_num, $row_num);


            //Le temps moyen de resolution - The average time to resolv
            $data = self::constructEntryValues("inter_avgsolvedtime", $date1, $date2, $type,
                                               $value[$i]["id"], $value2);
            foreach ($data as $key2 => $val2) {
               $data[$key2] = round($data[$key2]*$solved[$key2]);
            }

            if ($nb_solved>0) {
               $timedisplay = array_sum($data)/$nb_solved;
            } else {
               $timedisplay = 0;
            }
            if ($output_type==HTML_OUTPUT
                || $output_type==PDF_OUTPUT_LANDSCAPE
                || $output_type==PDF_OUTPUT_PORTRAIT) {
               $timedisplay = timestampToString($timedisplay, 0);
            }
            echo Search::showItem($output_type, $timedisplay, $item_num, $row_num);

            //Le temps moyen de cloture - The average time to close
            $data = self::constructEntryValues("inter_avgclosedtime", $date1, $date2, $type,
                                               $value[$i]["id"], $value2);
            foreach ($data as $key2 => $val2) {
               $data[$key2] = round($data[$key2]*$solved[$key2]);
            }

            if ($nb_closed>0) {
               $timedisplay = array_sum($data)/$nb_closed;
            } else {
               $timedisplay = 0;
            }
            if ($output_type==HTML_OUTPUT
                || $output_type==PDF_OUTPUT_LANDSCAPE
                || $output_type==PDF_OUTPUT_PORTRAIT) {
               $timedisplay = timestampToString($timedisplay, 0);
            }
            echo Search::showItem($output_type, $timedisplay, $item_num, $row_num);

            //Le temps moyen de l'intervention reelle - The average actiontime to resolv
            $data = self::constructEntryValues("inter_avgactiontime", $date1, $date2, $type,
                                               $value[$i]["id"], $value2);
            foreach ($data as $key2 => $val2) {
               if (isset($solved[$key2])) {
                  $data[$key2] *= $solved[$key2];
               } else {
                  $data[$key2] *= 0;
               }
            }
            $total_actiontime = array_sum($data);

            if ($nb_solved>0) {
               $timedisplay = $total_actiontime/$nb_solved;
            } else {
               $timedisplay = 0;
            }

            if ($output_type==HTML_OUTPUT
                || $output_type==PDF_OUTPUT_LANDSCAPE
                || $output_type==PDF_OUTPUT_PORTRAIT) {
               $timedisplay = timestampToString($timedisplay, 0);
            }
            echo Search::showItem($output_type, $timedisplay, $item_num, $row_num);
            //Le temps total de l'intervention reelle - The total actiontime to resolv
            $timedisplay = $total_actiontime;

            if ($output_type==HTML_OUTPUT
                || $output_type==PDF_OUTPUT_LANDSCAPE
                || $output_type==PDF_OUTPUT_PORTRAIT) {
               $timedisplay = timestampToString($timedisplay, 0);
            }
            echo Search::showItem($output_type, $timedisplay, $item_num, $row_num);

            echo Search::showEndLine($output_type);
         }
         // Display footer
         echo Search::showFooter($output_type);

      } else {
         echo $LANG['stats'][23];
      }

      if ($output_type==HTML_OUTPUT) { // HTML display
         echo "</div>";
      }
   }


   static function constructEntryValues($type, $begin="", $end="", $param="", $value="",
                                        $value2="") {
      global $DB;

      $query         = "";
      $WHERE         = getEntitiesRestrictRequest("WHERE", "glpi_tickets");
      $LEFTJOIN      = "";
      $LEFTJOINUSER  = "LEFT JOIN `glpi_tickets_users`
                           ON (`glpi_tickets_users`.`tickets_id` = `glpi_tickets`.`id`)";
      $LEFTJOINGROUP = "LEFT JOIN `glpi_groups_tickets`
                           ON (`glpi_groups_tickets`.`tickets_id` = `glpi_tickets`.`id`)";

      switch ($param) {
         case "technicien" :
            $LEFTJOIN = $LEFTJOINUSER;
            $WHERE .= " AND (`glpi_tickets_users`.`users_id` = '$value'
                              AND `glpi_tickets_users`.`type`='".Ticket::ASSIGN."')";
            break;

         case "technicien_followup" :
            $WHERE .= " AND `glpi_tickettasks`.`users_id` = '$value'";
            $LEFTJOIN = " LEFT JOIN `glpi_tickettasks`
                              ON (`glpi_tickettasks`.`tickets_id` = `glpi_tickets`.`id`)";
            break;

         case "enterprise" :
            $WHERE .= " AND `glpi_tickets`.`suppliers_id_assign` = '$value'";
            break;

         case "user" :
            $LEFTJOIN = $LEFTJOINUSER;
            $WHERE .= " AND (`glpi_tickets_users`.`users_id` = '$value'
                              AND `glpi_tickets_users`.`type` ='".Ticket::REQUESTER."')";
            break;

         case "usertitles_id" :
            $LEFTJOIN = $LEFTJOINUSER;
            $LEFTJOIN .= " LEFT JOIN `glpi_users`
                              ON (`glpi_users`.`id` = `glpi_tickets_users`.`users_id`)";
            $WHERE .= " AND (`glpi_users`.`usertitles_id` = '$value'
                              AND `glpi_tickets_users`.`type` = '".Ticket::REQUESTER."')";
            break;

         case "usercategories_id" :
            $LEFTJOIN = $LEFTJOINUSER;
            $LEFTJOIN .= " LEFT JOIN `glpi_users`
                              ON (`glpi_users`.`id` = `glpi_tickets_users`.`users_id`)";
            $WHERE .= " AND (`glpi_users`.`usercategories_id` = '$value'
                              AND `glpi_tickets_users`.`type` = '".Ticket::REQUESTER."')";
            break;

         case "users_id_recipient" :
            $WHERE .= " AND `glpi_tickets`.`users_id_recipient` = '$value'";
            break;

         case "type" :
            $WHERE .= " AND `glpi_tickets`.`type` = '$value'";
            break;

         case "ticketcategories_id" :
            if (!empty($value)) {
               // do not merge for pie chart
               if (!isset($_REQUEST['showgraph']) || !$_REQUEST['showgraph']) {
                  $categories = getSonsOf("glpi_ticketcategories", $value);
                  $condition  = implode("','",$categories);
                  $WHERE .= " AND `glpi_tickets`.`ticketcategories_id` IN ('$condition')";
               } else {
                  $WHERE .= " AND `glpi_tickets`.`ticketcategories_id` = '$value' ";
               }

            } else {
               $WHERE .= " AND `glpi_tickets`.`ticketcategories_id` = '$value' ";
            }
            break;

         case "group" :
            $LEFTJOIN = $LEFTJOINGROUP;
            $WHERE .= " AND (`glpi_groups_tickets`.`groups_id` = '$value'
                              AND `glpi_groups_tickets`.`type` = '".Ticket::REQUESTER."')";
            break;

         case "groups_id_assign" :
            $LEFTJOIN = $LEFTJOINGROUP;
            $WHERE .= " AND (`glpi_groups_tickets`.`groups_id` = '$value'
                              AND `glpi_groups_tickets`.`type` = '".Ticket::ASSIGN."')";
            break;

         case "requesttypes_id" :
         case "ticketsolutiontypes_id" :
         case "urgency" :
         case "impact" :
         case "priority" :
            $WHERE .= " AND `glpi_tickets`.`$param` = '$value'";
            break;


         case "device":
            $devtable = getTableForItemType('Computer_'.$value2);
            $fkname   = getForeignKeyFieldForTable(getTableForItemType($value2));
            //select computers IDs that are using this device;
            $LEFTJOIN = " INNER JOIN `glpi_computers`
                              ON (`glpi_computers`.`id` = `glpi_tickets`.`items_id`
                                  AND `glpi_tickets`.`itemtype` = 'Computer')
                          INNER JOIN `$devtable`
                              ON (`glpi_computers`.`id` = `$devtable`.`computers_id`
                                  AND `$devtable`.`$fkname` = '$value')";
            $WHERE .= " AND `glpi_computers`.`is_template` <> '1' ";
            break;

         case "comp_champ" :
            $table = getTableForItemType($value2);
            $champ = getForeignKeyFieldForTable($table);
            $LEFTJOIN = " INNER JOIN `glpi_computers`
                              ON (`glpi_computers`.`id` = `glpi_tickets`.`items_id`
                                  AND `glpi_tickets`.`itemtype` = 'Computer')";
            $WHERE .= " AND `glpi_computers`.`$champ` = '$value'
                        AND `glpi_computers`.`is_template` <> '1'";
            break;
      }

      switch($type) {
         case "inter_total" :
            $WHERE .= " AND ".getDateRequest("`glpi_tickets`.`date`",$begin,$end);

            $query = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`glpi_tickets`.`date`),'%Y-%m')
                                 AS date_unix,
                             COUNT(`glpi_tickets`.`id`) AS total_visites
                      FROM `glpi_tickets`
                      $LEFTJOIN
                      $WHERE
                      GROUP BY date_unix
                      ORDER BY `glpi_tickets`.`date`";
            break;

         case "inter_solved" :
            $WHERE .= " AND (`glpi_tickets`.`status` = 'closed'
                             OR `glpi_tickets`.`status` = 'solved')
                        AND `glpi_tickets`.`solvedate` IS NOT NULL
                        AND ".getDateRequest("`glpi_tickets`.`solvedate`", $begin, $end);

            $query = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`glpi_tickets`.`solvedate`),'%Y-%m')
                                 AS date_unix,
                             COUNT(`glpi_tickets`.`id`) AS total_visites
                      FROM `glpi_tickets`
                      $LEFTJOIN
                      $WHERE
                      GROUP BY date_unix
                      ORDER BY `glpi_tickets`.`solvedate`";
            break;

         case "inter_solved_late" :
            $WHERE .= " AND (`glpi_tickets`.`status` = 'closed'
                             OR `glpi_tickets`.`status` = 'solved')
                        AND `glpi_tickets`.`solvedate` IS NOT NULL
                        AND `glpi_tickets`.`due_date` IS NOT NULL
                        AND ".getDateRequest("`glpi_tickets`.`solvedate`", $begin, $end)."
                        AND `glpi_tickets`.`solvedate` > `glpi_tickets`.`due_date`";

            $query = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`glpi_tickets`.`solvedate`),'%Y-%m')
                                 AS date_unix,
                             COUNT(`glpi_tickets`.`id`) AS total_visites
                      FROM `glpi_tickets`
                      $LEFTJOIN
                      $WHERE
                      GROUP BY date_unix
                      ORDER BY `glpi_tickets`.`solvedate`";
            break;

         case "inter_closed" :
            $WHERE .= " AND (`glpi_tickets`.`status` = 'closed')
                        AND `glpi_tickets`.`closedate` IS NOT NULL
                        AND ".getDateRequest("`glpi_tickets`.`closedate`", $begin, $end);

            $query = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`glpi_tickets`.`closedate`),'%Y-%m')
                                 AS date_unix,
                             COUNT(`glpi_tickets`.`id`) AS total_visites
                      FROM `glpi_tickets`
                      $LEFTJOIN
                      $WHERE
                      GROUP BY date_unix
                      ORDER BY `glpi_tickets`.`closedate`";
            break;

         case "inter_avgsolvedtime" :
            $WHERE .= " AND (`glpi_tickets`. `status` = 'solved'
                             OR `glpi_tickets`.`status` = 'closed')
                        AND `glpi_tickets`.`solvedate` IS NOT NULL
                        AND ".getDateRequest("`glpi_tickets`.`solvedate`", $begin, $end);

            $query = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`glpi_tickets`.`solvedate`),'%Y-%m')
                                 AS date_unix,
                             AVG(solve_delay_stat) AS total_visites
                      FROM `glpi_tickets`
                      $LEFTJOIN
                      $WHERE
                      GROUP BY date_unix
                      ORDER BY `glpi_tickets`.`solvedate`";
            break;

         case "inter_avgclosedtime" :
            $WHERE .= " AND (`glpi_tickets`.`status` = 'closed')
                        AND `glpi_tickets`.`closedate` IS NOT NULL
                        AND ".getDateRequest("`glpi_tickets`.`closedate`", $begin, $end);

            $query = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`glpi_tickets`.`closedate`),'%Y-%m')
                                 AS date_unix,
                             AVG(close_delay_stat) AS total_visites
                      FROM `glpi_tickets`
                      $LEFTJOIN
                      $WHERE
                      GROUP BY date_unix
                      ORDER BY `glpi_tickets`.`closedate`";
            break;

         case "inter_avgactiontime" :
            if ($param=="technicien_followup") {
               $actiontime_table = "glpi_tickettasks";
            } else {
               $actiontime_table = "glpi_tickets";
            }
            $WHERE .= " AND `$actiontime_table`.`actiontime` > '0'
                        AND ".getDateRequest("`glpi_tickets`.`solvedate`", $begin, $end);

            $query = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`glpi_tickets`.`solvedate`),'%Y-%m')
                                 AS date_unix,
                             AVG(`$actiontime_table`.`actiontime`) AS total_visites
                      FROM `glpi_tickets`
                      $LEFTJOIN
                      $WHERE
                      GROUP BY date_unix
                      ORDER BY `glpi_tickets`.`solvedate`";
            break;

         case "inter_avgtakeaccount" :
            $WHERE .= " AND (`glpi_tickets`.`status` = 'solved'
                             OR `glpi_tickets`.`status` = 'closed')
                        AND `glpi_tickets`.`solvedate` IS NOT NULL
                        AND ".getDateRequest("`glpi_tickets`.`solvedate`", $begin, $end);

            $query = "SELECT `glpi_tickets`.`id`,
                             FROM_UNIXTIME(UNIX_TIMESTAMP(`glpi_tickets`.`solvedate`),'%Y-%m')
                                 AS date_unix,
                             AVG(`glpi_tickets`.`takeintoaccount_delay_stat`) AS total_visites
                      FROM `glpi_tickets`
                      $LEFTJOIN
                      $WHERE
                      GROUP BY `glpi_tickets`.`id`
                      ORDER BY `glpi_tickets`.`solvedate`";
            break;

         case "inter_opensatisfaction" :
            $WHERE .= " AND `glpi_tickets`.`status` = 'closed'
                        AND `glpi_tickets`.`closedate` IS NOT NULL
                        AND ".getDateRequest("`glpi_tickets`.`closedate`", $begin, $end);

            $query = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`glpi_tickets`.`closedate`),'%Y-%m')
                                 AS date_unix,
                             COUNT(`glpi_tickets`.`id`) AS total_visites
                      FROM `glpi_tickets`
                      INNER JOIN `glpi_ticketsatisfactions`
                        ON (`glpi_tickets`.`id` = `glpi_ticketsatisfactions`.`tickets_id`)
                      $LEFTJOIN
                      $WHERE
                      GROUP BY date_unix
                      ORDER BY `glpi_tickets`.`closedate`";
            break;

         case "inter_answersatisfaction" :
            $WHERE .= " AND `glpi_tickets`.`status` = 'closed'
                        AND `glpi_tickets`.`closedate` IS NOT NULL
                        AND `glpi_ticketsatisfactions`.`date_answered` IS NOT NULL
                        AND ".getDateRequest("`glpi_tickets`.`closedate`", $begin, $end);

            $query = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`glpi_tickets`.`closedate`),'%Y-%m')
                                 AS date_unix,
                             COUNT(`glpi_tickets`.`id`) AS total_visites
                      FROM `glpi_tickets`
                      INNER JOIN `glpi_ticketsatisfactions`
                        ON (`glpi_tickets`.`id` = `glpi_ticketsatisfactions`.`tickets_id`)
                      $LEFTJOIN
                      $WHERE
                      GROUP BY date_unix
                      ORDER BY `glpi_tickets`.`closedate`";
            break;

         case "inter_avgsatisfaction" :
            $WHERE .= " AND `glpi_ticketsatisfactions`.`date_answered` IS NOT NULL
                        AND `glpi_tickets`.`status` = 'closed'
                        AND `glpi_tickets`.`closedate` IS NOT NULL
                        AND ".getDateRequest("`glpi_tickets`.`closedate`", $begin, $end);

            $query = "SELECT FROM_UNIXTIME(UNIX_TIMESTAMP(`glpi_tickets`.`closedate`),'%Y-%m')
                                 AS date_unix,
                             AVG(`glpi_ticketsatisfactions`.`satisfaction`) AS total_visites
                      FROM `glpi_tickets`
                      INNER JOIN `glpi_ticketsatisfactions`
                        ON (`glpi_tickets`.`id` = `glpi_ticketsatisfactions`.`tickets_id`)
                      $LEFTJOIN
                      $WHERE
                      GROUP BY date_unix
                      ORDER BY `glpi_tickets`.`closedate`";
            break;

      }

      $entrees = array();
      $count   = array();
      if (empty($query)) {
         return array();
      }

      $result = $DB->query($query);
      if ($result && $DB->numrows($result)>0) {
         while ($row = $DB->fetch_array($result)) {
            $date = $row['date_unix'];
            //$visites = round($row['total_visites']);
            $entrees["$date"] = $row['total_visites'];
        }
      }

      // Remplissage de $entrees pour les mois ou il n'y a rien
//       $min=-1;
//       $max=0;
//       if (count($entrees)==0) {
//          return $entrees;
//       }
//       foreach ($entrees as $key => $val) {
//          $time=strtotime($key."-01");
//          if ($min>$time || $min<0) {
//             $min=$time;
//          }
//          if ($max<$time) {
//             $max=$time;
//          }
//       }

      $end_time   = strtotime(date("Y-m",strtotime($end))."-01");
      $begin_time = strtotime(date("Y-m",strtotime($begin))."-01");

//       if ($max<$end_time) {
//          $max=$end_time;
//       }
//       if ($min>$begin_time) {
//          $min=$begin_time;
//       }
      $current = $begin_time;

      while ($current<=$end_time) {
         $curentry=date("Y-m",$current);
         if (!isset($entrees["$curentry"])) {
            $entrees["$curentry"] = 0;
         }
         $month   = date("m",$current);
         $year    = date("Y",$current);
         $current = mktime(0,0,0,intval($month)+1,1,intval($year));
      }
      ksort($entrees);

      return $entrees;
   }


   /** Get groups assigned to tickets between 2 dates
    * BASED ON SPIP DISPLAY GRAPH : www.spip.net
    *
    * @param $type string : "month" or "year"
    * @param $entrees array : array containing data to displayed
    * @param $titre string : title
    * @param $unit string : unit
    * @param $showtotal boolean : also show total values ?
    *
    * @return array contains the distinct groups assigned to a tickets
   **/
   static function graphBy($entrees, $titre="", $unit="", $showtotal=1, $type="month") {
      global $DB, $CFG_GLPI, $LANG;

      $total = "";
      if ($showtotal==1) {
         $total = array_sum($entrees);
      }

      echo "<p class='center'>";
      echo "<font face='verdana,arial,helvetica,sans-serif' size='2'>";
      echo "<strong>$titre - $total $unit</strong></font>";
      echo "<div class='center'>";

      if (count($entrees)>0) {
         $max      = max($entrees);
         $maxgraph = substr(ceil(substr($max,0,2) / 10)."000000000000", 0, strlen($max));

         if ($maxgraph < 10) {
            $maxgraph = 10;
         }
         if (1.1 * $maxgraph < $max) {
            $maxgraph .= "0";
         }
         if (0.8*$maxgraph > $max) {
            $maxgraph = 0.8 * $maxgraph;
         }
         $rapport = 200 / $maxgraph;

         $largeur = floor(420 / (count($entrees)));
         if ($largeur < 1) {
            $largeur = 1;
         }
         if ($largeur > 50) {
            $largeur = 50;
         }
      }

      echo "<table class='tab_glpi'><tr>";
      echo "<td style='background-image:url(".$CFG_GLPI["root_doc"]."/pics/fond-stats.gif)' >";
      echo "<table><tr><td bgcolor='black'>";
      echo "<img src='".$CFG_GLPI["root_doc"]."/pics/noir.png' width='1' height='200' alt=''></td>";

      // Presentation graphique
      $n           = 0;
      $decal       = 0;
      $tab_moyenne = "";
      $total_loc   = 0;
      while (list($key, $value) = each($entrees)) {
         $n++;
         if ($decal == 30) {
            $decal = 0;
         }
         $decal ++;
         $tab_moyenne[$decal] = $value;

         $total_loc = $total_loc + $value;
         reset($tab_moyenne);

         $moyenne = 0;
         while (list(,$val_tab) = each($tab_moyenne)) {
            $moyenne += $val_tab;
         }
         $moyenne         = $moyenne / count($tab_moyenne);
         $hauteur_moyenne = round($moyenne * $rapport) ;
         $hauteur         = round($value * $rapport)	;

         echo "<td class='bottom' width=".$largeur.">";

         if ($hauteur >= 0) {
            if ($hauteur_moyenne > $hauteur) {
               $difference = ($hauteur_moyenne - $hauteur) -1;
               echo "<img alt='$key: $value' title='$key: $value' src='".$CFG_GLPI["root_doc"].
                      "/pics/moyenne.png' width=".$largeur." height='1'>";
               echo "<img alt='$key: $value' title='$key: $value' src='".$CFG_GLPI["root_doc"].
                      "/pics/rien.gif' width=".$largeur." height=".$difference." >";
               echo "<img alt='$key: $value' title='$key: $value' src='".$CFG_GLPI["root_doc"].
                      "/pics/noir.png' width=".$largeur." height='1'>";

               if (strstr($key,"-01")) { // janvier en couleur foncee
                  echo "<img alt='$key: $value' title='$key: $value' src='".$CFG_GLPI["root_doc"].
                         "/pics/fondgraph1.png' width=".$largeur." height=".$hauteur." >";
               } else {
                  echo "<img alt='$key: $value' title='$key: $value' src='".$CFG_GLPI["root_doc"].
                         "/pics/fondgraph2.png' width=".$largeur." height=".$hauteur." >";
               }

            } else if ($hauteur_moyenne < $hauteur) {
               $difference = ($hauteur - $hauteur_moyenne) -1;
               echo "<img alt='$key: $value' title='$key: $value' src='".$CFG_GLPI["root_doc"].
                      "/pics/noir.png' width=".$largeur." height='1'>";

               if (strstr($key,"-01")) { // janvier en couleur foncee
                  $couleur  = "1";
                  $couleur2 = "2";
               } else {
                  $couleur  = "2";
                  $couleur2 = "1";
               }
               echo "<img alt='$key: $value' title='$key: $value' src='".$CFG_GLPI["root_doc"].
                      "/pics/fondgraph$couleur.png' width=".$largeur." height=".$difference.">";
               echo "<img alt='$key: $value' title='$key: $value' src='".$CFG_GLPI["root_doc"].
                      "/pics/moyenne.png' width=".$largeur." height='1'>";
               echo "<img alt='$key: $value' title='$key: $value' src='".$CFG_GLPI["root_doc"].
                      "/pics/fondgraph$couleur.png' width=".$largeur." height=".$hauteur_moyenne.">";

            } else {
               echo "<img alt='$key: $value' title='$key: $value' src='".$CFG_GLPI["root_doc"].
                      "/pics/noir.png' width=".$largeur." height='1'>";

               if (strstr($key,"-01")) { // janvier en couleur foncee
                  echo "<img alt='$key: $val_tab' title='$key: $value' src='".$CFG_GLPI["root_doc"].
                         "/pics/fondgraph1.png' width=".$largeur." height=".$hauteur.">";
               } else {
                  echo "<img alt='$key: $value' title='$key: $value' src='".$CFG_GLPI["root_doc"].
                         "/pics/fondgraph2.png' width=".$largeur." height=".$hauteur.">";
               }
            }
         }
         echo "<img alt='$value' title='$value' src='".$CFG_GLPI["root_doc"].
                "/pics/rien.gif' width=".$largeur." height='1'>";
         echo "</td>\n";
      }
      echo "<td bgcolor='black'>";
      echo "<img src='".$CFG_GLPI["root_doc"]."/pics/noir.png' width='1' height='1' alt=''></td></tr>";

      if ($largeur>10) {
         echo "<tr><td></td>";
         foreach ($entrees as $key => $val) {
            if ($type=="month") {
               $splitter = explode("-",$key);
               echo "<td class='center'>".utf8_substr($LANG['calendarM'][$splitter[1]-1],0,3)."</td>";
            } else if ($type=="year") {
               echo "<td class='center'>".substr($key,2,2)."</td>";
            }
         }
         echo "</tr>";
      }

      if ($maxgraph<=10) {
         $r = 2;
      } else if ($maxgraph<=100) {
         $r = 1;
      } else {
         $r = 0;
      }
      echo "</table>";
      echo "</td>";
      echo "<td style='background-image:url(".
             $CFG_GLPI["root_doc"]."/pics/fond-stats.gif)' class='bottom'>";
      echo "<img src='".$CFG_GLPI["root_doc"]."/pics/rien.gif' style='background-color:black;' ".
             "width='3' height='1' alt=''></td>";
      echo "<td><img src='".$CFG_GLPI["root_doc"]."/pics/rien.gif' width='5' height='1' alt=''></td>";
      echo "<td class='top'>";

      echo "<table>";
      echo "<tr><td height='15' class='top'>";
      echo "<font face='arial,helvetica,sans-serif' size='1' class ='b'>".
             formatNumber($maxgraph, false, $r)."</font></td></tr>";

      echo "<tr><td height='25' class='middle'>";
      echo "<font face='arial,helvetica,sans-serif' size='1' color='#999999'>".
             formatNumber(7*($maxgraph/8), false, $r)."</font></td></tr>";

      echo "<tr><td height='25' class='middle'>";
      echo "<font face='arial,helvetica,sans-serif' size='1'>".
             formatNumber(3*($maxgraph/4), false, $r)."</font></td></tr>";

      echo "<tr><td height='25' class='middle'>";
      echo "<font face='arial,helvetica,sans-serif' size='1' color='#999999'>".
             formatNumber(5*($maxgraph/8), false, $r)."</font></td></tr>";

      echo "<tr><td height='25' class='middle'>";
      echo "<font face='arial,helvetica,sans-serif' size='1' class ='b'>".
            formatNumber($maxgraph/2, false, $r)."</font></td></tr>";

      echo "<tr><td height='25' class='middle'>";
      echo "<font face='arial,helvetica,sans-serif' size='1' color='#999999'>".
             formatNumber(3*($maxgraph/8), false, $r)."</font></td></tr>";

      echo "<tr><td height='25' class='middle'>";
      echo "<font face='arial,helvetica,sans-serif' size='1'>".
             formatNumber($maxgraph/4, false, $r)."</font></td></tr>";

      echo "<tr><td height='25' class='middle'>";
      echo "<font face='arial,helvetica,sans-serif' size='1' color='#999999'>".
             formatNumber(1*($maxgraph/8), false, $r)."</font></td></tr>";

      echo "<tr><td height='10' class='bottom'>";
      echo "<font face='arial,helvetica,sans-serif' size='1' class='b'>0</font></td></tr>";

      echo "</table>";
      echo "</td></tr></table>";
      echo "</div>";
   }


   /** Get groups assigned to tickets between 2 dates
    *
    * @param $entrees array : array containing data to displayed
    * @param $options array : options
    *     - title string title displayed (default empty)
    *     - showtotal boolean show total in title (default false)
    *     - width integer width of the graph (default 700)
    *     - height integer height of the graph (default 300)
    *     - unit integer height of the graph (default empty)
    *     - type integer height of the graph (default line) : line bar pie
    *     - csv boolean export to CSV (default true)
    *     - datatype string datatype (count or average / default is count)
    *
    * @return array contains the distinct groups assigned to a tickets
   **/
   static function showGraph($entrees, $options=array()) {
      global $CFG_GLPI, $LANG;

      if ($uid=getLoginUserID(false)) {
         if (!isset($_SESSION['glpigraphtype'])) {
            $_SESSION['glpigraphtype'] = $CFG_GLPI['default_graphtype'];
         }

         $param['showtotal'] = false;
         $param['title']     = '';
         $param['width']     = 900;
         $param['height']    = 300;
         $param['unit']      = '';
         $param['type']      = 'line';
         $param['csv']       = true;
         $param['datatype']  = 'count';

         if (is_array($options) && count($options)) {
            foreach ($options as $key => $val) {
               $param[$key] = $val;
            }
         }

         // Clean data
         if (is_array($entrees) && count($entrees)) {
            foreach ($entrees as $key => $val) {
               if (!is_array($val) || count($val)==0) {
                  unset($entrees[$key]);
               }
            }
         }

         if (!is_array($entrees) || count($entrees) == 0) {
            if (!empty($param['title'])) {
               echo "<div class='center'>".$param['title']." : ".$LANG['stats'][2]."</div>";
            }
            return false;
         }

         echo "<div class='center-h' style='width:".$param['width']."px'>";
         echo "<div>";

         switch ($param['type']) {
            case 'pie' :
               // Check datas : sum must be > 0
               reset($entrees);
               $sum = array_sum(current($entrees));
               while ($sum==0 && $data=next($entrees)) {
                  $sum += array_sum($data);
               }
               if ($sum==0) {
                  return false;
               }
               $graph           = new ezcGraphPieChart();
               $graph->palette  = new GraphPalette();
               $graph->options->font->maxFontSize             = 15;
               $graph->title->background                      = '#EEEEEC';
               $graph->renderer = new ezcGraphRenderer3d();
               $graph->renderer->options->pieChartHeight      = 20;
               $graph->renderer->options->moveOut             = .2;
               $graph->renderer->options->pieChartOffset      = 63;
               $graph->renderer->options->pieChartGleam       = .3;
               $graph->renderer->options->pieChartGleamColor  = '#FFFFFF';
               $graph->renderer->options->pieChartGleamBorder = 2;
               $graph->renderer->options->pieChartShadowSize  = 5;
               $graph->renderer->options->pieChartShadowColor = '#BABDB6';

               if (count($entrees)==1) {
                  $graph->legend = false;
               }

               break;

            case 'bar' :
               $graph           = new ezcGraphBarChart();
               $graph->options->fillLines                   = 210;
               $graph->xAxis->axisLabelRenderer = new ezcGraphAxisRotatedBoxedLabelRenderer();
               $graph->xAxis->axisLabelRenderer->angle      = 45;
               $graph->xAxis->axisSpace                     = .2;
               $graph->yAxis->min                           = 0;
               $graph->palette  = new GraphPalette();
               $graph->options->font->maxFontSize           = 15;
               $graph->title->background                    = '#EEEEEC';
               $graph->renderer = new ezcGraphRenderer3d();
               $graph->renderer->options->legendSymbolGleam = .5;
               $graph->renderer->options->barChartGleam     = .5;

               $max = 0;
               foreach ($entrees as $key => $val) {
                  if (count($val) > $max) {
                     $max = count($val);
                  }
               }
               $graph->xAxis->labelCount = $max;
               break;

            case 'line' :
               // No break default case

            default :
               $graph           = new ezcGraphLineChart();
               $graph->options->fillLines                   = 210;
               $graph->xAxis->axisLabelRenderer = new ezcGraphAxisRotatedLabelRenderer();
               $graph->xAxis->axisLabelRenderer->angle      = 45;
               $graph->xAxis->axisSpace                     = .2;
               $graph->yAxis->min                           = 0;
               $graph->palette  = new GraphPalette();
               $graph->options->font->maxFontSize           = 15;
               $graph->title->background                    = '#EEEEEC';
               $graph->renderer = new ezcGraphRenderer3d();
               $graph->renderer->options->legendSymbolGleam = .5;
               $graph->renderer->options->barChartGleam     = .5;
               $graph->renderer->options->depth             = 0.07;
               break;
         }


         if (!empty($param['title'])) {
            $pretoadd  = "";
            $posttoadd = "";
            if (!empty($param['unit'])) {
               $posttoadd = " ".$param['unit'];
               $pretoadd  = " - ";
            }

            // Add to title
            if (count($entrees)==1) {
               $param['title'] .= $pretoadd;
               if ($param['showtotal']==1) {
                  reset($entrees);
                  $param['title'] .= round(array_sum(current($entrees)),2);
               }
               $param['title'] .= $posttoadd;

            } else { // add sum to legend and unit to title
               $param['title'] .= $pretoadd.$posttoadd;
               // Cannot display totals of already average values

               if ($param['showtotal']==1 && $param['datatype']!='average') {
                  $entree_tmp = $entrees;
                  $entrees    = array();
                  foreach ($entree_tmp as $key => $data) {
                     $sum = round(array_sum($data));
                     $entrees[$key." ($sum)"] = $data;
                  }
               }
            }

            $graph->title = $param['title'];
         }

         switch ($_SESSION['glpigraphtype']) {
            case "png" :
               $extension="png";
               $graph->driver        = new ezcGraphGdDriver();
               $graph->options->font = GLPI_FONT_FREESANS;
               break;

            default :
               $extension = "svg";
               break;
         }

         $filename    = $uid.'_'.mt_rand();
         $csvfilename = $filename.'.csv';
         $filename   .= '.'.$extension;
         foreach ($entrees as $label => $data) {
            $graph->data[$label]         = new ezcGraphArrayDataSet( $data );
            $graph->data[$label]->symbol = ezcGraph::NO_SYMBOL;
         }

         switch ($_SESSION['glpigraphtype']) {
            case "png" :
               $graph->render( $param['width'], $param['height'], GLPI_GRAPH_DIR.'/'.$filename );
               echo "<img src='".$CFG_GLPI['root_doc']."/front/graph.send.php?file=$filename'>";
               break;

            default :
               $graph->render( $param['width'], $param['height'], GLPI_GRAPH_DIR.'/'.$filename );
               echo "<object data='".$CFG_GLPI['root_doc']."/front/graph.send.php?file=$filename'
                      type='image/svg+xml' width='".$param['width']."' height='".$param['height']."'>
                      <param name='src' value='".$CFG_GLPI['root_doc'].
                       "/front/graph.send.php?file=$filename'>
                      You need a browser capeable of SVG to display this image.
                     </object> ";
            break;
         }

         // Render CSV
         if ($param['csv']) {
            if ($fp = fopen(GLPI_GRAPH_DIR.'/'.$csvfilename, 'w')) {
               // reformat datas
               $values  = array();
               $labels  = array();
               $row_num = 0;
               foreach ($entrees as $label => $data) {
                  $labels[$row_num] = $label;
                  if (is_array($data) && count($data)) {
                     foreach ($data as $key => $val) {
                        if (!isset($values[$key])) {
                           $values[$key] = array();
                        }
                        if ($param['datatype']=='average') {
                           $val = round($val,2);
                        }
                        $values[$key][$row_num] = $val;
                     }
                  }
                  $row_num++;
               }
               ksort($values);
               // Print labels
               fwrite($fp,$_SESSION["glpicsv_delimiter"]);
               foreach ($labels as $val) {
                  fwrite($fp, $val.$_SESSION["glpicsv_delimiter"]);
               }
               fwrite($fp,"\n");
               foreach ($values as $key => $data) {
                  fwrite($fp, $key.$_SESSION["glpicsv_delimiter"]);
                  foreach ($data as $value) {
                     fwrite($fp, $value.$_SESSION["glpicsv_delimiter"]);
                  }
                  fwrite($fp,"\n");
               }

               fclose($fp);
            }
         }
         echo "</div>";
         echo "<div class='right' style='width:".$param['width']."px'>";
         if ($_SESSION['glpigraphtype']!='svg') {
            echo "&nbsp;<a href='".$CFG_GLPI['root_doc']."/front/graph.send.php?switchto=svg'>SVG".
                 "</a>";
         }
         if ($_SESSION['glpigraphtype']!='png') {
            echo "&nbsp;<a href='".$CFG_GLPI['root_doc']."/front/graph.send.php?switchto=png'>PNG".
                 "</a>";
         }
         if ($param['csv']) {
            echo " / <a href='".$CFG_GLPI['root_doc']."/front/graph.send.php?file=$csvfilename'>CSV".
                 "</a>";
         }
         echo "</div>";
         echo '</div>';
      }
   }


   static function showItems($target, $date1, $date2, $start) {
      global $DB, $CFG_GLPI, $LANG;

      $view_entities = isMultiEntitiesMode();

      if ($view_entities) {
         $entities = getAllDatasFromTable('glpi_entities');
      }

      $output_type = HTML_OUTPUT;
      if (isset($_GET["display_type"])) {
         $output_type = $_GET["display_type"];
      }
      if (empty($date2)) {
         $date2 = date("Y-m-d");
      }
      $date2 .= " 23:59:59";

      // 1 an par defaut
      if (empty($date1)) {
         $date1 = date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y")-1));
      }
      $date1 .= " 00:00:00";

      $query = "SELECT `itemtype`,
                       `items_id`,
                       COUNT(*) AS NB
                FROM `glpi_tickets`
                WHERE `date` <= '$date2'
                      AND `date` >= '$date1' ".
                      getEntitiesRestrictRequest("AND","glpi_tickets")."
                      AND `itemtype` <> ''
                      AND `items_id` > 0
                GROUP BY `itemtype`, `items_id`
                ORDER BY NB DESC";

      $result  = $DB->query($query);
      $numrows = $DB->numrows($result);

      if ($numrows>0) {
         if ($output_type==HTML_OUTPUT) {
            printPager($start, $numrows, $target,
                       "date1=".$date1."&amp;date2=".$date2."&amp;type=hardwares&amp;start=$start",
                       'Stat');
            echo "<div class='center'>";
         }

         $end_display = $start+$_SESSION['glpilist_limit'];
         if (isset($_GET['export_all'])) {
            $end_display = $numrows;
         }
         echo Search::showHeader($output_type, $end_display-$start+1, 2, 1);
         $header_num = 1;
         echo Search::showNewLine($output_type);
         echo Search::showHeaderItem($output_type, $LANG['document'][14], $header_num);
         if ($view_entities) {
            echo Search::showHeaderItem($output_type, $LANG['entity'][0], $header_num);
         }
         echo Search::showHeaderItem($output_type, $LANG['stats'][13], $header_num);
         echo Search::showEndLine($output_type);

         $DB->data_seek($result, $start);

         $i = $start;
         if (isset($_GET['export_all'])) {
            $start = 0;
         }

         for ($i=$start ; $i<$numrows && $i<$end_display ; $i++) {
            $item_num = 1;
            // Get data and increment loop variables
            $data = $DB->fetch_assoc($result);
            if (!class_exists($data["itemtype"])) {
               continue;
            }
            $item = new $data["itemtype"]();
            if ($item->getFromDB($data["items_id"])) {
               echo Search::showNewLine($output_type, $i%2);
               echo Search::showItem($output_type, $item->getTypeName()." - ".$item->getLink(),
                                     $item_num, $i-$start+1,
                                     "class='center'"." ".($item->isDeleted()?" class='deleted' ":""));
               if ($view_entities) {
                  $ent = $item->getEntityID();
                  if ($ent==0) {
                     $ent = $LANG['entity'][2];
                  } else {
                     $ent = $entities[$ent]['completename'];
                  }
                  echo Search::showItem($output_type, $ent, $item_num, $i-$start+1,
                                        "class='center'"." ".($item->isDeleted()?" class='deleted' ":""));
               }
               echo Search::showItem($output_type, $data["NB"], $item_num, $i-$start+1,
                                     "class='center'"." ".($item->isDeleted()?" class='deleted' ":""));
            }
         }

         echo Search::showFooter($output_type);
         if ($output_type==HTML_OUTPUT) {
            echo "</div>";
         }
      }
   }

}

?>
