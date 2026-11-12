<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\CorePasswordDemoType;
use App\Form\PasswordToggleDemoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
 * @copyright 2026 Nowo.tech
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
        $form = $this->createForm(PasswordToggleDemoType::class);

        $form->handleRequest($request);

        $submitted = false;
        $data      = null;

        if ($form->isSubmitted() && $form->isValid())
        {
            $submitted = true;
            $data      = $form->getData();
        }

        return $this->render('demo/form.html.twig', [
            'form'      => $form,
            'submitted' => $submitted,
            'data'      => $data,
        ]);
    }

    /**
     * 2.7: Symfony's own PasswordType (alone and in a RepeatedType) gets the toggle through
     * `nowo_password_toggle.extend_core_password_type: true` (config/packages/nowo_password_toggle.yaml);
     * `toggle: false` opts a field out.
     */
    #[Route('/core-password', name: 'demo_core_password')]
    public function corePassword(Request $request): Response
    {
        $form = $this->createForm(CorePasswordDemoType::class);

        $form->handleRequest($request);
        $submitted = $form->isSubmitted() && $form->isValid();

        return $this->render('demo/form.html.twig', [
            'form'      => $form,
            'submitted' => $submitted,
            'data'      => $submitted ? $form->getData() : null,
        ]);
    }
}
