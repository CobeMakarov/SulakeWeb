<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */
ob_start();
session_start();

/*
 * Include our main handler
 */
include ('Manhattan.php');

/*
 * Initialize our main handler
 */
$Manhattan = new Manhattan();


/*
 * Override REMOTE_ADDR
 */
if ($Manhattan->Config['Site']['Cloudflare'])
{
     $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
}

/*
 * Define some MISC. stuff
 */
define('LB', chr(13));
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('IP_ADDRESS', ($_SERVER['REMOTE_ADDR'] == '::1') ? '127.0.0.1' : $_SERVER['REMOTE_ADDR']);


define('AUTHENICATED', isset($_SESSION['account']['master_email']));
define('ACTIVATED', isset($_SESSION['habbo']['username']));

/*
 * Fill our path information array and definitions
 */
$PageInformation = array(
    'URL' => $Manhattan->Config['Site']['Path'],
    'DIRECTORY' => $Manhattan->Config['Site']['Directory'],
    'FULL_PATH' => ($Manhattan->Config['Site']['Directory'] == 'root') ? $Manhattan->Config['Site']['Path'] : $Manhattan->Config['Site']['Path'] . '/' . $Manhattan->Config['Site']['Directory'],
    'FORMATTED' => ($Manhattan->Config['Site']['Directory'] == 'root') ? '/' : '/' . $Manhattan->Config['Site']['Directory'] . '/',
    'THEME' => $Manhattan->Config['Site']['Theme'],
    'WEBMASTER' => $Manhattan->Config['Site']['Webmaster']);

foreach($PageInformation as $Key => $Value)
{
    define($Key, $Value);
}
?>
