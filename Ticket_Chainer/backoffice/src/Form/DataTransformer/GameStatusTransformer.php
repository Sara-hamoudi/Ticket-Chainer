<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use TicketChainer\ApiBundle\Enum\GameStatus;

class GameStatusTransformer implements DataTransformerInterface
{

    public function transform($statusFlag)
    {
        if ($statusFlag === GameStatus::PUBLISHED) {
            return true;
        }
        return false;

    }

    public function reverseTransform($isPublished)
    {
        if (true === $isPublished) {
            return GameStatus::PUBLISHED;
        }
        return GameStatus::UNPUBLISHED;
    }
}
