<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Module\Admin;
use Xmf\FilterInput;
use Geekwright\ComposerTool\ComposerUtility;

/**
 * @copyright 2015-2019 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author    Richard Griffith <richard@geekwright.com>
 */

require __DIR__ . '/admin_header.php';

$admin = new Admin();
$admin->displayNavigation('security.php');

$checker = new \SensioLabs\Security\SecurityChecker();
$composerUtility = new ComposerUtility();
$lockFile = $composerUtility->getComposerJsonPath() . '/composer.lock';
try {
    $result = $checker->check($lockFile, 'json');
    $alerts = json_decode((string) $result, true);
    $alertCount = count($alerts);
    if ($alertCount==0) {
        echo $xoops->alert('info', 'No issues detected', 'Security Advisories Checker');
    } else {
        foreach ($alerts as $package => $alert) {
            $body = "Version: {$alert['version']}<br><br>";
            foreach ($alert['advisories'] as $adv) {
                $title = FilterInput::clean($adv['title']);
                $cve = FilterInput::clean($adv['cve']);
                $link = FilterInput::clean($adv['link']);
                $body .= "{$title}<br>"
                        . (empty($cve) ? '' : "CVE: {$cve}<br>")
                        . "<a href=\"{$link}\" rel=\"external\">{$link}</a><br><br>";
            }
            echo $xoops->alert('error', $body, $package);
        }
    }
} catch (\Throwable $e) {
    $xoops->events()->triggerEvent('core.exception', $e);
    echo $xoops->alert('error', 'SensioLabs Security Advisories Checker failed to complete.');
}

$xoops->footer();
