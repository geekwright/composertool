<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xmf\Request;
use Xoops\Core\ComposerUtility;

/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author          Richard Griffith <richard@geekwright.com>
 */

require dirname(__FILE__) . '/admin_header.php';

$xoops = Xoops::getInstance();
$xoops->header();
$security = $xoops->security();

$modAdmin = new \Xoops\Module\Admin();
$modAdmin->displayNavigation('composertool.php');

$commands = array(
    'selfupd'  => array('cmd' => 'selfupdate', 'args' => null, 'name' => 'Update composer.phar'),
    'update'   => array('cmd' => 'update', 'args' => 'optpkg', 'name' => 'Update'),
    'autoload' => array('cmd' => 'dumpautoload --optimize', 'args' => null, 'name' => 'Optimize autoloader'),
    'showself' => array('cmd' => 'show --self', 'args' => null, 'name' => 'Show base package'),
    'showinst' => array('cmd' => 'show --installed', 'args' => null, 'name' => 'Show installed packages'),
    'depends'  => array('cmd' => 'depends', 'args' => 'pkg', 'name' => 'Show package dependencies'),
    'showlic'  => array('cmd' => 'licenses', 'args' => null, 'name' => 'Show package licences'),
    'status'   => array('cmd' => 'status -v', 'args' => null, 'name' => 'Show modified packages'),
    'version'  => array('cmd' => 'show --version', 'args' => null, 'name' => 'Show Version'),
    'require'  => array('cmd' => 'require', 'args' => 'pkgver', 'name' => 'Add package'),
    'search'   => array('cmd' => 'search', 'args' => 'pkg', 'name' => 'Search packages'),
    'validate' => array('cmd' => 'validate', 'args' => null, 'name' => 'Validate composer.json'),
    'diagnose' => array('cmd' => 'diagnose', 'args' => null, 'name' => 'Diagnose composer issues'),
);

$basicgroup = array(
    'update',
    'autoload',
    'selfupd',
);
$showgroup = array(
    'showself',
    'showinst',
    'depends',
    'showlic',
    'status',
    'version',
);
$utilgroup = array(
    'require',
    'search',
    'validate',
    'diagnose',
);

/**
 * getOptArray - build option arrays from command list given list of keys to include
 *
 * @param array $keys     keys to include
 * @param array $commands command array
 *
 * @return array
 */
function getOptArray($keys, $commands)
{
    $optArray = array();
    foreach ($keys as $k) {
        $optArray[$k] = $commands[$k]['name'];
    }
    return $optArray;
}

$basicOpt = getOptArray($basicgroup, $commands);
$showOpt = getOptArray($showgroup, $commands);
$utilOpt = getOptArray($utilgroup, $commands);

$method = Request::getMethod();

$form = new XoopsThemeForm('', 'composer', '', 'post', true, 'horizontal');

$select_optgroup = new XoopsFormSelect('Composer Command', 'composer_command', '', 1, false);
$select_optgroup->addOptgroup('Basics', getOptArray($basicgroup, $commands));
$select_optgroup->addOptgroup('Informational', getOptArray($showgroup, $commands));
$select_optgroup->addOptgroup('Utility', getOptArray($utilgroup, $commands));
//$select_optgroup->setDescription('Description Select Optgroup');
$select_optgroup->setClass('span3');
$form->addElement($select_optgroup, true);

$testtray = new XoopsFormElementTray('Package');

$pkg = new XoopsFormText('', 'package', 3, 128, '', 'vendor/package');
$pkg->setDescription('Description code');
//$pkg->setPattern('^.{3,}$', 'You need at least 3 characters');
//$pkg->setDatalist(array('list 1','list 2','list 3'));
$testtray ->addElement($pkg);

$ver = new XoopsFormText('Version', 'version', 1, 50, '', 'version');
$ver->setDescription('Description code');
//$ver->setPattern('^.{3,}$', 'You need at least 3 characters');
//$ver->setDatalist(array('list 1','list 2','list 3'));
$testtray ->addElement($ver);

$form->addElement($testtray);

$button = new XoopsFormButton('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
$form->addElement($button);

$form->display();

if ($method == 'POST') {
    $secResult = $security->check();
    if ($secResult) {
        $composer = new ComposerUtility();

        $composer_command = empty($_POST['composer_command']) ? '' : $_POST['composer_command'];

        $cmd = empty($commands[$composer_command])
            ? array('cmd' => '', 'args' => null, 'name' => 'Dummy')
            : $commands[$composer_command];

        $package = empty($_POST['package']) ? '' : $_POST['package'];
        $version = empty($_POST['version']) ? '' : $_POST['version'];
        $skipProcess = false;
        switch ($cmd['args']) {
            case 'pkg':
                $args = trim($package);
                if (empty($package)) {
                    echo $xoops->alert('warning', 'Package is required for this command', 'Warning');
                    $skipProcess = true;
                }
                break;
            case 'pkgver':
                $args = trim($package .' ' . $version);
                if (empty($package) || empty($version)) {
                    echo $xoops->alert('warning', 'Package and Version are required for this command', 'Warning');
                    $skipProcess = true;
                }
                break;
            case 'optpkg':
                $args = trim($package);
                break;
            default:
                $args = '';
                if (!empty($package) || !empty($version)) {
                    echo $xoops->alert('warning', 'Package and Version are not used for this command', 'Warning');
                }
                break;
        }

        if (false == $skipProcess) {
            $command = '';
            $command .= empty($cmd['cmd']) ? '' : $cmd['cmd'];
            $command .= empty($args) ? '' : ' ' . $args;

            if ($composer->composerExecute($command)) {
                echo $xoops->alert('success', $command, 'Success');
            } else {
                $errors = $composer->getLastError();
                echo $xoops->alert('error', $errors, 'Command Failed');
            }
            $output = $composer->getLastOutput();
            if (!empty($output)) {
                echo '<pre>';
                foreach ($output as $k => $v) {
                    $o = preg_replace('/ +/', ' ', str_replace("\x08", '', $v));
                    if (empty($o)) {
                        echo "\n";
                    } else {
                        echo $o;
                    }
                }
                echo '</pre>';
            }
        }
    } else {
        echo $xoops->alert('error', 'Invalid or missing security token', 'Command Failed');
    }
}

$xoops->footer();
