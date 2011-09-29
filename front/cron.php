<?php
/*
 * @version $Id: cron.php 14684 2011-06-11 06:32:40Z remi $
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
// Original Author of file: JMD
// Purpose of file:
// ----------------------------------------------------------------------

// Ensure current directory when run from crontab
chdir(dirname($_SERVER["SCRIPT_FILENAME"]));


define('GLPI_ROOT', '..');
include (GLPI_ROOT . "/inc/includes.php");

if (!is_writable(GLPI_LOCK_DIR)) {
   echo "\tERROR : " .GLPI_LOCK_DIR. " not writable\n";
   echo "\trun script as 'apache' user\n";
   exit (1);
}

if (!isCommandLine()) {
   //The advantage of using background-image is that cron is called in a separate
   //request and thus does not slow down output of the main page as it would if called
   //from there.
   $image = pack("H*", "47494638396118001800800000ffffff00000021f90401000000002c0000000".
                       "018001800000216848fa9cbed0fa39cb4da8bb3debcfb0f86e248965301003b");
   header("Content-Type: image/gif");
   header("Content-Length: ".strlen($image));
   header("Cache-Control: no-cache,no-store");
   header("Pragma: no-cache");
   header("Connection: close");
   echo $image;
   flush();

   CronTask::launch(CronTask::MODE_INTERNAL);

} else if (isset($_SERVER['argc']) && $_SERVER['argc']>1) {
   // TODO Warning : command line is cron.php 1 2 3 4 : will produce cron.php 10 !
   // Parse command line options
   for ($i=1 ; $i<$_SERVER['argc'] ; $i++) {
      if (is_numeric($_SERVER['argv'][$i])) {
         // Number of tasks
         CronTask::launch(CronTask::MODE_EXTERNAL, intval($_SERVER['argv'][$i]));
      } else {
         // Task name
         CronTask::launch(CronTask::MODE_EXTERNAL, $CFG_GLPI['cron_limit'], $_SERVER['argv'][$i]);
      }
   }

} else {
   // Default from configuration
   CronTask::launch(CronTask::MODE_EXTERNAL, $CFG_GLPI['cron_limit']);
}

?>
