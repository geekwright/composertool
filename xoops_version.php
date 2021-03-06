<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright 2015-2019 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author    Richard Griffith <richard@geekwright.com>
 */

$modversion['dirname'] = basename(__DIR__);
$modversion['name'] = _MI_COMPOSERTOOL_NAME;
$modversion['version'] = '1.1.0';
$modversion['description'] = _MI_COMPOSERTOOL_DESC;
$modversion['author'] = 'Richard Griffith';
$modversion['nickname'] = 'geekwright';
$modversion['credits'] = 'The XOOPS Project';
$modversion['help'] = 'page=help';
$modversion['license'] = "GNU GPL 2 or later";
$modversion['license_url'] = 'https://www.gnu.org/licenses/gpl-2.0.html';
$modversion['official'] = 0;
$modversion['image'] = 'icons/logo.png';
$modversion['namespace'] = 'Geekwright\ComposerTool';

// About stuff
$modversion['module_status'] = 'Final';
$modversion['status'] = 'Final';
$modversion['release_date'] = '2019/05/19';

$modversion['developer_lead'] = 'geekwright';
$modversion['developer_website_url'] = 'https://xoops.org';
$modversion['developer_website_name'] = 'Xoops';
$modversion['developer_email'] = 'richard@geekwright.com';

$modversion['people']['developers'][] = 'Richard Griffith';

$modversion['min_xoops'] = '2.6.0';
$modversion['min_php'] = '7.1.0';

// Menu
$modversion['hasMain'] = 0;

// Extension
$modversion['extension'] = 1;
$modversion['extension_module'][] = 'system';

// Admin things
$modversion['hasAdmin']   = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu']  = 'admin/menu.php';

// paypal
$modversion['paypal'] = array(
    'business'      => 'xoopsfoundation@gmail.com',
    'item_name'     => 'Donation : ' . _MI_COMPOSERTOOL_DESC,
    'amount'        => 0,
    'currency_code' => 'USD',
);

$modversion['config'] = [];

$modversion['config'][] = [
    'name'        => 'composer_json_path',
    'title'       => _MI_COMPOSERTOOL_CFG_JSON_TITLE,
    'description' => _MI_COMPOSERTOOL_CFG_JSON_DESC,
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];
