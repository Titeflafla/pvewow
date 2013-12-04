<?php
// -------------------------------------------------------------------------//
// Nuked-KlaN - PHP Portal                                                  //
// http://www.nuked-klan.org                                                //
// -------------------------------------------------------------------------//
// This program is free software. you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License.           //
// -------------------------------------------------------------------------//
if (!defined("INDEX_CHECK")) die ("<div style=\"text-align: center;\">You cannot open this page directly</div>");

global $language, $nuked, $theme, $bgcolor2, $bgcolor1;
translate("modules/Pve/lang/". $language .".lang.php");
include("modules/Pve/config.php");

echo '<script type="text/javascript" src="modules/Pve/pve.js"></script>';

$sql = mysql_query("SELECT id, nom, raid, img FROM ". WOW_PVE_CAT_TABLE ." WHERE block = '1'");
while ($RR = mysql_fetch_array($sql, MYSQL_ASSOC)) {
        echo '<div class="wowboss">'
	. '<div class="wboss-box d_b">'
	. '<h4 class="wboss-raidtitle">'
        . '<span class="r_'. $RR['raid'] .'">'. stripslashes($RR['nom']) .'</span></h4>
        <ul style="background-image:url(\'modules/Pve/images/raid/'. $RR['img'] .'\');">';
        $sql_b = mysql_query("SELECT nom, undefeated, defeated, defeated_hero FROM ". WOW_PVE_TABLE ." WHERE raid = '". $RR['id'] ."' ORDER BY CAST(ordre as signed integer) ASC");
        while ($R_R = mysql_fetch_array($sql_b, MYSQL_ASSOC)) {
                if ($R_R['undefeated'] == 'on') { $ud = 'undefeated'; } else { $ud = ''; }
                if ($R_R['defeated'] == 'on') { $dd = 'defeated'; } else { $dd = ''; }
                if ($R_R['defeated_hero'] == 'on') { $dh = '-hero'; } else { $dh = ''; }
                echo '<li class="wboss-'. $ud . $dd . $dh .'" style="color:#000000;">'. stripslashes(htmlentities($R_R['nom'])) .'</li>';
        }
        echo '</ul></div></div>';
}

echo '<div class="wboss-box-legend">'
. '<h4 class="wboss-legend">&nbsp;<img src="modules/Pve/images/go.png" alt="" />&nbsp;&nbsp;L&eacute;gende</h4>'
. '<ul>'
. '<li class="wboss-undefeated">Invaincu</li>'
. '<li class="wboss-defeated">Vaincu (Normal)</li>'
. '<li class="wboss-defeated-hero">Vaincu (H&eacute;ro&icirc;que)</li>'
. '</ul>'

. '</div>';


?>