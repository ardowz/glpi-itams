<?php
/*
 * @version $Id: rule.common.form.php 14684 2011-06-11 06:32:40Z remi $
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
// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

$rule = $rulecollection->getRuleClass();
$rulecollection->checkGlobal('r');

if (!isset($_GET["id"])) {
   $_GET["id"] = "";
}
$rulecriteria = new RuleCriteria(get_class($rule));
$ruleaction = new RuleAction(get_class($rule));

if (isset($_POST["delete_criteria"])) {
   $rulecollection->checkGlobal('w');

   if (count($_POST["item"])) {
      foreach ($_POST["item"] as $key => $val) {
         $input["id"] = $key;
         $rulecriteria->delete($input);
      }
   }
   // Can't do this in RuleCriteria, so do it here
   $rule->update(array('id'       => $_POST['rules_id'],
                       'date_mod' => $_SESSION['glpi_currenttime']));
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["delete_action"])) {
   $rulecollection->checkGlobal('w');

   if (count($_POST["item"])) {
      foreach ($_POST["item"] as $key => $val) {
         $input["id"] = $key;
         $ruleaction->delete($input);
      }
   }
   // Can't do this in RuleAction, so do it here
   $rule->update(array('id'       => $_POST['rules_id'],
                       'date_mod' => $_SESSION['glpi_currenttime']));
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["add_criteria"])) {
   $rulecollection->checkGlobal('w');
   $rulecriteria->add($_POST);

   // Can't do this in RuleCriteria, so do it here
   $rule->update(array('id'       => $_POST['rules_id'],
                       'date_mod' => $_SESSION['glpi_currenttime']));
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["add_action"])) {
   $rulecollection->checkGlobal('w');
   $ruleaction->add($_POST);

   // Can't do this in RuleCriteria, so do it here
   $rule->update(array('id'       => $_POST['rules_id'],
                       'date_mod' => $_SESSION['glpi_currenttime']));
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["update"])) {
   $rulecollection->checkGlobal('w');
   $rule->update($_POST);

   Event::log($_POST['id'], "rules", 4, "setup", $_SESSION["glpiname"]." ".$LANG['log'][21]);
   glpi_header($_SERVER['HTTP_REFERER']);

} else if (isset($_POST["add"])) {
   $rulecollection->checkGlobal('w');

   $newID = $rule->add($_POST);
   Event::log($newID, "rules", 4, "setup", $_SESSION["glpiname"]." ".$LANG['log'][20]);
   glpi_header($_SERVER['HTTP_REFERER']."?id=$newID");

} else if (isset($_POST["delete"])) {
   $rulecollection->checkGlobal('w');
   $rulecollection->deleteRuleOrder($_POST["ranking"]);
   $rule->delete($_POST);

   Event::log($_POST["id"], "rules", 4, "setup", $_SESSION["glpiname"]." ".$LANG['log'][22]);
   $rule->redirectToList();
}

commonHeader($LANG['common'][12], $_SERVER['PHP_SELF'], "admin",
             $rulecollection->menu_type, $rulecollection->menu_option);

$rule->showForm($_GET["id"]);
commonFooter();

?>
