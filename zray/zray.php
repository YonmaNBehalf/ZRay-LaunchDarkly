<?php
$zray = new ZrayExtension('launchdarkly');

$zre->setEnabledAfter('Unirest::request');

$zre->traceFunction('Unirest::request', function($context, &$storage){}, function($context, &$storage){

});