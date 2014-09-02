<?php

/**
 * @author Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
 */

namespace Tms\Bundle\RecipeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Tms\Bundle\RecipeBundle\Entity\Repository\IngredientRepository")
 * @ORM\Table(name="ingredient")
 * @ORM\HasLifecycleCallbacks()
 */
class Ingredient
{
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
     * @ORM\ManyToOne(targetEntity="Recipe", inversedBy="ingredients")
     * @ORM\JoinColumn(name="recipe_id", referencedColumnName="id", nullable=true)
     */
    protected $recipe;

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
     * @return Ingredient
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
     * Set recipe
     *
     * @param \Tms\Bundle\RecipeBundle\Entity\Recipe $recipe
     * @return Ingredient
     */
    public function setRecipe(\Tms\Bundle\RecipeBundle\Entity\Recipe $recipe = null)
    {
        $this->recipe = $recipe;

        return $this;
    }

    /**
     * Get recipe
     *
     * @return \Tms\Bundle\RecipeBundle\Entity\Recipe
     */
    public function getRecipe()
    {
        return $this->recipe;
    }
}