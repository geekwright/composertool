<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Geekwright\ComposerTool\ComposerUtility;
use Xmf\Request;
use Xmf\Module\Helper;
use Xoops\Module\Admin;

/**
 * @copyright 2015-2019 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author    Richard Griffith <richard@geekwright.com>
 */

require __DIR__ . '/admin_header.php';

$xoops = Xoops::getInstance();
$xoops->header();
$security = $xoops->security();

$modAdmin = new Admin();
$modAdmin->displayNavigation('composertool.php');

$dir = basename(dirname(__DIR__));
$helper = Helper::getHelper($dir);

$commands = [
    'selfupd'  => ['cmd' => 'selfupdate', 'args' => null, 'name' => 'Self update composer'],
    'update'   => ['cmd' => '--no-progress update', 'args' => 'optpkg', 'name' => 'Update'],
    'autoload' => ['cmd' => 'dumpautoload --optimize', 'args' => null, 'name' => 'Optimize autoloader'],
    'showself' => ['cmd' => 'show --self', 'args' => null, 'name' => 'Show base package'],
    'showinst' => ['cmd' => 'show', 'args' => null, 'name' => 'Show installed packages'],
    'showinsd' => ['cmd' => 'show -t', 'args' => null, 'name' => 'Show installed with dependencies'],
    'depends'  => ['cmd' => 'depends', 'args' => 'pkg', 'name' => 'What depends on package'],
    'showlic'  => ['cmd' => 'licenses', 'args' => null, 'name' => 'Show package licenses'],
    'status'   => ['cmd' => 'status -v', 'args' => null, 'name' => 'Show modified packages'],
    'version'  => ['cmd' => 'show --version', 'args' => null, 'name' => 'Show version'],
    'require'  => ['cmd' => 'require', 'args' => 'pkgver', 'name' => 'Add package'],
    'remove'   => ['cmd' => 'remove', 'args' => 'pkg', 'name' => 'Remove package'],
    'search'   => ['cmd' => 'search', 'args' => 'pkg', 'name' => 'Search packages'],
    'validate' => ['cmd' => 'validate', 'args' => null, 'name' => 'Validate composer.json'],
    'diagnose' => ['cmd' => 'diagnose', 'args' => null, 'name' => 'Diagnose composer issues'],
    'showupd'  => ['cmd' => '--dry-run update', 'args' => 'optpkg', 'name' => 'Check for updates'],
    'platform' => ['cmd' => 'show --platform', 'args' => null, 'name' => 'Show platform packages'],
];

$basicgroup = [
    'showupd',
    'update',
    'autoload',
    'selfupd',
];
$showgroup = [
    'showself',
    'showinst',
    'showinsd',
    'platform',
    'depends',
    'showlic',
    'status',
    'version',
];
$utilgroup = [
    'require',
    'remove',
    'search',
    'validate',
    'diagnose',
];

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

echo <<<EOT
<script>
setTimeout(function () {
   window.location.href = "index.php";
}, 900000); //will redirect after 900 secs - security token expires
</script>
EOT;

$basicOpt = getOptArray($basicgroup, $commands);
$showOpt = getOptArray($showgroup, $commands);
$utilOpt = getOptArray($utilgroup, $commands);

$method = Request::getMethod();

$form = new Xoops\Form\ThemeForm('', 'composer', 'composertool.php', 'post', true, 'horizontal');

$selected = Request::getCmd('composer_command', '');

$select_optgroup = new Xoops\Form\Select('Composer Command', 'composer_command', $selected, 1, false);
$select_optgroup->addOptionGroup('Basics', getOptArray($basicgroup, $commands));
$select_optgroup->addOptionGroup('Informational', getOptArray($showgroup, $commands));
$select_optgroup->addOptionGroup('Utility', getOptArray($utilgroup, $commands));
//$select_optgroup->setDescription('Description Select Optgroup');
$select_optgroup->setClass('span3');
$form->addElement($select_optgroup, true);

$testtray = new Xoops\Form\ElementTray('');

$pkg = new Xoops\Form\Text('Package', 'package', 32, 128, '', 'vendor/package');
//$pkg->setDescription('Description code');
//$pkg->setPattern('^.{3,}$', 'You need at least 3 characters');
//$pkg->setDatalist(array('list 1','list 2','list 3'));
$testtray ->addElement($pkg);

$ver = new Xoops\Form\Text('Version', 'version', 16, 50, '', 'version');
//$ver->setDescription('Description code');
//$ver->setPattern('^.{3,}$', 'You need at least 3 characters');
//$ver->setDatalist(array('list 1','list 2','list 3'));
$testtray ->addElement($ver);

$form->addElement($testtray);

$button = new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
$form->addElement($button);

$form->display();

if ($method == 'POST') {
    $secResult = $security->check();
    if ($secResult) {
        $jsonPath = $helper->getConfig('composer_json_path', '');
        $composer = new ComposerUtility($jsonPath);

        $composer_command = Request::getCmd('composer_command', '', 'POST');

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
