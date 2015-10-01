<?php

namespace LaunchDarkly\ZRayModule;

class Module extends \ZRay\ZRayModule {

	public function config() {
        return array(
            // The name defined in ZRayExtension
            'extension' => array(
                'name' => 'launchdarkly',
            ),
            // Prevent those default panels from being displayed
            'defaultPanels' => array(
            ),
            // configure all custom panels
            'panels' => array(
            )
        );
    }
	
	
	
}
