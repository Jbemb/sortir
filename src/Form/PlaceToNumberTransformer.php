<?php
namespace App\Form;

use App\Entity\Place;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class PlaceToNumberTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (place) to a string (number).
     *
     * @param  Place|null $place
     * @return string
     */
    public function transform($place)
    {
        if (null === $place) {
            return '';
        }

        return $place->getId();
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $placeNumber
     * @return Place|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($placeNumber)
    {
        // no issue number? It's optional, so that's ok
        if (!$placeNumber) {
            return;
        }

        $place = $this->entityManager
            ->getRepository(Place::class)
            // query for the issue with this id
            ->find($placeNumber)
        ;

        if (null === $place) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An issue with number "%s" does not exist!',
                $placeNumber
            ));
        }

        return $place;
    }
}
