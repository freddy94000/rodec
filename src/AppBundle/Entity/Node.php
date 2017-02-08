<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Node
 *
 * @ORM\Table(name="node")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NodeRepository")
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
     * @ORM\Column(name="rank", type="integer", unique=true, nullable=false)
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
     * Constructor
     */
    public function __construct()
    {
        $this->nodeChilds = new ArrayCollection();
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
}
