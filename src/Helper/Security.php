<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 13/04/2020
 * Time: 3:39 pm
 */

namespace App\Helper;

use App\Entity\User;
use Google\Cloud\SecretManager\V1\SecretManagerServiceClient;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Security
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function currentUser()
    {
        return $this->tokenStorage->getToken()->getUser();
    }

    public function isAdmin()
    {
        $roles = $this->currentUser()->getRoles();
        if(
            in_array(User::ROLE_ADMIN, $roles) || in_array(User::ROLE_SUPERADMIN, $roles)
        ) return true;
        return false;
    }

    public function getSecret($secretId)
    {
        $client = new SecretManagerServiceClient();
        $name = $client->secretVersionName(getenv('GOOGLE_CLOUD_PROJECT'), $secretId, 'latest');
        $response = $client->accessSecretVersion($name);
        $payload = $response->getPayload()->getData();
        return $payload;
    }
}