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
            $em->persist($recipe);
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
        $ingredient = new Ingredient();
        $recipe->getIngredients()->add($ingredient);
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
        return array(
            'recipe' => $recipe,
        );
    }

    /**
     * Displays a form to edit an existing recipe
     *
     * @Route("/{id}/edit", name="recipe_edit")
     * @ParamConverter("recipe", options={"mapping": {"id": "id"}})
     * @Method("GET")
     * @Template()
     */
    public function editAction(Recipe $recipe)
    {
        $editForm = $this->createForm(new RecipeUpdateType(), $recipe, array(
            'action' => $this->generateUrl('recipe_update', array(
                'id' => $recipe->getId()
            )),
            'method' => 'PUT',
        ));

        return array(
            'recipe'      => $recipe,
            'edit_form'   => $editForm->createView(),
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
            $em->flush();

            if ($editForm->get('save_and_validate')->isClicked()) {
                return $this->redirect($this->generateUrl('recipe_validate', array('id' => $recipe->getId())));
            } else if ($editForm->get('save_and_unvalidate')->isClicked()) {
                return $this->redirect($this->generateUrl('recipe_unvalidate', array('id' => $recipe->getId())));
            }

            return $this->redirect($this->generateUrl('recipe_edit', array('id' => $recipe->getId())));
        }

        return array(
            'recipe'      => $recipe,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Validates an existing recipe
     *
     * @Route("/{id}/validate", name="recipe_validate")
     * @ParamConverter("recipe", options={"mapping": {"id": "id"}})
     * @Method("GET")
     * @Template()
     */
    public function validateAction(Recipe $recipe)
    {
        $recipe->setState(Recipe::STATE_VALIDATED);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirect($this->generateUrl('recipe'));
    }

    /**
     * Unvalidates an existing recipe
     *
     * @Route("/{id}/unvalidate", name="recipe_unvalidate")
     * @ParamConverter("recipe", options={"mapping": {"id": "id"}})
     * @Method("GET")
     * @Template()
     */
    public function unvalidateAction(Recipe $recipe)
    {
        $recipe->setState(Recipe::STATE_REFUSED);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirect($this->generateUrl('recipe'));
    }
}
