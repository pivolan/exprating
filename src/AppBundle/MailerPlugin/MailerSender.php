<?php
/**
 * Date: 01.04.16
 * Time: 2:12
 */

namespace AppBundle\MailerPlugin;


use Swift_Events_SendEvent;

class MailerSender implements \Swift_Events_SendListener
{
    /**
     * @var string
     */
    private $senderAddress;

    /**
     * @var string
     */
    private $senderFullname;

    /**
     * MailerSender constructor.
     *
     * @param string $senderAddress
     * @param string $senderFullname
     */
    public function __construct($senderAddress, $senderFullname)
    {
        $this->senderAddress = $senderAddress;
        $this->senderFullname = $senderFullname;
    }

    /**
     * Invoked immediately before the Message is sent.
     *
     * @param Swift_Events_SendEvent $evt
     */
    public function beforeSendPerformed(Swift_Events_SendEvent $evt)
    {
        $message = $evt->getMessage();
        if ($this->senderAddress && $this->senderFullname) {
            $message->setFrom([$this->senderAddress => $this->senderFullname]);
        }
    }

    /**
     * Invoked immediately after the Message is sent.
     *
     * @param Swift_Events_SendEvent $evt
     */
    public function sendPerformed(Swift_Events_SendEvent $evt)
    {
    }
}