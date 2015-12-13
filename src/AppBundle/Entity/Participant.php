<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Participant
 *
 * @ORM\Table(name="participant")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ParticipantRepository")
 */
class Participant
{
    // Constantes pour le champ Type.
    // On pourrait aussi passer par un enum
    const TYPE_AUTHOR = 'author';
    const TYPE_PUBLISHER = 'publisher';

    /**
     * @return array
     */
    public static function getAllTypes()
    {
        return [
            self::TYPE_PUBLISHER,
            self::TYPE_AUTHOR
        ];
    }


    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=5)
     */
    private $fullname;

    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=255, nullable=true)
     */
    private $company = null;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var Artwork
     *
     * @ORM\ManyToOne(targetEntity="Artwork", inversedBy="participants")
     * @ORM\JoinColumn(name="artwork_id", referencedColumnName="id")
     */
    private $artwork;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     * @return Participant
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string 
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Participant
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return Artwork
     */
    public function getArtwork()
    {
        return $this->artwork;
    }

    /**
     * @param Artwork $artwork
     * @return Participant
     */
    public function setArtwork(Artwork $artwork)
    {
        $this->artwork = $artwork;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAuthor()
    {
        return self::TYPE_AUTHOR == $this->type;
    }

    /**
     * @return bool
     */
    public function isPublisher()
    {
        return self::TYPE_PUBLISHER == $this->type;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->isAuthor()
            ? $this->fullname
            : sprintf('%s @%s', $this->fullname, $this->company);
    }
}
