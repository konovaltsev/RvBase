<?php

namespace RvBase\Mail;

use Zend\Mail\Address;
use Zend\Mail\Address\AddressInterface;
use Zend\Mail\AddressList;
use Zend\Mail\Exception;

/**
 * Class AddressProvider
 * @package RvBase\Mail
 */
class AddressProvider implements AddressProviderInterface
{
    protected $config = array();

    public function __construct(array $config=array())
    {
        $this->config = $config;
    }

    /**
     * Get default "FROM" address
     *
     * @return AddressInterface|AddressList
     */
    public function getFromAddress()
    {
        return $this->getAddressFromConfig('from');
    }

    /**
     * Get administrator address for system messages
     *
     * @return AddressInterface|AddressList
     */
    public function getAdminAddress()
    {
        return $this->getAddressFromConfig('admin');
    }

    /**
     * Get address(list) from config
     *
     * @param string $configKey
     * @return AddressInterface|AddressList
     * @throws Exception\DomainException
     */
    public function getAddressFromConfig($configKey)
    {
        if(!isset($this->config[$configKey]))
        {
            throw new Exception\DomainException(
                sprintf(
                    'Address `%s` not exists in config',
                    $configKey
                )
            );
        }
        $address = $this->config[$configKey];
        if($address instanceof AddressInterface || $address instanceof AddressList)
        {
            return clone $address;
        }

        if(is_array($address))
        {
            if(count($address) == 1)
            {
                $elem = each($address);
                return new Address($elem['key'], $elem['value']);
            }
            $addressList = new AddressList();
            $addressList->addMany($address);
            return $addressList;
        }

        return new Address($address);
    }
}
