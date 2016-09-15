<?php
namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Okto\MediaBundle\Entity\Tag;

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
     * @param  Tag|null $tag
     * @return string
     */
    public function transform($tags)
    {
        if (null === $tags) {
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
     * @param  string $text
     *
     * @return Tag|null
     *
     * @throws TransformationFailedException if object (tag) is not found.
     */
    public function reverseTransform($texts)
    {
        if (!$texts) {
            return null;
        }
        $tags = array();
        foreach ($texts as $text) {
            $tag = $this->repository->findOneBy(['text' => $text]);
            if (null === $tag) {
                throw new TransformationFailedException(sprintf(
                    'A tag with key "%s" does not exist!',
                    $text
                ));
            }
            $tags[] = $tag;
        }

        return $tags;
    }
}
