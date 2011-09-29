<?php
/*
 * @version $Id: notificationtargetcartridge.class.php 14684 2011-06-11 06:32:40Z remi $
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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

// Class NotificationTarget
class NotificationTargetCartridge extends NotificationTarget {

   function getEvents() {
      global $LANG;

      return array('alert' => $LANG['mailing'][33]);
   }

   /**
    * Get all data needed for template processing
   **/
   function getDatasForTemplate($event, $options=array()) {
      global $LANG,$CFG_GLPI;

      $events = $this->getAllEvents();

      $this->datas['##cartridge.entity##'] = Dropdown::getDropdownName('glpi_entities',
                                                                       $options['entities_id']);
      $this->datas['##cartridge.action##'] = $events[$event];

      foreach ($options['cartridges'] as $id => $cartridge) {
         $tmp = array();
         $tmp['##cartridge.item##']      = $cartridge['cartname'];
         $tmp['##cartridge.reference##'] = $cartridge['cartref'];
         $tmp['##cartridge.remaining##'] = cartridge::getUnusedNumber($id);
         $tmp['##cartridge.url##']       = urldecode($CFG_GLPI["url_base"].
                                                     "/index.php?redirect=cartridgeitem_".$id);
         $this->datas['cartridges'][] = $tmp;
      }

      $this->getTags();
      foreach ($this->tag_descriptions[NotificationTarget::TAG_LANGUAGE] as $tag => $values) {
         if (!isset($this->datas[$tag])) {
            $this->datas[$tag] = $values['label'];
         }
      }
   }


   function getTags() {
      global $LANG;

      $tags = array('cartridge.action'    => $LANG['mailing'][119],
                    'cartridge.reference' => $LANG['consumables'][2],
                    'cartridge.item'      => $LANG['financial'][104],
                    'cartridge.remaining' => $LANG['software'][20],
                    'cartridge.url'       => $LANG['common'][94],
                    'cartridge.entity'    => $LANG['entity'][0]);

      foreach ($tags as $tag => $label) {
         $this->addTagToList(array('tag'   => $tag,
                                   'label' => $label,
                                   'value' => true));
      }

      $this->addTagToList(array('tag'     => 'cartridges',
                                'label'   => $LANG['reports'][57],
                                'value'   => false,
                                'foreach' => true));

      asort($this->tag_descriptions);
   }

}
?>
