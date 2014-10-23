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

        'permissions' => array(
            'acl' => array(
                /*
                'roles' => array(
                ),
                'resources' => array(
                ),
                'rules' => array(
                ),
                'initializers' => array(
                    'roles' => array(
                        'RvBase\ServiceFactory\Acl\RolesConfigInitializer',
                    ),
                    'resources' => array(
                        'RvBase\ServiceFactory\Acl\ResourcesConfigInitializer',
                    ),
                    'rules' => array(
                        'RvBase\ServiceFactory\Acl\RulesConfigInitializer',
                    ),
                ),

                'parent_roles_providers' => array(
                    array(
                        'class' => 'MyRolesProviderInvokable',
                        'priority' => 1,
                    ),
                    array(
                        'service' => 'my.parent.roles.provider.service',
                        'priority' => 2,
                    ),
                ),
                */

                'identity_role_provider' => 'rv-base.permissions.acl.identity-role-provider',
                'identity_parent_roles_provider' => 'rv-base.permissions.acl.identity-parent-roles-provider',
                'identity_role_initializer' => 'rv-base.permissions.acl.identity-role-initializer',

                /*
                // For true init_authenticated_identity_role
                'authentication_service' => 'rv-base.authentication',
                */
                'init_authenticated_identity_role' => false,

                /*
                // Resources providers
                'resource_providers' => array(
                    array(
                        'class' => 'My\Provider1',
                    ),
                    array(
                        'service' => 'my.provider1',
                    ),
                ),
                'object_resource_providers' => array(
                    'My\Some\Entity' => array(
                        'class' => 'My\Some\Entity\Provider',
                    ),
                    'My\Another\Entity' => array(
                        'service' => 'my.another.entity.provider.service',
                    ),
                ),
                 */
            ),
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

        'typograph' => array(
            'driver' => array(
                'name' => 'mdash',
                'options' => array(
                    'path' => __DIR__ . '/../',
                    'mdash' => array(
                    ),
                ),
            ),
        ),
    ),

    'controller_plugins' => array(
		'invokables' => array(
			'notAllowed' => 'RvBase\Mvc\Controller\Plugin\NotAllowed',
		),
	),

    'service_manager' => array(
        'factories' => array(
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
		),
		'invokables' => array(
            /*
            'rv-base.permissions.acl.identity-role-provider' => 'MyModule\Permissions\Acl\Role\IdentityRoleProvider',
             */
		),
    ),
);
