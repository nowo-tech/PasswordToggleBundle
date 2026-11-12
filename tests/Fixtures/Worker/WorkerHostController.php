<?php

declare(strict_types=1);

namespace Nowo\PasswordToggleBundle\Tests\Fixtures\Worker;

use Nowo\PasswordToggleBundle\Form\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Host controller that owns the form: renders it and reads the submission.
 */
final readonly class WorkerHostController
{
    public function __construct(
        private FormFactoryInterface $forms,
        private Environment $twig,
    ) {
    }

    public function __invoke(Request $request, string $who): Response
    {
        $form = $this->forms->createNamedBuilder('f', FormType::class)
            ->add('secret', PasswordType::class, $who === 'alpha'
                ? ['visible_label' => 'Show alpha', 'hidden_label' => 'Hide alpha', 'visible_icon' => '', 'hidden_icon' => '', 'javascript' => PasswordType::JAVASCRIPT_WEB_COMPONENT, 'toggle_container_classes' => ['alpha-box']]
                : ['visible_label' => 'Show beta', 'hidden_label' => 'Hide beta', 'visible_icon' => '', 'hidden_icon' => '', 'javascript' => PasswordType::JAVASCRIPT_STIMULUS, 'stimulus_controller' => 'beta-ctl', 'toggle_container_classes' => ['beta-box']])
            ->getForm();
        $form->handleRequest($request);

        $html = $this->twig->render('host_form.html.twig', ['form' => $form->createView()]);
        $sent = $form->isSubmitted() ? (string) $form->get('secret')->getData() : '';

        return new Response('who=' . $who . ';submitted=[' . $sent . '];locale=' . $request->getLocale() . ';' . $html);
    }
}
