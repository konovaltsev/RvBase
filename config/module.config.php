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

        'db' => array(
            'tables' => array(
                /*
                'my_table' => array(
                    'name' => 'my_table',
                    'schema' => 'my_schema',
                    'class' => 'MyModule\Table\MyTable'
                    'db-adapter' => 'MyAnotherDbAdapter',
                ),
                */
            ),
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

    /*
    // Mail config example
    'mail' => array(
        'transports' => array(
            'mail.transport.default' => array(
                'type' => 'smtp',
                'options' => array(
                    'host' => 'example.com',
                    'connection_class'  => 'plain',
                    'connection_config' => array(
                        'username' => 'noreply@example.com',
                        'password' => 'dummy password',
                    ),
                ),
            ),
        ),
        'messages' => array(
            'options' => array(
                'defaults' => array(
                    'encoding' => 'UTF-8',
                    'content_charset' => 'UTF-8',
                    'content_type' => \Zend\Mime\Mime::TYPE_HTML,
                    'content_encoding' => \Zend\Mime\Mime::ENCODING_BASE64,
                ),
            ),
        ),
        'addresses' => array(
            'from' => array(
                'noreply@example.com' => 'Example Mail Bot',
            ),
            'admin' => array(
                'admin1@example.com' => 'First Admin',
                'developer@another-example.com' => 'Second Admin',
            ),
        ),
    ),
    */

    'controller_plugins' => array(
		'invokables' => array(
			'notallowed' => 'RvBase\Mvc\Controller\Plugin\NotAllowed',
		),
	),

    'service_manager' => array(
        'factories' => array(
            'rv-base.dispatch-listener' => 'RvBase\Mvc\Service\DispatchListenerFactory',
            'rv-base.http-view-manager' => 'RvBase\Mvc\Service\HttpViewManagerFactory',
            'rv-base.mail.addresses' => 'RvBase\Mail\AddressProviderServiceFactory',
            'rv-base.mail.messages' => 'RvBase\Mail\MessageProviderServiceFactory',
            'rv-base.mailer' => 'RvBase\Mail\MailerServiceFactory',
			'rv-base.time.current' => 'RvBase\DateTime\Service\CurrentTime\TimeProviderFactory',
			'rv-base.time.current.source.db-adapter' => 'RvBase\DateTime\Service\CurrentTime\Source\DbAdapterSourceFactory',
			'rv-base.time.request' => 'RvBase\DateTime\Service\RequestTime\RequestTimeProviderFactory',
			'rv-base.time.request.source.current-time' => 'RvBase\DateTime\Service\RequestTime\Source\CurrentTimeSourceFactory',
            'rv-base.view-manager' => 'RvBase\Mvc\Service\ViewManagerFactory',
		),
		'invokables' => array(
		),
    ),
);
