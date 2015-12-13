<?php
namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="social_code", type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=5)
     */
    private $socialCode;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getSocialCode()
    {
        return $this->socialCode;
    }

    /**
     * @param mixed $socialCode
     */
    public function setSocialCode($socialCode)
    {
        $this->socialCode = $socialCode;
    }
}