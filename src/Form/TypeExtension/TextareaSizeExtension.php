<?php

namespace App\Form\TypeExtension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeExtensionInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextareaSizeExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [TextareaType::class];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // Je passe la valeur par défaut de mon option
        $resolver->setDefaults([
            'rows' => 10
        ]);
    }
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // Et je la récupère ici
        $view->vars['attr']['rows'] = $options['rows'];
    }




}