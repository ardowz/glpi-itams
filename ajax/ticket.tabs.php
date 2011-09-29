<?php
/*
 * @version $Id: ticket.tabs.php 14684 2011-06-11 06:32:40Z remi $
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

define('GLPI_ROOT', '..');
include (GLPI_ROOT . "/inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
header_nocache();

if (!isset($_POST["id"])) {
   exit();
}
if (!isset($_REQUEST['glpi_tab'])) {
   exit();
}

$ticket = new Ticket();

if ($_POST["id"]>0 && $ticket->getFromDB($_POST["id"])) {

   switch($_REQUEST['glpi_tab']) {
      case -1 :
         $fup = new TicketFollowup();
         $fup->showSummary($ticket);
         $validation = new Ticketvalidation();
         $validation->showSummary($ticket);
         $task = new TicketTask();
         $task->showSummary($ticket);
         $ticket->showSolutionForm();
         if ($ticket->canApprove()) {
            $fup->showApprobationForm($ticket);
         }
         $ticket->showCost($_POST['target']);
         $ticket->showStats();
         Document::showAssociated($ticket);
         Log::showForItem($ticket);
         Plugin::displayAction($ticket, $_REQUEST['glpi_tab']);
         break;

      case 2 :
         $task = new TicketTask();
         $task->showSummary($ticket);
         break;

      case 3 :
         $ticket->showCost($_POST['target']);
         break;

      case 4 :
         if (!isset($_POST['load_kb_sol'])) {
            $_POST['load_kb_sol'] = 0;
         }
         $ticket->showSolutionForm($_POST['load_kb_sol']);
         if ($ticket->canApprove()) {
            $fup = new TicketFollowup();
            $fup->showApprobationForm($ticket);
         }
         break;

      case 5 :
         Document::showAssociated($ticket);
         break;

      case 6 :
         Log::showForItem($ticket);
         break;

      case 7 :
         $validation = new Ticketvalidation();
         $validation->showSummary($ticket);
         break;

      case 8 :
         $ticket->showStats();
         break;

      case 10 :
      // affichage uniquement  si enquete déclenchée et status clos
         $satisfaction = new TicketSatisfaction();
         if ($ticket->fields['status'] == 'closed' && $satisfaction->getFromDB($_POST["id"])) {
            $satisfaction->showSatisfactionForm($ticket);
         } else {
            echo "<p class='center b'>".$LANG['satisfaction'][2]."</p>";
         }
         break;

      default :
         if (!Plugin::displayAction($ticket, $_REQUEST['glpi_tab'])) {
            $fup = new TicketFollowup();
            $fup->showSummary($ticket);
         }
   }
}

ajaxFooter();
?>
