<?php
namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use AppBundle\Entity\Tag;

class TagTransformer implements DataTransformerInterface
{

    private $repository;

    /**
     * @param ObjectManager $om
     */
    public function __construct(\Doctrine\ORM\EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Transforms object (assets) to a string (key).
     *
     * @param  Asset|null $asset
     * @return string
     */
    public function transform($tags)
    {
        if (null == $tags) {
            return "";
        }
        $texts = [];

        foreach ($tags as $tag) {
            $texts[] = $tag->getText();
        }

        return $texts;
    }

    /**
     * Transforms a string (key) to an object (asset).
     *
     * @param  string $filekey
     *
     * @return Asset|null
     *
     * @throws TransformationFailedException if object (asset) is not found.
     */
    public function reverseTransform($texts)
    {
        if (!$texts) {
            return null;
        }
        $tags = [];
        foreach ($texts as $text) {
            $tag = $this->repository->findOneBy(array('text' => $text));
            if ($tag) {
                $tags[] = $tag;
            } else {
                throw new TransformationFailedException(sprintf(
                    'An tag with key "%s" does not exist!',
                    $text
                ));
            }
        }

        return $tags;
    }
}
