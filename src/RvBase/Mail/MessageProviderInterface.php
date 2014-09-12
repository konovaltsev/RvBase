<?php

namespace RvBase\Mail;

use Zend\Mail\Message;
use Zend\Mime;

/**
 * Interface MessageProviderInterface
 * @package RvBase\Mail
 */
interface MessageProviderInterface
{
    /**
     * Create Mail\Message with default options
     *
     * @return Message
     */
    public function createMailMessage();

    /**
     * Create Mime\Part with default options
     *
     * @param mixed $content
     * @return Mime\Part
     */
    public function createMimePart($content);

    /**
     * Create Mime\Message (with single part with default options)
     *
     * @return Mime\Message
     */
    public function createMimeMessage();
}
