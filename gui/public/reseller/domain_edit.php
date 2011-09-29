<?php
/**
 * i-MSCP - internet Multi Server Control Panel
 *
 * @copyright	2001-2006 by moleSoftware GmbH
 * @copyright	2006-2010 by ispCP | http://isp-control.net
 * @copyright	2010-2011 by i-msCP | http://i-mscp.net
 * @version		SVN: $Id$
 * @link		http://i-mscp.net
 * @author		ispCP Team
 * @author		i-MSCP Team
 *
 * @license
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * The Original Code is "VHCS - Virtual Hosting Control System".
 *
 * The Initial Developer of the Original Code is moleSoftware GmbH.
 * Portions created by Initial Developer are Copyright (C) 2001-2006
 * by moleSoftware GmbH. All Rights Reserved.
 *
 * Portions created by the ispCP Team are Copyright (C) 2006-2010 by
 * isp Control Panel. All Rights Reserved.
 *
 * Portions created by the i-MSCP Team are Copyright (C) 2010-2011 by
 * i-MSCP a internet Multi Server Control Panel. All Rights Reserved.
 */

// Include core library
require 'imscp-lib.php';

iMSCP_Events_Manager::getInstance()->dispatch(iMSCP_Events::onResellerScriptStart);

check_login(__FILE__);

/** @var $cfg iMSCP_Config_Handler_File */
$cfg = iMSCP_Registry::get('config');

/* @var $phpini iMSCP_PHPini */
$phpini = new iMSCP_PHPini();

$tpl = new iMSCP_pTemplate();
$tpl->define_dynamic(array(
						  'page' => $cfg->RESELLER_TEMPLATE_PATH . '/domain_edit.tpl',
						  'page_message' => 'page',
						  'ip_entry' => 'page',
						  'logged_from' => 'page',
						  'subdomain_edit' => 'page',
						  'alias_edit' => 'page',
						  'mail_edit' => 'page',
						  'ftp_edit' => 'page',
						  'sql_db_edit' => 'page',
						  'sql_user_edit' => 'page',
						  't_software_support' => 'page',
						  't_phpini_system' => 'page',
						  't_phpini_register_globals' => 'page',
						  't_phpini_allow_url_fopen' => 'page',
						  't_phpini_display_errors' => 'page',
						  't_phpini_disable_functions' => 'page',
						  't_phpini_al_system_perm' => 'page',
						  't_phpini_register_globals_perm' => 'page',
						  't_phpini_allow_url_fopen_perm' => 'page',
						  't_phpini_display_errors_perm' => 'page',
						  't_phpini_disable_functions_perm' => 'page'));

if (isset($cfg->HOSTING_PLANS_LEVEL) && $cfg->HOSTING_PLANS_LEVEL === 'admin') {
	redirectTo('users.php?psi=last');
}

$tpl->assign(array(
				  'TR_EDIT_DOMAIN_PAGE_TITLE' => tr('i-MSCP - Domain/Edit'),
				  'THEME_COLOR_PATH' => "../themes/{$cfg->USER_INITIAL_THEME}",
				  'THEME_CHARSET' => tr('encoding'),
				  'ISP_LOGO' => layout_getUserLogo(),
				  'TR_EDIT_DOMAIN' => tr('Edit Domain'),
				  'TR_DOMAIN_PROPERTIES' => tr('Domain properties'),
				  'TR_DOMAIN_NAME' => tr('Domain name'),
				  'TR_DOMAIN_EXPIRE' => tr('Domain expire'),
				  'TR_DOMAIN_NEW_EXPIRE' => tr('New expire date'),
				  'TR_DOMAIN_IP' => tr('Domain IP'),
				  'TR_DOMAIN_MAIN_PROPERTIES' => tr('Domain main properties'),
				  'TR_DOMAIN_ACCOUNT_LIMITS' => tr('Domain account limits'),
				  'TR_OPTIONAL_FEATURES' => tr('Optional features'),
				  'TR_PHP_SUPP' => tr('PHP support'),
				  'TR_CGI_SUPP' => tr('CGI support'),
				  'TR_DNS_SUPP' => tr('Custom DNS records support'),
				  'TR_SUBDOMAINS' => tr('Max subdomains<br /><i>(-1 disabled, 0 unlimited)</i>'),
				  'TR_ALIAS' => tr('Max aliases<br /><i>(-1 disabled, 0 unlimited)</i>'),
				  'TR_MAIL_ACCOUNT' => tr('Mail accounts limit <br /><i>(-1 disabled, 0 unlimited)</i>'),
				  'TR_FTP_ACCOUNTS' => tr('FTP accounts limit <br /><i>(-1 disabled, 0 unlimited)</i>'),
				  'TR_SQL_DB' => tr('SQL databases limit <br /><i>(-1 disabled, 0 unlimited)</i>'),
				  'TR_SQL_USERS' => tr('SQL users limit <br /><i>(-1 disabled, 0 unlimited)</i>'),
				  'TR_TRAFFIC' => tr('Traffic limit [MB] <br /><i>(0 unlimited)</i>'),
				  'TR_DISK' => tr('Disk limit [MB] <br /><i>(0 unlimited)</i>'),
				  'TR_USER_NAME' => tr('Username'),
				  'TR_BACKUP' => tr('Backup'),
				  'TR_BACKUP_DOMAIN' => tr('Domain'),
				  'TR_BACKUP_SQL' => tr('SQL'),
				  'TR_BACKUP_FULL' => tr('Full'),
				  'TR_BACKUP_NO' => tr('No'),
				  'TR_UPDATE_DATA' => tr('Submit changes'),
				  'TR_CANCEL' => tr('Cancel'),
				  'TR_YES' => tr('Yes'),
				  'TR_NO' => tr('No'),
				  'TR_EXPIRE_CHECKBOX' => tr('Never expire'),
				  'TR_DMN_EXP_HELP' => tr("In case 'Domain expire' is 'N/A', the expiration date will be set from today."),
				  'TR_PHPINI_SYSTEM' => tr('Custom php.ini'),
				  'TR_PHPINI_ALLOW_URL_FOPEN' => 'allow_url_fopen',
				  'TR_PHPINI_REGISTER_GLOBALS' => 'register_globals',
				  'TR_PHPINI_DISPLAY_ERRORS' => 'display_errors',
				  'TR_PHPINI_ERROR_REPORTING' => 'error_reporting',

				  'TR_PHPINI_ERROR_REPORTING_DEFAULT' => tr('Show all errors, except for notices and coding standards warnings (Default)'),
				  'TR_PHPINI_ERROR_REPORTING_DEVELOPEMENT' => tr('Show all errors, warnings and notices including coding standards (Development)'),
				  'TR_PHPINI_ERROR_REPORTING_PRODUCTION' => tr(' Show all errors, except for warnings about deprecated code (Production)'),
				  'TR_PHPINI_ERROR_REPORTING_NONE' => tr('Do not show any error'),

				  'TR_PHPINI_POST_MAX_SIZE' => tr('post_max_size [MB]'),
				  'TR_PHPINI_UPLOAD_MAX_FILESIZE' => tr('upload_max_filesize [MB]'),
				  'TR_PHPINI_MAX_EXECUTION_TIME' => tr('max_execution_time [sec]'),
				  'TR_PHPINI_MAX_INPUT_TIME' => tr('max_input_time [sec]'),
				  'TR_PHPINI_MEMORY_LIMIT' => tr('memory_limit [MB]'),
				  'TR_PHPINI_DISABLE_FUNCTIONS' => tr('disable_functions'),
				  'TR_ENABLED' => tr('Enabled'),
				  'TR_DISABLED' => tr('Disabled'),
				  'TR_PHP_DIRECTIVES_EDITOR' => tr('PHP directives editor'),
				  'TR_PHPINI_AL_SYSTEM' => tr('Can edit PHP directives'),
				  'TR_PHPINI_AL_REGISTER_GLOBALS' => tr("Can edit the <strong>register_globals</strong> directive"),
				  'TR_PHPINI_AL_ALLOW_URL_FOPEN' => tr("Can edit the <strong>allow_url_open</strong> directive"),
				  'TR_PHPINI_AL_DISPLAY_ERRORS' => tr("Can edit the <strong>display_errors</strong> and <strong>error_reporting</strong> directives"),
				  'TR_PHPINI_AL_DISABLE_FUNCTIONS' => tr("Can edit the <strong>'disable_functions</strong> directive"),
				  'TR_USER_EDITABLE_EXEC' => tr("Allows 'exec' only")));

gen_reseller_mainmenu($tpl, $cfg->RESELLER_TEMPLATE_PATH . '/main_menu_users_manage.tpl');
gen_reseller_menu($tpl, $cfg->RESELLER_TEMPLATE_PATH . '/menu_users_manage.tpl');
get_reseller_software_permission($tpl, $_SESSION['user_id']);
gen_logged_from($tpl);

$phpini->loadRePerm($_SESSION['user_id']); // load reseller permission into object

if (isset($_POST['uaction']) && ('sub_data' === $_POST['uaction'])) {
	// Process data
	if (isset($_SESSION['edit_id'])) {
		$editid = $_SESSION['edit_id'];
	} else {
		unset($_SESSION['edit_id']);
		$_SESSION['edit'] = '_no_';

		redirectTo('users.php?psi=last');
	}

	if (check_user_data($tpl, $_SESSION['user_id'], $editid, $phpini)) { // Save data to db
		$_SESSION['dedit'] = "_yes_";
		redirectTo('users.php?psi=last');
	}

	load_additional_data($_SESSION['user_id'], $editid);
} else {
	// Get user id that comes for edit
	if (isset($_GET['edit_id'])) {
		$editid = $_GET['edit_id'];
	}

	$phpini->loadClPerm($_GET['edit_id']); //load client perm into object
	load_user_data($_SESSION['user_id'], $editid, $phpini);

	$_SESSION['edit_id'] = $editid;
}

gen_editdomain_page($tpl, $phpini);
generatePageMessage($tpl);

// Begin function block

/**
 * Load domain properties.
 *
 * @param  int $user_id
 * @param  int $domain_id
 * @param  iMSCP_PHPini $phpini
 * @return void
 */
function load_user_data($user_id, $domain_id, $phpini)
{
	global $sub, $als, $mail, $ftp, $sql_db, $sql_user, $traff, $disk, $software_supp;

	$query = "
		SELECT
			`domain_id`
		FROM
			`domain`
		WHERE
			`domain_id` = ?
		AND
			`domain_created_id` = ?
	";

	$rs = exec_query($query, array($domain_id, $user_id));

	if ($rs->recordCount() == 0) {
		set_page_message(tr('User does not exist or you do not have permission to access this interface.'), 'error');

		redirectTo('users.php?psi=last');
	}

	list(, $sub, , $als, , $mail, , $ftp, , $sql_db, , $sql_user, $traff, $disk) = generate_user_props($domain_id);

	$phpini->loadCustomPHPini($domain_id); //load custom ini if exist  - if not the defaults values are loaded from constructer are still valid
	load_additional_data($user_id, $domain_id);
}


/**
 * Load additional domain properties.
 *
 * @param  int $user_id
 * @param  int $domain_id
 * @return void
 */
function load_additional_data($user_id, $domain_id)
{
	global $domain_name, $domain_expires, $domain_ip, $php_sup, $cgi_supp, $username,
		$allowbackup, $dns_supp, $domain_expires_date, $software_supp;

	/** @var $cfg iMSCP_Config_Handler_File */
	$cfg = iMSCP_Registry::get('config');

	// Get domain data
	$query = "
		SELECT
			`domain_name`, `domain_expires`, `domain_ip_id`, `domain_php`, `domain_cgi`,
			`domain_admin_id`, `allowbackup`, `domain_dns`, `domain_software_allowed`
		FROM
			`domain`
		WHERE
			`domain_id` = ?
	";

	$res = exec_query($query, $domain_id);
	$data = $res->fetchRow();

	$domain_name = $data['domain_name'];

	$domain_expires = $data['domain_expires'];
	$_SESSION['domain_expires'] = $domain_expires;

	if ($domain_expires == 0) {
		$domain_expires = tr('N/A');
		$domain_expires_date = '0';
	} else {
		$date_formt = $cfg->DATE_FORMAT;
		$domain_expires_date = date("m/d/Y", $domain_expires);
		$domain_expires = date($date_formt, $domain_expires);
	}

	$domain_ip_id = $data['domain_ip_id'];
	$php_sup = $data['domain_php'];
	$cgi_supp = $data['domain_cgi'];
	$allowbackup = $data['allowbackup'];
	$domain_admin_id = $data['domain_admin_id'];
	$dns_supp = $data['domain_dns'];
	$software_supp = $data['domain_software_allowed'];

	// Get IP of domain
	$query = "
		SELECT
			`ip_number`, `ip_domain`
		FROM
			`server_ips`
		WHERE
			`ip_id` = ?
	";

	$res = exec_query($query, $domain_ip_id);
	$data = $res->fetchRow();

	$domain_ip = $data['ip_number'] . '&nbsp;(' . $data['ip_domain'] . ')';

	// Get username of domain
	$query = "
		SELECT
			`admin_name`
		FROM
			`admin`
		WHERE
			`admin_id` = ?
		AND
			`admin_type` = 'user'
		AND
			`created_by` = ?
	";

	$res = exec_query($query, array($domain_admin_id, $user_id));
	$data = $res->fetchRow();

	$username = $data['admin_name'];

} // End of load_additional_data()

/**
 * Generates edit page.
 *
 * @param iMSCP_pTemplate $tpl
 * @param iMSCP_PHPini $phpini
 * @return void
 */
function gen_editdomain_page($tpl, $phpini)
{
	global $domain_name, $domain_expires, $domain_ip, $php_sup, $cgi_supp, $sub, $als,
		$mail, $ftp, $sql_db, $sql_user, $traff, $disk, $username, $allowbackup,
		$dns_supp, $domain_expires_date, $software_supp;

	/** @var $cfg iMSCP_Config_Handler_File */
	$cfg = iMSCP_Registry::get('config');

	// Fill in the fields
	$domain_name = decode_idna($domain_name);
	$username = decode_idna($username);

	generate_ip_list($tpl, $_SESSION['user_id']);

	if ($allowbackup === 'dmn') {
		$tpl->assign(array(
						  'BACKUP_DOMAIN' => $cfg->HTML_SELECTED,
						  'BACKUP_SQL' => '',
						  'BACKUP_FULL' => '',
						  'BACKUP_NO' => ''));
	} elseif ($allowbackup === 'sql') {
		$tpl->assign(array(
						  'BACKUP_DOMAIN' => '',
						  'BACKUP_SQL' => $cfg->HTML_SELECTED,
						  'BACKUP_FULL' => '',
						  'BACKUP_NO' => ''));
	} else if ($allowbackup === 'full') {
		$tpl->assign(array(
						  'BACKUP_DOMAIN' => '',
						  'BACKUP_SQL' => '',
						  'BACKUP_FULL' => $cfg->HTML_SELECTED,
						  'BACKUP_NO' => ''));
	} else if ($allowbackup === 'no') {
		$tpl->assign(array(
						  'BACKUP_DOMAIN' => '',
						  'BACKUP_SQL' => '',
						  'BACKUP_FULL' => '',
						  'BACKUP_NO' => $cfg->HTML_SELECTED));
	}

	if ($domain_expires_date === '0') {
		$tpl->assign(array(
						  'VL_DOMAIN_EXPIRE_DATE' => '',
						  'VL_NEVEREXPIRE' => $cfg->HTML_CHECKED,
						  'VL_DISABLED' => $cfg->HTML_DISABLED));
	} else {
		$tpl->assign(array(
						  'VL_DOMAIN_EXPIRE_DATE' => $domain_expires_date,
						  'VL_NEVEREXPIRE' => ''));
	}

	list($rsub_max, $rals_max, $rmail_max, $rftp_max, $rsql_db_max, $rsql_user_max) = check_reseller_permissions(
		$_SESSION['user_id'], 'all_permissions'
	);

	if ($rsub_max == "-1") $tpl->assign('ALIAS_EDIT', '');
	if ($rals_max == "-1") $tpl->assign('SUBDOMAIN_EDIT', '');
	if ($rmail_max == "-1") $tpl->assign('MAIL_EDIT', '');
	if ($rftp_max == "-1") $tpl->assign('FTP_EDIT', '');
	if ($rsql_db_max == "-1") $tpl->assign('SQL_DB_EDIT', '');
	if ($rsql_user_max == "-1") $tpl->assign('SQL_USER_EDIT', '');

	$tpl->assign(array(
					  'PHP_YES' => ($php_sup == 'yes') ? $cfg->HTML_SELECTED : '',
					  'PHP_NO' => ($php_sup != 'yes') ? $cfg->HTML_SELECTED : '',
					  'SOFTWARE_YES' => ($software_supp == 'yes')
						  ? $cfg->HTML_SELECTED : '',
					  'SOFTWARE_NO' => ($software_supp != 'yes')
						  ? $cfg->HTML_SELECTED : '',
					  'CGI_YES' => ($cgi_supp == 'yes') ? $cfg->HTML_SELECTED : '',
					  'CGI_NO' => ($cgi_supp != 'yes') ? $cfg->HTML_SELECTED : '',
					  'DNS_YES' => ($dns_supp == 'yes') ? $cfg->HTML_SELECTED : '',
					  'DNS_NO' => ($dns_supp != 'yes') ? $cfg->HTML_SELECTED : '',
					  'VL_DOMAIN_NAME' => tohtml($domain_name),
					  'VL_DOMAIN_EXPIRE' => $domain_expires,
					  'VL_DOMAIN_IP' => $domain_ip,
					  'DOMAIN_EXPIRES_DATE' => $domain_expires_date,
					  'VL_DOM_SUB' => $sub,
					  'VL_DOM_ALIAS' => $als,
					  'VL_DOM_MAIL_ACCOUNT' => $mail,
					  'VL_FTP_ACCOUNTS' => $ftp,
					  'VL_SQL_DB' => $sql_db,
					  'VL_SQL_USERS' => $sql_user,
					  'VL_TRAFFIC' => $traff,
					  'VL_DOM_DISK' => $disk,
					  'VL_USER_NAME' => tohtml($username),
					  'PHPINI_SYSTEM_YES' => ($phpini->getDataVal('phpiniSystem') == 'yes')
						  ? $cfg->HTML_CHECKED : '',
					  'PHPINI_SYSTEM_NO' => ($phpini->getDataVal('phpiniSystem') == 'no')
						  ? $cfg->HTML_CHECKED : '',
					  'PHPINI_ALLOW_URL_FOPEN_ON' => ($phpini->getDataVal('phpiniAllowUrlFopen') == 'On')
						  ? $cfg->HTML_SELECTED : '',
					  'PHPINI_ALLOW_URL_FOPEN_OFF' => ($phpini->getDataVal('phpiniAllowUrlFopen') != 'On')
						  ? $cfg->HTML_SELECTED : '',
					  'PHPINI_REGISTER_GLOBALS_ON' => ($phpini->getDataVal('phpiniRegisterGlobals') == 'On')
						  ? $cfg->HTML_SELECTED : '',
					  'PHPINI_REGISTER_GLOBALS_OFF' => ($phpini->getDataVal('phpiniRegisterGlobals') != 'On')
						  ? $cfg->HTML_SELECTED : '',
					  'PHPINI_DISPLAY_ERRORS_ON' => ($phpini->getDataVal('phpiniDisplayErrors') == 'On')
						  ? $cfg->HTML_SELECTED : '',
					  'PHPINI_DISPLAY_ERRORS_OFF' => ($phpini->getDataVal('phpiniDisplayErrors') != 'On')
						  ? $cfg->HTML_SELECTED : '',
					  'PHPINI_ERROR_REPORTING_0' => ($phpini->getDataVal('phpiniErrorReporting') == 'E_ALL & ~E_NOTICE')
						  ? $cfg->HTML_SELECTED : '',
					  'PHPINI_ERROR_REPORTING_1' => ($phpini->getDataVal('phpiniErrorReporting') == 'E_ALL | E_STRICT')
						  ? $cfg->HTML_SELECTED : '',
					  'PHPINI_ERROR_REPORTING_2' => ($phpini->getDataVal('phpiniErrorReporting') == 'E_ALL & ~E_DEPRECATED')
						  ? $cfg->HTML_SELECTED : '',
					  'PHPINI_ERROR_REPORTING_3' => ($phpini->getDataVal('phpiniErrorReporting') == '0')
						  ? $cfg->HTML_SELECTED : '',
					  'PHPINI_POST_MAX_SIZE' => $phpini->getDataVal('phpiniPostMaxSize'),
					  'PHPINI_UPLOAD_MAX_FILESIZE' => $phpini->getDataVal('phpiniUploadMaxFileSize'),
					  'PHPINI_MAX_EXECUTION_TIME' => $phpini->getDataVal('phpiniMaxExecutionTime'),
					  'PHPINI_MAX_INPUT_TIME' => $phpini->getDataVal('phpiniMaxInputTime'),
					  'PHPINI_MEMORY_LIMIT' => $phpini->getDataVal('phpiniMemoryLimit'),
					  'PHPINI_AL_SYSTEM_YES' => ($phpini->getClPermVal('phpiniSystem') == 'yes')
						  ? $cfg->HTML_CHECKED : '',
					  'PHPINI_AL_SYSTEM_NO' => ($phpini->getClPermVal('phpiniSystem') == 'no')
						  ? $cfg->HTML_CHECKED : '',
					  'PHPINI_AL_REGISTER_GLOBALS_YES' => ($phpini->getClPermVal('phpiniRegisterGlobals') == 'yes')
						  ? $cfg->HTML_CHECKED : '',
					  'PHPINI_AL_REGISTER_GLOBALS_NO' => ($phpini->getClPermVal('phpiniRegisterGlobals') != 'yes')
						  ? $cfg->HTML_CHECKED : '',
					  'PHPINI_AL_ALLOW_URL_FOPEN_YES' => ($phpini->getClPermVal('phpiniAllowUrlFopen') == 'yes')
						  ? $cfg->HTML_CHECKED : '',
					  'PHPINI_AL_ALLOW_URL_FOPEN_NO' => ($phpini->getClPermVal('phpiniAllowUrlFopen') != 'yes')
						  ? $cfg->HTML_CHECKED : '',
					  'PHPINI_AL_DISPLAY_ERRORS_YES' => ($phpini->getClPermVal('phpiniDisplayErrors') == 'yes')
						  ? $cfg->HTML_CHECKED : '',
					  'PHPINI_AL_DISPLAY_ERRORS_NO' => ($phpini->getClPermVal('phpiniDisplayErrors') != 'yes')
						  ? $cfg->HTML_CHECKED : '',
					  'PHPINI_AL_DISABLE_FUNCTIONS_YES' => ($phpini->getClPermVal('phpiniDisableFunctions') == 'yes')
						  ? $cfg->HTML_CHECKED : '',
					  'PHPINI_AL_DISABLE_FUNCTIONS_NO' => ($phpini->getClPermVal('phpiniDisableFunctions') == 'no')
						  ? $cfg->HTML_CHECKED : '',
					  'PHPINI_AL_DISABLE_FUNCTIONS_EXEC' => ($phpini->getClPermVal('phpiniDisableFunctions') == 'exec')
						  ? $cfg->HTML_CHECKED : ''));

	$phpiniDf = explode(',', $phpini->getDataVal('phpiniDisableFunctions')); //deAssemble the disable_functions
	$phpiniDfAll = array(
		'PHPINI_DF_SHOW_SOURCE_CHK',
		'PHPINI_DF_SYSTEM_CHK',
		'PHPINI_DF_SHELL_EXEC_CHK',
		'PHPINI_DF_PASSTHRU_CHK',
		'PHPINI_DF_EXEC_CHK',
		'PHPINI_DF_PHPINFO_CHK',
		'PHPINI_DF_SHELL_CHK',
		'PHPINI_DF_SYMLINK_CHK');


	foreach ($phpiniDfAll as $phpiniDfVar) {
		$phpiniDfShortVar = substr($phpiniDfVar, 10);
		$phpiniDfShortVar = strtolower(substr($phpiniDfShortVar, 0, -4));

		if (in_array($phpiniDfShortVar, $phpiniDf)) {
			$tpl->assign($phpiniDfVar, 'CHECKED');
		}
		else {
			$tpl->assign($phpiniDfVar, '');
		}
	}

	if ($phpini->checkRePerm('phpiniSystem')) { // if reseller has permission to use php.ini feature
		if ($phpini->checkRePerm('phpiniRegisterGlobals')) {
			$tpl->parse('T_PHPINI_REGISTER_GLOBALS', 't_phpini_register_globals');
			$tpl->parse('T_PHPINI_REGISTER_GLOBALS_PERM', 't_phpini_register_globals_perm');
		} else {
			$tpl->assign(array(
							 'T_PHPINI_REGISTER_GLOBALS' => '',
							 'T_PHPINI_REGISTER_GLOBALS_PERM' => ''));
		}

		if ($phpini->checkRePerm('phpiniAllowUrlFopen')) {
			$tpl->parse('T_PHPINI_ALLOW_URL_FOPEN', 't_phpini_allow_url_fopen');
			$tpl->parse('T_PHPINI_ALLOW_URL_FOPEN_PERM', 't_phpini_allow_url_fopen_perm');
		} else {
			$tpl->assign(array(
							  'T_PHPINI_ALLOW_URL_FOPEN' => '',
							  'T_PHPINI_ALLOW_URL_FOPEN_PERM' => ''));
		}

		if ($phpini->checkRePerm('phpiniDisplayErrors')) {
			$tpl->parse('T_PHPINI_DISPLAY_ERRORS', 't_phpini_display_errors');
			$tpl->parse('T_PHPINI_DISPLAY_ERRORS_PERM', 't_phpini_display_errors_perm');
		} else {
			$tpl->assign(array(
							  'T_PHPINI_DISPLAY_ERRORS' => '',
							  'T_PHPINI_DISPLAY_ERRORS_PERM' => ''));
		}

		if ($phpini->checkRePerm('phpiniDisableFunctions')) {
			$tpl->parse('T_PHPINI_DISABLE_FUNCTIONS', 't_phpini_disable_functions');
			$tpl->parse('T_PHPINI_DISABLE_FUNCTIONS_PERM', 't_phpini_disable_functions_perm');
		} else {
			$tpl->assign(array(
							  'T_PHPINI_DISABLE_FUNCTIONS' => '',
							 'T_PHPINI_DISABLE_FUNCTIONS_PERM' => ''));
		}

	} else { // if no permission at all
		$tpl->assign('T_PHPINI_SYSTEM', '');
	}
}

/**
 *
 * @param iMSCP_pTemplate $tpl
 * @param int $reseller_id Reseller unique identifier
 * @param int $user_id
 * @param iMSCP_PHPini $phpini
 * @return bool
 */
function check_user_data($tpl, $reseller_id, $user_id, $phpini)
{
	global $sub, $als, $mail, $ftp, $sql_db, $sql_user, $traff, $disk, $domain_php,
		$domain_cgi, $allowbackup, $domain_dns, $domain_expires, $domain_new_expire,
		$domain_software_allowed;

	$datepicker = (isset($_POST['dmn_expire_date']))
		? clean_input($_POST['dmn_expire_date']) : ''; //Fix PHP NOTICE display
	$domain_new_expire = (isset($_POST['dmn_expire']))
		? clean_input($_POST['dmn_expire']) : ''; //Fix PHP NOTICE display
	$sub = clean_input($_POST['dom_sub']);
	$als = clean_input($_POST['dom_alias']);
	$mail = clean_input($_POST['dom_mail_acCount']);
	$ftp = clean_input($_POST['dom_ftp_acCounts']);
	$sql_db = clean_input($_POST['dom_sqldb']);
	$sql_user = clean_input($_POST['dom_sql_users']);
	$traff = clean_input($_POST['dom_traffic']);
	$disk = clean_input($_POST['dom_disk']);

	$domain_php = preg_replace("/\_/", '', $_POST['domain_php']);
	$domain_cgi = preg_replace("/\_/", '', $_POST['domain_cgi']);
	$domain_dns = preg_replace("/\_/", '', $_POST['domain_dns']);
	$allowbackup = preg_replace("/\_/", '', $_POST['backup']);
	$domain_software_allowed = preg_replace("/\_/", '', $_POST['domain_software_allowed']);

	list($rsub_max, $rals_max, $rmail_max, $rftp_max, $rsql_db_max, $rsql_user_max) = check_reseller_permissions(
		$_SESSION['user_id'], 'all_permissions'
	);

	if ($rsub_max == "-1") {
		$sub = "-1";
	} elseif (!imscp_limit_check($sub, -1)) {
		set_page_message(tr('Incorrect subdomains limit.'), 'error');
	}

	if ($rals_max == "-1") {
		$als = "-1";
	} elseif (!imscp_limit_check($als, -1)) {
		set_page_message(tr('Incorrect aliases limit.'), 'error');
	}

	if ($rmail_max == "-1") {
		$mail = "-1";
	} elseif (!imscp_limit_check($mail, -1)) {
		set_page_message(tr('Incorrect mail accounts limit.'), 'error');
	}

	if ($rftp_max == "-1") {
		$ftp = "-1";
	} elseif (!imscp_limit_check($ftp, -1)) {
		set_page_message(tr('Incorrect FTP accounts limit.'), 'error');
	}

	if ($rsql_db_max == "-1") {
		$sql_db = "-1";
	} elseif (!imscp_limit_check($sql_db, -1)) {
		set_page_message(tr('Incorrect SQL users limit.'), 'error');
	} elseif ($sql_db == -1 && $sql_user != -1) {
		set_page_message(tr('SQL databases limit is <i>disabled</i>.'), 'error');
	}

	if ($rsql_user_max == "-1") {
		$sql_user = "-1";
	} elseif (!imscp_limit_check($sql_user, -1)) {
		set_page_message(tr('Incorrect SQL databases limit.'), 'error');
	} elseif ($sql_user == -1 && $sql_db != -1) {
		set_page_message(tr('SQL users limit is <i>disabled</i>.'), 'error');
	}

	if (!imscp_limit_check($traff, null)) {
		set_page_message(tr('Incorrect traffic limit.'), 'error');
	}

	if (!imscp_limit_check($disk, null)) {
		set_page_message(tr('Incorrect disk quota limit.'), 'error');
	}

	if ($domain_php == "no" && $domain_software_allowed == "yes") {
		set_page_message(tr('The i-MSCP application installer needs PHP to enable it.'), 'error');
	}

	list(
		$usub_current, $usub_max, $uals_current, $uals_max, $umail_current, $umail_max, $uftp_current, $uftp_max,
		$usql_db_current, $usql_db_max, $usql_user_current, $usql_user_max, $utraff_max, $udisk_max
		) = generate_user_props($user_id);

	$previous_utraff_max = $utraff_max;

	list(
		$rdmn_current, $rdmn_max, $rsub_current, $rsub_max, $rals_current, $rals_max, $rmail_current, $rmail_max,
		$rftp_current, $rftp_max, $rsql_db_current, $rsql_db_max, $rsql_user_current, $rsql_user_max, $rtraff_current,
		$rtraff_max, $rdisk_current, $rdisk_max
		) = get_reseller_default_props($reseller_id);

	list(, , , , , , $utraff_current, $udisk_current) = generate_user_traffic($user_id);

	if (!Zend_Session::namespaceIsset('pageMessages')) {
		calculate_user_dvals($sub, $usub_current, $usub_max, $rsub_current, $rsub_max, tr('Subdomain'));
		calculate_user_dvals($als, $uals_current, $uals_max, $rals_current, $rals_max, tr('Alias'));
		calculate_user_dvals($mail, $umail_current, $umail_max, $rmail_current, $rmail_max, tr('Mail'));
		calculate_user_dvals($ftp, $uftp_current, $uftp_max, $rftp_current, $rftp_max, tr('FTP'));
		calculate_user_dvals($sql_db, $usql_db_current, $usql_db_max, $rsql_db_current, $rsql_db_max, tr('SQL Database'));
	}

	if (!Zend_Session::namespaceIsset('pageMessages')) {
		$query = "
			SELECT
				COUNT(su.`sqlu_id`) AS cnt
			FROM
				`sql_user` AS su,
				`sql_database` AS sd
			WHERE
				su.`sqld_id` = sd.`sqld_id`
			AND
				sd.`domain_id` = ?
		";

		$rs = exec_query($query, $_SESSION['edit_id']);
		calculate_user_dvals($sql_user, $rs->fields['cnt'], $usql_user_max, $rsql_user_current, $rsql_user_max, tr('SQL User'));
	}

	if (!Zend_Session::namespaceIsset('pageMessages')) {
		calculate_user_dvals(
			$traff, $utraff_current / 1024 / 1024, $utraff_max, $rtraff_current, $rtraff_max, tr('Traffic')
		);

		calculate_user_dvals(
			$disk, $udisk_current / 1024 / 1024, $udisk_max, $rdisk_current, $rdisk_max, tr('Disk')
		);
	}
	//phpini check and safe into db
	if ($phpini->checkRePerm('phpiniSystem')) {
		$phpini->setData('phpiniSystem', $_POST['phpini_system']);
		$phpini->setClPerm('phpiniSystem', $_POST['phpini_al_system']);

		if ($phpini->checkRePerm('phpiniRegisterGlobals') && isset($_POST['phpini_register_globals'])) {
			$phpini->setData('phpiniRegisterGlobals', clean_input($_POST['phpini_register_globals']));
		}

		if ($phpini->checkRePerm('phpiniAllowUrlFopen') && isset($_POST['phpini_allow_url_fopen'])) {
			$phpini->setData('phpiniAllowUrlFopen', clean_input($_POST['phpini_allow_url_fopen']));
		}

		if ($phpini->checkRePerm('phpiniDisplayErrors') && isset($_POST['phpini_display_errors'])) {
			$phpini->setData('phpiniDisplayErrors', clean_input($_POST['phpini_display_errors']));
		}

		if ($phpini->checkRePerm('phpiniDisplayErrors') && isset($_POST['phpini_error_reporting'])) {
			$phpini->setData('phpiniErrorReporting', clean_input($_POST['phpini_error_reporting']));
		}

		if ($phpini->checkRePerm('phpiniRegisterGlobals') && isset($_POST['phpini_al_register_globals'])) {
			$phpini->setClPerm('phpiniRegisterGlobals', clean_input($_POST['phpini_al_register_globals']));
		}

		if ($phpini->checkRePerm('phpiniAllowUrlFopen') && isset($_POST['phpini_al_allow_url_fopen'])) {
			$phpini->setClPerm('phpiniAllowUrlFopen', clean_input($_POST['phpini_al_allow_url_fopen']));
		}

		if ($phpini->checkRePerm('phpiniDisplayErrors') && isset($_POST['phpini_al_display_errors'])) {
			$phpini->setClPerm('phpiniDisplayErrors', clean_input($_POST['phpini_al_display_errors']));
		}

		if ($phpini->checkRePerm('phpiniDisableFunctions') && isset($_POST['phpini_al_disable_functions'])) {
			$phpini->setClPerm('phpiniDisableFunctions', clean_input($_POST['phpini_al_disable_functions']));
		}

		if ($_POST['phpini_system'] == 'yes') { // Only check if Custom php.ini is on Yes (prevent error message if unlikely paramater match)
			if (isset($_POST['phpini_post_max_size']) && (!$phpini->setDataWithPermCheck('phpiniPostMaxSize', $_POST['phpini_post_max_size']))) {
				set_page_message(tr('post_max_size out of range.'), 'error');
			}

			if (isset($_POST['phpini_upload_max_filesize']) && (!$phpini->setDataWithPermCheck('phpiniUploadMaxFileSize', $_POST['phpini_upload_max_filesize']))) {
				set_page_message(tr('upload_max_filesize out of range.'), 'error');
			}

			if (isset($_POST['phpini_max_execution_time']) && (!$phpini->setDataWithPermCheck('phpiniMaxExecutionTime', $_POST['phpini_max_execution_time']))) {
				set_page_message(tr('max_execution_time out of range.'), 'error');
			}

			if (isset($_POST['phpini_max_input_time']) && (!$phpini->setDataWithPermCheck('phpiniMaxInputTime', $_POST['phpini_max_input_time']))) {
				set_page_message(tr('max_input_time out of range.'), 'error');
			}

			if (isset($_POST['phpini_memory_limit']) && (!$phpini->setDataWithPermCheck('phpiniMemoryLimit', $_POST['phpini_memory_limit']))) {
				set_page_message(tr('memory_limit out of range.'), 'error');
			}	
		}

		// collect all parts of disabled_function from $_POST
		$mytmp = array();

		foreach ($_POST as $key => $value) {
			if (substr($key, 0, 10) == "phpini_df_") {
				array_push($mytmp, clean_input($value));
			}
		}

		if (!$phpini->setDataWithPermCheck('phpiniDisableFunctions', $phpini->assembleDisableFunctions($mytmp))) {
			set_page_message(tr('disable_functions error.'), 'error');
		}

		// if all OK Update data in php_ini table and client perm in domain table
		if (!Zend_Session::namespaceIsset('pageMessages')) {
			if ($phpini->getDataVal('phpiniSystem') == 'yes') {
				$phpini->saveCustomPHPiniIntoDb($_SESSION['edit_id']);
			} else {
				$phpini->delCustomPHPiniFromDb($_SESSION['edit_id']);
			}

			$phpini->saveClPermIntoDb($_SESSION['edit_id']);
			$phpini->sendToEngine($_SESSION['edit_id']);
		}

	} else { //if no permission at all - do nothing with the saved phpini data but load the default vars
		$phpini->loadDefaultData();
	}

	if (!Zend_Session::namespaceIsset('pageMessages')) {
		// Set domains status to 'change' to update mod_cband's limit
		if ($previous_utraff_max != $utraff_max) {
			$query = "UPDATE `domain` SET `domain_status` = 'change' WHERE `domain_id` = ?";
			exec_query($query, $user_id);

			$query = "UPDATE `subdomain` SET `subdomain_status` = 'change' WHERE `domain_id` = ?";
			exec_query($query, $user_id);

			// Send request to the daemon for backend process
			send_request();
		}

		$user_props = "$usub_current;$usub_max;";
		$user_props .= "$uals_current;$uals_max;";
		$user_props .= "$umail_current;$umail_max;";
		$user_props .= "$uftp_current;$uftp_max;";
		$user_props .= "$usql_db_current;$usql_db_max;";
		$user_props .= "$usql_user_current;$usql_user_max;";
		$user_props .= "$utraff_max;";
		$user_props .= "$udisk_max;";

		// $user_props .= "$domain_ip;";
		$user_props .= "$domain_php;";
		$user_props .= "$domain_cgi;";
		$user_props .= "$allowbackup;";
		$user_props .= "$domain_dns;";
		$user_props .= "$domain_software_allowed";
		update_user_props($user_id, $user_props);


		// Date-Picker domain expire update
		if ($_POST['neverexpire'] != "on") {
			$domain_expires = datepicker_reseller_convert($datepicker);
		} else {
			$domain_expires = "0";
		}

		update_expire_date($user_id, $domain_expires);

		$reseller_props = "$rdmn_current;$rdmn_max;";
		$reseller_props .= "$rsub_current;$rsub_max;";
		$reseller_props .= "$rals_current;$rals_max;";
		$reseller_props .= "$rmail_current;$rmail_max;";
		$reseller_props .= "$rftp_current;$rftp_max;";
		$reseller_props .= "$rsql_db_current;$rsql_db_max;";
		$reseller_props .= "$rsql_user_current;$rsql_user_max;";
		$reseller_props .= "$rtraff_current;$rtraff_max;";
		$reseller_props .= "$rdisk_current;$rdisk_max";

		if (!update_reseller_props($reseller_id, $reseller_props)) {
			set_page_message(tr('Domain properties could not be updated.'), 'error');

			return false;
		}

		// Backup Settings
		$query = "UPDATE `domain` SET `allowbackup` = ? WHERE `domain_id` = ?";
		exec_query($query, array($allowbackup, $user_id));

		// update the sql quotas, too
		$query = "SELECT `domain_name` FROM `domain` WHERE `domain_id` = ?";
		$rs = exec_query($query, $user_id);
		$temp_dmn_name = $rs->fields['domain_name'];

		$query = "SELECT COUNT(`name`) AS cnt FROM `quotalimits` WHERE `name` = ?";
		$rs = exec_query($query, $temp_dmn_name);

		if ($rs->fields['cnt'] > 0) {
			// we need to update it
			if ($disk == 0) {
				$dlim = 0;
			} else {
				$dlim = $disk * 1024 * 1024;
			}

			$query = "UPDATE `quotalimits` SET `bytes_in_avail` = ? WHERE `name` = ?";
			exec_query($query, array($dlim, $temp_dmn_name));
		}

		set_page_message(tr('Domain properties successfully updated.'), 'success');

		return true;
	} else {
		return false;
	}
}

/**
 * Must be documented.
 *
 * @throws iMSCP_Exception
 * @param  $data
 * @param  $u
 * @param  $umax
 * @param  $r
 * @param  $rmax
 * @param  $obj
 * @return void
 */
function calculate_user_dvals($data, $u, &$umax, &$r, $rmax, $obj)
{
	if ($rmax == -1 && $umax >= 0) {
		if ($u > 0) {
			set_page_message(tr('The <em>%s</em> service cannot be disabled.', $obj) . tr('There are <em>%s</em> records on system.', $obj), 'error');
			return;
		} else if ($data != -1) {
			set_page_message(tr('The <em>%s</em> have to be disabled.', $obj) . tr('The admin has <em>%s</em> disabled on this system.', $obj), 'error');
			return;
		} else {
			$umax = $data;
		}
		return;
	} else if ($rmax == 0 && $umax == -1) {
		if ($data == -1) {
			return;
		} else if ($data == 0) {
			$umax = $data;
			return;
		} else if ($data > 0) {
			$umax = $data;
			$r += $umax;
			return;
		}
	} else if ($rmax == 0 && $umax == 0) {
		if ($data == -1) {
			if ($u > 0) {
				set_page_message(tr('The <em>%s</em> service cannot be disabled.', $obj) . tr('There are <em>%s</em> records on system.', $obj), 'error');
			} else {
				$umax = $data;
			}

			return;
		} else if ($data == 0) {
			return;
		} else if ($data > 0) {
			if ($u > $data) {
				set_page_message(tr('The <em>%s</em> service cannot be limited.', $obj) . tr('Specified number is smaller than <em>%s</em> records, present on the system.', $obj), 'error');
			} else {
				$umax = $data;
				$r += $umax;
			}
			return;
		}
	} else if ($rmax == 0 && $umax > 0) {
		if ($data == -1) {
			if ($u > 0) {
				set_page_message(tr('The <em>%s</em> service cannot be disabled.', $obj) . tr('There are <em>%s</em> records on the system.', $obj), 'error');
			} else {
				$r -= $umax;
				$umax = $data;
			}
			return;
		} else if ($data == 0) {
			$r -= $umax;
			$umax = $data;
			return;
		} else if ($data > 0) {
			if ($u > $data) {
				set_page_message(tr('The <em>%s</em> service cannot be limited.', $obj) . tr('Specified number is smaller than <em>%s</em> records, present on the system.', $obj), 'error');
			} else {
				if ($umax > $data) {
					$data_dec = $umax - $data;
					$r -= $data_dec;
				} else {
					$data_inc = $data - $umax;
					$r += $data_inc;
				}
				$umax = $data;
			}
			return;
		}
	} else if ($rmax > 0 && $umax == -1) {
		if ($data == -1) {
			return;
		} else if ($data == 0) {
			set_page_message(tr('The <em>%s</em> service cannot be unlimited.', $obj) . tr('There are reseller limits for the <em>%s</em> service.', $obj), 'error');
			return;
		} else if ($data > 0) {
			if ($r + $data > $rmax) {
				set_page_message(tr('The <em>%s</em> service cannot be limited.', $obj) . tr('You are exceeding reseller limits for the <em>%s</em> service.', $obj), 'error');
			} else {
				$r += $data;

				$umax = $data;
			}

			return;
		}
	} else if ($rmax > 0 && $umax == 0) {
		throw new iMSCP_Exception("FIXME: " . __FILE__ . ":" . __LINE__);
	} else if ($rmax > 0 && $umax > 0) {
		if ($data == -1) {
			if ($u > 0) {
				set_page_message(tr('The <em>%s</em> service cannot be disabled.', $obj) . tr('There are <em>%s</em> records on the system.', $obj), 'error');
			} else {
				$r -= $umax;
				$umax = $data;
			}

			return;
		} else if ($data == 0) {
			set_page_message(tr('The <em>%s</em> service cannot be unlimited.', $obj) . tr('There are reseller limits for the <em>%s</em> service.', $obj), 'error');

			return;
		} else if ($data > 0) {
			if ($u > $data) {
				set_page_message(tr('The <em>%s</em> service cannot be limited.', $obj) . tr('Specified number is smaller than <em>%s</em> records, present on the system.', $obj), 'error');
			} else {
				if ($umax > $data) {
					$data_dec = $umax - $data;
					$r -= $data_dec;
				} else {
					$data_inc = $data - $umax;

					if ($r + $data_inc > $rmax) {
						set_page_message(tr('The <em>%s</em> service cannot be limited.', $obj) . tr('You are exceeding reseller limits for the <em>%s</em> service.', $obj), 'error');
						return;
					}

					$r += $data_inc;
				}

				$umax = $data;
			}

			return;
		}
	}
}

$tpl->parse('PAGE', 'page');

iMSCP_Events_Manager::getInstance()->dispatch(
	iMSCP_Events::onResellerScriptEnd, new iMSCP_Events_Response($tpl));

$tpl->prnt();

unsetMessages();
