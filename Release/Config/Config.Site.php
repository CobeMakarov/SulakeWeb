<?php
/*
 * @project SulakeWEB Release 2
 * @author Cobe Makarov
 * @description
 *
 */

//SITE CONFIGURATION

/*
 * What url is the framework running from?
 */
$Config['Site']['Path'] = 'localhost';

/*
 * What directory are we in?
 * root - We aren't in a directory at all!
 */
$Config['Site']['Directory'] = 'root';

/*
 * What's the web master's e-mail?
 */
$Config['Site']['Webmaster'] = 'makarov@ragezone.com';

/*
 * What is the title of the web site?
 */
$Config['Site']['Title'] = 'Swif.ME';

/*
 * What style are we using!
 */
$Config['Site']['Theme'] = 'Habbo';

/*
 * What environment shall we use?
 * 0 - No errors shown.
 * 1 - Some errors shown.
 * 2 - All errors shown.
 */
$Config['Site']['Environment'] = 2;

/*
 * What is the website desired timezone
 */
$Config['Site']['Timezone'] = 'America/New_York';

/*
 * What hashing method do you prefer?
 * md5
 * sha1
 * whirlpool
 * sha256
 */
$Config['Site']['Hash'] = 'md5';

/*
 * Are you using cloudflare?
 */
$Config['Site']['Cloudfare'] = false;
?>
