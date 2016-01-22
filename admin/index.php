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
 * @copyright 2015-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    Richard Griffith <richard@geekwright.com>
 */

require __DIR__ . '/admin_header.php';

$indexAdmin = new \Xoops\Module\Admin();
$indexAdmin->displayNavigation('index.php');
$max_time = ini_get('max_execution_time');
$indexAdmin->addConfigBoxLine(
    sprintf('For full function, max_execution_time should be at least 120. (Currently %d)', $max_time),
    ($max_time < 120) ? 'warning' : 'accept'

);
//Admin::checkModuleVersion('xmf', 100);
$indexAdmin->displayIndex();

$xoops->footer();
