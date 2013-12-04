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

global $user, $language;
translate("modules/Pve/lang/". $language .".lang.php");
include("modules/Admin/design.php");
include("modules/Pve/config.php");

$visiteur = !$user ? 0 : $user[1];
$ModName = basename(dirname(__FILE__));
$level_admin = admin_mod($ModName);

if($_REQUEST['op'] != 'show_icone') {
	admintop();
}

if ($visiteur >= $level_admin && $level_admin > -1) {

        function main() {

                global $nuked, $language;

                echo "<script type=\"text/javascript\">\n"
                . "<!--\n"
                . "function del(nom, id){\n"
                . "if (confirm('". _DELTHIS ." '+nom+' ! ". _CONFIRM ."')){\n"
                . "document.location.href = 'index.php?file=Pve&page=admin&op=del_boss&id='+id;}\n"
                . "}\n"
                . "//-->\n"
                . "</script>\n"
                . "<div class=\"content-box\">\n"
	        . "<div class=\"content-box-header\"><h3>" . _ADMINPVE . "</h3>\n"
	        . "<div style=\"text-align:right;\"><a href=\"help/" . $language . "/Pve.php\" rel=\"modal\">\n"
	        . "<img style=\"border: 0;\" src=\"help/help.gif\" alt=\"\" title=\"" . _HELP . "\" /></a>\n"
	        . "</div></div>\n"
	        . "<div class=\"tab-content\" id=\"tab2\"><div style=\"text-align: center;\">"
                . "". _WOW_PVE_LIST_BOSS ."<b> | "
                . "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=add_boss\">". _WOW_PVE_ADD ."</a> | "
                . "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=list_raid\">". _WOW_PVE_LIST_RAID ."</a> | "
                . "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=add_raid\">". _WOW_PVE_ADD_RAID ."</a></b></div><br />\n"

                . '<form action="index.php?file=Pve&page=admin" method="post">'
                . "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">"
                . "<tr><td style=\"text-align:right;\"><select class=\"styled\" name=\"id_cat\"><option value=\"\">Tous</option>";
                select_raid('');
                echo "</select>&nbsp;<input class=\"connexion_input\" type=\"submit\" value=\"Ok\"></td></tr></table></form>";

                if ($_REQUEST['id_cat'] == '') {
                	$and = '';
                	$sql = mysql_query("SELECT id FROM ". WOW_PVE_TABLE);
                } else {
                	$and = "WHERE raid = '". $_REQUEST['id_cat'] ."'";
                	$sql = mysql_query("SELECT id FROM ". WOW_PVE_TABLE ." WHERE raid = '". $_REQUEST['id_cat'] ."' ");
                }

                $count = mysql_num_rows($sql);
                $nb_media_admin = 20;
                if (!$_REQUEST['p']) $_REQUEST['p'] = 1;
                $start = $_REQUEST['p'] * $nb_media_admin - $nb_media_admin;

                if ($count > $nb_media_admin) {
                        $url_page = 'index.php?file=Pve&amp;page=admin&amp;id_cat='. $_REQUEST['id_cat'] .'&amp;voir='. $_REQUEST['voir'];
                        number($count, $nb_media_admin, $url_page);
                } else echo '<br />';

                echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\">"
                . "<tr>"
                . "<td style=\"width: 25%;\">". _P_NOM ."</td>"
                . "<td style=\"width: 25%;\">". _P_B_R ."</td>"
                . "<td style=\"width: 10%;\">". _P_UNDEFEATED ."</td>"
                . "<td style=\"width: 12%;\">". _P_DEFEATED ."</td>"
                . "<td style=\"width: 12%;\">". _P_DEFEATED_HERO ."</td>"
                . "<td style=\"width: 8%;\">". _P_ORDRE ."</td>"
                . "<td style=\"width: 8%;\">". _P_ACTION ."</td></tr>";

                $sql_b = mysql_query("SELECT id, nom, img, undefeated, defeated, defeated_hero, ordre, raid FROM " . WOW_PVE_TABLE . " ". $and ." ORDER BY raid DESC, CAST(ordre as signed integer) ASC LIMIT " . $start . ", " . $nb_media_admin);
                while ($RR = mysql_fetch_array($sql_b, MYSQL_ASSOC)) {

                        $nom = stripslashes($RR['nom']);
                        if ($RR['undefeated'] == 'on') { $ud = '<a href="index.php?file=Pve&amp;page=admin&amp;op=change&amp;s=0&amp;f=0&amp;id='. $RR['id'] .'"><img src="modules/Pve/images/on.png" alt="" /></a>'; } else { $ud = '<a href="index.php?file=Pve&amp;page=admin&amp;op=change&amp;s=1&amp;f=0&amp;id='. $RR['id'] .'"><img src="modules/Pve/images/off.png" alt="" /></a>'; }
                        if ($RR['defeated'] == 'on') { $dd = '<a href="index.php?file=Pve&amp;page=admin&amp;op=change&amp;s=0&amp;f=1&amp;id='. $RR['id'] .'"><img src="modules/Pve/images/on.png" alt="" /></a>'; } else { $dd = '<a href="index.php?file=Pve&amp;page=admin&amp;op=change&amp;s=1&amp;f=1&amp;id='. $RR['id'] .'"><img src="modules/Pve/images/off.png" alt="" /></a>'; }
                        if ($RR['defeated_hero'] == 'on') { $dh = '<a href="index.php?file=Pve&amp;page=admin&amp;op=change&amp;s=0&amp;f=2&amp;id='. $RR['id'] .'"><img src="modules/Pve/images/on.png" alt="" /></a>'; } else { $dh = '<a href="index.php?file=Pve&amp;page=admin&amp;op=change&amp;s=1&amp;f=2&amp;id='. $RR['id'] .'"><img src="modules/Pve/images/off.png" alt="" /></a>'; }

                        if ($RR['img'] == '') { $a_i = 'modules/Pve/images/no_img.png'; } else { $a_i = 'modules/Pve/images/boss/'. $RR['img']; }

                        $sql_r = mysql_query("SELECT nom FROM ". WOW_PVE_CAT_TABLE ." WHERE id = '". $RR['raid'] ."' ");
                        list($nom_r) = mysql_fetch_array($sql_r);

                        echo "<tr>"
                        . "<td><a href=\"javascript:void(0);\" onmouseover=\"AffBulle('" . mysql_real_escape_string(stripslashes($nom)) . "', '". htmlentities('<img src="'.$a_i.'" alt="" />') ."', 200)\" onmouseout=\"HideBulle()\">". $nom ."</a></td>"
                        . "<td>". stripslashes($nom_r) ."</td>"
                        . "<td>". $ud ."</td>"
                        . "<td>". $dd ."</td>"
                        . "<td>". $dh ."</td>"
                        . "<td>"; if($RR['ordre'] != "0") echo "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=modif_position&amp;id=". $RR['id'] ."&amp;method=down\" title=\"". _MOVEDOWN ."\"><img style=\"vertical-align:middle;\" src=\"modules/Pve/images/down_alt.png\" alt=\"\" /></a>";
                        echo "&nbsp;". $RR['ordre'] ."&nbsp;<a href=\"index.php?file=Pve&amp;page=admin&amp;op=modif_position&amp;id=". $RR['id'] ."&amp;method=up\" title=\"". _MOVEUP ."\"><img style=\"vertical-align:middle;\" src=\"modules/Pve/images/up_alt.png\" alt=\"\" /></a></td>"
                        . "<td><a href=\"index.php?file=Pve&amp;page=admin&amp;op=edit_boss&amp;id=". $RR['id'] ."\" title=\"". _P_EDIT ."\"><img src=\"images/edit.gif\" alt=\"\" /></a>&nbsp;<a href=\"javascript:del('". $RR['nom'] ."','". $RR['id'] ."');\" title=\"Supprimer ce boss\"><img src=\"images/del.gif\" alt=\"\" /></a></td></tr>";
                }

                echo "</table>";

                if ($count > $nb_media_admin) {
                        $url_page = "index.php?file=Pve&amp;page=admin&amp;id_cat=". $_REQUEST['id_cat'] ."&amp;voir=". $_REQUEST['voir'];
                        number($count, $nb_media_admin, $url_page);
                } else echo '<br />';

                echo "<br /></div></div>\n";

        }

        function change($id, $s, $f) {

		if($s == 1) $txt_s = 'on';
		else $txt_s = 'off';

		if($f == 0) $txt_d = 'undefeated';
		elseif($f == 1) $txt_d = 'defeated';
		elseif($f == 2) $txt_d = 'defeated_hero';

                if($f == 1 || $f == 2) {                	$undefeated = mysql_query("UPDATE ". WOW_PVE_TABLE ." SET undefeated = 'off' WHERE id = '". $id ."'");
                	if($f == 2) $defeated = mysql_query("UPDATE ". WOW_PVE_TABLE ." SET defeated = 'on' WHERE id = '". $id ."'");                }

                if($f == 0 && $s == 1) {                	$defeated = mysql_query("UPDATE ". WOW_PVE_TABLE ." SET defeated = 'off' WHERE id = '". $id ."'");
                	$defeated_hero = mysql_query("UPDATE ". WOW_PVE_TABLE ." SET defeated_hero = 'off' WHERE id = '". $id ."'");                }

                $bdd = mysql_query("UPDATE ". WOW_PVE_TABLE ." SET ". $txt_d ." = '". $txt_s ."' WHERE id = '". $id ."'");
                redirect($_SERVER["HTTP_REFERER"],0);
        }

        function modif_position($id, $method) {

                global $nuked;

                $sqlq = mysql_query("SELECT nom, ordre FROM " . WOW_PVE_TABLE . " WHERE id='". $id ."'");
                list($titre, $position) = mysql_fetch_array($sqlq);
                if ($position <=0 && $method == "down") {
                        echo "<br /><br /><div class=\"notice notice-warn\">". _CATERRORPOS ."<span></span></div><br /><br />";
                        redirect("index.php?file=Pve&page=admin", 2);
                        exit();
                }
                if ($method == "down") $upd = mysql_query("UPDATE ". WOW_PVE_TABLE ." SET ordre = ordre - 1 WHERE id = '". $id ."'");
                else if ($method == "up") $upd = mysql_query("UPDATE ". WOW_PVE_TABLE ." SET ordre = ordre + 1 WHERE id = '". $id ."'");

                echo "<div class=\"notification success png_bg\"><div>" . _BOSSMODIF . "</div></div>\n";
                redirect($_SERVER["HTTP_REFERER"], 2);
        }

        function modif_p_r($id, $method) {

                global $nuked;

                $sqlq = mysql_query("SELECT nom, ordre FROM " . WOW_PVE_CAT_TABLE . " WHERE id='". $id ."'");
                list($titre, $position) = mysql_fetch_array($sqlq);
                if ($position <=0 && $method == "down") {
                        echo "<div class=\"notification error png_bg\"><div>". _CATERRORPOS ."</div></div>\n";
                        redirect("index.php?file=Pve&page=admin", 2);
                        exit();
                }
                if ($method == "down") $upd = mysql_query("UPDATE ". WOW_PVE_CAT_TABLE ." SET ordre = ordre - 1 WHERE id = '". $id ."'");
                else if ($method == "up") $upd = mysql_query("UPDATE ". WOW_PVE_CAT_TABLE ." SET ordre = ordre + 1 WHERE id = '". $id ."'");

                echo "<div class=\"notification success png_bg\"><div>". _RAIDMODIF ."</div></div>\n";
                redirect("index.php?file=Pve&page=admin&op=list_raid", 2);
        }

        function del_boss($id) {

                $del = mysql_query("DELETE FROM ". WOW_PVE_TABLE ." WHERE id = '" . $id . "'");
                echo "<div class=\"notification success png_bg\"><div>". _BOSSDEL ."</div></div>\n";
                redirect("index.php?file=Pve&page=admin", 2);
        }

        function add_boss() {

                global $nuked, $language;

                echo "<div class=\"content-box\">\n"
	        . "<div class=\"content-box-header\"><h3>" . _ADMINPVE . "</h3>\n"
	        . "<div style=\"text-align:right;\"><a href=\"help/" . $language . "/Pve.php\" rel=\"modal\">\n"
	        . "<img style=\"border: 0;\" src=\"help/help.gif\" alt=\"\" title=\"" . _HELP . "\" /></a>\n"
	        . "</div></div>\n"
	        . "<div class=\"tab-content\" id=\"tab2\"><div style=\"text-align: center;\"><b>"
                . "<a href=\"index.php?file=Pve&amp;page=admin\">". _WOW_PVE_LIST_BOSS ."</a> | "
                . "</b>". _WOW_PVE_ADD ."<b> | "
                . "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=list_raid\">". _WOW_PVE_LIST_RAID ."</a> | "
                . "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=add_raid\">". _WOW_PVE_ADD_RAID ."</a></b></div><br />\n"

                . "<form method=\"post\" action=\"index.php?file=Pve&amp;page=admin&amp;op=add_boss_ok\">"
                . "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n"
                . "<tr><td>" . _P_B_B . " : <input type=\"text\" name=\"nom\" class=\"login_input_big\" /></td></tr>"
                . "<tr><td>" . _P_B_N . " : <select class=\"styled\" name=\"raid\">";
                select_raid('');
                echo "</select></td></tr>"
                . "<tr><td>" . _P_IMG . " : <input type=\"text\" id=\"img\" name=\"img\" class=\"login_input_big\" value=\"". $img ."\" />&nbsp;<a href=\"javascript:void(0);\" onclick=\"javascript:window.open('index.php?file=Pve&page=admin&nuked_nude=admin&op=show_icone&v_dir=2','Icone','toolbar=0,location=0,directories=0,status=0,scrollbars=1,resizable=0,copyhistory=0,menuBar=0,width=800,height=600,top=30,left=0');return(false)\">". _SELECTION ."</a></td></tr>"
                . "<tr><td>" . _P_UNDEFEATED . " : <select class=\"styled\" size=\"1\" name=\"undefeated\"><option value=\"on\">Oui</option><option value=\"off\">Non</option></select></td></tr>"
                . "<tr><td>" . _P_DEFEATED . " : <select class=\"styled\" size=\"1\" name=\"defeated\"><option value=\"off\">Non</option><option value=\"on\">Oui</option></select></td></tr>"
                . "<tr><td>" . _P_DEFEATED_HERO . " : <select class=\"styled\" size=\"1\" name=\"defeated_hero\"><option value=\"off\">Non</option><option value=\"on\">Oui</option></select></td></tr>"
                . "<tr><td>". _P_ORDRE ." : <input type=\"text\" name=\"ordre\" class=\"login_input_petit\" /></td></tr>"
                . "<tr><td><b>" . _P_DESCRIPTION . " :</b></td></tr>\n"
                . "<tr><td><textarea class=\"editor\" id=\"img_texte\" name=\"description\" cols=\"66\" rows=\"10\" onselect=\"storeCaret('img_texte');\" onclick=\"storeCaret('img_texte');\" onkeyup=\"storeCaret('img_texte');\"></textarea></td></tr>\n"
                . "<tr><td><input type=\"submit\" name=\"send\" value=\"Ajouter\" /></td></tr></table>"
                . "</form><br /></div></div>\n";
        }

        function add_boss_ok($nom, $img, $description, $undefeated, $defeated, $defeated_hero, $ordre, $raid) {

                if (!is_numeric($ordre)) { $ordre = '0'; } else { $ordre = $ordre; }
                $nom = mysql_real_escape_string(stripslashes($nom));
                $img = mysql_real_escape_string(stripslashes($img));
                $description = html_entity_decode($description);
	        $description = mysql_real_escape_string(stripslashes($description));
                $sql2 = mysql_query("INSERT INTO ". WOW_PVE_TABLE ." ( `id` , `nom` , `img` , `description` , `undefeated` , `defeated` , `defeated_hero`, `ordre`, `raid` ) VALUES ('', '". $nom ."', '". $img ."', '". $description ."', '". $undefeated ."', '". $defeated ."', '". $defeated_hero ."', '". $ordre ."', '". $raid ."')");
                echo "<div class=\"notification success png_bg\"><div>". _BOSSADD ."</div></div>\n";
                redirect("index.php?file=Pve&page=admin",2);
        }

        function edit_boss($id) {

                global $nuked, $language, $bgcolor4;

                $sql = mysql_query("SELECT id, nom, img, description, undefeated, defeated, defeated_hero, ordre, raid FROM ". WOW_PVE_TABLE ." WHERE id = '". $id ."'");
                list($bid, $nom, $img, $description, $undefeated, $defeated, $defeated_hero, $ordre, $raid) = mysql_fetch_array($sql);

                $nom = stripslashes($nom);
                $img = stripslashes($img);
                $description = stripslashes($description);

                echo "<div class=\"content-box\">\n"
	        . "<div class=\"content-box-header\"><h3>" . _ADMINPVE . "</h3>\n"
	        . "<div style=\"text-align:right;\"><a href=\"help/" . $language . "/Pve.php\" rel=\"modal\">\n"
	        . "<img style=\"border: 0;\" src=\"help/help.gif\" alt=\"\" title=\"" . _HELP . "\" /></a>\n"
	        . "</div></div>\n"
	        . "<div class=\"tab-content\" id=\"tab2\"><div style=\"text-align: center;\"><b>"
                . "<a href=\"index.php?file=Pve&amp;page=admin\">". _WOW_PVE_LIST_BOSS ."</a> | "
                . "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=add_boss\">". _WOW_PVE_ADD ."</a> | "
                . "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=list_raid\">". _WOW_PVE_LIST_RAID ."</a> | "
                . "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=add_raid\">". _WOW_PVE_ADD_RAID ."</a></b></div><br />\n"

                . "<form method=\"post\" action=\"index.php?file=Pve&page=admin&amp;op=edit_ok\">"
                . "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n"
                . "<tr>" . _P_B_B . " : <input type=\"text\" name=\"nom\" class=\"login_input_big\" value=\"". $nom ."\" /></td></tr>"
                . "<tr><td>" . _P_B_N . " : <select class=\"styled\" name=\"raid\">";
                select_raid($raid);
                echo "</select></td></tr>"
                . "<tr><td>" . _P_IMG . " : <input type=\"text\" id=\"img\" name=\"img\" class=\"login_input_big\" value=\"". $img ."\" />&nbsp;<a href=\"javascript:void(0);\" onclick=\"javascript:window.open('index.php?file=Pve&page=admin&popup=pve&op=show_icone&v_dir=2','Icone','toolbar=0,location=0,directories=0,status=0,scrollbars=1,resizable=0,copyhistory=0,menuBar=0,width=800,height=600,top=30,left=0');return(false)\">". _SELECTION ."</a></td></tr>"
                . "<tr><td>" . _P_UNDEFEATED . " : <select class=\"styled\" size=\"1\" name=\"undefeated\"><option value=\"on\""; if ($undefeated == "on") { echo "selected=\"selected\""; } else { echo ""; }  echo ">Oui</option><option value=\"off\""; if ($undefeated == "off") { echo "selected=\"selected\""; } else { echo ""; }  echo ">Non</option></select></td></tr>"
                . "<tr><td>" . _P_DEFEATED . " : <select class=\"styled\" size=\"1\" name=\"defeated\"><option value=\"on\""; if ($defeated == "on") { echo "selected=\"selected\""; } else { echo ""; }  echo ">Oui</option><option value=\"off\""; if ($defeated == "off") { echo "selected=\"selected\""; } else { echo ""; }  echo ">Non</option></select></td></tr>"
                . "<tr><td>" . _P_DEFEATED_HERO . " : <select class=\"styled\" size=\"1\" name=\"defeated_hero\"><option value=\"on\""; if ($defeated_hero == "on") { echo "selected=\"selected\""; } else { echo ""; }  echo ">Oui</option><option value=\"off\""; if ($defeated_hero == "off") { echo "selected=\"selected\""; } else { echo ""; }  echo ">Non</option></select></td></tr>"
                . "<tr><td>" . _P_ORDRE . " : <input type=\"text\" name=\"ordre\" class=\"login_input_petit\" value=\"". $ordre ."\" /></td></tr>"
                . "<tr><td><b>" . _P_DESCRIPTION . " :</b></td></tr>\n"
                . "<tr><td><textarea class=\"editor\" id=\"img_texte\" name=\"description\" cols=\"66\" rows=\"10\" onselect=\"storeCaret('img_texte');\" onclick=\"storeCaret('img_texte');\" onkeyup=\"storeCaret('img_texte');\">". $description ."</textarea></td></tr>\n"
                . "<tr><td><input type=\"submit\" name=\"send\" value=\"Modifier\" /><input type=\"hidden\" name=\"id\" value=\"" . $id . "\" /><input type=\"hidden\" name=\"referer\" value=\"" . $_SERVER["HTTP_REFERER"] . "\" /></td></tr></table>\n"
                . "</form><br /></div></div>\n";
        }

        function edit_ok($id, $nom, $img, $description, $undefeated, $defeated, $defeated_hero, $ordre, $raid, $referer) {

                if (!is_numeric($ordre)) { $ordre = '0'; } else { $ordre = $ordre; }
                $nom = mysql_real_escape_string(stripslashes($nom));
                $img = mysql_real_escape_string(stripslashes($img));
                $description = html_entity_decode($description);
	        $description = mysql_real_escape_string(stripslashes($description));
                $sql = mysql_query("UPDATE ". WOW_PVE_TABLE ." SET nom = '" . $nom . "', img = '" . $img . "', description = '" . $description . "', undefeated = '" . $undefeated . "', defeated = '" . $defeated . "', defeated_hero = '" . $defeated_hero . "', ordre = '" . $ordre . "', raid = '" . $raid . "' WHERE id = '" . $id . "'");
                echo "<div class=\"notification success png_bg\"><div>". _BOSSMODIF ."</div></div>\n";
                redirect($referer,2);
        }

        function select_raid($raid) {
                global $nuked;

                $sql = mysql_query("SELECT id, nom FROM ". WOW_PVE_CAT_TABLE ." ORDER BY nom");
                while (list($id, $nom) = mysql_fetch_array($sql)) {
                        if ($raid == $id) $checked = "selected=\"selected\"";
                        else $checked = "";
                        echo "<option value=\"". $id ."\" ". $checked .">". stripslashes($nom) ."</option>";
                }
        }

        function list_raid() {

                global $nuked, $language;

                $sql = mysql_query("SELECT id FROM ". WOW_PVE_CAT_TABLE);
                $count = mysql_num_rows($sql);
                $nb_pve_admin = 15;

                if (!$_REQUEST['p']) $_REQUEST['p'] = 1;
                $start = $_REQUEST['p'] * $nb_pve_admin - $nb_pve_admin;

                echo "<script type=\"text/javascript\">\n"
                . "<!--\n"
                . "function del(nom, id){\n"
                . "if (confirm('" . _DELTHIS . " '+nom+' ! " . _CONFIRM . "')){\n"
                . "document.location.href = 'index.php?file=Pve&page=admin&op=del_raid&id='+id;}\n"
                . "}\n"
                . "//-->\n"
                . "</script>\n"
                . "<div class=\"content-box\">\n"
	        . "<div class=\"content-box-header\"><h3>" . _ADMINPVE . "</h3>\n"
	        . "<div style=\"text-align:right;\"><a href=\"help/" . $language . "/Pve.php\" rel=\"modal\">\n"
	        . "<img style=\"border: 0;\" src=\"help/help.gif\" alt=\"\" title=\"" . _HELP . "\" /></a>\n"
	        . "</div></div>\n"
	        . "<div class=\"tab-content\" id=\"tab2\"><div style=\"text-align: center;\"><b>"
                . "<a href=\"index.php?file=Pve&amp;page=admin\">". _WOW_PVE_LIST_BOSS ."</a> | "
                . "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=add_boss\">". _WOW_PVE_ADD ."</a> | "
                . "</b>". _WOW_PVE_LIST_RAID ."<b> | "
                . "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=add_raid\">". _WOW_PVE_ADD_RAID ."</a></b></div><br />\n";

                if ($count > $nb_pve_admin) number($count, $nb_pve_admin, "index.php?file=Pve&page=admin&op=list_raid");
                else echo '<br />';

                echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n"
                . "<tr style=\"background: " . $bgcolor1 . ";\">"
                . "<td style=\"width: 50%;\">" . _P_NOM . "</td>"
                . "<td style=\"width: 10%;\">" . _P_B_R . "</td>"
                . "<td style=\"width: 10%;\">Block</td>"
                . "<td style=\"width: 15%;\">" . _P_ORDRE . "</td>"
                . "<td style=\"width: 15%;\">" . _P_ACTION . "</td></tr>";

                $sql_b = mysql_query("SELECT id, nom, raid, img, block, ordre, status FROM ". WOW_PVE_CAT_TABLE ." ORDER BY CAST(ordre as signed integer) DESC LIMIT " . $start . ", " . $nb_pve_admin);
                while ($RR = mysql_fetch_array($sql_b, MYSQL_ASSOC)) {

                        $nom = stripslashes($RR['nom']);

                        if ($RR['raid'] == '10') { $ud = '<img src="modules/Pve/images/10.png" alt="" />'; } else { $ud = '<img src="modules/Pve/images/25.png" alt="" />'; }
                        if ($RR['img'] == '') { $a_i = 'modules/Pve/images/no_img.png'; } else { $a_i = 'modules/Pve/images/raid/'. $RR['img']; }

                        if ($RR['status'] == "0") $statut_r = "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=r_on&amp;id=". $RR['id'] ."\" title=\"". _RAIDACTIVE ."\"><img style=\"vertical-align:middle;\" src=\"modules/Pve/images/on.png\" alt=\"\" /></a>";
                        else $statut_r = "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=r_off&amp;id=". $RR['id'] ."\" title=\"". _RAIDDESACTIVE ."\"><img style=\"vertical-align:middle;\" src=\"modules/Pve/images/off.png\" alt=\"\" /></a>";

                        if ($RR['block'] == "0") $statut_b = "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=b_on&amp;id=". $RR['id'] ."\" title=\"". _ADDRAIDBLOCK ."\"><img src=\"modules/Pve/images/off.png\" alt=\"\" /></a>";
                        else $statut_b = "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=b_off&amp;id=". $RR['id'] ."\" title=\"". _REMOVERAIDBLOCK ."\"><img src=\"modules/Pve/images/on.png\" alt=\"\" /></a>";

                        echo "<tr>"
                        . "<td class=\"td_2_b_rt_t_l\"><a href=\"javascript:void(0);\" onmouseover=\"AffBulle('" . mysql_real_escape_string(stripslashes($nom)) . "', '". htmlentities('<img src="'.$a_i.'" alt="" />') ."', 200)\" onmouseout=\"HideBulle()\">". $nom ."</a></td>"
                        . "<td class=\"td_2_b_rt_t_c\">". $ud ."</td>"
                        . "<td class=\"td_2_b_rt_t_c\">". $statut_b ."</td>"
                        . "<td class=\"td_2_b_rt_t_c\">"; if($RR['ordre'] != "0") echo "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=modif_p_r&amp;id=". $RR['id'] ."&amp;method=down\" title=\"" . _MOVEDOWN . "\"><img style=\"vertical-align:middle;\" src=\"modules/Pve/images/down_alt.png\" alt=\"\" /></a>";
                        echo "&nbsp;". $RR['ordre'] ."&nbsp;<a href=\"index.php?file=Pve&amp;page=admin&amp;op=modif_p_r&amp;id=". $RR['id'] ."&amp;method=up\" title=\"" . _MOVEUP . "\"><img style=\"vertical-align:middle;\" src=\"modules/Pve/images/up_alt.png\" alt=\"\" /></a></td>"
                        . "<td class=\"td_2_b_t_t_c\">". $statut_r ."&nbsp;&nbsp;<a href=\"index.php?file=Pve&amp;page=admin&amp;op=edit_raid&amp;id=". $RR['id'] ."\" title=\"". _R_EDIT ."\"><img style=\"vertical-align:middle;\" src=\"images/edit.gif\" alt=\"\" /></a>&nbsp;&nbsp;<a href=\"javascript:del('". $RR['nom'] ."','". $RR['id'] ."');\" title=\"Supprimer ce raid\"><img style=\"vertical-align:middle;\" src=\"images/del.gif\" alt=\"\" /></a></td></tr>";
                }

                echo "</table><br />";

                if ($count > $nb_pve_admin) number($count, $nb_pve_admin, "index.php?file=Pve&page=admin&op=list_raid");
                else echo '<br />';

                echo "</div></div>\n";
        }

        function r_on($id) {

                $bdd = mysql_query("UPDATE ". WOW_PVE_CAT_TABLE ." SET status = '1' WHERE id = '". $id ."'");
                redirect($_SERVER["HTTP_REFERER"],0);
        }

        function r_off($id) {

                $bdd = mysql_query("UPDATE ". WOW_PVE_CAT_TABLE ." SET status = '0' WHERE id = '". $id ."'");
                redirect($_SERVER["HTTP_REFERER"],0);
        }

        function b_on($id) {

                $bdd = mysql_query("UPDATE ". WOW_PVE_CAT_TABLE ." SET block = '1' WHERE id = '". $id ."'");
                redirect($_SERVER["HTTP_REFERER"],0);
        }

        function b_off($id) {

                $bdd = mysql_query("UPDATE ". WOW_PVE_CAT_TABLE ." SET block = '0' WHERE id = '". $id ."'");
                redirect($_SERVER["HTTP_REFERER"],0);
        }

        function del_raid($id) {

                $del = mysql_query("DELETE FROM ". WOW_PVE_CAT_TABLE ." WHERE id = '" . $id . "'");
                echo "<div class=\"notification success png_bg\"><div>". _RAIDDEL ."</div></div>\n";
                redirect("index.php?file=Pve&page=admin&op=list_raid", 2);
        }

        function add_raid() {

                global $nuked, $language;

                echo "<div class=\"content-box\">\n"
	        . "<div class=\"content-box-header\"><h3>" . _ADMINPVE . "</h3>\n"
	        . "<div style=\"text-align:right;\"><a href=\"help/" . $language . "/Pve.php\" rel=\"modal\">\n"
	        . "<img style=\"border: 0;\" src=\"help/help.gif\" alt=\"\" title=\"" . _HELP . "\" /></a>\n"
	        . "</div></div>\n"
	        . "<div class=\"tab-content\" id=\"tab2\"><div style=\"text-align: center;\"><b>"
                . "<a href=\"index.php?file=Pve&amp;page=admin\">". _WOW_PVE_LIST_BOSS ."</a> | "
                . "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=add_boss\">". _WOW_PVE_ADD ."</a> | "
                . "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=list_raid\">". _WOW_PVE_LIST_RAID ."</a> | "
                . "</b>". _WOW_PVE_ADD_RAID ."</div><br />\n"

                . "<form method=\"post\" action=\"index.php?file=Pve&page=admin&amp;op=add_raid_ok\">"
                . "<div style=\"text-align:center;\"><img src=\"images/del.gif\" id=\"pve_img_th\" alt=\"\" /></div><br />"
                . "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n"
                . "<tr><td>" . _P_NOM . " : <input type=\"text\" name=\"nom\" class=\"login_input_big\" /></td></tr>"
                . "<tr><td>" . _P_B_R . " : <select class=\"styled\" size=\"1\" name=\"raid\"><option value=\"10\">10</option><option value=\"25\">25</option></select></td></tr>"
                . "<tr><td>Block : <select class=\"styled\" size=\"1\" name=\"block\"><option value=\"0\">Non</option><option value=\"1\">Oui</option></select></td></tr>"
                . "<tr><td>" . _P_IMG . " : <input type=\"text\" id=\"img\" name=\"img\" class=\"login_input_big\" value=\"\" />&nbsp;<a href=\"javascript:void(0);\" onclick=\"javascript:window.open('index.php?file=Pve&page=admin&popup=pve&op=show_icone&v_dir=1','Icone','toolbar=0,location=0,directories=0,status=0,scrollbars=1,resizable=0,copyhistory=0,menuBar=0,width=680,height=450,top=30,left=0');return(false)\">". _SELECTION ."</a></td></tr>"
                . "<tr><td>" . _P_ORDRE . " : <input type=\"text\" name=\"ordre\" class=\"login_input_petit\" /></td></tr>"
                . "<tr><td>Actif : <select class=\"styled\" size=\"1\" name=\"status\"><option value=\"1\">Oui</option><option value=\"0\">Non</option></select></td></tr>"
                . "<tr><td>Carte : <input id=\"carte\" type=\"text\" name=\"carte\" class=\"login_input_big\" />&nbsp;<a href=\"javascript:void(0);\" onclick=\"javascript:window.open('index.php?file=Pve&page=admin&popup=pve&op=show_icone&v_dir=3','Icone','toolbar=0,location=0,directories=0,status=0,scrollbars=1,resizable=0,copyhistory=0,menuBar=0,width=780,height=450,top=30,left=0');return(false)\">". _SELECTION ."</a></td></tr>"
                . "<tr><td><input type=\"submit\" name=\"send\" value=\"Ajouter\" /></td></tr></table>\n"
                . "</form><br /><div id=\"img_carte\" style=\"text-align:center;\"></div></div></div>\n";
        }

        function add_raid_ok($nom, $raid, $block, $img, $carte, $ordre, $status) {

                if (!is_numeric($ordre)) { $ordre = '0'; } else { $ordre = $ordre; }
                $nom = mysql_real_escape_string(stripslashes($nom));
                $img = mysql_real_escape_string(stripslashes($img));
                $carte = mysql_real_escape_string(stripslashes($carte));
                $sql2 = mysql_query("INSERT INTO ". WOW_PVE_CAT_TABLE ." ( `id` , `nom` , `raid` , `img` , `carte` , `block` , `ordre`, `status` ) VALUES ('', '". $nom ."', '". $raid ."', '". $img ."', '". $carte ."', '". $block ."', '". $ordre ."', '". $status ."')");
                echo "<div class=\"notification success png_bg\"><div>". _RAIDADD ."</div></div>\n";
                redirect("index.php?file=Pve&page=admin&op=list_raid",2);
        }

        function edit_raid($id) {

                global $nuked, $language;

                $sql = mysql_query("SELECT nom, raid, img, carte, block, ordre, status FROM ". WOW_PVE_CAT_TABLE ." WHERE id = '". $id ."'");
                list($nom, $raid, $img, $carte, $block, $ordre, $status) = mysql_fetch_array($sql);

                $nom = stripslashes($nom);
                $img = stripslashes($img);
                $carte = stripslashes($carte);

                echo "<div class=\"content-box\">\n"
	        . "<div class=\"content-box-header\"><h3>" . _ADMINPVE . "</h3>\n"
	        . "<div style=\"text-align:right;\"><a href=\"help/" . $language . "/Pve.php\" rel=\"modal\">\n"
	        . "<img style=\"border: 0;\" src=\"help/help.gif\" alt=\"\" title=\"" . _HELP . "\" /></a>\n"
	        . "</div></div>\n"
	        . "<div class=\"tab-content\" id=\"tab2\"><div style=\"text-align: center;\"><b>"
                . "<a href=\"index.php?file=Pve&amp;page=admin\">". _WOW_PVE_LIST_BOSS ."</a> | "
                . "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=add_boss\">". _WOW_PVE_ADD ."</a> | "
                . "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=list_raid\">". _WOW_PVE_LIST_RAID ."</a> | "
                . "<a href=\"index.php?file=Pve&amp;page=admin&amp;op=add_raid\">". _WOW_PVE_ADD_RAID ."</a></b></div><br />\n"

                . "<form method=\"post\" action=\"index.php?file=Pve&amp;page=admin&amp;op=edit_raid_ok\">"
                . '<div style="text-align:center;"><img src="modules/Pve/images/raid/'. $img .'" width="147" height="167" id="pve_img_th" alt="" /></div><br />'
                . "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n"
                . "<tr><td class=\"td_2_b_rt_t_r\">" . _P_NOM . " : <input type=\"text\" name=\"nom\" class=\"login_input_big\" value=\"". $nom ."\" /></td></tr>"
                . "<tr><td class=\"td_2_b_rt_t_r\">" . _P_B_R . " : <select class=\"styled\" size=\"1\" name=\"raid\"><option value=\"10\""; if ($raid == "10") { echo "selected=\"selected\""; } else { echo ""; }  echo ">10</option><option value=\"25\""; if ($raid == "25") { echo "selected=\"selected\""; } else { echo ""; }  echo ">25</option></select></td></tr>"
                . "<tr><td class=\"td_2_b_rt_t_r\">Block : <select class=\"styled\" size=\"1\" name=\"block\"><option value=\"1\""; if ($block == "1") { echo "selected=\"selected\""; } else { echo ""; }  echo ">Oui</option><option value=\"0\""; if ($block == "0") { echo "selected=\"selected\""; } else { echo ""; }  echo ">Non</option></select></td></tr>"
                . "<tr><td class=\"td_2_b_rt_t_r\">" . _P_IMG . " : <input type=\"text\" id=\"img\" name=\"img\" class=\"login_input_big\" value=\"". $img ."\" />&nbsp;<a href=\"javascript:void(0);\" onclick=\"javascript:window.open('index.php?file=Pve&page=admin&popup=pve&op=show_icone&v_dir=1','Icone','toolbar=0,location=0,directories=0,status=0,scrollbars=1,resizable=0,copyhistory=0,menuBar=0,width=680,height=450,top=30,left=0');return(false)\">". _SELECTION ."</a></td></tr>"
                . "<tr><td class=\"td_2_b_rt_t_r\">" . _P_ORDRE . " : <input type=\"text\" name=\"ordre\" class=\"login_input_petit\" value=\"". $ordre ."\" /></td></tr>"
                . "<tr><td class=\"td_2_b_rt_t_r\">Actif : <select class=\"styled\" size=\"1\" name=\"status\"><option value=\"1\""; if ($status == "1") { echo "selected=\"selected\""; } else { echo ""; }  echo ">Oui</option><option value=\"0\""; if ($status == "0") { echo "selected=\"selected\""; } else { echo ""; }  echo ">Non</option></select></td></tr>"
                . "<tr><td class=\"td_2_b_rt_t_r\">Carte : <input id=\"carte\" type=\"text\" name=\"carte\" class=\"login_input_big\" value=\"". $carte ."\" />&nbsp;<a href=\"javascript:void(0);\" onclick=\"javascript:window.open('index.php?file=Pve&page=admin&popup=pve&op=show_icone&v_dir=3','Icone','toolbar=0,location=0,directories=0,status=0,scrollbars=1,resizable=0,copyhistory=0,menuBar=0,width=780,height=450,top=30,left=0');return(false)\">". _SELECTION ."</a></td></tr>"
                . "<tr><td><input type=\"submit\" name=\"send\" value=\"Modifier\" /><input type=\"hidden\" name=\"id\" value=\"" . $id . "\" /></td></tr></table>\n"
                . "</form><br />";
                if ($carte != "") {
         		$breadcrumbs_code = '';
         		$displayfolders = explode('|', $carte);
          		for ($i=0; $i <= sizeof($displayfolders); $i++) {
          			if (isset($displayfolders[$i]) && $displayfolders[$i] != null) {
          				$breadcrumbs_code .= '<img src="modules/Pve/images/carte/'. $displayfolders[$i] .'" width="150" alt="" />&nbsp;';
          			}
          		}
          		echo "<div id=\"img_carte\" style=\"text-align:center;\">". $breadcrumbs_code ."</div><br />";
          	}
          	echo "<br /><div id=\"img_carte\" style=\"text-align:center;\"></div><br /></div></div>\n";
        }

        function edit_raid_ok($id, $nom, $raid, $block, $img, $carte, $ordre, $status) {

                if (!is_numeric($ordre)) { $ordre = '0'; } else { $ordre = $ordre; }
                $nom = mysql_real_escape_string(stripslashes($nom));
                $img = mysql_real_escape_string(stripslashes($img));
                $carte = mysql_real_escape_string(stripslashes($carte));
                $sql = mysql_query("UPDATE ". WOW_PVE_CAT_TABLE ." SET nom = '". $nom ."', raid = '". $raid ."', block = '". $block ."', img = '". $img ."', carte = '". $carte ."', ordre = '". $ordre ."', status = '". $status ."' WHERE id = '". $id ."'");
                echo "<div class=\"notification success png_bg\"><div>". _RAIDMODIF ."</div></div>\n";
                redirect("index.php?file=Pve&page=admin&op=list_raid",2);
        }

        function show_icone() {

                global $bgcolor2, $bgcolor3, $theme, $nuked;

                echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n"
                . "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\">"
                . "<head><title>" . _ICONLIST . "</title>"
                . "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />"
                . "<meta http-equiv=\"content-style-type\" content=\"text/css\" />"
                . "<link title=\"style\" type=\"text/css\" rel=\"stylesheet\" href=\"themes/". $theme ."/style.css\" />\n"
                . '<script type="text/javascript" src="modules/Pve/pve.js"></script>'
                . "</head><body>"
                . "<script type=\"text/javascript\">"
                . "<!--\n"
                . "function go_r(img) {\n"
                . "opener.document.getElementById('img').value=img;\n"
                . "opener.document.getElementById('pve_img_th').src='modules/Pve/images/raid/'+img;\n"
                . "opener.document.getElementById('pve_img_th').style.width='147px';\n"
                . "opener.document.getElementById('pve_img_th').style.height='167px';\n"
                . "}\n"
                . "function go_b(img) {\n"
                . "opener.document.getElementById('img').value=img;\n"
                . "}\n"
                . "function go_c(img) {\n"
                . "opener.document.getElementById('carte').value+=img+'|';\n"
                . "b = opener.document.createElement('img');\n"
                . "b.setAttribute('src', 'modules/Pve/images/carte/'+img);\n"
                . "b.setAttribute('width', '150');\n"
                . "b.setAttribute('height', '100');\n"
                . "opener.document.getElementById('img_carte').appendChild(b);\n"
                . "}\n"
                . "function del_img(titre, img){if(confirm('". _DEL ." '+titre+' ! ". _CONFIRM ."')){document.location.href = 'index.php?file=Pve&page=admin&nuked_nude=admin&op=show_icone&v_dir=". $_REQUEST['v_dir'] ."&del='+img+'';}}\n"
                . "//-->\n"
                . "</script>";

                $a_img  = array();
		$col = 2;
		$maxrow = 5;
                switch ($_REQUEST['v_dir']) {
                        case"1":
                        $imgdir = 'modules/Pve/images/raid/';
                        $jj = 'go_r';
                        break;
                        case"2":
                        $imgdir = 'modules/Pve/images/boss/';
                        $jj = 'go_b';
                        break;
                        case"3":
                        $imgdir = 'modules/Pve/images/carte/';
                        $jj = 'go_c';
                        break;
                }

                if (isset($_REQUEST['del']) && $_REQUEST['del'] != "") {
                        if (file_exists($imgdir . $_REQUEST['del'])) {
                                unlink($imgdir . $_REQUEST['del']);
                                echo '<div class="g2_succes">'. _THISIMAGE .' '. $_REQUEST['del'] .' '. _DELIMAGE .'</div><br />';
                                redirect('index.php?file=Pve&page=admin&nuked_nude=admin&op=show_icone&v_dir='. $_REQUEST['v_dir'], 1);
                        } else echo '<div class="g2_error">'. _NOFILE .'</div><br />';
                }

                if (isset($_FILES["fichier"]) && $_FILES["fichier"] != "") {
                        $fichier = basename($_FILES['fichier']['name']);
                        $taille_maxi = 1000000;
                        $taille = filesize($_FILES['fichier']['tmp_name']);
                        $extensions = array('.png', '.gif', '.jpg', '.jpeg');
                        $extension = strrchr($_FILES['fichier']['name'], '.');

                        if( preg_match('#[\x00-\x1F\x7F-\x9F/\\\\]#', $fichier) ) $erreur = '<div class="g2_error">'. _NOTVALID .'</div>';
                        if(!in_array($extension, $extensions)) $erreur = '<div class="g2_error">'. _TYPEUPLOAD .'</div>';
                        if($taille > $taille_maxi) $erreur = '<div class="g2_warn">'. _TOOBIG .'</div>';
                        if(!isset($erreur)) {
                                $ext = pathinfo($fichier, PATHINFO_EXTENSION);
                                $fichier = time() .".". $ext;
                                if(move_uploaded_file($_FILES['fichier']['tmp_name'], $imgdir . $fichier)) {
                                        echo '<div class="g2_succes">'. _UPLOADOK .'</div><br />';
                                        redirect('index.php?file=Pve&page=admin&nuked_nude=admin&op=show_icone&v_dir='. $_REQUEST['v_dir'], 1);
                                } else {
                                        echo '<div class="g2_error">'. _NOTUPLOAD .'</div><br />';
                                }
                        } else {
                                echo $erreur;
                        }
                }

                $dimg = opendir($imgdir);
		while($imgfile = readdir($dimg)) {
        		if( (substr($imgfile,-3)=="png") || (substr($imgfile,-3)=="jpg") || (substr($imgfile,-3)=="gif")  ) {
                		$a_img[count($a_img)] = $imgfile;
                		sort($a_img);
                		reset($a_img);
        		}
		}

                $totimg = count($a_img);
		$totxpage = $col*$maxrow;
		$totpages = $totimg%$totxpage == 0 ? (int)$totimg/$totxpage : (int)($totimg/$totxpage)+1;

                if($_REQUEST['p'] == "" || $_REQUEST['p'] == 1) {
        		$x = 0;
        		$_REQUEST['p'] = 1;
        		$r = 0;
        	} else {
        		$x = ($_REQUEST['p']-1)*($totxpage);
        		$r = 0;
                }

                echo '<div class="g2_info">'
                . '<form action="index.php?file=Pve&page=admin&amp;nuked_nude=admin&amp;op=show_icone&amp;v_dir='. $_REQUEST['v_dir'] .'&amp;p='. $_REQUEST['p'] .'" method="post" enctype="multipart/form-data">
                <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
                '. _SELECTPICTURE .'<br /><input type="file" name="fichier" />
                <input type="submit" value="Envoyer" />
                </form></div><br /><div style="margin:auto;width:90%;background:#FFF;">';

                if ($totimg > $totxpage) {
        		number($totimg, $totxpage, "index.php?file=Pve&page=admin&nuked_nude=admin&op=show_icone&v_dir=". $_REQUEST['v_dir']);
        	}

                echo "<table style=\"margin-left: auto;margin-right: auto;text-align: left;width:98%;background:#FFF;\" cellpadding=\"10\" cellspacing=\"10\">";
        	foreach($a_img as $key=>$val) {
	                if($x%$col == 0) print "<tr>";

	        	if(isset($a_img[$x])) {
	                        $size = getimagesize($imgdir . $a_img[$x]);
	                        if ($size[0] > 250) {
	                                $url_img = '<a href="javascript:void(0);" onclick="javascript:'. $jj .'(\''. $a_img[$x] .'\');self.close();"><img src="'. $imgdir . $a_img[$x] .'" width="300" alt="" /></a>';
	                        } else {
	                                $url_img = '<a href="javascript:void(0);" onclick="javascript:'. $jj .'(\''. $a_img[$x] .'\');self.close();"><img src="'. $imgdir . $a_img[$x] .'" alt="" /></a>';
	                        }

	                        echo '<td valign="top" style="text-align:center;">'. $url_img .''
	                        . "<br /><a href=\"javascript:del_img('". $a_img[$x] ."','". $a_img[$x] ."');\" title=\"Supprimer ". $a_img[$x] ."\"><img src=\"images/del.gif\" alt=\"\" /></a>"
	                        . '</td>';
	                }

		        if($x%$col == $col-1) {
		        	print "</tr>";
		        	$r++;
		        }
		        if($r == $maxrow) {
		        	break;
		        } else {
		        	$x++;
		        }
		}

                echo '</table>';

                if ($totimg > $totxpage) {
        		number($totimg, $totxpage, "index.php?file=Pve&page=admin&nuked_nude=admin&op=show_icone&v_dir=". $_REQUEST['v_dir']);
        	}

                echo '</div><br /><br /></body></html>';
        }

        switch ($_REQUEST['op']) {

                case "modif_position":
                modif_position($_REQUEST['id'], $_REQUEST['method']);
                break;

                case "change":
                change($_REQUEST['id'], $_REQUEST['s'], $_REQUEST['f']);
                break;

                case "modif_p_r":
                modif_p_r($_REQUEST['id'], $_REQUEST['method']);
                break;

                case "del_boss":
                del_boss($_REQUEST['id']);
                break;

                case "add_boss":
                add_boss();
                break;

                case "add_boss_ok":
                add_boss_ok($_REQUEST['nom'], $_REQUEST['img'], $_REQUEST['description'], $_REQUEST['undefeated'], $_REQUEST['defeated'], $_REQUEST['defeated_hero'], $_REQUEST['ordre'], $_REQUEST['raid']);
                break;

                case "edit_boss":
                edit_boss($_REQUEST['id']);
                break;

                case "edit_ok":
                edit_ok($_REQUEST['id'], $_REQUEST['nom'], $_REQUEST['img'], $_REQUEST['description'], $_REQUEST['undefeated'], $_REQUEST['defeated'], $_REQUEST['defeated_hero'], $_REQUEST['ordre'], $_REQUEST['raid'], $_REQUEST['referer']);
                break;

                case "add_raid":
                add_raid();
                break;

                case "add_raid_ok":
                add_raid_ok($_REQUEST['nom'], $_REQUEST['raid'], $_REQUEST['block'], $_REQUEST['img'], $_REQUEST['carte'], $_REQUEST['ordre'], $_REQUEST['status']);
                break;

                case "list_raid":
                list_raid();
                break;

                case "r_on":
                r_on($_REQUEST['id']);
                break;

                case "r_off":
                r_off($_REQUEST['id']);
                break;

                case "b_on":
                b_on($_REQUEST['id']);
                break;

                case "b_off":
                b_off($_REQUEST['id']);
                break;

                case "del_raid":
                del_raid($_REQUEST['id']);
                break;

                case "edit_raid":
                edit_raid($_REQUEST['id']);
                break;

                case "edit_raid_ok":
                edit_raid_ok($_REQUEST['id'], $_REQUEST['nom'], $_REQUEST['raid'], $_REQUEST['block'], $_REQUEST['img'], $_REQUEST['carte'], $_REQUEST['ordre'], $_REQUEST['status']);
                break;

                case "show_icone":
                show_icone();
                break;

                default:
                main();
                break;
        }

} else if ($level_admin == -1) {
    	echo "<div class=\"notification error png_bg\">\n"
    	. "<div>\n"
    	. "<br /><br /><div style=\"text-align: center;\">" . _MODULEOFF . "<br /><br /><a href=\"javascript:history.back()\"><b>" . _BACK . "</b></a></div><br /><br />"
    	. "</div>\n"
    	. "</div>\n";
} else if ($visiteur > 1) {
    	echo "<div class=\"notification error png_bg\">\n"
    	. "<div>\n"
    	. "<br /><br /><div style=\"text-align: center;\">" . _NOENTRANCE . "<br /><br /><a href=\"javascript:history.back()\"><b>" . _BACK . "</b></a></div><br /><br />"
    	. "</div>\n"
    	. "</div>\n";
} else {
    	echo "<div class=\"notification error png_bg\">\n"
    	. "<div>\n"
    	. "<br /><br /><div style=\"text-align: center;\">" . _ZONEADMIN . "<br /><br /><a href=\"javascript:history.back()\"><b>" . _BACK . "</b></a></div><br /><br />"
    	. "</div>\n"
    	. "</div>\n";
}

if($_REQUEST['op'] != 'show_icone') {
	adminfoot();
}

?>