<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Artwork;
use AppBundle\Entity\Participant;
use AppBundle\Form\Artwork\ArtworkEdit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/artworks")
 *
 * Class ArtworkController
 * @package AppBundle\Controller
 */
class ArtworkController extends Controller
{
    /**
     * @Route(
     *      "/",
     *      name="artworks",
     *      requirements= {
     *          "_method": "GET"
     *      }
     * )
     */
    public function indexAction(Request $request)
    {
        return $this->render('artwork/index.html.twig', [
            'artworks' => $this->getDoctrine()->getRepository(Artwork::class)->findAll(),
        ]);
    }

    /**
     * @Route("/add", name="artwork_add")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $artwork = new Artwork();
        $form = $this->getEditForm($artwork);

        if ($this->processForm($form, $request)) {
            return $this->redirect($this->generateUrl('artwork_show', ['id' => $artwork->getId()]));
        }

        return $this->render('artwork/edit.html.twig', [
            'artwork' => $artwork,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(
     *      "/{id}",
     *      name="artwork_show",
     *      requirements={
     *          "_method": "GET",
     *          "id": "\d+"
     *      }
     * )
     * @ParamConverter("artwork", options={"repository_method" = "findWithParticipants"})
     *
     * @param Artwork $artwork
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Artwork $artwork)
    {
        return $this->render('artwork/show.html.twig', [
            'artwork' => $artwork,
        ]);
    }

    /**
     * @Route(
     *      "/{id}/edit",
     *      name="artwork_edit",
     *      requirements={
     *          "id": "\d+"
     *      }
     * )
     * @ParamConverter("artwork", options={"repository_method" = "findWithParticipants"})
     *
     * @param Artwork $artwork
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Artwork $artwork, Request $request)
    {
        $form = $this->getEditForm($artwork);

        if ($this->processForm($form, $request)) {
            return $this->redirect($this->generateUrl('artwork_show', ['id' => $artwork->getId()]));
        }

        return $this->render('artwork/edit.html.twig', [
            'artwork' => $artwork,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Artwork $artwork
     * @return \Symfony\Component\Form\Form
     */
    private function getEditForm(Artwork $artwork)
    {
        return $this->createForm(new ArtworkEdit(), $artwork);
    }

    /**
     * @param Form $form
     * @param Request $request
     * @return bool
     */
    private function processForm(Form $form, Request $request)
    {
        $participants = $form->getData()->getParticipants()->toArray();

        $form->handleRequest($request);

        if (!$form->isValid()) {
            return false;
        }

        // Gestion de la suppression des participants
        $participantOm = $this->getDoctrine()->getManagerForClass(Participant::class);
        foreach ($participants as $participant) {
            if (!$form->getData()->getParticipants()->contains($participant)) {
                $participantOm->remove($participant);
            }
        }
        $participantOm->flush();

        $artworkOm = $this->getDoctrine()->getManagerForClass(Artwork::class);
        $artworkOm->persist($form->getData());
        $artworkOm->flush();

        return true;
    }
}
