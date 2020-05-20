<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 30/01/2020
 * Time: 22:59
 */

namespace App\Messenger;


use App\Helper\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Superadmin\Settings;
use Swift_Attachment;
use Swift_Mailer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EmailNotificationHandler implements MessageHandlerInterface
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function __invoke(EmailNotification $message)
    {
        $googleRefreshToken = $this->security->getSecret(Security::SECRET_GOOGLE_API_REFRESH_TOKEN);
        $googleClientId = $this->security->getSecret(Security::SECRET_GOOGLE_CLIENT_ID);
        $googleClientSecret = $this->security->getSecret(Security::SECRET_GOOGLE_CLIENT_SECRET);
        if($googleRefreshToken && $googleClientId && $googleClientSecret) {
            $messageMime = (new \Swift_Message($message->getSubject()))
                ->setFrom($message->getFrom())
                ->setTo($message->getTo())
                ->setBody($message->getBody(),
                    'text/html'
                )
                // you can remove the following code if you don't define a text version for your emails
                ->addPart(
                    strip_tags($message->getBody()),
                    'text/plain'
                );
            if($message->getAttachment()) {
                $attachment = new Swift_Attachment($message->getAttachment());
                if($message->getAttachmentName()) $attachment->setFilename($message->getAttachmentName());
                if($message->getAttachmentMime()) $attachment->setContentType($message->getAttachmentMime());
                $messageMime->attach($attachment);
            }
            $client = new \Google_Client();
            $client->setClientId($googleClientId);
            $client->setClientSecret($googleClientSecret);
            $client->refreshToken($googleRefreshToken);
            $gmail = new \Google_Service_Gmail($client);
            $gmailMessage = new \Google_Service_Gmail_Message();
            $gmailMessage->setRaw($this->base64urlEncode($messageMime->toString()));
            $gmail->users_messages->send('me', $gmailMessage);
        }
    }

    private function base64urlEncode(string $str) {
        $url = strtr(base64_encode($str), array('+' => '-', '/' => '_'));
        return rtrim($url, "=");
    }
}