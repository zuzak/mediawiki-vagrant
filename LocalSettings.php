<?php
/**
 * MediaWiki configuration
 *
 * To customize your MediaWiki instance, you may change the content of this
 * file. See settings.d/README for an alternate way of managing small snippets
 * of configuration data, such as extension invocations.
 *
 * This file is part of Mediawiki-Vagrant.
 */

// Enable error reporting
error_reporting( -1 );
ini_set( 'display_errors', 1 );

$wgUploadDirectory = '/srv/images';
$wgUploadPath = '/images';
$wgArticlePath = "/wiki/$1";
$wgMaxShellMemory = 1024 * 512;

// Show the debug toolbar if 'debug' is set on the request, either as a
// parameter or a cookie.
if ( !empty( $_REQUEST['debug'] ) ) {
	$wgDebugToolbar = true;
}

// Expose debug info for PHP errors.
$wgShowExceptionDetails = true;

$logDir = '/vagrant/logs';
$wgDebugLogFile = "{$logDir}/mediawiki-debug.log";
foreach ( array( 'exception', 'runJobs', 'JobQueueRedis' ) as $logGroup ) {
	$wgDebugLogGroups[$logGroup] = "{$logDir}/mediawiki-{$logGroup}.log";
}

// Calls to deprecated methods will trigger E_USER_DEPRECATED errors
// in the PHP error log.
$wgDevelopmentWarnings = true;

// Expose debug info for SQL errors.
$wgDebugDumpSql = true;
$wgShowDBErrorBacktrace = true;
$wgShowSQLErrors = true;

// Profiling
$wgDebugProfiling = false;

// Images
$wgLogo = '/mediawiki-vagrant.png';
$wgUseInstantCommons = true;
$wgEnableUploads = true;

// User settings and permissions
$wgAllowUserJs = true;

// Eligibility for autoconfirmed group
$wgAutoConfirmAge = 3600 * 24; // one day
$wgAutoConfirmCount = 5; // five edits

// Caching
$wgObjectCaches['redis'] = array(
    'class' => 'RedisBagOStuff',
    'servers' => array( '127.0.0.1:6379' ),
    'persistent' => true,
);
$wgMainCacheType = 'redis';
$wgSessionCacheType = 'redis';

// Jobqueue
$wgJobTypeConf['default'] = array(
	'class'       => 'JobQueueRedis',
	'redisServer' => '127.0.0.1',
	'redisConfig' => array( 'connectTimeout' => 2, 'compression' => 'gzip' ),
);

$wgJobQueueAggregator = array(
	'class'        => 'JobQueueAggregatorRedis',
	'redisServers' => array( '127.0.0.1' ),
	'redisConfig'  => array( 'connectTimeout' => 2 ),
);

$wgDisableCounters = true;

$wgLegacyJavaScriptGlobals = false;
$wgEnableJavaScriptTest = true;

$wgProfilerParams = array(
	'forceprofile' => 'ProfilerSimpleText',
	'forcetrace' => 'ProfilerSimpleTrace'
);

foreach( $wgProfilerParams as $param => $cls ) {
	if ( array_key_exists( $param, $_REQUEST ) ) {
		$wgProfiler['class'] = $cls;
	}
}

require_once __DIR__ . '/settings.d/wikis/CommonSettings.php';

// XXX: Is this a bug in core? (ori-l, 27-Aug-2013)
$wgHooks['GetIP'][] = function ( &$ip ) {
	if ( PHP_SAPI === 'cli' ) {
		$ip = '127.0.0.1';
	}
	return true;
};

// Execute all jobs via standalone jobrunner service rather than
// piggybacking them on web requests.
$wgJobRunRate = 0;

// Bug 73037: handmade gzipping sometimes makes error messages impossible to see in HHVM
$wgDisableOutputCompression = true;
