<?php
/*
 * @version $Id: reservation.form.php 14684 2011-06-11 06:32:40Z remi $
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

checkRight("reservation_helpdesk","1");

$rr = new Reservation();

if ($_SESSION["glpiactiveprofile"]["interface"] == "helpdesk") {
   helpHeader($LANG['Menu'][31],$_SERVER['PHP_SELF'],$_SESSION["glpiname"]);
} else {
   commonHeader($LANG['Menu'][17],$_SERVER['PHP_SELF'],"utils","reservation");
}

if (isset($_POST["update"])) {
   list($begin_year,$begin_month,$begin_day) = explode("-",$_POST["begin"]);
   if (haveRight("reservation_central","w")
       || getLoginUserID() === $_POST["users_id"]) {
      $_POST['_target'] = $_SERVER['PHP_SELF'];
      $_POST['_item'] = key($_POST["items"]);
      if ($rr->update($_POST)) {
         glpi_header($CFG_GLPI["root_doc"]."/front/reservation.php?reservationitems_id=".
                     $_POST['_item']."&mois_courant=$begin_month&annee_courante=$begin_year");
      }
   }

} else if (isset($_POST["delete"])) {
   $reservationitems_id = key($_POST["items"]);
   if ($rr->delete($_POST)) {
      Event::log($_POST["id"], "reservation", 4, "inventory",
                 $_SESSION["glpiname"]." ".$LANG['log'][22]);
   }

   list($begin_year,$begin_month,$begin_day) = explode("-",$_POST["begin"]);
   glpi_header($CFG_GLPI["root_doc"]."/front/reservation.php?reservationitems_id=".
               "$reservationitems_id&mois_courant=$begin_month&annee_courante=$begin_year");

} else if (isset($_POST["add"])) {
   $all_ok = true;
   $reservationitems_id = 0;
   if (empty($_POST['users_id'])) {
      $_POST['users_id'] = getLoginUserID();
   }
   foreach ($_POST['items'] as $reservationitems_id) {
      $_POST['reservationitems_id'] = $reservationitems_id;

      $times = $_POST["periodicity_times"];
      $begin = $_POST["begin"];
      list($begin_year,$begin_month,$begin_day) = explode("-",$_POST["begin"]);
      $end = $_POST["end"];
      $to_add = 1;

      if ($_POST["periodicity"] == "week") {
         $to_add = 7;
      }
      $_POST['_target'] = $_SERVER['PHP_SELF'];

      $_POST['_ok'] = true;
      for ($i=0 ; $i<$times && ($_POST['_ok']) ; $i++) {
         $_POST["begin"] = date('Y-m-d H:i:s', strtotime($begin)+$i*$to_add*DAY_TIMESTAMP);
         $_POST["end"] = date('Y-m-d H:i:s', strtotime($end)+$i*$to_add*DAY_TIMESTAMP);

         if (haveRight("reservation_central","w")
             || getLoginUserID() === $_POST["users_id"]) {
            unset($rr->fields["id"]);
            $_POST['_ok'] = $rr->add($_POST);
         }
      }
      // Positionnement du calendrier au mois de debut
      $_GET["mois_courant"] = $begin_month;
      $_GET["annee_courant"] = $begin_year;

      if ($_POST['_ok']) {
         Event::log($_POST["reservationitems_id"], "reservation", 4, "inventory",
                    $_SESSION["glpiname"]." ".$LANG['log'][20]);
      } else {
         $all_ok = false;
      }
   }
   if ($all_ok) {
      $toadd = "";
      // Only one reservation
      if (count($_POST['items']) == 1) {
         $toadd = "?reservationitems_id=$reservationitems_id";
      }
      glpi_header($CFG_GLPI["root_doc"] . "/front/reservation.php$toadd");
   }

} else if (isset($_GET["id"])) {
   if (!isset($_GET['date'])) {
      $_GET['date'] = date('Y-m-d');
   }
   if (empty($_GET["id"])
       && (!isset($_GET['item']) || count($_GET['item']) == 0 )) {
      glpi_header($_SERVER['HTTP_REFERER']);
   }
   if (!empty($_GET["id"])
       || (isset($_GET['item']) && isset($_GET['date']))) {
      $rr->showForm($_GET['id'], $_GET);
   }
}

if ($_SESSION["glpiactiveprofile"]["interface"] == "helpdesk") {
   helpFooter();
} else {
   commonFooter();
}

?>