<?php

return [
    'rv-base' => [
        'view-manager' => [
            'enabled' => true,
        ],

        'dispatch-listener' => [
            'enabled' => true,
        ],

        'filter_helpers' => [
        ],

        'permissions' => [
            'acl' => [
                /*
                'roles' => [
                ],
                'resources' => [
                ],
                'rules' => [
                ],
                'initializers' => [
                    'roles' => [
                        'RvBase\ServiceFactory\Acl\RolesConfigInitializer',
                    ],
                    'resources' => [
                        'RvBase\ServiceFactory\Acl\ResourcesConfigInitializer',
                    ],
                    'rules' => [
                        'RvBase\ServiceFactory\Acl\RulesConfigInitializer',
                    ],
                ],

                'parent_roles_providers' => [
                    [
                        'class' => 'MyRolesProviderInvokable',
                        'priority' => 1,
                    ],
                    [
                        'service' => 'my.parent.roles.provider.service',
                        'priority' => 2,
                    ],
                ],
                */

                //Services
                'acl'                            => 'acl',
                'identity_role_provider'         => 'rv-base.permissions.acl.identity-role-provider',
                'identity_parent_roles_provider' => 'rv-base.permissions.acl.identity-parent-roles-provider',
                'identity_role_initializer'      => 'rv-base.permissions.acl.identity-role-initializer',
                'resource_initializer'           => 'rv-base.permissions.acl.resource-initializer',

                'init_authenticated_identity_role' => false,

                /*
                // Resources providers
                'resource_providers' => [
                    [
                        'class' => 'My\Provider1',
                    ],
                    [
                        'service' => 'my.provider1',
                    ],
                ],
                'object_resource_providers' => [
                    'My\Some\Entity' => [
                        'class' => 'My\Some\Entity\Provider',
                    ],
                    'My\Another\Entity' => [
                        'service' => 'my.another.entity.provider.service',
                    ],
                ],
                 */
            ],

//            'authentication_service' => 'rv-base.authentication',
//            'service' => 'rv-base.permissions',
        ],

        'db' => [
            'tables' => [
                /*
                'my_table' => [
                    'name' => 'my_table',
                    'schema' => 'my_schema',
                    'class' => 'MyModule\Table\MyTable'
                    'db-adapter' => 'MyAnotherDbAdapter',
                ],
                */
            ],
        ],

        /*
        // Mail config example
        'mail' => [
            'transports' => [
                'mail.transport.default' => [
                    'type' => 'smtp',
                    'options' => [
                        'host' => 'example.com',
                        'connection_class'  => 'plain',
                        'connection_config' => [
                            'username' => 'noreply@example.com',
                            'password' => 'dummy password',
                        ],
                    ],
                ],
            ],
            'messages' => [
                'options' => [
                    'defaults' => [
                        'encoding' => 'UTF-8',
                        'content_charset' => 'UTF-8',
                        'content_type' => \Zend\Mime\Mime::TYPE_HTML,
                        'content_encoding' => \Zend\Mime\Mime::ENCODING_BASE64,
                    ],
                ],
            ],
            'addresses' => [
                'from' => [
                    'noreply@example.com' => 'Example Mail Bot',
                ],
                'admin' => [
                    'admin1@example.com' => 'First Admin',
                    'developer@another-example.com' => 'Second Admin',
                ],
            ],
        ],
        */

        /*
        // time config example
        'time' => [
            'current' => [
                'source-service' => 'rv-base.time.current.source.db-adapter',
                'source' => [
                    'adapter' => 'Zend\Db\Adapter\Adapter',
                ],
            ],
            'request' => [
                'source-service' => 'rv-base.time.request.source.current-time',
                'source' => [
                    'current-time-service' => 'rv-base.time.current',
                ],
            ],
        ],
        */
    ],

    'controller_plugins' => [
		'invokables' => [
			'notAllowed' => 'RvBase\Mvc\Controller\Plugin\NotAllowed',
		],
	],

    'service_manager' => [
        'factories' => [
            'rv-base.dispatch-listener' => 'RvBase\Mvc\Service\DispatchListenerFactory',
            'rv-base.http-view-manager' => 'RvBase\Mvc\Service\HttpViewManagerFactory',
            'rv-base.mail.addresses' => 'RvBase\Mail\AddressProviderServiceFactory',
            'rv-base.mail.messages' => 'RvBase\Mail\MessageProviderServiceFactory',
            'rv-base.mailer' => 'RvBase\Mail\MailerServiceFactory',
            'rv-base.permissions.acl.identity-parent-roles-provider'
                => 'RvBase\ServiceFactory\Acl\IdentityRoleParentsProviderFactory',
            'rv-base.permissions.acl.identity-role-initializer'
                => 'RvBase\ServiceFactory\Acl\IdentityRoleInitializerServiceFactory',
            'rv-base.permissions.acl.resource-initializer'
                => 'RvBase\ServiceFactory\Acl\ResourceInitializerServiceFactory',
            'rv-base.permissions.acl.resource-provider'
                => 'RvBase\ServiceFactory\Acl\ResourceProviderChainServiceFactory',
			'rv-base.time.current' => 'RvBase\DateTime\Service\CurrentTime\TimeProviderFactory',
			'rv-base.time.current.source.db-adapter' => 'RvBase\DateTime\Service\CurrentTime\Source\DbAdapterSourceFactory',
			'rv-base.time.request' => 'RvBase\DateTime\Service\RequestTime\RequestTimeProviderFactory',
			'rv-base.time.request.source.current-time' => 'RvBase\DateTime\Service\RequestTime\Source\CurrentTimeSourceFactory',
            'rv-base.view-manager' => 'RvBase\Mvc\Service\ViewManagerFactory',
		],
		'invokables' => [
            'rv-base.inject-template-from-route-listener' => 'RvBase\View\Http\InjectTemplateFromRouteListener',
            /*
            'rv-base.permissions.acl.identity-role-provider' => 'MyModule\Permissions\Acl\Role\IdentityRoleProvider',
             */
		],
    ],

    'filters' => [
        'invokables' => [
            'EmTypograph' => 'RvBase\Filter\EmTypograph',
            'HtmlPurifier' => 'RvBase\Filter\HtmlPurifier',
        ],
    ],

    'view_helpers' => [
        'factories' => [
            'IsAllowed' => \RvBase\View\Helper\IsAllowedFactory::class,
        ],
    ],
];
