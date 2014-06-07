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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Richard Griffith <richard@geekwright.com>
 */

require dirname(__FILE__) . '/admin_header.php';

$indexAdmin = new \Xoops\Module\Admin();
$indexAdmin->displayNavigation('security.php');

$checker = new \SensioLabs\Security\SecurityChecker();
try {
    $alerts = $checker->check(XOOPS_PATH . '/composer.lock', 'text');
    $alertCount = $checker->getLastVulnerabilityCount();
} catch (\Exception $e) {
    $xoops->events()->triggerEvent('core.exception', $e);
    $alerts = 'SensioLabs Security Advisories Checker failed to complete.';
    $alertCount = 1;
}
echo $xoops->alert($alertCount==0?'info':'error', '<div style="white-space:pre;">'.$alerts.'</div>', 'Security Advisories Checker');

$xoops->footer();
