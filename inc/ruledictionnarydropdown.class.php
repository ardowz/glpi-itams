<?php
/*
 * @version $Id: ruledictionnarydropdown.class.php 14684 2011-06-11 06:32:40Z remi $
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

class RuleDictionnaryDropdown extends RuleCached {

   // From Rule
   public $right      = 'rule_dictionnary_dropdown';
   public $can_sort   = true;
   public $show_cache = false;


   function maxActionsCount() {
      return 1;
   }


   function showCacheRuleHeader() {
      global $LANG;

      if ($this->show_cache) {
         echo "<tr><th colspan='3'>".$LANG['rulesengine'][100]."&nbsp;: ".$this->fields["name"].
              "</th></tr>";
         echo "<tr><td class='tab_bg_1 b'>".$LANG['rulesengine'][104]."</td>";
         echo "<td class='tab_bg_1 b'>".$LANG['common'][5]."</td>";
         echo "<td class='tab_bg_1 b'>".$LANG['rulesengine'][105]."</td></tr>";
      } else {
         parent::showCacheRuleHeader();
      }
   }


   function showCacheRuleDetail($fields) {
      global $LANG;

      if ($this->show_cache) {
         echo "<td class='tab_bg_2'>".$fields["old_value"]."</td>";
         echo "<td class='tab_bg_2'>".($fields["manufacturer"]!=''?$fields["manufacturer"]:'').
              "</td>";
         echo "<td class='tab_bg_2'>".($fields["new_value"]!='' ? $fields["new_value"]
                                                                : $LANG['rulesengine'][106])."</td>";
      } else {
         parent::showCacheRuleDetail($fields);
      }
   }

}

?>
