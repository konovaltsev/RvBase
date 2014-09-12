<?php

namespace RvBase\Mail;

use Zend\Mail\Transport\TransportInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Renderer\RendererInterface;

/**
 * Class MailerServiceFactory
 * @package RvBase\Mail
 */
class MailerServiceFactory implements FactoryInterface
{
    protected $transportName = 'mail.transport.default';
    protected $mailerClass = 'RvBase\Mail\Mailer';

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new $this->mailerClass(
            $this->getTransport($serviceLocator),
            $this->getAddressProvider($serviceLocator),
            $this->getMessageProvider($serviceLocator),
            $this->getRenderer($serviceLocator),
            $this->getOptions($serviceLocator)
        );
    }

    protected function getOptions(ServiceLocatorInterface $serviceLocator)
    {
        $config = $this->getConfig($serviceLocator);
        $options = array();
        if(isset($config['options']) && is_array($config['options']))
        {
            $options = $config['options'];
        }
        return $options;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AddressProviderInterface
     */
    protected function getAddressProvider(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('rv-base.mail.addresses');
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return MessageProviderInterface
     */
    protected function getMessageProvider(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get('rv-base.mail.messages');
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return RendererInterface
     */
    protected function getRenderer(ServiceLocatorInterface $serviceLocator)
    {
        $config = $this->getConfig($serviceLocator);
        $renderer = isset($config['renderer'])? $config['renderer'] : null;
        if($renderer === null)
        {
            $renderer = new PhpRenderer();
            $renderer->setResolver($serviceLocator->get('ViewResolver'));
            return $renderer;
        }

        return $serviceLocator->get($renderer);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return TransportInterface
     */
    protected function getTransport(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator->get($this->transportName);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return array
     */
    protected function getConfig(ServiceLocatorInterface $serviceLocator)
    {
        if (!$serviceLocator->has('Config')) {
            return array();
        }

        $config = $serviceLocator->get('Config');
        if (!isset($config['mail'])
            || !is_array($config['mail'])
        ) {
            return array();
        }

        $config = $config['mail'];
        if (!isset($config['mailer'])
            || !is_array($config['mailer'])
        ) {
            return array();
        }

        return $config['mailer'];
    }
}
