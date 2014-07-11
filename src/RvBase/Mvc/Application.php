<?php

namespace RvBase\Mvc;

//use Zend\Mvc as ZendMvc;

/**
 * Контейнер констант. Экстенд не нужен
 * Class Application
 * @package RvBase\Mvc
 */
class Application //extends ZendMvc\Application
{
    const ERROR_CONTROLLER_NOT_ALLOWED = 'error-controller-not-allowed';
    const ERROR_ACL_FAILED = 'error-acl-failed';
    const ERROR_ACL_ROLE_NOT_SET = 'error-acl-role-not-set';
    const ERROR_ACL_NOT_FOUND = 'error-acl-not-found';
}
