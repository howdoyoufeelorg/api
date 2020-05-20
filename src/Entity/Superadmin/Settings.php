<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 08/02/2020
 * Time: 12:25
 */

namespace App\Entity\Superadmin;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table(name="superadmin_settings")
 * @ORM\Entity
 */
class Settings
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    /**
     * @var string
     *
     * @ORM\Column(name="google_refresh_token", type="string", length=255, nullable=false)
     * @deprecated the refresh token is now saved in GoogleCloud secret manager
     */
    protected $googleRefreshToken;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getGoogleRefreshToken(): string
    {
        return $this->googleRefreshToken;
    }

    /**
     * @param string $googleRefreshToken
     * @return Settings
     */
    public function setGoogleRefreshToken(string $googleRefreshToken): Settings
    {
        $this->googleRefreshToken = $googleRefreshToken;
        return $this;
    }
}