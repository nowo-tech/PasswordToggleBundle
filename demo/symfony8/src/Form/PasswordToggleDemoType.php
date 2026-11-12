<?php

declare(strict_types=1);

namespace App\Form;

use Nowo\FormKitBundle\Attribute\FormKitConfig;
use Nowo\FormKitBundle\Form\FormOptionsTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * REQ-DEMO-015: a FormKit form (profile `demo`); the bundle's PasswordType is added through FormKit's
 * addPasswordToggle().
 *
 * @extends AbstractType<array{username: string|null, password: string|null}>
 */
#[FormKitConfig('demo')]
final class PasswordToggleDemoType extends AbstractType
{
    use FormOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addText($builder, 'username', [
            'label'       => 'Username',
            'placeholder' => 'Enter your username',
            'help'        => false,
        ]);
        $this->addPasswordToggle($builder, 'password', [
            'label'                    => 'Password',
            'help'                     => 'Click the eye icon to toggle password visibility',
            'placeholder'              => 'Enter your password',
            'toggle'                   => true,
            'visible_icon'             => 'tabler:eye-off',
            'hidden_icon'              => 'tabler:eye',
            'visible_label'            => 'Show password',
            'hidden_label'             => 'Hide password',
            'toggle_container_classes' => ['form-password-toggle'],
        ]);
        $this->addWithDefaults($builder, 'submit', SubmitType::class, [
            'label' => 'Submit',
            'attr'  => ['class' => 'nowo-ui-btn nowo-ui-btn--primary'],
        ]);
    }
}
