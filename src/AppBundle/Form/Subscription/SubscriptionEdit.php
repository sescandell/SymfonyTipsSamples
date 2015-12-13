<?php

namespace AppBundle\Form\Subscription;


use AppBundle\Entity\Image;
use AppBundle\Entity\ReferenceRepository;
use AppBundle\Entity\Subscription;
use AppBundle\Form\Image\ImageEdit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SubscriptionEdit extends AbstractType
{
    /**
     * @var ReferenceRepository
     */
    private $referenceRepository;

    public function __construct(ReferenceRepository $referenceRepository)
    {
        $this->referenceRepository = $referenceRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('images', 'collection', [
                'allow_add' => false,
                'allow_delete' => false,
                'type' => new ImageEdit()
            ]);

        $referenceRepository = $this->referenceRepository;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($referenceRepository){
            /** @var Subscription $subscription */
            $subscription = $event->getData();
            $imageReferences = $referenceRepository->findBy([
                'name' => 'file',
                'type' => $subscription->getType()
            ]);

            // On ajoute les références non présentes dans les subscriptions
            foreach ($imageReferences as $imageReference) {
                if ($subscription->getImages()->filter(function($image) use ($imageReference){
                    return $image->getReference()->getId() == $imageReference->getId();
                })->isEmpty()) {
                    $image = new Image();
                    $image->setReference($imageReference);

                    $subscription->addImage($image);
                }
            }
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subscription::class
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'subscription_edit';
    }
}