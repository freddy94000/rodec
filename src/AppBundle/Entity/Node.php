<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Node
 *
 * @ORM\Table(name="node")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NodeRepository")
 * @Vich\Uploadable
 */
class Node
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    protected $url;

    /**
     * @var int
     *
     * @ORM\Column(name="rank", type="integer", nullable=false)
     */
    protected $rank;

    /**
     * @ORM\OneToMany(targetEntity="Node", mappedBy="nodeParent", orphanRemoval=true, cascade={"all"})
     */
    protected $nodeChilds;

    /**
     * @ORM\ManyToOne(targetEntity="Node", inversedBy="nodeChilds")
     */
    protected $nodeParent;

    /**
     * @ORM\OneToOne(targetEntity="Page", inversedBy="node")
     */
    protected $page;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="keyword", type="string", length=255, nullable=true)
     */
    private $keyword;

    /**
     * @var string
     * 
     * @ORM\Column(name="accroche", type="string", length=255, nullable=true)
     */
    private $accroche;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="image", fileNameProperty="imageName")
     */
    protected $imageFile;

    /**
     * @var string
     *
     * @ORM\Column(name="imageName", type="string", length=255, nullable=true)
     */
    protected $imageName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->nodeChilds = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return ($this->title) ?: '';
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Node
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
     * Set url
     *
     * @param string $url
     *
     * @return Node
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set rank
     *
     * @param integer $rank
     *
     * @return Node
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return int
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Add nodeChild
     *
     * @param \AppBundle\Entity\Node $nodeChild
     *
     * @return Node
     */
    public function addNodeChild(Node $nodeChild)
    {
        if (!$this->getNodeChilds()->contains($nodeChild)) {
            $this->nodeChilds[] = $nodeChild;
        }

        return $this;
    }

    /**
     * Remove nodeChild
     *
     * @param \AppBundle\Entity\Node $nodeChild
     */
    public function removeNodeChild(Node $nodeChild)
    {
        if ($this->getNodeChilds()->contains($nodeChild)) {
            $this->nodeChilds->removeElement($nodeChild);
        }
    }

    /**
     * Get nodeChilds
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNodeChilds()
    {
        return $this->nodeChilds;
    }

    /**
     * Set nodeParent
     *
     * @param \AppBundle\Entity\Node $nodeParent
     *
     * @return Node
     */
    public function setNodeParent(Node $nodeParent = null)
    {
        $this->nodeParent = $nodeParent;
        
        $nodeParent->addNodeChild($this);

        return $this;
    }

    /**
     * Get nodeParent
     *
     * @return \AppBundle\Entity\Node
     */
    public function getNodeParent()
    {
        return $this->nodeParent;
    }

    /**
     * Set page
     *
     * @param \AppBundle\Entity\Page $page
     *
     * @return Node
     */
    public function setPage(Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \AppBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Page
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set keyword
     *
     * @param string $keyword
     *
     * @return Page
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * Get keyword
     *
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Set accroche
     *
     * @param string $accroche
     *
     * @return Node
     */
    public function setAccroche($accroche)
    {
        $this->accroche = $accroche;

        return $this;
    }

    /**
     * Get accroche
     *
     * @return string
     */
    public function getAccroche()
    {
        return $this->accroche;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     *
     * @return $this
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @param File|null $image
     * @return $this
     */
    public function setImageFile($image = null)
    {
        $this->imageFile = $image;

        $this->updatedAt = new \DateTime();

        return $this;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }
}
