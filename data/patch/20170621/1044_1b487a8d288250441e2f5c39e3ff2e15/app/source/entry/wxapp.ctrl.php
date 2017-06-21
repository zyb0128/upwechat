<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
 
defined('IN_IA') or exit('Access Denied');
if (strexists($_SERVER['HTTP_REFERER'], 'https://servicewechat.com/')) {
	$referer_url = parse_url($_SERVER['HTTP_REFERER']);
	list($appid, $version) = explode('/', ltrim($referer_url['path'], '/'));
}
$site = WeUtility::createModuleWxapp($entry['module']);
if(!is_error($site)) {
	$site->appid = $appid;
	$site->version = $version;
	$method = 'doPage' . ucfirst($entry['do']);
	if (!empty($site->token)) {
		if (!$site->checkSign()) {
			message(error(1, '签名错误'), '', 'ajax');
		}
	}
	if (!empty($_W['uniacid'])) {
		$version = trim($_GPC['v']);
		$version_info = pdo_get('wxapp_versions', array('uniacid' => $_W['uniacid'], 'version' => $version), array('id', 'uniacid', 'template', 'modules'));
		if (!empty($version_info)) {
			$connection = iunserializer($version_info['modules'], true);
			$_W['uniacid'] = !empty($connection[$entry['module']]) ? $connection[$entry['module']]['uniacid'] : $version_info['uniacid'];
		}
	}
	exit($site->$method());
}
exit();