<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 13/04/2020
 * Time: 3:39 pm
 */

namespace App\Helper;

use App\Entity\User;
use Google\ApiCore\ApiException;
use Google\Cloud\SecretManager\V1\Replication;
use Google\Cloud\SecretManager\V1\Replication\Automatic;
use Google\Cloud\SecretManager\V1\Secret;
use Google\Cloud\SecretManager\V1\SecretManagerServiceClient;
use Google\Cloud\SecretManager\V1\SecretPayload;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Security
{
    const SECRET_GOOGLE_CLIENT_ID = 'GOOGLE_CLIENT_ID';
    const SECRET_GOOGLE_CLIENT_SECRET = 'GOOGLE_CLIENT_SECRET';
    const SECRET_GOOGLE_API_REFRESH_TOKEN = 'GOOGLE_API_REFRESH_TOKEN';
    const SECRET_GOOGLE_API_KEY = 'GOOGLE_API_KEY';

    const ENV_GOOGLE_CLOUD_PROJECT = 'GOOGLE_CLOUD_PROJECT';

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
        $name = $client->secretVersionName(getenv(self::ENV_GOOGLE_CLOUD_PROJECT), $secretId, 'latest');
        $response = $client->accessSecretVersion($name);
        $payload = $response->getPayload()->getData();
        return $payload;
    }

    public function setSecret($secretId, $value): bool
    {
        $projectId = getenv(self::ENV_GOOGLE_CLOUD_PROJECT);
        $client = new SecretManagerServiceClient();
        $parent = $client->projectName($projectId);
        $secretName = $client->secretName($projectId, $secretId);

        $secretExists = true;
        try {
            $client->getSecret($secretName);
        } catch (ApiException $e) {
            $secretExists = false;
        }

        try {
            if(!$secretExists) {
                $secret = $client->createSecret($parent, $secretId, new Secret([
                        'replication' => new Replication([
                            'automatic' => new Automatic(),
                        ]),
                    ]));
                $secretName = $secret->getName();
            }
            $client->addSecretVersion($secretName, new SecretPayload([
                'data' => $value,
            ]));
            $client->close();
            return true;
        } catch (ApiException $e) {
            return false;
        }
    }
}