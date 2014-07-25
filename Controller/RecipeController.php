<?php

namespace Tms\RecipeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tms\RecipeBundle\Entity\Recipe;
use Tms\RecipeBundle\Entity\Ingredient;
use Tms\RecipeBundle\Form\RecipeCreateType;
use Tms\RecipeBundle\Form\RecipeUpdateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Recipe controller.
 *
 * @Route("/recipe")
 */
class RecipeController extends Controller
{

    /**
     * Lists all recipes
     *
     * @Route("/", name="recipe")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $recipes = $em
            ->getRepository('TmsRecipeBundle:Recipe')
            ->findAll()
        ;

        return array(
            'recipes' => $recipes,
        );
    }
    /**
     * Creates a new recipe
     *
     * @Route("/", name="recipe_create")
     * @Method("POST")
     * @Template("TmsRecipeBundle:Recipe:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $recipe = new Recipe();
        $form = $this->createForm(new RecipeCreateType(), $recipe, array(
            'action' => $this->generateUrl('recipe_create'),
            'method' => 'POST',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $recipe->setState(Recipe::STATE_AWAITING);
            $em->persist($recipe);
            foreach ($recipe->getIngredients() as $ingredient) {
                $ingredient->setRecipe($recipe);
                $em->persist($ingredient);
            }
            $em->flush();

            return $this->redirect($this->generateUrl('recipe_show', array('id' => $recipe->getId())));
        }

        return array(
            'recipe' => $recipe,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new recipe
     *
     * @Route("/new", name="recipe_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $recipe = new Recipe();
        $ingredient1 = new Ingredient();
        $recipe->getIngredients()->add($ingredient1);
        $ingredient2 = new Ingredient();
        $recipe->getIngredients()->add($ingredient2);
        $form = $this->createForm(new RecipeCreateType(), $recipe, array(
            'action' => $this->generateUrl('recipe_create'),
            'method' => 'POST',
        ));

        return array(
            'recipe' => $recipe,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a recipe
     *
     * @Route("/{id}", name="recipe_show")
     * @ParamConverter("recipe", options={"mapping": {"id": "id"}})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Recipe $recipe)
    {
        $refuseForm = $this->createUpdateForm(
            $this->generateUrl('recipe_change_state', array(
                'id'    => $recipe->getId(),
                'state' => Recipe::STATE_REFUSED
            )),
            "Refuser la recette",
            "btn-danger"
        );
        $validateForm = $this->createUpdateForm(
            $this->generateUrl('recipe_change_state', array(
                'id'    => $recipe->getId(),
                'state' => Recipe::STATE_VALIDATED
            )),
            "Accepter la recette",
            "btn-success"
        );
        $editForm = $this->createUpdateForm(
            $this->generateUrl('recipe_edit', array(
                'id' => $recipe->getId()
            )),
            "Modifier"
        );

        return array(
            'recipe'        => $recipe,
            'refuse_form'   => $refuseForm->createView(),
            'validate_form' => $validateForm->createView(),
            'edit_form'     => $editForm->createView()
        );
    }

    /**
     * Displays a form to edit an existing recipe
     *
     * @Route("/{id}/edit", name="recipe_edit")
     * @ParamConverter("recipe", options={"mapping": {"id": "id"}})
     * @Method({"PATCH", "POST"})
     * @Template()
     */
    public function editAction(Recipe $recipe)
    {
        $em = $this->getDoctrine()->getManager();
        $recipe->setState(Recipe::STATE_PENDING);
        $em->flush();

        $editForm = $this->createForm(new RecipeUpdateType(), $recipe, array(
            'action' => $this->generateUrl('recipe_update', array(
                'id' => $recipe->getId()
            )),
            'method' => 'PUT',
        ));
        $refuseForm = $this->createUpdateForm(
            $this->generateUrl('recipe_change_state', array(
                'id'    => $recipe->getId(),
                'state' => Recipe::STATE_REFUSED
            )),
            "Refuser la recette",
            "btn-danger"
        );
        $validateForm = $this->createUpdateForm(
            $this->generateUrl('recipe_change_state', array(
                'id'    => $recipe->getId(),
                'state' => Recipe::STATE_VALIDATED
            )),
            "Accepter la recette",
            "btn-success"
        );

        return array(
            'recipe'        => $recipe,
            'edit_form'     => $editForm->createView(),
            'refuse_form'   => $refuseForm->createView(),
            'validate_form' => $validateForm->createView(),
        );
    }

    /**
     * Edits an existing recipe
     *
     * @Route("/{id}", name="recipe_update")
     * @Method("PUT")
     * @Template("TmsRecipeBundle:Recipe:edit.html.twig")
     */
    public function updateAction(Request $request, Recipe $recipe)
    {
        $editForm = $this->createForm(new RecipeUpdateType(), $recipe, array(
            'action' => $this->generateUrl('recipe_update', array(
                'id' => $recipe->getId()
            )),
            'method' => 'PUT',
        ));

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach ($recipe->getIngredients() as $ingredient) {
                $ingredient->setRecipe($recipe);
                $em->persist($ingredient);
            }

            if ($editForm->get('save_and_validate')->isClicked()) {
                $recipe->setState(Recipe::STATE_VALIDATED);
            } else if ($editForm->get('save_and_unvalidate')->isClicked()) {
                $recipe->setState(Recipe::STATE_REFUSED);
            }
            $em->flush();

            return $this->redirect($this->generateUrl('recipe'));
        }

        return array(
            'recipe'      => $recipe,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Validates an existing recipe
     *
     * @Route("/{id}/change-state/{state}", name="recipe_change_state")
     * @ParamConverter("recipe", options={"mapping": {"id": "id"}})
     * @Method({"PATCH", "POST"})
     */
    public function changeStateAction(Recipe $recipe, $state)
    {
        if (!in_array($state, array(
            Recipe::STATE_AWAITING,
            Recipe::STATE_VALIDATED,
            Recipe::STATE_REFUSED,
            Recipe::STATE_PENDING)
        )) {
            throw new \InvalidArgumentException();
        }

        $recipe->setState($state);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirect($this->generateUrl('recipe'));
    }
    
    /**
     * Creates a form to update a recipe
     *
     * @param string  $action Form action
     * @param string  $label Label to display in the form submit
     * @param string  $class Class to set in the form submit
     * @param string  $method Form Method
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createUpdateForm($action, $label, $class = "", $method = 'POST')
    {
        return $this->createFormBuilder()
            ->setAction($action)
            ->setMethod($method)
            ->add('submit', 'submit', array(
                'label' => $label,
                'attr'  => array(
                    'class' => sprintf("btn btn-block %s", $class)
                )
            ))
            ->getForm()
        ;
    }
}
