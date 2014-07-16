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

		/*
		// time config example
		'time' => array(
			'current' => array(
				'source-service' => 'rv-base.time.current.source.db-adapter',
				'source' => array(
					'adapter' => 'Zend\Db\Adapter\Adapter',
				),
			),
			'request' => array(
				'source-service' => 'rv-base.time.request.source.current-time',
				'source' => array(
					'current-time-service' => 'rv-base.time.current',
				),
			),
		),
		*/
    ),

	'controller_plugins' => array(
		'invokables' => array(
			'notallowed' => 'RvBase\Mvc\Controller\Plugin\NotAllowed',
		),
	),

    'service_manager' => array(
        'factories' => array(
            'rv-base.view-manager' => 'RvBase\Mvc\Service\ViewManagerFactory',
            'rv-base.http-view-manager' => 'RvBase\Mvc\Service\HttpViewManagerFactory',
            'rv-base.dispatch-listener' => 'RvBase\Mvc\Service\DispatchListenerFactory',
			'rv-base.time.current' => 'RvBase\DateTime\Service\CurrentTime\TimeProviderFactory',
			'rv-base.time.current.source.db-adapter' => 'RvBase\DateTime\Service\CurrentTime\Source\DbAdapterSourceFactory',
			'rv-base.time.request' => 'RvBase\DateTime\Service\RequestTime\RequestTimeProviderFactory',
			'rv-base.time.request.source.current-time' => 'RvBase\DateTime\Service\RequestTime\Source\CurrentTimeSourceFactory',
		),
		'invokables' => array(
		),
    ),
);
