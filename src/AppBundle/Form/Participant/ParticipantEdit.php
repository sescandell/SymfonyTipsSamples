<?php

namespace AppBundle\Form\Participant;


use AppBundle\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParticipantEdit extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullname');

        if (Participant::TYPE_PUBLISHER == $options['type']) {
            $builder->add('company');
        }

        /*
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($options){
            // On pourrait forcer le champ "type" ici plutôt que dans Participant::setAuthors
            $event->getData()->setType($options['type']);
        });
        // */
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired([
            'type'
        ]);
        $resolver->addAllowedTypes([
            'type' => ['string']
        ]);
        $resolver->setAllowedValues([
            'type' => Participant::getAllTypes()
        ]);

        $resolver->setDefaults([
            'data_class' => Participant::class
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'participant_edit';
    }
}