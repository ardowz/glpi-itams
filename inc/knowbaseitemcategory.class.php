<?php
/*
 * @version $Id: knowbaseitemcategory.class.php 14684 2011-06-11 06:32:40Z remi $
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
// Original Author of file: Remi Collet
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/// Class KnowbaseItemCategory
class KnowbaseItemCategory extends CommonTreeDropdown {

   function canCreate() {
      return haveRight('entity_dropdown', 'w');
   }


   function canView() {
      return haveRight('entity_dropdown', 'r');
   }


   static function getTypeName() {
      global $LANG;

      return $LANG['setup'][87];
   }


   /**
    * Report if a dropdown have Child
    * Used to (dis)allow delete action
   **/
   function haveChildren() {

      if (parent::haveChildren()) {
         return true;
      }
      $kb = new KnowbaseItem();
      $fk = $this->getForeignKeyField();
      $id = $this->fields['id'];

      return (countElementsInTable($kb->getTable(),"`$fk`='$id'")>0);
   }


   /**
    * Show KB categories
    *
    * @param $options : $_GET
    * @param $faq display on faq ?
    *
    * @return nothing (display the form)
   **/
   static function showFirstLevel($options, $faq=0) {
      global $DB, $LANG, $CFG_GLPI;

      // Default values of parameters
      $params["knowbaseitemcategories_id"] = "0";
      $params["target"]                    = $_SERVER['PHP_SELF'];

      if (is_array($options) && count($options)) {
         foreach ($options as $key => $val) {
            $params[$key]=$val;
         }
      }

      $faq_limit = '';

      if ($faq) {
         if (!$CFG_GLPI["use_public_faq"] && !haveRight("faq","r")) {
            return false;
         }

         if (getLoginUserID()) {
            $faq_limit = getEntitiesRestrictRequest("AND", "glpi_knowbaseitemcategories", "", "",
                                                    true);
         } else {
            // Anonymous access
            if (isMultiEntitiesMode()) {
               $faq_limit = " AND (`glpi_knowbaseitemcategories`.`entities_id` = '0'
                                   AND `glpi_knowbaseitemcategories`.`is_recursive` = '1')";
            }
         }

         // Get All FAQ categories
         if (!isset($_SESSION['glpi_faqcategories'])) {

            $_SESSION['glpi_faqcategories'] = '(0)';
            $tmp = array();
            $query = "SELECT DISTINCT `glpi_knowbaseitems`.`knowbaseitemcategories_id`
                      FROM `glpi_knowbaseitems`
                      LEFT JOIN `glpi_knowbaseitemcategories`
                           ON (`glpi_knowbaseitemcategories`.`id`
                                 = `glpi_knowbaseitems`.`knowbaseitemcategories_id`)
                      WHERE `glpi_knowbaseitems`.`is_faq` = '1'
                            $faq_limit";

            if ($result=$DB->query($query)) {
               if ($DB->numrows($result)) {
                  while ($data=$DB->fetch_array($result)) {
                     if (!in_array($data['knowbaseitemcategories_id'], $tmp)) {
                        $tmp[] = $data['knowbaseitemcategories_id'];
                        $tmp   = array_merge($tmp,
                                             getAncestorsOf('glpi_knowbaseitemcategories',
                                                            $data['knowbaseitemcategories_id']));
                     }
                  }
               }
               if (count($tmp)) {
                  $_SESSION['glpi_faqcategories'] = "('".implode("','",$tmp)."')";
               }
            }
         }
         $query = "SELECT DISTINCT `glpi_knowbaseitemcategories`.*
                   FROM `glpi_knowbaseitemcategories`
                   WHERE `id` IN ".$_SESSION['glpi_faqcategories']."
                         AND (`glpi_knowbaseitemcategories`.`knowbaseitemcategories_id`
                                 = '".$params["knowbaseitemcategories_id"]."')
                         $faq_limit
                   ORDER BY `name` ASC";

      } else {
         if (!haveRight("knowbase", "r")) {
            return false;
         }
         $faq_limit = getEntitiesRestrictRequest("AND", "glpi_knowbaseitemcategories", "entities_id",
                                                 $_SESSION['glpiactiveentities'], true);

         $query = "SELECT *
                   FROM `glpi_knowbaseitemcategories`
                   WHERE `glpi_knowbaseitemcategories`.`knowbaseitemcategories_id`
                              = '".$params["knowbaseitemcategories_id"]."'
                         $faq_limit
                   ORDER BY `name` ASC";
      }

      // Show category
      if ($result=$DB->query($query)) {
         echo "<table class='tab_cadre_central'>";
         echo "<tr><td colspan='3'><a href='".$params['target']."'>";
         echo "<img alt='' src='".$CFG_GLPI["root_doc"]."/pics/folder-open.png' class='bottom'></a>";

         // Display Category
         if ($params["knowbaseitemcategories_id"]!=0) {
            $tmpID     = $params["knowbaseitemcategories_id"];
            $todisplay = "";

            while ($tmpID!=0) {
               $query2 = "SELECT *
                          FROM `glpi_knowbaseitemcategories`
                          WHERE `id` = '$tmpID'
                                $faq_limit";
               $result2 = $DB->query($query2);

               if ($DB->numrows($result2)==1) {
                  $data  = $DB->fetch_assoc($result2);
                  $tmpID = $data["knowbaseitemcategories_id"];
                  $todisplay = "<a href='".$params['target']."?knowbaseitemcategories_id=".
                                 $data["id"]."'>".$data["name"]."</a>".(empty($todisplay)?"":" > ").
                                 $todisplay;
               } else {
                  $tmpID = 0;
               }
            }
            echo " > ".$todisplay;
         }

         if ($DB->numrows($result)>0) {
            $i = 0;
            while ($row=$DB->fetch_array($result)) {
               // on affiche les résultats sur trois colonnes
               if ($i%3==0) {
                  echo "<tr>";
               }
               $ID = $row["id"];
               echo "<td class='tdkb_result'>";
               echo "<img alt='' src='".$CFG_GLPI["root_doc"]."/pics/folder.png' hspace='5'>";
               echo "<strong><a href='".$params['target']."?knowbaseitemcategories_id=".$row["id"]."'>".
                              $row["name"]."</a></strong>";
               echo "<div class='kb_resume'>".resume_text($row['comment'],60)."</div>";

               if ($i%3==2) {
                  echo "</tr>";
               }
               $i++;
            }
         }
         echo "<tr><td colspan='3'>&nbsp;</td></tr></table><br>";
      }
   }

}

?>