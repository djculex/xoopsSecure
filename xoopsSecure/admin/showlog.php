<?php
/**
 * ****************************************************************************
 * marquee - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard (http://www.herve-thouzard.com)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Hervé Thouzard (http://www.herve-thouzard.com)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         marquee
 * @author 			Hervé Thouzard (http://www.herve-thouzard.com)
 *
 * Version : $Id:
 * ****************************************************************************
 */

include_once dirname(__FILE__) . '/admin_header.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';
require_once XOOPS_ROOT_PATH . '/modules/xoopssecure/class/log.php';
require_once XOOPS_ROOT_PATH . '/modules/xoopssecure/class/scan.php';
require_once XOOPS_ROOT_PATH . '/modules/xoopssecure/class/mech.php';
if (!isset($xoopsTpl)) {
    $xoopsTpl = new XoopsTpl();
}
$xoopsTpl->caching=0;

$scan = new xoopsSecure_scan;
$sys = new xoopsSecure_mech;
$tpl = new XoopsTpl();
$log = new xoopsSecure_log;

xoops_cp_header();
global $xoopsUser, $xoTheme, $xoopsTpl,$xoopsLogger, $scan, $xoopsLogger;
$xoopsLogger->activated = true;
  //error_reporting(E_ALL);

$adminscript  =  "var xoopssecure_url = '" . XOOPS_URL . "/modules/xoopssecure/admin/'\n";
$adminscript .=  "var xoopssecure_anonurl = '" . XOOPS_URL . "/modules/xoopssecure/'\n";
$adminscript .=  "var xoopssecure_xoopsurl = '" . XOOPS_URL . "'\n";
$adminscript .=  "var xoopssecure_root = '".XOOPS_ROOT_PATH."'\n";
$adminscript .=  "var xoopssecure_scanurl = '" . $scan->urlToScan . "';\n";
$adminscript .=  "var xoopssecure_malwaretitle = '" . _AM_XOOPSSECURETITLEMALWARE . "';\n";
$adminscript .=  "var xoopssecure_autoindexfilestitle = '" . _AM_XOOPSSECURETITLEINDEXFILES . "';\n";
$adminscript .=  "var xoopssecure_checkindexfilestitle = '" . _AM_XOOPSSECURETITLECHECKINDEXFILES . "';\n";
$adminscript .=  "var xoopssecure_autoindexfiles = {$scan->autoindexcreate};\n";
$adminscript .=  "var xoopssecure_indexfiletype = {$scan->indexfiletype};\n";
$adminscript .=  "var xoopssecure_autochmod = {$scan->autochmod[0]};\n";
$adminscript .=  "var xoopssecure_autochmodtitle = '"._AM_XOOPSSECURETITLECHMOD."';\n";
$adminscript .=  "var xoopssecure_dbhasfiles = ".$scan->xoopssecure_fullscan_hasFiles().";\n";
$adminscript .=  "var xoopssecure_dbhasissues = ".$scan->xoopssecure_dbHasMallIssues().";\n";
$adminscript .=  "var xoopssecure_backuptype = '".$scan->backuptype[0]."';\n";

$xoTheme->addScript('','',$adminscript);
$xoTheme->addScript(XOOPS_URL . '/modules/xoopssecure/assets/js/jquery.js');
$xoTheme->addScript(XOOPS_URL . '/modules/xoopssecure/assets/js/jquery-ui.js');
$xoTheme->addScript(XOOPS_URL . '/modules/xoopssecure/assets/js/xoopssecure.js');
$xoTheme->addScript(XOOPS_URL . '/modules/xoopssecure/assets/js/jquery.colorbox.js');
//$xoTheme->addScript(XOOPS_URL . '/modules/xoopssecure/assets/js/jquery.easing.1.3.js');
$xoTheme->addStylesheet('/modules/xoopssecure/assets/css/base/jquery.ui.all.css');
$xoTheme->addStyleSheet('modules/xoopssecure/assets/css/xoopsSecure.css');

$log->getIssues ('file');
$log->getIssues ('dir');

$test = $sys->testServer (); // viser 5.3.13 php version
$vul  = $sys->systemArray (); //get versions and vulnerbilities
$dropdts = $log->getdropdates();
$drop = $log->dropdown( "scandatedrop", array ($dropdts), $selected=0 ); // Get all dates

$xoopsTpl->assign('phpinfo', $test['phpini']);
$xoopsTpl->assign('phpver', $vul['php']['version']);
$xoopsTpl->assign('phpvul', $vul['php']['vulner']);
$xoopsTpl->assign('mysqlver', $vul['mysql']['version']);
$xoopsTpl->assign('mysqlvul', $vul['mysql']['vulner']);
$xoopsTpl->assign('apachever', $vul['apache']['version']);
$xoopsTpl->assign('apachevul', $vul['apache']['vulner']);
$xoopsTpl->assign('xoopsver', $vul['xoops']['version']);
$xoopsTpl->assign('xoopsvul', $vul['xoops']['vulner']);
$xoopsTpl->assign('datedropdown', $drop);

$xoopsTpl->display(XOOPS_ROOT_PATH .'/modules/xoopssecure/templates/admin/showlog.tpl');

//$xoTheme->addScript(XOOPS_URL . '/modules/xoopssecure/js/jquery.colorbox.js');
//$xoTheme->addStylesheet('/modules/xoopssecure/css/base/jquery.ui.all.css');


include "admin_footer.php";
