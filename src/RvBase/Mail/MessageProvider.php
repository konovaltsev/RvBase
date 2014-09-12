<?php

namespace RvBase\Mail;

use Zend\Mail\Message;
use Zend\Mime;

/**
 * Class MessageProvider
 * @package RvBase\Mail
 */
class MessageProvider implements MessageProviderInterface
{
    /**
     * Кодировка сообщения по умолчанию
     *
     * @var string
     */
    protected $defaultEncoding = 'UTF-8';

    /**
     * Кодировка тела сообщения по умолчанию
     *
     * @var string
     */
    protected $defaultContentEncoding = Mime\Mime::ENCODING_BASE64;

    /**
     * Обозначение кодировки тела сообщения по умолчанию
     *
     * @var string
     */
    protected $defaultContentCharset = 'UTF-8';

    /**
     * Mime-тип тела сообщения по умолчанию
     *
     * @var string
     */
    protected $defaultContentType = Mime\Mime::TYPE_HTML;

    public function __construct(array $options = array())
    {
        if(!empty($options))
        {
            $this->setOptions($options);
        }
    }

    /**
     * Create Mail\Message with default options
     *
     * @return Message
     */
    public function createMailMessage()
    {
        $message = new Message();
        $message->setEncoding($this->defaultEncoding);

        return $message;
    }

    /**
     * Create Mime\Message (with single part with default options)
     *
     * @return Mime\Message
     */
    public function createMimeMessage()
    {
        return new Mime\Message();
    }

    /**
     * Create Mime\Part with default options
     *
     * @param mixed $content
     * @return Mime\Part
     */
    public function createMimePart($content)
    {
        $part = new Mime\Part($content);
        $part->type = $this->defaultContentType;
        $part->charset = $this->defaultContentCharset;
        $part->encoding = $this->defaultContentEncoding;

        return $part;
    }

    protected function setOptions(array $options)
    {
        if(isset($options['defaults']['encoding']))
        {
            $this->defaultEncoding = $options['defaults']['encoding'];
        }

        if(isset($options['defaults']['content_charset']))
        {
            $this->defaultContentCharset = $options['defaults']['content_charset'];
        }

        if(isset($options['defaults']['content_type']))
        {
            $this->defaultContentType = $options['defaults']['content_type'];
        }

        if(isset($options['defaults']['content_encoding']))
        {
            $this->defaultContentEncoding = $options['defaults']['content_encoding'];
        }
    }
}
