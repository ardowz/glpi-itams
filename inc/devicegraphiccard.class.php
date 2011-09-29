<?php
/*
 * @version $Id: devicegraphiccard.class.php 14684 2011-06-11 06:32:40Z remi $
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

/// Class DeviceGraphicCard
class DeviceGraphicCard extends CommonDevice {

   static function getTypeName() {
      global $LANG;

      return $LANG['devices'][2];
   }


   static function getSpecifityLabel() {
      global $LANG;

      return array('specificity' => $LANG['devices'][6]);
   }


   function getAdditionalFields() {
      global $LANG;

      return array_merge(parent::getAdditionalFields(),
                         array(array('name'  => 'specif_default',
                                     'label' => $LANG['devices'][6]." ".$LANG['devices'][24],
                                     'type'  => 'text',
                                     'unit'  => $LANG['common'][82]),
                               array('name'  => 'interfacetypes_id',
                                     'label' => $LANG['common'][65],
                                     'type'  => 'dropdownValue')));
   }


   function getSearchOptions() {
      global $LANG;

      $tab = parent::getSearchOptions();

      $tab[12]['table']    = $this->getTable();
      $tab[12]['field']    = 'specif_default';
      $tab[12]['name']     = $LANG['devices'][6]." ".$LANG['devices'][24];
      $tab[12]['datatype'] = 'text';

      $tab[14]['table'] = 'glpi_interfacetypes';
      $tab[14]['field'] = 'name';
      $tab[14]['name']  = $LANG['common'][65];

      return $tab;
   }


   /**
    * return the display data for a specific device
    *
    * @return array
   **/
   function getFormData() {
      global $LANG;

      $data['label'] = $data['value'] = array();
      if ($this->fields["interfacetypes_id"]) {
         $data['label'][] = $LANG['common'][65];
         $data['value'][] = Dropdown::getDropdownName("glpi_interfacetypes",
                                                      $this->fields["interfacetypes_id"]);
      }
      // Specificity
      $data['label'][] = $LANG['devices'][6];
      $data['size']    = 10;

      return $data;
   }

}

?>