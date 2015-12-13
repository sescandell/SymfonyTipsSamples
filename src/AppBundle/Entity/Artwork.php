<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Artwork
 *
 * @ORM\Table(name="artwork")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ArtworkRepository")
 */
class Artwork
{
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
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=5)
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Participant", mappedBy="artwork", cascade={"persist"})
     * @Assert\Count(min=2)
     */
    private $participants;


    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

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
     * Set title
     *
     * @param string $title
     * @return Artwork
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return ArrayCollection
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|static
     */
    public function getAuthors()
    {
        return $this->participants->filter(function($p){ return $p->isAuthor(); });
    }

    /**
     * @param Participant $author
     * @return Artwork
     */
    public function addAuthor(Participant $author)
    {
        $author->setType(Participant::TYPE_AUTHOR);
        $author->setArtwork($this);
        $this->participants->add($author);

        return $this;
    }

    /**
     * @param Participant $author
     * @return Artwork
     */
    public function removeAuthor(Participant $author)
    {
        $this->participants->removeElement($author);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|static
     */
    public function getPublishers()
    {
        return $this->participants->filter(function($p){ return $p->isPublisher(); });
    }

    /**
     * @param Participant $publisher
     * @return Artwork
     */
    public function addPublisher(Participant $publisher)
    {
        $publisher->setType(Participant::TYPE_PUBLISHER);
        $publisher->setArtwork($this);
        $this->participants->add($publisher);

        return $this;
    }

    /**
     * @param Participant $publisher
     * @return Artwork
     */
    public function removePublisher(Participant $publisher)
    {
        $this->participants->removeElement($publisher);

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }
}
