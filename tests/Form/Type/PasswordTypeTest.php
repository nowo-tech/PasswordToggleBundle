<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\Tests\Form\Type;

use Nowo\PasswordToggleBundle\Form\Type\PasswordType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\{FormInterface, FormView};
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Tests for PasswordType form type.
 *
 * @author Héctor Franco Aceituno <hectorfranco@nowo.com>
 * @copyright 2024 Nowo.tech
 */
final class PasswordTypeTest extends TestCase
{
  private PasswordType $formType;

  protected function setUp(): void
  {
    $this->formType = new PasswordType();
  }

  public function testGetParent(): void
  {
    $this->assertSame(TextType::class, $this->formType->getParent());
  }

  public function testGetBlockPrefix(): void
  {
    $this->assertSame('toggle_password', $this->formType->getBlockPrefix());
  }

  public function testConfigureOptionsSetsDefaults(): void
  {
    $resolver = new OptionsResolver();
    $this->formType->configureOptions($resolver);

    $resolved = $resolver->resolve([]);

    $this->assertTrue($resolved['always_empty']);
    $this->assertFalse($resolved['trim']);
    $this->assertSame('The password is invalid.', $resolved['invalid_message']);
    $this->assertSame(['input-group-text', 'cursor-pointer'], $resolved['button_classes']);
    $this->assertSame('tabler:eye', $resolved['hidden_icon']);
    $this->assertSame('Hide', $resolved['hidden_label']);
    $this->assertTrue($resolved['toggle']);
    $this->assertSame(['form-password-toggle'], $resolved['toggle_container_classes']);
    $this->assertTrue($resolved['use_toggle_form_theme']);
    $this->assertSame('tabler:eye-off', $resolved['visible_icon']);
    $this->assertSame('Show', $resolved['visible_label']);
  }

  public function testConfigureOptionsAllowsCustomOptions(): void
  {
    $resolver = new OptionsResolver();
    $this->formType->configureOptions($resolver);

    $customOptions = [
      'toggle'                   => false,
      'visible_icon'             => 'custom:eye-off',
      'hidden_icon'              => 'custom:eye',
      'visible_label'            => 'Mostrar',
      'hidden_label'             => 'Ocultar',
      'button_classes'           => ['custom-button'],
      'toggle_container_classes' => ['custom-container'],
    ];

    $resolved = $resolver->resolve($customOptions);

    $this->assertFalse($resolved['toggle']);
    $this->assertSame('custom:eye-off', $resolved['visible_icon']);
    $this->assertSame('custom:eye', $resolved['hidden_icon']);
    $this->assertSame('Mostrar', $resolved['visible_label']);
    $this->assertSame('Ocultar', $resolved['hidden_label']);
    $this->assertSame(['custom-button'], $resolved['button_classes']);
    $this->assertSame(['custom-container'], $resolved['toggle_container_classes']);
  }

  public function testBuildViewPassesOptionsToView(): void
  {
    $view = new FormView();
    $form = $this->createMock(FormInterface::class);

    $options = [
      'toggle'                   => true,
      'toggle_container_classes' => ['form-password-toggle', 'custom'],
      'button_classes'           => ['input-group-text', 'cursor-pointer'],
      'visible_icon'             => 'tabler:eye-off',
      'hidden_icon'              => 'tabler:eye',
      'visible_label'            => 'Show',
      'hidden_label'             => 'Hide',
    ];

    $this->formType->buildView($view, $form, $options);

    $this->assertTrue($view->vars['toggle']);
    $this->assertSame(['form-password-toggle', 'custom'], $view->vars['toggle_container_classes']);
    $this->assertSame(['input-group-text', 'cursor-pointer'], $view->vars['button_classes']);
    $this->assertSame('tabler:eye-off', $view->vars['visible_icon']);
    $this->assertSame('tabler:eye', $view->vars['hidden_icon']);
    $this->assertSame('Show', $view->vars['visible_label']);
    $this->assertSame('Hide', $view->vars['hidden_label']);
  }

  public function testBuildViewWithToggleDisabled(): void
  {
    $view = new FormView();
    $form = $this->createMock(FormInterface::class);

    $options = [
      'toggle'                   => false,
      'toggle_container_classes' => [],
      'button_classes'           => [],
      'visible_icon'             => 'tabler:eye-off',
      'hidden_icon'              => 'tabler:eye',
      'visible_label'            => 'Show',
      'hidden_label'             => 'Hide',
    ];

    $this->formType->buildView($view, $form, $options);

    $this->assertFalse($view->vars['toggle']);
    $this->assertSame([], $view->vars['toggle_container_classes']);
    $this->assertSame([], $view->vars['button_classes']);
  }

  public function testBuildViewSetsAllVars(): void
  {
    $view = new FormView();
    $form = $this->createMock(FormInterface::class);

    $options = [
      'toggle'                   => true,
      'toggle_container_classes' => ['class1', 'class2'],
      'button_classes'           => ['btn', 'btn-primary'],
      'visible_icon'             => 'custom:eye-off',
      'hidden_icon'              => 'custom:eye',
      'visible_label'            => 'Mostrar',
      'hidden_label'             => 'Ocultar',
    ];

    $this->formType->buildView($view, $form, $options);

    $this->assertTrue($view->vars['toggle']);
    $this->assertSame(['class1', 'class2'], $view->vars['toggle_container_classes']);
    $this->assertSame(['btn', 'btn-primary'], $view->vars['button_classes']);
    $this->assertSame('custom:eye-off', $view->vars['visible_icon']);
    $this->assertSame('custom:eye', $view->vars['hidden_icon']);
    $this->assertSame('Mostrar', $view->vars['visible_label']);
    $this->assertSame('Ocultar', $view->vars['hidden_label']);
  }

  public function testConfigureOptionsWithAllDefaults(): void
  {
    $resolver = new OptionsResolver();
    $this->formType->configureOptions($resolver);

    $resolved = $resolver->resolve([]);

    $this->assertArrayHasKey('always_empty', $resolved);
    $this->assertArrayHasKey('trim', $resolved);
    $this->assertArrayHasKey('invalid_message', $resolved);
    $this->assertArrayHasKey('button_classes', $resolved);
    $this->assertArrayHasKey('hidden_icon', $resolved);
    $this->assertArrayHasKey('hidden_label', $resolved);
    $this->assertArrayHasKey('toggle', $resolved);
    $this->assertArrayHasKey('toggle_container_classes', $resolved);
    $this->assertArrayHasKey('use_toggle_form_theme', $resolved);
    $this->assertArrayHasKey('visible_icon', $resolved);
    $this->assertArrayHasKey('visible_label', $resolved);
  }
}
