<?php
namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class PlaylistItemTransformer implements DataTransformerInterface
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
    public function transform($episode)
    {
        if (null === $episode) {
            return null;
        }

        return $episode->getUniqID();
    }

    /**
     * Transforms a string (key) to an object (playlistitem).
     *
     * @param  string $text
     *
     * @return Tag|null
     *
     * @throws TransformationFailedException if object (tag) is not found.
     */
    public function reverseTransform($uniqID)
    {
        if (!$uniqID) {
            return null;
        }
        $episode = $this->repository->findOneBy(['uniqID' => $uniqID]);
        if (null === $episode) {
            throw new TransformationFailedException(sprintf(
                'An episode with uniqID "%s" does not exist!',
                $uniqID
            ));
        }

        return $episode;
    }
}
