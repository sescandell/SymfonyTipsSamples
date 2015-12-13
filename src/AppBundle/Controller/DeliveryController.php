<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Delivery;
use AppBundle\Form\Delivery\Step1;
use AppBundle\Form\Delivery\Step2;
use AppBundle\Form\Delivery\Step3;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/deliveries")
 *
 * Class DeliveryController
 * @package AppBundle\Controller
 */
class DeliveryController extends Controller
{
    /**
     * @Route(
     *      "/",
     *      name="deliveries",
     *      requirements= {
     *          "_method": "GET"
     *      }
     * )
     */
    public function indexAction(Request $request)
    {
        return $this->render('delivery/index.html.twig', [
            'deliveries' => $this->getDoctrine()->getRepository(Delivery::class)->findAll(),
        ]);
    }

    /**
     * @Route("/step1", name="delivery_step1")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function step1Action(Request $request)
    {
        $delivery = new Delivery();
        $form = $this->createForm(new Step1(), $delivery, ['validation_groups' => false]);

        if ($this->processForm($form, $request)) {
            $token = sha1(time()); // TODO: en situation réelle, utiliser un vrai token aléatoire
            $request->getSession()->set('delivery_form_'.$token, $delivery->getId());

            return $this->redirect($this->generateUrl('delivery_step2', ['token' => $token]));
        }

        return $this->render('delivery/step.html.twig', [
            'delivery' => $delivery,
            'form' => $form->createView(),
            'stepNumber' => 1
        ]);
    }

    /**
     * @Route("/step2/{token}", name="delivery_step2")
     *
     * @param string $token
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function step2Action($token, Request $request)
    {
        $deliveryId = $request->getSession()->get('delivery_form_'.$token, null);
        if (null == $deliveryId) {
            return $this->createNotFoundException();
        }

        $delivery = $this->getDoctrine()->getRepository(Delivery::class)->find($deliveryId);
        if (null == $delivery) {
            return $this->createNotFoundException();
        }

        $form = $this->createForm(new Step2(), $delivery, ['validation_groups' => false]);

        if ($this->processForm($form, $request)) {
            return $this->redirect($this->generateUrl('delivery_step3', ['token' => $token]));
        }

        return $this->render('delivery/step.html.twig', [
            'delivery' => $delivery,
            'form' => $form->createView(),
            'stepNumber' => 2
        ]);
    }

    /**
     * @Route("/step3/{token}", name="delivery_step3")
     *
     * @param string $token
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function step3Action($token, Request $request)
    {
        $deliveryId = $request->getSession()->get('delivery_form_'.$token, null);
        if (null == $deliveryId) {
            return $this->createNotFoundException();
        }

        $delivery = $this->getDoctrine()->getRepository(Delivery::class)->find($deliveryId);
        if (null == $delivery) {
            return $this->createNotFoundException();
        }

        $validator = $this->get('validator');
        $errors = $validator->validate($delivery);

        $form = $this->createForm(new Step3(), $delivery);
        $delivery->setStatus(Delivery::CONFIRMED);

        if ($this->processForm($form, $request)) {
            $request->getSession()->remove('delivery_form_'.$token);
            return $this->redirect($this->generateUrl('deliveries'));
        }

        return $this->render('delivery/step.html.twig', [
            'delivery' => $delivery,
            'form' => $form->createView(),
            'errors' => $errors,
            'stepNumber' => 3
        ]);
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

        $om = $this->getDoctrine()->getManagerForClass(Delivery::class);
        $om->persist($form->getData());
        $om->flush();

        return true;
    }
}
