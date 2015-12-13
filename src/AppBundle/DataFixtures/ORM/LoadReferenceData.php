<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Reference;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadReferenceData implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $references = [
            [
                'name' => 'file',
                'type' => Reference::COMPANY,
                'label' => 'PDG'
            ], [
                'name' => 'file',
                'type' => Reference::COMPANY,
                'label' => 'Logo'
            ], [
                'name' => 'file',
                'type' => Reference::COMPANY,
                'label' => 'Signature'
            ], [
                'name' => 'file',
                'type' => Reference::USER,
                'label' => 'Cover'
            ], [
                'name' => 'file',
                'type' => Reference::USER,
                'label' => 'Picture'
            ]
        ];

        foreach ($references as $reference) {
            $manager->persist($this->generateEntity($reference));
        }

        $manager->flush();
    }

    /**
     * @param $attributes
     * @return Reference
     */
    private function generateEntity($attributes)
    {
        $reference = new Reference();
        $reference->setName($attributes['name']);
        $reference->setType($attributes['type']);
        $reference->setLabel($attributes['label']);

        return $reference;
    }
}