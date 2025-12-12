<?php

declare(strict_types=1);

namespace App\Controller;

use Nowo\PasswordToggleBundle\Form\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Demo controller for Password Toggle Bundle.
 *
 * This controller demonstrates the usage of the PasswordType form type
 * with toggle visibility functionality.
 *
 * @author Héctor Franco Aceituno <hectorfranco@nowo.tech>
 * @copyright 2024 Nowo.tech
 */
class DemoController extends AbstractController
{
    /**
     * Displays and handles the demo form with password toggle.
     *
     * This action creates a form with username and password fields, where the password
     * field uses the PasswordType with toggle visibility functionality.
     *
     * @param Request $request The HTTP request
     *
     * @return Response The rendered form page
     */
    #[Route('/', name: 'demo_form')]
    public function form(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('username', TextType::class, [
                'label' => 'Username',
                'attr'  => [
                    'class'       => 'form-control',
                    'placeholder' => 'Enter your username',
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'attr'  => [
                    'class'       => 'form-control',
                    'placeholder' => 'Enter your password',
                ],
                'toggle'                   => true,
                'visible_icon'             => 'tabler:eye-off',
                'hidden_icon'              => 'tabler:eye',
                'visible_label'            => 'Show password',
                'hidden_label'             => 'Hide password',
                'toggle_container_classes' => ['form-password-toggle'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr'  => [
                    'class' => 'btn btn-primary',
                ],
            ])
            ->getForm();

        $form->handleRequest($request);

        $submitted = false;
        $data      = null;

        if ($form->isSubmitted() && $form->isValid())
        {
            $submitted = true;
            $data      = $form->getData();
        }

        return $this->render('demo/form.html.twig', [
            'form'      => $form->createView(),
            'submitted' => $submitted,
            'data'      => $data,
        ]);
    }
}

