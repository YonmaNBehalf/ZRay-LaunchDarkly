<?php

namespace LaunchDarkly\ZRayModule;

class Module extends \ZRay\ZRayModule {

	public function config() {
        return array(
            // The name defined in ZRayExtension
            'extension' => array(
                'name' => 'Requests',
            ),
            // Prevent those default panels from being displayed
            'defaultPanels' => array(
//                'Requests' => false,
            ),
            // configure all custom panels
            'panels' => array(
//                'unirestApiTable' => array(
//                    'display'           => true,
//                    'menuTitle'         => 'UniRest Requests',
//                    'panelTitle'        => 'UniRest Requests',
//                    'searchId'          => 'unirest-table-search',
//                    'pagerId'           => 'unirest-table-pager',
//                )
            )
        );
    }
	
	
	
}
