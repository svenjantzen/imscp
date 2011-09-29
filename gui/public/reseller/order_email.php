<?php
/**
 * i-MSCP a internet Multi Server Control Panel
 *
 * @copyright 	2001-2006 by moleSoftware GmbH
 * @copyright 	2006-2010 by ispCP | http://isp-control.net
 * @copyright 	2010 by i-msCP | http://i-mscp.net
 * @version 	SVN: $Id$
 * @link 		http://i-mscp.net
 * @author 		ispCP Team
 * @author 		i-MSCP Team
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
 * Portions created by the ispCP Team are Copyright (C) 2006-2010 by
 * isp Control Panel. All Rights Reserved.
 * Portions created by the i-MSCP Team are Copyright (C) 2010 by
 * i-MSCP a internet Multi Server Control Panel. All Rights Reserved.
 */

require 'imscp-lib.php';

iMSCP_Events_Manager::getInstance()->dispatch(iMSCP_Events::onResellerScriptStart);

/************************************************************************************
 * Main script
 */
check_login(__FILE__);

/** @var $cfg iMSCP_Config_Handler_File */
$cfg = iMSCP_Registry::get('config');

$tpl = new iMSCP_pTemplate();
$tpl->define_dynamic('page', $cfg->RESELLER_TEMPLATE_PATH . '/order_email.tpl');
$tpl->define_dynamic('page_message', 'page');
$tpl->define_dynamic('logged_from', 'page');

$user_id = $_SESSION['user_id'];
$data = get_order_email($user_id);

if (isset($_POST['uaction']) && $_POST['uaction'] == 'order_email') {
	$data['subject'] = clean_input($_POST['auto_subject']);
	$data['message'] = clean_input($_POST['auto_message']);

	if ($data['subject'] == '') {
		set_page_message(tr('You must specify a subject.'), 'error');
	} else if ($data['message'] == '') {
		set_page_message(tr('You must specify a message.'), 'error');
	} else {
		set_order_email($user_id, $data);
		set_page_message(tr('Template for Auto email successfully updated.'), 'success');
	}
}

$tpl->assign(array(
                  'TR_RESELLER_ORDER_EMAL' => tr('i-MSCP - Reseller/Order email setup'),
                  'THEME_COLOR_PATH' => "../themes/{$cfg->USER_INITIAL_THEME}",
                  'THEME_CHARSET' => tr('encoding'),
                  'ISP_LOGO' => layout_getUserLogo()));

gen_reseller_mainmenu($tpl, $cfg->RESELLER_TEMPLATE_PATH . '/main_menu_orders.tpl');
gen_reseller_menu($tpl, $cfg->RESELLER_TEMPLATE_PATH . '/menu_orders.tpl');
gen_logged_from($tpl);

$tpl->assign(array(
                  'TR_EMAIL_SETUP' => tr('Email setup'),
                  'TR_MANAGE_ORDERS' => tr('Manage orders'),
                  'TR_MESSAGE_TEMPLATE_INFO' => tr('Message template info'),
                  'TR_USER_LOGIN_NAME' => tr('User login (system) name'),
                  'TR_USER_DOMAIN' => tr('Domain name'),
                  'TR_USER_REAL_NAME' => tr('User (first and last) name'),
                  'TR_ACTIVATION_LINK' => tr('Activation Link'),
                  'TR_MESSAGE_TEMPLATE' => tr('Message template'),
                  'TR_SUBJECT' => tr('Subject'),
                  'TR_MESSAGE' => tr('Message'),
                  'TR_SENDER_EMAIL' => tr('Senders email'),
                  'TR_SENDER_NAME' => tr('Senders name'),
                  'TR_APPLY_CHANGES' => tr('Apply changes'),
                  'SUBJECT_VALUE' => tohtml($data['subject']),
                  'MESSAGE_VALUE' => tohtml($data['message']),
                  'SENDER_EMAIL_VALUE' => tohtml($data['sender_email']),
                  'SENDER_NAME_VALUE' => tohtml(!empty($data['sender_name'])
                        ? $data['sender_name'] : tr('Unknown'))));

generatePageMessage($tpl);

$tpl->parse('PAGE', 'page');

iMSCP_Events_Manager::getInstance()->dispatch(
    iMSCP_Events::onResellerScriptEnd, new iMSCP_Events_Response($tpl));

$tpl->prnt();

unsetMessages();
