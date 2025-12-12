<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\Form\Type;

use Symfony\Component\Form\{AbstractType, FormInterface, FormView};
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for password fields with toggle visibility functionality.
 *
 * This form type extends TextType and adds a toggle button to show/hide the password value.
 * It provides a user-friendly way to reveal passwords when needed while maintaining security
 * by defaulting to hidden input.
 *
 * The form type includes:
 * - Toggle button with customizable icons (hidden/visible states)
 * - Customizable labels for accessibility
 * - Configurable CSS classes for styling
 * - Native JavaScript implementation for maximum compatibility
 *
 * @author Héctor Franco Aceituno <hectorfranco@nowo.tech>
 * @copyright 2024 Nowo.tech
 */
final class PasswordType extends AbstractType
{
  /**
   * Returns the parent form type.
   *
   * This form type extends TextType to inherit its basic functionality,
   * including validation, rendering, and data handling capabilities.
   *
   * @return string The parent form type class name
   */
  public function getParent(): string
  {
    return TextType::class;
  }

  /**
   * Configures the options for this form type.
   *
   * Sets default values for toggle functionality, icons, labels, and CSS classes.
   * All options can be overridden when using this form type in a form builder.
   *
   * @param OptionsResolver $resolver The options resolver to configure
   *
   * @return void
   */
  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'always_empty'             => true,
      'trim'                     => false,
      'invalid_message'          => 'The password is invalid.',
      'button_classes'           => ['input-group-text', 'cursor-pointer'],
      'hidden_icon'              => 'tabler:eye',
      'hidden_label'             => 'Hide',
      'toggle'                   => true,
      'toggle_container_classes' => ['form-password-toggle'],
      'use_toggle_form_theme'    => true,
      'visible_icon'             => 'tabler:eye-off',
      'visible_label'            => 'Show'
    ]);
  }

  /**
   * Passes configuration variables to the Twig template.
   *
   * Makes all form options available as template variables for rendering.
   * This allows the Twig template to access toggle settings, icons, labels,
   * and CSS classes to render the password field with toggle functionality.
   *
   * @param FormView             $view    The form view to build
   * @param FormInterface        $form    The form interface instance
   * @param array<string, mixed> $options The form options array
   *
   * @return void
   */
  public function buildView(FormView $view, FormInterface $form, array $options): void
  {
    $view->vars['toggle']                   = $options['toggle'];
    $view->vars['toggle_container_classes'] = $options['toggle_container_classes'];
    $view->vars['button_classes']           = $options['button_classes'];
    $view->vars['visible_icon']             = $options['visible_icon'];
    $view->vars['hidden_icon']              = $options['hidden_icon'];
    $view->vars['visible_label']            = $options['visible_label'];
    $view->vars['hidden_label']             = $options['hidden_label'];
  }

  /**
   * Returns the block prefix used to identify this form type in Twig templates.
   *
   * Symfony will look for the {% block toggle_password_widget %} in form themes
   * to render this form type. The block prefix determines which Twig block is used.
   *
   * @return string The block prefix identifier
   */
  public function getBlockPrefix(): string
  {
    return 'toggle_password';
  }
}
