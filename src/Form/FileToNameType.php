<?php

namespace App\Form;

use App\Form\DataTransformer\FileToNameTransformer;
use App\Service\UploadHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileToNameType extends AbstractType
{
    private string $projectDir;
    private UploadHelper $helper;

    public function __construct(string $projectDir, UploadHelper $helper)
    {
        $this->projectDir = $projectDir;
        $this->helper = $helper;
    }

    public function getParent()
    {
        return FileType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new FileToNameTransformer($this->helper));
    }
}