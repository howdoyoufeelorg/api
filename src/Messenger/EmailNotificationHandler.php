<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 30/01/2020
 * Time: 22:59
 */

namespace App\Messenger;


use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Superadmin\Settings;
use Swift_Attachment;
use Swift_Mailer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EmailNotificationHandler implements MessageHandlerInterface
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(Swift_Mailer $mailer, ParameterBagInterface $parameterBag, EntityManagerInterface $entityManager)
    {
        $this->mailer = $mailer;
        $this->parameterBag = $parameterBag;
        $this->entityManager = $entityManager;
    }

    public function __invoke(EmailNotification $message)
    {
        $superadminSettings = $this->entityManager->getRepository(Settings::class)->find(1);
        if($superadminSettings->getGoogleRefreshToken()) {
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
            $client_id = $this->parameterBag->get('google_client_id');
            $client_secret = $this->parameterBag->get('google_client_secret');
            $client = new \Google_Client();
            $client->setClientId($client_id);
            $client->setClientSecret($client_secret);
            $client->refreshToken($superadminSettings->getGoogleRefreshToken());
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