<?php

namespace RvBase\Mail;

use Zend\Mail\Address\AddressInterface;
use Zend\Mail\AddressList;

/**
 * Interface AddressProviderInterface
 * @package RvBase\Mail\Transport
 */
interface AddressProviderInterface
{
    /**
     * Get administrator address for system messages
     *
     * @return AddressInterface|AddressList
     */
    public function getAdminAddress();

    /**
     * Get default "FROM" address
     *
     * @return AddressInterface|AddressList
     */
    public function getFromAddress();

    /**
     * Get address(list) from config
     *
     * @param string $configKey
     * @return AddressInterface|AddressList
     */
    public function getAddressFromConfig($configKey);
}
