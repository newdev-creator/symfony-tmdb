<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class BookRatingDto
{
    public function __construct(
        #[Assert\NotNull]
        #[Assert\Range(min: 1, max: 5)]
        public int $rating,
    ) {
    }
}
