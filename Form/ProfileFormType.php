<?php
namespace Aescarcha\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseForm;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('language', ChoiceType::class, array(
                      'label' => 'Language',
                      'choices' => ['es' => 'Español', 'en' => 'English']
                      ));

        $builder->add('bio', TextareaType::class, array(
                      'label' => 'Biography',
                      'required' => false,
                      ));

        $builder->add('country', CountryType::class, array(
                      'label' => 'Country',
                      'translation_domain' => 'messages',
                      ));

        $builder->add('birthday', BirthdayType::class,array(
                      'label' => 'Birthday',
                      'years' => range(date('Y'), 1900)
                      ));

        $builder->remove('current_password');

    }

    public function getParent()
    {
        return BaseForm::class;
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }
}