<?php

declare(strict_types=1);

namespace App\Form;

use Nowo\FormKitBundle\Attribute\FormKitConfig;
use Nowo\FormKitBundle\Form\FormOptionsTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * REQ-DEMO-015: a FormKit form (profile `demo`) with Symfony's own PasswordType (alone and in a RepeatedType), which
 * gets the toggle through `nowo_password_toggle.extend_core_password_type: true`; `toggle: false` opts a field out.
 *
 * @extends AbstractType<array<string, mixed>>
 */
#[FormKitConfig('demo')]
final class CorePasswordDemoType extends AbstractType
{
    use FormOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addText($builder, 'username', [
            'label'       => 'Username',
            'placeholder' => false,
            'help'        => false,
        ]);
        $this->addPassword($builder, 'current', [
            'label'       => 'Current password (Symfony PasswordType)',
            'placeholder' => false,
            'help'        => false,
        ]);
        $this->addWithDefaults($builder, 'new', RepeatedType::class, [
            'type'           => PasswordType::class,
            'first_options'  => ['label' => 'New password (RepeatedType)'],
            'second_options' => ['label' => 'Repeat it'],
        ]);
        $this->addPassword($builder, 'pin', [
            'label'       => 'PIN (toggle: false, opted out)',
            'toggle'      => false,
            'placeholder' => false,
            'help'        => false,
        ]);
        $this->addWithDefaults($builder, 'submit', SubmitType::class, [
            'label' => 'Submit',
            'attr'  => ['class' => 'nowo-ui-btn nowo-ui-btn--primary'],
        ]);
    }
}
