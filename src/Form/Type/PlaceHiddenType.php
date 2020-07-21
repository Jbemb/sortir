<?php


namespace App\Form\Type;


use App\Form\Transformer\PlaceToNumberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Entity hidden custom type class definition
 */
class PlaceHiddenType extends AbstractType
{
    /**
     * @var PlaceToNumberTransformer $transformer
     */
    private $transformer;

    /**
     * Constructor
     *
     * @param PlaceToNumberTransformer $transformer
     */
    public function __construct(PlaceToNumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // attach the specified model transformer for this entity list field
        // this will convert data between object and string formats
        $builder->addModelTransformer($this->transformer);
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return HiddenType::class;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'entityhidden';
    }
}