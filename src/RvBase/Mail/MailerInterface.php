<?php

namespace RvBase\Mail;

use Zend\Mail;
use Zend\Mail\Message;

/**
 * Interface MailerInterface
 * @package RvBase\Mail
 */
interface MailerInterface
{
    /**
     * Send message
     *
     * @param Message $message
     * @return void
     */
    public function send(Message $message);

    /**
     * Create, render and send message to $to
     *
     * @param string|\Zend\Mail\Address\AddressInterface|array|\Zend\Mail\AddressList|\Traversable $emailOrAddressList
     * @param string $template
     * @param array $templateVars
     * @return void
     */
    public function sendTo($emailOrAddressList, $template, array $templateVars);

    /**
     * Populate message object
     *
     * @param Message $message Message object for populate
     * @param null|string|\Zend\Mail\Address\AddressInterface|array|\Zend\Mail\AddressList|\Traversable $to
     * @param null|string $template
     * @param null|array $templateVars
     * @return void
     */
    public function populateMessage(Message $message, $to = null, $template = null, array $templateVars = array());

    /**
     * Create, render and send message to Admin(s)
     *
     * @param string $template
     * @param $templateVars
     * @return void
     */
    public function sendToAdmin($template, $templateVars);

    /**
     * Render message body
     *
     * @param string $template
     * @param array $templateVars
     * @return string
     */
    public function renderBody($template, array $templateVars);

    /**
     * Render message subject
     *
     * @param string $template
     * @param array $templateVars
     * @return string
     */
    public function renderSubject($template, array $templateVars);

    /**
     * @return Message
     */
    public function createMessage();

    /**
     * @param mixed $content
     * @return \Zend\Mime\Message
     */
    public function createMimeMessage($content);

    /**
     * @param mixed $content
     * @return \Zend\Mime\Part
     */
    public function createMimePart($content);

    /**
     * Get admin email(s)
     *
     * @return array|string|\Traversable|Mail\Address\AddressInterface|Mail\AddressList
     */
    public function getAdminAddress();

    /**
     * Get mail FROM address
     *
     * @return array|string|\Traversable|Mail\Address\AddressInterface|Mail\AddressList
     */
    public function getFromAddress();
}
