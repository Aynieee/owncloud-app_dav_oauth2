<?php
/**
 * ownCloud - dav_oauth2
 *
 * Script for starting the WebDAV server with OAuth 2.0 authentication backend.
 * Adapted from: apps/dav/appinfo/v1/webdav.php
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Jonathan Neugebauer
 * @copyright Jonathan Neugebauer 2016
 */

use OCA\DavOAuth2\OAuth2;

// no php execution timeout for webdav
set_time_limit(0);

// Turn off output buffering to prevent memory problems
\OC_Util::obEnd();

$serverFactory = new \OCA\DAV\Connector\Sabre\ServerFactory(
    \OC::$server->getConfig(),
    \OC::$server->getLogger(),
    \OC::$server->getDatabaseConnection(),
    \OC::$server->getUserSession(),
    \OC::$server->getMountManager(),
    \OC::$server->getTagManager(),
    \OC::$server->getRequest()
);

// Backends
$authBackend = new OAuth2();
$requestUri = \OC::$server->getRequest()->getRequestUri();

$server = $serverFactory->createServer($baseuri, $requestUri, $authBackend, function () {
    // use the view for the logged in user
    return \OC\Files\Filesystem::getView();
});

// And off we go!
$server->exec();
