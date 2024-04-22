<?php

namespace App\Form\DataTransformer;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CommentTransformer implements DataTransformerInterface
{
    public function __construct(
        // private EntityManagerInterface $entityManager,
        private readonly CommentRepository $commentRepository,
    ) {
    }


    /**
     * @param PersistentCollection<Comment> $value
     * @return string
     */
    public function transform($value): string
    {   
        
        if (null === $value) {
            return '';
        }
        $array = [];
        foreach($value as $comment) {
            $array[] = $comment->getText();
        }
        return implode(',', $array);
    }

    public function reverseTransform(mixed $value = null): ArrayCollection
    {
        if (!$value) {
            return new ArrayCollection();
        }
        $items = explode(",", $value);
        $items = array_map('trim', $items);
        $items = array_unique($items);

        $comments = new ArrayCollection();
        foreach ($items as $item) {
            $comment = $this->commentRepository->findOneBy(['text' => $item]);
            if(!$comment) {
                $comment = (new Comment()) -> setText($item);
            }
            $comments->add($comment);
        }
        return $comments;
    }
}