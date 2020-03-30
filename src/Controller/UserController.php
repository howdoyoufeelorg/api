<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 27/03/2020
 * Time: 10:24 am
 */

namespace App\Controller;

use App\Entity\Area;
use App\Entity\User;
use App\Messenger\EmailNotification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route as Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, MessageBusInterface $messageBus)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/users/process-invite", name="users_process_invite")
     */
    public function processInvite(Request $request) {
        $emailsString = $request->request->get('emails');
        $areaString = $request->request->get('area');
        $areaStringElements = explode("/",$areaString);
        $areaId = end($areaStringElements);
        $area = $this->entityManager->getRepository(Area::class)->find($areaId);
        $emails = array_filter(explode("\n", $emailsString));
        if($area instanceof Area) {
            // Send invites
            foreach ($emails as $email) {
                $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
                if ($existingUser instanceof User) {
                    $existingAreas = $existingUser->getAreas();
                    if(!$existingAreas->contains($area)) {
                        $existingUser->addArea($area);
                    }
                } else {
                    $user = new User();
                    $this->entityManager->persist($user);
                    $user->setEmail($email);
                    $user->setPassword($this->passwordEncoder->encodePassword($user, $this->generatePassword()));
                    $user->setRoles([User::ROLE_EDITOR]);
                    $user->addArea($area);
                    $user->setRegistrationHash($this->generateToken());
                    // SEND INVITATIONS
                    $invitationSubject = 'HowDoYouFeel.org - Please join our efforts';
                    $confirmationLink = $this->generateUrl('users_confirm_user', ['confirmation' => $user->getRegistrationHash()], UrlGeneratorInterface::ABSOLUTE_URL);
                    $invitationText = 'Hi, <br> <a href="'.$confirmationLink.'">please click on this link to confirm your account on HowDoYouFeel.org. </a>';
                    $invite = new EmailNotification(
                        'info@howdoyoufeel.org',
                        $email,
                        $invitationSubject,
                        $invitationText
                    );
                    $this->messageBus->dispatch($invite);
                }
            }
            $this->entityManager->flush();
            return new JsonResponse(["result" => "OK", "emails" => $emails]);
        } else {
            return new JsonResponse(["result" => "ERROR", "error" => "Area does not exist!"]);
        }
    }

    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    private function generatePassword()
    {
        return rtrim(strtr(base64_encode(random_bytes(16)), '+/', '-_'), '=');
    }

    /**
     * @Route("/users/confirm-user", name="users_confirm_user")
     */
    public function confirmUser(Request $request)
    {
        $registrationHash = $request->query->get('confirmation');
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['registrationHash' => $registrationHash]);
        if($user instanceof User) {
            $user->setRegistrationHash('');
            $user->setConfirmed(true);
            $this->entityManager->flush();
            return $this->render('user/confirmation_success.html.twig');
        }
        return (new Response())->setStatusCode(500);
    }
}