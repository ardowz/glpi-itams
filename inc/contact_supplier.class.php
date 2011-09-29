<?php
/*
 * @version $Id: contact_supplier.class.php 14684 2011-06-11 06:32:40Z remi $
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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class Contact_Supplier extends CommonDBRelation{

   // From CommonDBRelation
   public $itemtype_1 = 'Contact';
   public $items_id_1 = 'contacts_id';

   public $itemtype_2 = 'Supplier';
   public $items_id_2 = 'suppliers_id';


   static function getTypeName() {
      global $LANG;

      return $LANG['setup'][620].' '.$LANG['common'][92].'-'.$LANG['financial'][26];
   }


   /**
    * Get search function for the class
    *
    * @return array of search option
    */
   function getSearchOptions() {
      return parent::getSearchOptions();
   }

}
?>
