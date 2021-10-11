<?php

namespace App\Form\DataTransformer;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EmailToUserTransformer implements DataTransformerInterface
{
    private UserRepository $userRepository;

    /**
     * @var callable
     */
    private $finder_callback;

    // Pour le récupérer là
    public function __construct(UserRepository $userRepository, callable $finder_callback)
    {
        $this->userRepository = $userRepository;
        $this->finder_callback = $finder_callback;
    }

    public function transform($value)
    {
        if ($value === null) {
            return '';
        }
        if (!$value instanceof User) {
            throw new \LogicException('N\'utilisez ce Type que sur User');
        }

        return $value->getEmail();
    }

    public function reverseTransform($value)
    {
        $callback = $this->finder_callback;
        $user = $callback($this->userRepository, $value);

        if (!$user) {
            // Un type d'erreur spécifique, qui va se render
            // comme erreur de notre formulaire, ce qui est sympa
            throw new TransformationFailedException('cassé !');
        }

        return $user;
    }
}