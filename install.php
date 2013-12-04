<?php

//------------------------------------------------------------------------------//
//  Nuked-KlaN - PHP Portal							//
//  http://www.nuked-klan.org							//
//------------------------------------------------------------------------------//
//  This program is free software. you can redistribute it and/or modify	//
//  it under the terms of the GNU General Public License as published by	//
//  the Free Software Foundation; either version 2 of the License.        	//
//------------------------------------------------------------------------------//

define("INDEX_CHECK", 1);

if (is_file('globals.php')) include ("globals.php");
else die('<br /><br /><div style=\"text-align: center;\"><b>install.php must be near globals.php</b></div>');
if (is_file('conf.inc.php')) include ("conf.inc.php");
else die('<br /><br /><div style=\"text-align: center;\"><b>install.php must be near conf.inc.php</b></div>');
if (is_file('nuked.php')) include('nuked.php');
else die('<br /><br /><div style=\"text-align: center;\"><b>install.php must be near nuked.php</b></div>');

function top() {
	global $nuked;

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    	<html xmlns="http://www.w3.org/1999/xhtml">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>' . $nuked['name'] . ' - Installation</title>
        <link rel="stylesheet" href="modules/Admin/css/reset.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="modules/Admin/css/style.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="modules/Admin/css/invalid.css" type="text/css" media="screen" />
        <style type="text/css">
        .css3button {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #050505;
	padding: 5px 20px;
	background: -moz-linear-gradient(top,#ffffff 0%,#c7d95f 50%,#add136 50%,#6d8000);
	background: -webkit-gradient(linear, left top, left bottom,from(#ffffff),color-stop(0.50, #c7d95f),color-stop(0.50, #add136),to(#6d8000));
	border-radius: 12px;
	-moz-border-radius: 12px;
	-webkit-border-radius: 12px;
	border: 1px solid #6d8000;
	-moz-box-shadow:0px 1px 3px rgba(000,000,000,0.5),inset 0px 0px 2px rgba(255,255,255,1);
	-webkit-box-shadow:0px 1px 3px rgba(000,000,000,0.5),inset 0px 0px 2px rgba(255,255,255,1);
	text-shadow:0px -1px 0px rgba(000,000,000,0.2),0px 1px 0px rgba(255,255,255,0.4);
	}
	</style>';
}

function index() {

	global $nuked;

	top();

        echo '<body id="login">
        <div id="login-wrapper" class="png_bg">
        <div id="login-top">
        <h1>' . $nuked['name'] . ' - Installation</h1>
        <img id="logo" src="modules/Admin/images/logo.png" alt="NK Logo" />
        </div>';
	//Correction par Sekuline
	$version = $nuked['version'];
	$last = $version[0] . '.' . $version[2] . '.' . $version[4];

    	if ($last == '1.7.9') {

		echo '<div class="content-box" style="width:700px!important;margin:auto;">',"\n" //<!-- Start Content Box -->
        	. '<div class="content-box-header"><h3>Installation Module Pve</h3></div>',"\n"
        	. '<div class="tab-content" id="tab2"><table style="margin:auto;width:80%;color:black;" cellspacing="0" cellpadding="0" border="0">';

		//Vérification si INSTALLATION ou REINSTALLATION du module afin de ne pas dupliquer le liens dans l'admin
		$test = mysql_query("SELECT id FROM " . $nuked['prefix'] . "_modules WHERE nom='Pve'");
		$req = mysql_num_rows($test);
		if($req == 1) echo '<tr><td style="text-align:center;"><span style="color:red; font-weight:bold;">Attention L\'installation remettra la configuration par défault du module.</span></td></tr>';

		echo '<tr>
		<td>
		Vous allez installer le module <strong>Pve</strong> <br /><br />
		Créé par <a href="http://www.titeflafla.net" target="_blank">Kipcool</a> Pour <a href="http://www.nuked-klan.eu" target="_blank">Nuked-Klan</a><br /><br />
		</td>
		</tr>
		<tr>
		<td style="text-align:center;">
		<input type="button" name="yes" onclick="document.location.href=\'install.php?op=update\';" value="Installer" class="css3button"/>&nbsp;&nbsp;
		<input type="button" name="No" onclick="document.location.href=\'install.php?op=nan\';" value="Ne pas installer" class="css3button"/>
		</td>
		</tr>
		</table>
		</div></div>
		</div>
        	</body>
    		</html>';
	}
	else echo 'Bad version, Only for NK 1.7.9';
}

function update() {

	global $nuked;

	//Efface les tables
	$req = mysql_query("DROP TABLE IF EXISTS ". $nuked['prefix'] ."_wow_pve");
	$req = mysql_query("DROP TABLE IF EXISTS ". $nuked['prefix'] ."_wow_pve_cat");
	$req = mysql_query("DELETE FROM ". $nuked['prefix'] ."_modules WHERE nom = 'Pve'");
        $req = mysql_query("DELETE FROM ". $nuked['prefix'] ."_block WHERE module = 'Pve'");

	$sql = mysql_query("INSERT INTO ". $nuked['prefix'] ."_block (`bid`, `active`, `position`, `module`, `titre`, `content`, `type`, `nivo`, `page`) VALUES ('', 2, 1, 'Pve', 'Avancée PVE', '', 'module', 0, 'Tous') ");

	$sql = "CREATE TABLE IF NOT EXISTS `".$nuked['prefix']."_wow_pve` (
  	`id` int(11) NOT NULL AUTO_INCREMENT,
  	`nom` varchar(800) NOT NULL,
  	`img` varchar(500) NOT NULL,
  	`description` text NOT NULL,
  	`undefeated` varchar(3) NOT NULL DEFAULT 'off',
  	`defeated` varchar(3) NOT NULL DEFAULT 'off',
  	`defeated_hero` varchar(3) NOT NULL DEFAULT 'off',
  	`ordre` int(5) NOT NULL DEFAULT '0',
  	`raid` varchar(500) NOT NULL,
  	PRIMARY KEY (`id`),
  	KEY `nom` (`nom`(767))
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1;";
	$req = mysql_query($sql);

        $sql_insert = mysql_query("INSERT INTO `".$nuked['prefix']."_wow_pve` (`id`, `nom`, `img`, `description`, `undefeated`, `defeated`, `defeated_hero`, `ordre`, `raid`) VALUES
	(1, 'Beth\'tilac', '1315311816.png', '', 'on', 'off', 'off', 1, '1'),
	(2, 'Seigneur Rhyolith', '1315311826.png', '', 'on', 'off', 'off', 2, '1'),
	(3, 'Alysrazor', '1315311812.png', '', 'on', 'off', 'off', 3, '1'),
	(4, 'Shannox', '1315311829.png', '', 'on', 'off', 'off', 0, '1'),
	(5, 'Baleroc', '1315311814.png', '', 'on', 'off', 'off', 4, '1'),
	(6, 'Chambellan', '1315311819.png', '', 'on', 'off', 'off', 5, '1'),
	(7, 'Ragnaros', '1315311821.png', '', 'on', 'off', 'off', 6, '1'),
	(8, 'Halfus Brise-Wyrm', '1315311776.png', '', 'on', 'off', 'off', 0, '4'),
	(9, 'Theralioff et Valioffa', '1315311782.png', '', 'on', 'off', 'off', 1, '4'),
	(10, 'Coffseil d\'Ascendance', '1315311780.png', '', 'on', 'off', 'off', 2, '4'),
	(11, 'Cho\'gall', '1315311772.png', '', 'on', 'off', 'off', 3, '4'),
	(12, 'Omnitroff', '1315311809.png', '', 'on', 'off', 'off', 0, '3'),
	(13, 'Magmagueule', '1315311793.png', '', 'on', 'off', 'off', 1, '3'),
	(14, 'Atram&eacute;d&egrave;s', '1315311785.png', '', 'on', 'off', 'off', 2, '3'),
	(15, 'Chimaeroff', '1315311790.png', '', 'on', 'off', 'off', 4, '3'),
	(16, 'Maloriak', '1315311796.png', '', 'on', 'off', 'off', 5, '3'),
	(17, 'Nefarian', '1315311799.png', '', 'on', 'off', 'off', 6, '3'),
	(18, 'Coffclave du Vent', '1315311834.png', '', 'on', 'off', 'off', 0, '5'),
	(19, 'Al\'Akir', '1315311831.png', '', 'on', 'off', 'off', 1, '5'),
	(20, 'Argaloth', '1315401194.png', '', 'on', 'off', 'off', 0, '2'),
	(21, 'Occu\'thar', '1315401360.png', '', 'on', 'off', 'off', 1, '2'),
	(22, 'Alizabal', '1321818149.png', '', 'on', 'off', 'off', 2, '2'),
	(23, 'Morchok', '1322533460.png', '', 'on', 'off', 'off', 0, '6'),
	(24, 'Zoff\'ozz', '1322533501.png', '', 'on', 'off', 'off', 1, '6'),
	(25, 'Yor\'sahj', '1322534253.png', '', 'on', 'off', 'off', 2, '6'),
	(26, 'Hagara', '1322534880.png', '', 'on', 'off', 'off', 3, '6'),
	(27, 'Ultraxioff', '1322534929.png', '', 'on', 'off', 'off', 4, '6'),
	(28, 'Échine d\'aile de mort', '1322534969.png', '', 'on', 'off', 'off', 6, '6'),
	(29, 'Corne-Noire', '1322767557.png', '', 'on', 'off', 'off', 5, '6'),
	(30, 'Folie d\'Aile de mort', '1324634454.png', '', 'on', 'off', 'off', 7, '6');");
        $req = mysql_query($sql_insert);

        $sql = "CREATE TABLE IF NOT EXISTS `".$nuked['prefix']."_wow_pve_cat` (
  	`id` int(5) NOT NULL AUTO_INCREMENT,
  	`nom` varchar(500) NOT NULL,
  	`raid` varchar(2) NOT NULL DEFAULT '10',
  	`img` varchar(500) NOT NULL,
  	`carte` text NOT NULL,
  	`block` int(1) NOT NULL DEFAULT '0',
  	`ordre` int(5) NOT NULL,
  	`status` int(1) NOT NULL DEFAULT '0',
  	PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1;";
	$req = mysql_query($sql);

        $sql_insert = "INSERT INTO `".$nuked['prefix']."_wow_pve_cat` (`id`, `nom`, `raid`, `img`, `carte`, `block`, `ordre`, `status`) VALUES
	(1, 'Terre de Feu', '10', '1315067407.png', '1322447178.jpg|1322447240.jpg|', 0, 4, 1),
	(2, 'Bastion de Baradin', '10', '1315314447.png', '1322447084.jpg|', 0, 0, 1),
	(3, 'Descente de l\'Aile noire', '10', '1315314613.png', '1322447102.jpg|1322447127.jpg|', 0, 1, 1),
	(4, 'Le bastion du Cr&eacute;puscule', '10', '1315314687.png', '1322447290.jpg|1322447306.jpg|1322447326.jpg|', 0, 2, 1),
	(5, 'Tr&ocirc;ne des quatre vents', '10', '1315314751.png', '1322446821.jpg|', 0, 3, 1),
	(6, 'l\'&Acirc;me des Dragons', '10', '1321819354.png', '1322767702.jpg|1322767713.jpg|1322767727.jpg|1322767744.jpg|1322767757.jpg|1322767767.jpg|1322767778.jpg|', 1, 5, 1),
	(7, 'Caveaux de Mogu''shan', '10', '1344233246.png', '', 0, 6, 0),
	(8, 'Coeur de la Peur', '10', '1344233794.png', '', 0, 7, 0),
	(9, 'Terrasse Printanière', '10', '1344235140.png', '', 0, 8, 0);";
	$req = mysql_query($sql_insert);

	$sql = mysql_query("INSERT INTO ". $nuked['prefix'] ."_modules (`id`, `nom`, `niveau`, `admin`) VALUES ('', 'Pve', '0', '9');");
        $sql = mysql_query("INSERT INTO ". $nuked['prefix'] ."_stats (`nom`, `type`, `count`) VALUES ('Pve', 'pages', '0');");

        top();
        echo '<div class="tab-content" id="tab2" style="width:700px!important;margin:auto;">'
        . "<br /><br /><div class=\"notification success png_bg\"><div>Le module Pve a été installé correctement.<br />
        N'oublier pas d'ajouter le module dans le menu<br />
        Redirection en cours vers l'administration ...</div></div>";

	//Supression automatique du fichier install.php
	if(@!unlink("install.php")) echo "<br /><br /><div class=\"notification error png_bg\"><div>Penser à supprimer le fichier install.php de votre FTP .</div></div>";

        echo '</div></body></html>';
	redirect("index.php?file=Admin", 2);
}

function nan() {

	top();
        echo '<div class="tab-content" id="tab2" style="width:700px!important;margin:auto;">'
	. "<br /><br /><div class=\"notification error png_bg\"><div>Installation annulé .</div></div>";

	if(@!unlink("install.php")) echo "<br /><br /><div class=\"notification error png_bg\"><div>Penser à supprimer le fichier install.php de votre FTP .</div></div>";

        echo '</div></body></html>';

    	redirect("index.php", 2);
}

switch($_GET['op']) {
	case"index":
	index();
	break;

	case"update":
	update();
	break;

	case"nan":
	nan();
	break;

	default:
	index();
	break;
}

?>