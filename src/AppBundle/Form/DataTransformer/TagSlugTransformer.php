<?php
namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Okto\MediaBundle\Entity\Tag;

class TagSlugTransformer implements DataTransformerInterface
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
        return $tags;
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
    public function reverseTransform($tag_forms)
    {
        if (!$tag_forms) {
            return null;
        }
        $tags = array();
        foreach ($tag_forms as $tag_form) {
            $tag = $this->repository->findOneBy(['slug' => $tag_form->getSlug()]);
            if (null === $tag) {
                throw new TransformationFailedException(sprintf(
                    'A tag with key "%s" does not exist!',
                    $tag_form->getSlug()
                ));
            }
            $tag->setRank($tag_form->getRank());
            $tags[] = $tag;
        }

        return $tags;
    }
}
