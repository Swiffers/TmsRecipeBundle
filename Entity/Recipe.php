<?php

/**
 * @author Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
 */

namespace Tms\Bundle\RecipeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Tms\Bundle\RecipeBundle\Entity\Repository\RecipeRepository")
 * @ORM\Table(name="recipe")
 * @ORM\HasLifecycleCallbacks()
 */
class Recipe
{
    const STATE_AWAITING     = "awaiting";
    const STATE_VALIDATED    = "validated";
    const STATE_REFUSED      = "refused";
    const STATE_PENDING      = "pending";

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=256)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $people;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    private $cookingTime;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    private $preparationTime;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(type="string", columnDefinition="ENUM('pending', 'awaiting', 'refused', 'validated')")
     */
    private $state;

     /**
     * @ORM\OneToMany(targetEntity="Ingredient", mappedBy="recipe")
     */
    protected $ingredients;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $preparation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var Media
     *
     * @ORM\OneToOne(targetEntity="Tms\Bundle\MediaClientBundle\Entity\Media", cascade={"all"})
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $media;

    /**
     * @ORM\PrePersist()
     */
    public function onCreate()
    {
        $now = new \DateTime("now");
        $this
            ->setCreatedAt($now)
            ->setUpdatedAt($now)
        ;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function onUpdate()
    {
        $this->setUpdatedAt(new \DateTime("now"));
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ingredients = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
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
     * Set name
     *
     * @param string $name
     * @return Recipe
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Recipe
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
     * Set author
     *
     * @param string $author
     * @return Recipe
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Recipe
     */
    public function setState($state)
    {
        if (!in_array($state, array(self::STATE_AWAITING, self::STATE_PENDING, self::STATE_REFUSED, self::STATE_VALIDATED))) {
            throw new \InvalidArgumentException("Invalid state for recipe.");
        }
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set people
     *
     * @param integer $people
     * @return Recipe
     */
    public function setPeople($people)
    {
        $this->people = $people;

        return $this;
    }

    /**
     * Get people
     *
     * @return integer
     */
    public function getPeople()
    {
        return $this->people;
    }

    /**
     * Set cookingTime
     *
     * @param string $cookingTime
     * @return Recipe
     */
    public function setCookingTime($cookingTime)
    {
        $this->cookingTime = $cookingTime;

        return $this;
    }

    /**
     * Get cookingTime
     *
     * @return string
     */
    public function getCookingTime()
    {
        return $this->cookingTime;
    }

    /**
     * Set preparationTime
     *
     * @param string $preparationTime
     * @return Recipe
     */
    public function setPreparationTime($preparationTime)
    {
        $this->preparationTime = $preparationTime;

        return $this;
    }

    /**
     * Get preparationTime
     *
     * @return string
     */
    public function getPreparationTime()
    {
        return $this->preparationTime;
    }

    /**
     * Add ingredients
     *
     * @param \Tms\RecipeBundle\Entity\Ingredient $ingredient
     * @return Recipe
     */
    public function addIngredient(\Tms\RecipeBundle\Entity\Ingredient $ingredient)
    {
        $this->ingredients[] = $ingredient;

        return $this;
    }

    /**
     * Remove ingredients
     *
     * @param \Tms\RecipeBundle\Entity\Ingredient $ingredient
     */
    public function removeIngredient(\Tms\RecipeBundle\Entity\Ingredient $ingredient)
    {
        $this->ingredients->removeElement($ingredient);
    }

    /**
     * Get ingredients
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * Add ingredients
     *
     * @param ArrayCollection $ingredients
     * @return Recipe
     */
    public function setIngredients(ArrayCollection $ingredients)
    {
        foreach ($ingredients as $ingredient) {
            $ingredient->setRecipe($this);
        }

        $this->ingredients = $ingredients;
    }

    /**
     * Set preparation
     *
     * @param string $preparation
     * @return Recipe
     */
    public function setPreparation($preparation)
    {
        $this->preparation = $preparation;

        return $this;
    }

    /**
     * Get preparation
     *
     * @return string
     */
    public function getPreparation()
    {
        return $this->preparation;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Recipe
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Recipe
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set media
     *
     * @param \Tms\Bundle\MediaClientBundle\Entity\Media $media
     * @return Recipe
     */
    public function setMedia(\Tms\Bundle\MediaClientBundle\Entity\Media $media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \Tms\Bundle\MediaClientBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }
}
