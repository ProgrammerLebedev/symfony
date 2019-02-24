<?php 
namespace App\Form; 
use Symfony\Component\Form\AbstractType; 
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface; 

class AllImagesForm extends AbstractType { 
    public function buildForm(FormBuilderInterface $builder, array $options) 
    { 
        $builder ->add('url', TextType::class,['label' => 'Введите url страницы'])->add('script', CheckboxType::class, [
            'label'    => 'Учитывать скрипты ?',
            'required' => false,
        ]);; 
    } 
}