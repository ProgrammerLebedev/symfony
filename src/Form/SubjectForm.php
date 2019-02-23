<?php 
namespace App\Form; 
use Symfony\Component\Form\AbstractType; 
use Symfony\Component\Form\Extension\Core\Type\IntegerType; 
use Symfony\Component\Form\FormBuilderInterface; 

class SubjectForm extends AbstractType { 
    public function buildForm(FormBuilderInterface $builder, array $options) 
    { 
        $builder ->add('number', IntegerType::class,['label' => 'Введите номер страницы']); 
    } 
}