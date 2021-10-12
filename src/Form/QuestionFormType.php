<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionFormType extends AbstractType
{
    private UserRepository $ur;

    public function __construct(UserRepository $ur)
    {
        $this->ur = $ur;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $question = $options['data'] ?? null;
        $isEdit = $question && $question->getId();

        $builder
            ->add('name', TextType::class, [
                'help' => 'Je suis une aide pas très utile'
            ])
            ->add('question')
            ->add('askedAt', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('user', UserSelectTextType::class, [
                'disabled' => $isEdit // Vérification aussi coté server
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
            'include_askedAt' => false
        ]);
    }
}
