<?php

namespace Exprating\ImportBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * SiteProductRubrics.
 *
 * @ORM\Entity()
 * @ORM\Table(name="site_product_rubrics", indexes={@ORM\Index(name="left_key", columns={"left_key", "right_key", "level", "parent_id"}), @ORM\Index(name="tree_id", columns={"tree_id"}), @ORM\Index(name="level", columns={"level"}), @ORM\Index(name="fkParent", columns={"parent_id"})})
 */
class SiteProductRubrics
{
    /**
     * @var int
     *
     * @ORM\Column(name="left_key", type="integer", nullable=false)
     */
    private $leftKey;

    /**
     * @var int
     *
     * @ORM\Column(name="right_key", type="integer", nullable=false)
     */
    private $rightKey;

    /**
     * @var int
     *
     * @ORM\Column(name="level", type="integer", nullable=false)
     */
    private $level;

    /**
     * @var int
     *
     * @ORM\Column(name="tree_id", type="integer", nullable=false)
     */
    private $treeId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="added", type="datetime", nullable=true)
     */
    private $added;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="childCount", type="integer", nullable=true)
     */
    private $childcount;

    /**
     * @var bool
     *
     * @ORM\Column(name="showWoman", type="boolean", nullable=false)
     */
    private $showwoman = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="showMan", type="boolean", nullable=false)
     */
    private $showman = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="showChild", type="boolean", nullable=false)
     */
    private $showchild = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="showAll", type="boolean", nullable=false)
     */
    private $showall = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="parserShortName", type="string", length=100, nullable=true)
     */
    private $parsershortname;

    /**
     * @var string
     *
     * @ORM\Column(name="parserAttr", type="string", length=32, nullable=true)
     */
    private $parserattr;

    /**
     * @var string
     *
     * @ORM\Column(name="parserSynonyms", type="text", length=65535, nullable=true)
     */
    private $parsersynonyms;

    /**
     * @var int
     *
     * @ORM\Column(name="searchWeightSummer", type="integer", nullable=false)
     */
    private $searchweightsummer = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="searchWeightWinter", type="integer", nullable=false)
     */
    private $searchweightwinter = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="parserLinkingEnabled", type="boolean", nullable=false)
     */
    private $parserlinkingenabled = '1';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var SiteProductRubrics
     *
     * @ORM\ManyToOne(targetEntity="Exprating\ImportBundle\Entity\SiteProductRubrics")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * })
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Exprating\ImportBundle\Entity\SiteProductRubrics", mappedBy="parent")
     */
    private $children;

    public function __construct($children)
    {
        $this->children = new ArrayCollection();
    }

    /**
     * Set leftKey.
     *
     * @param int $leftKey
     *
     * @return SiteProductRubrics
     */
    public function setLeftKey($leftKey)
    {
        $this->leftKey = $leftKey;

        return $this;
    }

    /**
     * Get leftKey.
     *
     * @return int
     */
    public function getLeftKey()
    {
        return $this->leftKey;
    }

    /**
     * Set rightKey.
     *
     * @param int $rightKey
     *
     * @return SiteProductRubrics
     */
    public function setRightKey($rightKey)
    {
        $this->rightKey = $rightKey;

        return $this;
    }

    /**
     * Get rightKey.
     *
     * @return int
     */
    public function getRightKey()
    {
        return $this->rightKey;
    }

    /**
     * Set level.
     *
     * @param int $level
     *
     * @return SiteProductRubrics
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level.
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set treeId.
     *
     * @param int $treeId
     *
     * @return SiteProductRubrics
     */
    public function setTreeId($treeId)
    {
        $this->treeId = $treeId;

        return $this;
    }

    /**
     * Get treeId.
     *
     * @return int
     */
    public function getTreeId()
    {
        return $this->treeId;
    }

    /**
     * Set added.
     *
     * @param \DateTime $added
     *
     * @return SiteProductRubrics
     */
    public function setAdded($added)
    {
        $this->added = $added;

        return $this;
    }

    /**
     * Get added.
     *
     * @return \DateTime
     */
    public function getAdded()
    {
        return $this->added;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return SiteProductRubrics
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set childcount.
     *
     * @param int $childcount
     *
     * @return SiteProductRubrics
     */
    public function setChildcount($childcount)
    {
        $this->childcount = $childcount;

        return $this;
    }

    /**
     * Get childcount.
     *
     * @return int
     */
    public function getChildcount()
    {
        return $this->childcount;
    }

    /**
     * Set showwoman.
     *
     * @param bool $showwoman
     *
     * @return SiteProductRubrics
     */
    public function setShowwoman($showwoman)
    {
        $this->showwoman = $showwoman;

        return $this;
    }

    /**
     * Get showwoman.
     *
     * @return bool
     */
    public function getShowwoman()
    {
        return $this->showwoman;
    }

    /**
     * Set showman.
     *
     * @param bool $showman
     *
     * @return SiteProductRubrics
     */
    public function setShowman($showman)
    {
        $this->showman = $showman;

        return $this;
    }

    /**
     * Get showman.
     *
     * @return bool
     */
    public function getShowman()
    {
        return $this->showman;
    }

    /**
     * Set showchild.
     *
     * @param bool $showchild
     *
     * @return SiteProductRubrics
     */
    public function setShowchild($showchild)
    {
        $this->showchild = $showchild;

        return $this;
    }

    /**
     * Get showchild.
     *
     * @return bool
     */
    public function getShowchild()
    {
        return $this->showchild;
    }

    /**
     * Set showall.
     *
     * @param bool $showall
     *
     * @return SiteProductRubrics
     */
    public function setShowall($showall)
    {
        $this->showall = $showall;

        return $this;
    }

    /**
     * Get showall.
     *
     * @return bool
     */
    public function getShowall()
    {
        return $this->showall;
    }

    /**
     * Set parsershortname.
     *
     * @param string $parsershortname
     *
     * @return SiteProductRubrics
     */
    public function setParsershortname($parsershortname)
    {
        $this->parsershortname = $parsershortname;

        return $this;
    }

    /**
     * Get parsershortname.
     *
     * @return string
     */
    public function getParsershortname()
    {
        return $this->parsershortname;
    }

    /**
     * Set parserattr.
     *
     * @param string $parserattr
     *
     * @return SiteProductRubrics
     */
    public function setParserattr($parserattr)
    {
        $this->parserattr = $parserattr;

        return $this;
    }

    /**
     * Get parserattr.
     *
     * @return string
     */
    public function getParserattr()
    {
        return $this->parserattr;
    }

    /**
     * Set parsersynonyms.
     *
     * @param string $parsersynonyms
     *
     * @return SiteProductRubrics
     */
    public function setParsersynonyms($parsersynonyms)
    {
        $this->parsersynonyms = $parsersynonyms;

        return $this;
    }

    /**
     * Get parsersynonyms.
     *
     * @return string
     */
    public function getParsersynonyms()
    {
        return $this->parsersynonyms;
    }

    /**
     * Set searchweightsummer.
     *
     * @param int $searchweightsummer
     *
     * @return SiteProductRubrics
     */
    public function setSearchweightsummer($searchweightsummer)
    {
        $this->searchweightsummer = $searchweightsummer;

        return $this;
    }

    /**
     * Get searchweightsummer.
     *
     * @return int
     */
    public function getSearchweightsummer()
    {
        return $this->searchweightsummer;
    }

    /**
     * Set searchweightwinter.
     *
     * @param int $searchweightwinter
     *
     * @return SiteProductRubrics
     */
    public function setSearchweightwinter($searchweightwinter)
    {
        $this->searchweightwinter = $searchweightwinter;

        return $this;
    }

    /**
     * Get searchweightwinter.
     *
     * @return int
     */
    public function getSearchweightwinter()
    {
        return $this->searchweightwinter;
    }

    /**
     * Set parserlinkingenabled.
     *
     * @param bool $parserlinkingenabled
     *
     * @return SiteProductRubrics
     */
    public function setParserlinkingenabled($parserlinkingenabled)
    {
        $this->parserlinkingenabled = $parserlinkingenabled;

        return $this;
    }

    /**
     * Get parserlinkingenabled.
     *
     * @return bool
     */
    public function getParserlinkingenabled()
    {
        return $this->parserlinkingenabled;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set parent.
     *
     * @param SiteProductRubrics $parent
     *
     * @return SiteProductRubrics
     */
    public function setParent(SiteProductRubrics $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return SiteProductRubrics
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child.
     *
     * @param SiteProductRubrics $child
     *
     * @return SiteProductRubrics
     */
    public function addChild(SiteProductRubrics $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param SiteProductRubrics $child
     */
    public function removeChild(SiteProductRubrics $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children.
     *
     * @return \Doctrine\Common\Collections\Collection|SiteProductRubrics[]
     */
    public function getChildren()
    {
        return $this->children;
    }
}
