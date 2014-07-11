<?php

return array(
    'rv-base' => array(
        'view-manager' => array(
            'enabled' => true,
        ),

        'dispatch-listener' => array(
            'enabled' => true,
        ),

        'acl' => array(
            'default_role' => 'current_user',
            'default_acl' => 'acl',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'rv-base.view-manager' => 'RvBase\Mvc\Service\ViewManagerFactory',
            'rv-base.http-view-manager' => 'RvBase\Mvc\Service\HttpViewManagerFactory',
            'rv-base.dispatch-listener' => 'RvBase\Mvc\Service\DispatchListenerFactory',
        ),
    ),
);
