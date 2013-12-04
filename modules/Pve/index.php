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

global $nuked, $language, $user, $theme;
translate("modules/Pve/lang/". $language .".lang.php");
include("modules/Pve/config.php");

opentable();

$visiteur = !$user ? 0 : $user[1];
$ModName = basename(dirname(__FILE__));
$level_access = nivo_mod($ModName);

if ($visiteur >= $level_access && $level_access > -1) {

        function index() {

                global $nuked;

                echo '<br /><div class="g2_title">'. _WOW_PVE .'</div><br />';

                $test = 0;
                echo "<table style=\"margin:auto;width:98%;\" cellspacing=\"5\" cellpadding=\"5\">";
                $sql_b = mysql_query("SELECT id, nom, raid, img FROM ". WOW_PVE_CAT_TABLE ." WHERE status = '1' ORDER BY CAST(ordre as signed integer) DESC");
                while ($RR = mysql_fetch_array($sql_b, MYSQL_ASSOC)) {
                        $test++;

                        if ($test == 1) { echo "<tr>"; }

                        echo "<td valign=\"top\" class=\"g2_cadre_table g2_gradient\"><img style=\"vertical-align: bottom;\" src=\"modules/Pve/images/go.png\" alt=\"\" />&nbsp;<a href=\"index.php?file=Pve&amp;op=voir_raid&amp;raid_id=". $RR['id'] ."\" title=\"Voir le raid : " . stripslashes($RR['nom']) . "\">". stripslashes($RR['nom']) ."</a><br /><br />
                        <div style=\"text-align:center;\"><a href=\"index.php?file=Pve&amp;op=voir_raid&amp;raid_id=". $RR['id'] ."\" title=\"Voir le raid : ". stripslashes($RR['nom']) ."\"><img src=\"modules/Pve/images/raid/". $RR['img'] ."\" width=\"200\" alt=\"\" /></a></div>";
                        echo "</td>";
                        if ($test == 3) {
                                $test = 0;
                                echo "</tr>";
                        }

                }
                if ($test == 1) echo "<td style=\"width:33%;\"></td><td style=\"width:33%;\"></td></tr>";
                if ($test == 2) echo "<td style=\"width:33%;\"></td></tr>";
                echo "</table><br /><br />";

        }

        function voir_raid($raid_id) {

                global $nuked;

                echo '<br /><div class="g2_title">'. _WOW_PVE .'</div><br />'
                . '<div class="centeredmenu"><div class="nav l_g"><ul>'
                . "<li><a href=\"index.php?file=Pve\"><span>Liste des raids</span></a></li>"
                . "<li><a href=\"index.php?file=Pve&op=voir_raid&raid_id=". $raid_id ."#r_". $raid_id ."\"><span>Voir la carte</span></a></li></ul></div></div><div class=\"clear\"></div><br /><br />"
                . '<div style="text-align:center;"><span style="color:#d73920;">Invaincu</span> | <span style="color:#063913;">Vaincu (Normal)</span> | <span style="color:#0A3D6B;">Vaincu (Héroïque)</span></div><br />';

                $sql = mysql_query("SELECT nom, carte FROM ". WOW_PVE_CAT_TABLE ." WHERE id = '". $raid_id ."' ");
                list($nom_b, $carte) = mysql_fetch_array($sql);
                $nom_b = stripslashes($nom_b);
                $sql_b = mysql_query("SELECT id, nom, img, description, undefeated, defeated, defeated_hero, ordre FROM ". WOW_PVE_TABLE ." WHERE raid = '". $raid_id ."' ORDER BY raid, CAST(ordre as signed integer) ASC ");
                while ($RR = mysql_fetch_array($sql_b, MYSQL_ASSOC)) {

                        echo "<table class=\"table_90_border\" cellspacing=\"0\" cellpadding=\"0\">";

                        if ($RR['undefeated'] == 'on') {
                                 $ud = ' style=\'background: #C7371E url(modules/Pve/images/bg_red.png) repeat-x top;background: -moz-linear-gradient(top,#C7371E,#EFA598 50%,#C7371E);\'';
                                 $sc = '<span style="color:#d73920;">';
                        } else if ($RR['defeated'] == 'on' && $RR['defeated_hero'] == 'off') {
                                 $ud = ' style=\'background: #037B03 url(modules/Pve/images/bg_green.png) repeat-x top;background: -moz-linear-gradient(top,#037B03,#9AD3B1 50%,#037B03);\'';
                                 $sc = '<span style="color:#063913;">';
                        } else if ($RR['defeated_hero'] == 'on' && $RR['defeated'] == 'on') {
                                 $ud = ' style=\'background: #0667BE url(modules/Pve/images/bg_bleu.png) repeat-x top;background: -moz-linear-gradient(top,#0667BE,#9ACDFC 50%,#0667BE);\'';
                                 $sc = '<span style="color:#0A3D6B;">';
                        } else {
                                 $ud = '';
                                 $sc = '<span>';
                        }

                        $nom = stripslashes($RR['nom']);
                        if ($RR['description']!= '') { $description = stripslashes(BBcode(icon($RR['description']))); } else { $description = ''; }

                        if ($RR['img'] == '') { $a_i = '<img src="modules/Pve/images/no_img.png" alt="" />'; } else { $a_i = '<img src="modules/Pve/images/boss/'. $RR['img'] .'" alt="" />'; }

                        echo "<tr>"
                        . '<td class="td_1_t_l">&nbsp;'. $nom_b .' : '.$sc . $nom .'</span></td></tr>'
                        . "<tr><td ". $ud ." class=\"td_2_b_t_t_c tr_2\">". $a_i ."</td></tr>";
                        if($description != '') {
                        	echo "<tr class=\"tr_1\"><td class=\"td_2_b_t_t_l\">". $description ."</td></tr>";
                        }
                        echo '</table><br />';

                }
                echo '<a name="r_'. $raid_id .'" id="r_'. $raid_id .'"></a>';
                if ($carte != "") {
         		$breadcrumbs_code= '';
         		$displayfolders = explode('|', $carte);
          		for ($i=0; $i <= sizeof($displayfolders); $i++) {
          			if (isset($displayfolders[$i]) && $displayfolders[$i] != null) {
          				$breadcrumbs_code .= '<img src="modules/Pve/images/carte/'. $displayfolders[$i] .'" alt="" /><br />';
          			}
          		}
          		echo "<div style=\"text-align:center;\">". $breadcrumbs_code ."</div>";
          	}
                echo "<br />";

        }

        switch($_REQUEST['op']) {
                case"index":
                index();
                break;

                case"voir_raid":
                voir_raid($_REQUEST['raid_id']);
                break;

                default:
                index();
                break;
        }

} else if ($level_access == -1) {
    	echo "<br /><br /><div style=\"text-align: center;\">" . _MODULEOFF . "<br /><br /><a href=\"javascript:history.back()\"><b>" . _BACK . "</b></a><br /><br /></div>";
} else if ($level_access == 1 && $visiteur == 0) {
    	echo "<br /><br /><div style=\"text-align: center;\">" . _USERENTRANCE . "<br /><br /><b><a href=\"index.php?file=User&amp;op=login_screen\">" . _LOGINUSER . "</a> | <a href=\"index.php?file=User&amp;op=reg_screen\">" . _REGISTERUSER . "</a></b><br /><br /></div>";
} else {
    	echo "<br /><br /><div style=\"text-align: center;\">" . _NOENTRANCE . "<br /><br /><a href=\"javascript:history.back()\"><b>" . _BACK . "</b></a><br /><br /></div>";
}

closetable();

?>