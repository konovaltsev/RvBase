<?php

namespace RvBase\Mail;

use Zend\Mail;
use Zend\Mail\Message;
use Zend\View\Renderer\RendererInterface;

/**
 * Mail facade
 *
 * @package RvBase\Mail
 */
class Mailer implements MailerInterface
{
    /** @var Mail\Transport\TransportInterface */
    protected $transport;

    /** @var MessageProviderInterface */
    protected $messageProvider;

    /** @var RendererInterface */
    protected $renderer;

    /** @var AddressProviderInterface  */
    protected $addressProvider;

    /** @var string */
    protected $templatePath = 'mail';

    public function __construct(
        Mail\Transport\TransportInterface $transport,
        AddressProviderInterface $addressProvider,
        MessageProviderInterface $messageProvider,
        RendererInterface $renderer,
        array $options = array()
    )
    {
        $this->transport = $transport;
        $this->addressProvider = $addressProvider;
        $this->messageProvider = $messageProvider;
        $this->renderer = $renderer;
        if(!empty($options))
        {
            $this->setOptions($options);
        }
    }

    /**
     * Send message
     *
     * @param Message $message
     * @return void
     */
    public function send(Message $message)
    {
        $this->transport->send($message);
    }

    /**
     * Create, render and send message to $to
     *
     * @param $emailOrAddressList string|\Zend\Mail\Address\AddressInterface|array|\Zend\Mail\AddressList|\Traversable $emailOrAddressList
     * @param $template
     * @param array $templateVars
     * @return void
     */
    public function sendTo($emailOrAddressList, $template, array $templateVars)
    {
        $message = $this->createMessage();
        $this->populateMessage($message, $emailOrAddressList, $template, $templateVars);
        $this->send($message);
    }

    /**
     * Populate message object
     *
     * @param Message $message Message object for populate
     * @param null $emailOrAddressList
     * @param null $template
     * @param array $templateVars
     * @return void
     */
    public function populateMessage(Message $message, $emailOrAddressList = null, $template = null, array $templateVars = array())
    {
        $message->setFrom($this->getFromAddress());

        if($emailOrAddressList !== null)
        {
            $message->setTo($emailOrAddressList);
        }

        if($template !== null)
        {
            $subjectContent = $this->renderSubject($template, $templateVars);
            $message->setSubject($subjectContent);

            $bodyContent = $this->renderBody($template, $templateVars);
            $message->setBody($this->createMimeMessage($bodyContent));
        }
    }

    /**
     * Create, render and send message to Admin(s)
     *
     * @param string $template
     * @param $templateVars
     * @return void
     */
    public function sendToAdmin($template, $templateVars)
    {
        $this->sendTo($this->getAdminAddress(), $template, $templateVars);
    }

    /**
     * Render message body
     *
     * @param string $template
     * @param array $templateVars
     * @return string
     */
    public function renderBody($template, array $templateVars)
    {
        return $this->renderPart('body', $template, $templateVars);
    }

    /**
     * Render message subject
     *
     * @param string $template
     * @param array $templateVars
     * @return string
     */
    public function renderSubject($template, array $templateVars)
    {
        return $this->renderPart('subject', $template, $templateVars);
    }

    /**
     * Create message
     *
     * @return Message
     */
    public function createMessage()
    {
        $message = $this->messageProvider->createMailMessage();

        return $message;
    }

    /**
     * @param mixed $content
     * @return \Zend\Mime\Message
     */
    public function createMimeMessage($content = null)
    {
        $message = $this->messageProvider->createMimeMessage();
        if($content !== null)
        {
            $message->addPart($this->createMimePart($content));
        }

        return $message;
    }

    /**
     * @param mixed $content
     * @return \Zend\Mime\Part
     */
    public function createMimePart($content)
    {
        return $this->messageProvider->createMimePart($content);
    }

    /**
     * Get admin email(s)
     *
     * @return array|string|\Traversable|Mail\Address\AddressInterface|Mail\AddressList
     */
    public function getAdminAddress()
    {
        return $this->addressProvider->getAdminAddress();
    }

    /**
     * Get mail FROM address
     *
     * @return array|string|\Traversable|Mail\Address\AddressInterface|Mail\AddressList
     */
    public function getFromAddress()
    {
        return $this->addressProvider->getFromAddress();
    }

    protected function renderPart($part, $template, array $templateVars)
    {
        $template = $this->templatePath . '/' . $template . '_' . $part;
        return $this->renderer->render($template, $templateVars);
    }

    protected function setOptions(array $options)
    {
        if(isset($options['template_path']))
        {
            $this->templatePath = $options['template_path'];
        }
    }
}
