<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 30/01/2020
 * Time: 22:54
 */

namespace App\Messenger;


class EmailNotification
{
    protected $from;
    protected $to;
    protected $subject;
    protected $body;
    protected $attachment;
    protected $attachmentName;
    protected $attachmentMime;

    /**
     * EmailNotification constructor.
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $message
     * @param string|null $attachment
     * @param string|null $attachmentName
     * @param string|null $attachmentMime
     */
    public function __construct(string $from, string $to, string $subject, string $message,
                                string $attachment = null, string $attachmentName = null, string $attachmentMime = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $message;
        $this->attachment = base64_encode($attachment);
        $this->attachmentName = $attachmentName;
        $this->attachmentMime = $attachmentMime;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getAttachment(): ?string
    {
        return base64_decode($this->attachment);
    }

    /**
     * @return string|null
     */
    public function getAttachmentName(): ?string
    {
        return $this->attachmentName;
    }

    /**
     * @return string|null
     */
    public function getAttachmentMime(): ?string
    {
        return $this->attachmentMime;
    }
}