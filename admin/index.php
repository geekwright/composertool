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

require __DIR__ . '/admin_header.php';

$admin = new \Xoops\Module\Admin();
$admin->displayNavigation('index.php');

$admin->addInfoBox('Composer Statistics');
$path = ComposerLocator::getRootPath();
$admin->addInfoBoxLine(sprintf('RootPath: %s', $path));
$packages = ComposerLocator::getPackages();
$admin->addInfoBoxLine(sprintf('%0d packages', count($packages)));
\Xmf\Debug::log(ComposerLocator::getPackages());

$max_time = ini_get('max_execution_time');
$admin->addConfigBoxLine(
    sprintf('For full function, max_execution_time should be at least 120. (Currently %d)', $max_time),
    ($max_time < 120) ? 'warning' : 'accept'

);

$admin->displayIndex();

$xoops->footer();
