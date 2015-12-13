<?php

namespace AppBundle\Form\Artwork;


use AppBundle\Entity\Artwork;
use AppBundle\Entity\Participant;
use AppBundle\Form\Participant\ParticipantEdit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArtworkEdit extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('authors', 'collection', [
                'allow_add' => true,
                'allow_delete' => true,
                'type' => new ParticipantEdit(),
                'by_reference' => false, // http://symfony.com/doc/2.3/reference/forms/types/collection.html#by-reference
                'options' => [
                    'label' => false,
                    'type' => Participant::TYPE_AUTHOR
                ]
            ])
            ->add('publishers', 'collection', [
                'allow_add' => true,
                'allow_delete' => true,
                'type' => new ParticipantEdit(),
                'by_reference' => false, // http://symfony.com/doc/2.3/reference/forms/types/collection.html#by-reference
                'options' => [
                    'label' => false,
                    'type' => Participant::TYPE_PUBLISHER
                ]
            ]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'class' => Artwork::class
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'artwork_edit';
    }
}