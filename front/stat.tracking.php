<?php
/*
 * @version $Id: stat.tracking.php 14684 2011-06-11 06:32:40Z remi $
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

define('GLPI_ROOT', '..');
include (GLPI_ROOT . "/inc/includes.php");

commonHeader($LANG['Menu'][13],'',"maintain","stat");

checkRight("statistic","1");

if (empty($_REQUEST["type"])) {
   $_REQUEST["type"] = "user";
}

if (empty($_REQUEST["showgraph"])) {
   $_REQUEST["showgraph"] = 0;
}

if (empty($_REQUEST["date1"]) && empty($_REQUEST["date2"])) {
   $year = date("Y")-1;
   $_REQUEST["date1"] = date("Y-m-d",mktime(1,0,0,date("m"),date("d"),$year));
   $_REQUEST["date2"] = date("Y-m-d");
}

if (!empty($_REQUEST["date1"])
    && !empty($_REQUEST["date2"])
    && strcmp($_REQUEST["date2"],$_REQUEST["date1"]) < 0) {

   $tmp = $_REQUEST["date1"];
   $_REQUEST["date1"] = $_REQUEST["date2"];
   $_REQUEST["date2"] = $tmp;
}

if (!isset($_REQUEST["start"])) {
   $_REQUEST["start"] = 0;
}

$items =
   array($LANG['job'][4]
            => array('user'               => array('title' => $LANG['job'][4]),
                     'users_id_recipient' => array('title' => $LANG['common'][37]),
                     'group'              => array('title' => $LANG['common'][35]),
                     'usertitles_id'      => array('title' => $LANG['users'][1]),
                     'usercategories_id'  => array('title' => $LANG['users'][2])),
         $LANG['common'][32]
            => array('type'                   => array('title' => $LANG['common'][17]),
                     'ticketcategories_id'    => array('title' => $LANG['common'][36]),
                     'urgency'                => array('title' => $LANG['joblist'][29]),
                     'impact'                 => array('title' => $LANG['joblist'][30]),
                     'priority'               => array('title' => $LANG['joblist'][2]),
                     'requesttypes_id'        => array('title' => $LANG['job'][44]),
                     'ticketsolutiontypes_id' => array('title' => $LANG['job'][48])),
         $LANG['job'][5]
            => array('technicien'          => array('title' => $LANG['job'][6]." ".$LANG['stats'][48]),
                     'technicien_followup' => array('title' => $LANG['job'][6]." ".$LANG['stats'][49]),
                     'groups_id_assign'    => array('title' => $LANG['common'][35]),
                     'enterprise'          => array('title' => $LANG['financial'][26])));

$INSELECT = "";
foreach ($items as $label => $tab) {
   $INSELECT .= "<optgroup label=\"$label\">";
   foreach ($tab as $key => $val) {
      $INSELECT .= "<option value='$key' ".($key==$_REQUEST["type"]?"selected":"").">".$val['title'].
                   "</option>";
   }
   $INSELECT .= "</optgroup>";
}

echo "<div class='center'><form method='get' name='form' action='stat.tracking.php'>";
echo "<table class='tab_cadre'>";
echo "<tr class='tab_bg_2'><td rowspan='2' class='center'>";
echo "<select name='type'>".$INSELECT."</select></td>";
echo "<td class='right'>".$LANG['search'][8]."&nbsp;:</td><td>";
showDateFormItem("date1", $_REQUEST["date1"]);
echo "</td>";
echo "<td class='right'>".$LANG['stats'][7]."&nbsp;:</td>";
echo "<td rowspan='2' class='center'>";
echo "<input type='submit' class='button' name='submit' value=\"". $LANG['buttons'][7] ."\"></td></tr>";

echo "<tr class='tab_bg_2'><td class='right'>".$LANG['search'][9]."&nbsp;:</td><td>";
showDateFormItem("date2", $_REQUEST["date2"]);
echo "</td><td class='center'>";
Dropdown::showYesNo('showgraph', $_REQUEST['showgraph']);
echo "</td></tr>";
echo "</table></form></div>";

$val    = Stat::getItems($_REQUEST["date1"], $_REQUEST["date2"], $_REQUEST["type"]);
$params = array('type'  => $_REQUEST["type"],
                'date1' => $_REQUEST["date1"],
                'date2' => $_REQUEST["date2"],
                'start' => $_REQUEST["start"]);

printPager($_REQUEST['start'], count($val), $CFG_GLPI['root_doc'].'/front/stat.tracking.php',
           "date1=".$_REQUEST["date1"]."&amp;date2=".$_REQUEST["date2"]."&amp;type=".
               $_REQUEST["type"]."&amp;showgraph=".$_REQUEST["showgraph"],
           'Stat', $params);

if (!$_REQUEST['showgraph']) {
   Stat::show($_REQUEST["type"], $_REQUEST["date1"], $_REQUEST["date2"], $_REQUEST['start'], $val);

} else {
   $data = Stat::getDatas($_REQUEST["type"], $_REQUEST["date1"], $_REQUEST["date2"],
                          $_REQUEST['start'], $val);

   if (isset($data['opened']) && is_array($data['opened'])) {
      foreach ($data['opened'] as $key => $val) {
         $newkey             = html_clean($key);
         $cleandata[$newkey] = $val;
      }
      Stat::showGraph(array($LANG['stats'][5] => $cleandata),
                      array('title'     => $LANG['stats'][5],
                            'showtotal' => 1,
                            'unit'      => $LANG['stats'][35],
                            'type'      => 'pie'));
   }

   if (isset($data['solved']) && is_array($data['solved'])) {
      foreach ($data['solved'] as $key => $val) {
         $newkey             = html_clean($key);
         $cleandata[$newkey] = $val;
      }
      Stat::showGraph(array($LANG['stats'][11] => $cleandata),
                      array('title'     => $LANG['stats'][11],
                            'showtotal' => 1,
                            'unit'      => $LANG['stats'][35],
                            'type'      => 'pie'));
   }

   if (isset($data['late']) && is_array($data['late'])) {
      foreach ($data['late'] as $key => $val) {
         $newkey             = html_clean($key);
         $cleandata[$newkey] = $val;
      }

      Stat::showGraph(array($LANG['stats'][19] => $cleandata),
                      array('title'     => $LANG['stats'][19],
                            'showtotal' => 1,
                            'unit'      => $LANG['stats'][35],
                            'type'      => 'pie'));
   }


   if (isset($data['closed']) && is_array($data['closed'])) {
      foreach ($data['closed'] as $key => $val) {
         $newkey             = html_clean($key);
         $cleandata[$newkey] = $val;
      }
      Stat::showGraph(array($LANG['stats'][11] => $cleandata),
                      array('title'     => $LANG['stats'][17],
                            'showtotal' => 1,
                            'unit'      => $LANG['stats'][35],
                            'type'      => 'pie'));
   }

   if (isset($data['opensatisfaction']) && is_array($data['opensatisfaction'])) {
      foreach ($data['opensatisfaction'] as $key => $val) {
         $newkey             = html_clean($key);
         $cleandata[$newkey] = $val;
      }
      Stat::showGraph(array($LANG['stats'][11] => $cleandata),
                      array('title'     => $LANG['satisfaction'][3],
                            'showtotal' => 1,
                            'unit'      => $LANG['stats'][35],
                            'type'      => 'pie'));
   }

}

commonFooter();

?>
