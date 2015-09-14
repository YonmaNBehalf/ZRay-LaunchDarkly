<?php
$zre = new ZrayExtension('launchdarkly');

$zre->setEnabledAfter('\LaunchDarkly\LDClient::__construct');

$zre->traceFunction('\LaunchDarkly\LDUser::__construct', function($context, &$storage){}, function($context, &$storage){
    $storage['LaunchDarklyUser'][] = $context['functionArgs'];
});

$zre->traceFunction('\LaunchDarkly\LDClient::__construct', function($context, &$storage){}, function($context, &$storage){
    $storage['LaunchDarklyConfig'][] = $context['functionArgs'][1];
});

$zre->traceFunction('\GuzzleHttp\Client::__construct', function($context, &$storage){}, function($context, &$storage){
    $storage['GuzzleHttpClient'][] = $context['returnValue'];
});

