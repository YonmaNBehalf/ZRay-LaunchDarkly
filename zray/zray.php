<?php

use GuzzleHttp\Message\ResponseInterface;
use GuzzleHttp\Message\Request;

class LaunchDarklyZrayExtension extends ZRayExtension {
    /**
     * @var array
     */
    public $features = array();

    /**
     * Dummy function to signal shutdown for the extension
     */
    public function eventShutdown(){}
}

$zre = new LaunchDarklyZrayExtension('launchdarkly');

register_shutdown_function(function() use ($zre) {
    $zre->eventShutdown();
});

$zre->setEnabledAfter('LaunchDarkly\LDUser::__construct');

$zre->traceFunction('LaunchDarkly\LDUser::__construct', function($context, &$storage){}, function($context, &$storage){
    $storage['UserObjects'][] = array(
        'key' => $context['functionArgs'][0],
        'secondary' => $context['functionArgs'][1],
        'ip' => $context['functionArgs'][2],
        'country' => $context['functionArgs'][3],
        'email' => $context['functionArgs'][4],
        'name' => $context['functionArgs'][5],
        'avatar' => $context['functionArgs'][6],
        'firstName' => $context['functionArgs'][7],
        'lastName' => $context['functionArgs'][8],
        'anonymous' => $context['functionArgs'][9],
        'custom' => $context['functionArgs'][10],
    );
});

$zre->traceFunction('LaunchDarkly\LDClient::__construct', function($context, &$storage){}, function($context, &$storage){
    $storage['ClientConfig'][] = array_merge($context['functionArgs'][1], array('key' => $context['functionArgs'][0]));
});

/// collect features mapping calls
$zre->traceFunction('GuzzleHttp\Client::send', function($context, &$storage){}, function($context, &$storage) use ($zre) {

    $response = $context['returnValue']; // @var $response ResponseInterface
    $request = $context['functionArgs'][0]; // @var $request Request

    if (! ($request instanceof Request)) {
        /// not a request object
        return ;
    }

    $isFeaturesCall = $request->getPath() == '/api/features';

    if (! $isFeaturesCall) {
        /// not a features list call
        return ;
    }

    if (! ($response instanceof ResponseInterface)) {
        // Not a response object
        return ;
    }

    $flags = $response->json();

    if (! isset($flags['items']) || ! is_array($flags['items'])) {
        // don't have any items or items are not an array
        return ;
    }
    $zre->features = array_map(function ($item){
        return array('toggle' => $item['key'], 'name' => $item['name'], 'on' => $item['on'] ? 'true' : 'false');
    }, $flags['items']);

});

$zre->traceFunction('LaunchDarklyZrayExtension::eventShutdown', function($context, &$storage){}, function($context, &$storage) use ($zre) {
    $storage['Features'] = $zre->features;
});
