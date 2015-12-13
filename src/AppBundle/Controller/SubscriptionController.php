<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Subscription;
use AppBundle\Entity\Reference;
use AppBundle\Form\Subscription\SubscriptionEdit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/subscriptions")
 *
 * Class SubscriptionController
 * @package AppBundle\Controller
 */
class SubscriptionController extends Controller
{
    /**
     * @Route(
     *      "/",
     *      name="subscriptions",
     *      requirements= {
     *          "_method": "GET"
     *      }
     * )
     */
    public function indexAction(Request $request)
    {
        return $this->render('subscription/index.html.twig', [
            'subscriptions' => $this->getDoctrine()->getRepository(Subscription::class)->findAll(),
        ]);
    }

    /**
     * @Route("/add-company", name="subscription_add_company")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addCompanyAction(Request $request)
    {
        $subscription = new Subscription();
        $subscription->setType(Reference::COMPANY);
        $form = $this->getEditForm($subscription);

        if ($this->processForm($form, $request)) {
            return $this->redirect($this->generateUrl('subscription_show', ['id' => $subscription->getId()]));
        }

        return $this->render('subscription/edit.html.twig', [
            'subscription' => $subscription,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/add-user", name="subscription_add_user")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addUserAction(Request $request)
    {
        $subscription = new Subscription();
        $subscription->setType(Reference::USER);
        $form = $this->getEditForm($subscription);

        if ($this->processForm($form, $request)) {
            return $this->redirect($this->generateUrl('subscription_show', ['id' => $subscription->getId()]));
        }

        return $this->render('subscription/edit.html.twig', [
            'subscription' => $subscription,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(
     *      "/{id}",
     *      name="subscription_show",
     *      requirements={
     *          "_method": "GET",
     *          "id": "\d+"
     *      }
     * )
     * @ParamConverter("subscription", options={"repository_method" = "findWithImages"})
     *
     * @param Subscription $subscription
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Subscription $subscription)
    {
        return $this->render('subscription/show.html.twig', [
            'subscription' => $subscription,
        ]);
    }

    /**
     * @Route(
     *      "/{id}/edit",
     *      name="subscription_edit",
     *      requirements={
     *          "id": "\d+"
     *      }
     * )
     * @ParamConverter("subscription", options={"repository_method" = "findWithImages"})
     *
     * @param Subscription $subscription
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Subscription $subscription, Request $request)
    {
        $form = $this->getEditForm($subscription);

        if ($this->processForm($form, $request)) {
            return $this->redirect($this->generateUrl('subscription_show', ['id' => $subscription->getId()]));
        }

        return $this->render('subscription/edit.html.twig', [
            'subscription' => $subscription,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Subscription $subscription
     * @return \Symfony\Component\Form\Form
     */
    private function getEditForm(Subscription $subscription)
    {
        return $this->createForm(new SubscriptionEdit($this->getDoctrine()->getRepository(Reference::class)), $subscription);
    }

    /**
     * @param Form $form
     * @param Request $request
     * @return bool
     */
    private function processForm(Form $form, Request $request)
    {
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return false;
        }

        $om = $this->getDoctrine()->getManagerForClass(Subscription::class);
        $om->persist($form->getData());
        $om->flush();

        return true;
    }
}
