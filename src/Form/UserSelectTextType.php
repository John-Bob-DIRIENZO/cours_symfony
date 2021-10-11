<?php

namespace App\Form;

use App\Form\DataTransformer\EmailToUserTransformer;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSelectTextType extends AbstractType
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'invalid_message' => 'C\'est cassé...',
            'finder_callback' => function(UserRepository $userRepository, string $email) {
                return $userRepository->findOneBy([
                    'email' => $email
                ]);
            }
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // J'injecte mon Repo ET mon callback
        $builder->addModelTransformer(new EmailToUserTransformer(
            $this->userRepository,
            $options['finder_callback']
        ));
    }




    // Les types n'utilisent pas un vrai système
    // d'héritage mais cette fonction signifie que
    // à moins qu'on le spécifie autrement, cette classe
    // se comportera comme un EmailType
    public function getParent()
    {
        return TextType::class;
    }

}

