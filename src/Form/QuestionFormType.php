<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Clue\StreamFilter\fun;

class QuestionFormType extends AbstractType
{
    private UserRepository $ur;

    public function __construct(UserRepository $ur)
    {
        $this->ur = $ur;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'help' => 'Je suis une aide pas très utile'
            ])
            ->add('question')
            ->add('askedAt', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('user', EntityType::class, [
                'label' => 'Qui est l\'auteur ?',
                'placeholder' => 'Select author',
                'class' => User::class,
                'choices' => $this->ur->findAllNonAdmin(),
                'choice_label' => function (User $user) {
                    return sprintf('#%d | %s | %s',
                        $user->getId(),
                        $user->getFirstName(),
                        $user->getEmail());
                }
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class
        ]);
    }
}
