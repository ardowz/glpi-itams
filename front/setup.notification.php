<?php
/*
 * @version $Id: setup.notification.php 14684 2011-06-11 06:32:40Z remi $
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

checkSeveralRightsOr(array('notification'=>'r','config'=>'w'));

commonHeader($LANG['setup'][704], $_SERVER['PHP_SELF'],"config","mailing",-1);

if (isset($_GET['activate'])) {
   $config = new Config;
   $tmp['id'] = $CFG_GLPI['id'];
   $tmp['use_mailing'] = 1;
   $config->update($tmp);
   glpi_header($_SERVER['HTTP_REFERER']);
}
if (!$CFG_GLPI['use_mailing']) {
   echo "<div align='center'<p>";
   if (haveRight("config","w")) {
      echo "<a href='setup.notification.php?activate=1' class='icon_consol b'>" .
               $LANG['setup'][202] ."</a></p></div>";
   }
}
else {
   if (!haveRight("config","r") && haveRight("notification","r") && $CFG_GLPI['use_mailing']) {
      glpi_header($CFG_GLPI["root_doc"].'/front/notification.php');
   }else {
      echo "<table class='tab_cadre'>";
      echo "<tr><th>&nbsp;" . $LANG['setup'][704]."&nbsp;</th></tr>";
      if (haveRight("config","r")) {
         echo "<tr class='tab_bg_1'><td class='center'><a href='notificationmailsetting.form.php'>" .
               $LANG['setup'][201] ."</a></td></tr>";
            echo "<tr class='tab_bg_1'><td class='center'><a href='notificationtemplate.php'>" .
                  $LANG['mailing'][113] ."</a></td> </tr>";
      }
      if (haveRight("notification","r") && $CFG_GLPI['use_mailing']) {
         echo "<tr class='tab_bg_1'><td class='center'><a href='notification.php'>" . $LANG['setup'][704] .
               "</a></td></tr>";
      }
      else {
            echo "<tr class='tab_bg_1'><td class='center'>" . $LANG['setup'][661] ."</td></tr>";
      }
      echo "</table>";
   }
}

commonFooter();
?>
