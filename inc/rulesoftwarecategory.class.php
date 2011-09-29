<?php
/*
 * @version $Id: rulesoftwarecategory.class.php 14684 2011-06-11 06:32:40Z remi $
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


/**
* Rule class store all informations about a GLPI rule :
*   - description
*   - criterias
*   - actions
*
**/
class RuleSoftwareCategory extends Rule {

   // From Rule
   public $right    = 'rule_softwarecategories';
   public $can_sort = true;

   function getTitle() {
      global $LANG;

      return $LANG['rulesengine'][37];
   }


   function maxActionsCount() {
      return 1;
   }


   function getCriterias() {
      global $LANG;

      $criterias = array();
      $criterias['name']['field'] = 'name';
      $criterias['name']['name']  = $LANG['help'][31];
      $criterias['name']['table'] = 'glpi_softwares';

      $criterias['manufacturer']['field'] = 'name';
      $criterias['manufacturer']['name']  = $LANG['common'][5];
      $criterias['manufacturer']['table'] = 'glpi_manufacturers';

      $criterias['comment']['field'] = 'comment';
      $criterias['comment']['name']  = $LANG['common'][25];
      $criterias['comment']['table'] = 'glpi_softwares';
      return $criterias;
   }


   function getActions() {
      global $LANG;

      $actions = array();
      $actions['softwarecategories_id']['name']  = $LANG['common'][36];
      $actions['softwarecategories_id']['type']  = 'dropdown';
      $actions['softwarecategories_id']['table'] = 'glpi_softwarecategories';
      return $actions;
   }

}

?>
