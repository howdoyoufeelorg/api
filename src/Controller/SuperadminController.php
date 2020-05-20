<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 08/02/2020
 * Time: 17:56
 */

namespace App\Controller;


use App\Helper\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route as Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SuperadminController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/superadmin", name="superadmin_index")
     */
    public function index()
    {
        $client = $this->getGoogleClient();
        $authorize_gmail_url = $client->createAuthUrl();
        return $this->render('superadmin/index.html.twig', [
            'authorize_gmail' => $authorize_gmail_url
        ]);
    }
    /**
     * @Route("/superadmin/authorize-api", name="superadmin_authorize_api")
     */
    public function authorizeApi(Request $request)
    {
        $code = $request->query->get('code');
        $client = $this->getGoogleClient();

        $errors = [];

        if(!empty($code)) {
            $authresult = $client->fetchAccessTokenWithAuthCode($code);
            $access_token = $client->getAccessToken();
            $refresh_token = $client->getRefreshToken();
            if($refresh_token) {
                $this->security->setSecret(Security::SECRET_GOOGLE_API_REFRESH_TOKEN, $refresh_token);
            } else {
                if($access_token) {
                    // It may happen that we get the access token but not the refresh token.
                    // I'm not completely sure why but I saw it happen
                    $errors[] = [
                        'error' => 'No Refresh token',
                        'error_description' => 'The access_token was received but not the refresh_token.'
                    ];
                } else {
                    $errors[] = $authresult;
                }
            }
        } else {
            $errors[] = [
                'error' => 'No Code',
                'error_description' => 'The CODE was not returned - auth impossible'
            ];
        }
        return $this->render('superadmin/auth_results.html.twig', [
            'errors' => $errors
        ]);
    }

    private function getGoogleClient()
    {
        $client_id = $this->security->getSecret(Security::SECRET_GOOGLE_CLIENT_ID);
        $client_secret = $this->security->getSecret(Security::SECRET_GOOGLE_CLIENT_SECRET);
        $client = new \Google_Client();
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setAccessType('offline');
        $client->setScopes([\Google_Service_Gmail::GMAIL_SEND]);
        $client->setRedirectUri($this->generateUrl('superadmin_authorize_api', [], UrlGeneratorInterface::ABSOLUTE_URL));
        return $client;
    }
}