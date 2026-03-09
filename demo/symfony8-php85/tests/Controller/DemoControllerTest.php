<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests for DemoController.
 *
 * Verifies that the demo controller works correctly and integrates
 * with the Password Toggle Bundle.
 *
 * @covers \App\Controller\DemoController
 */
final class DemoControllerTest extends WebTestCase
{
    /**
     * Tests that the form page is accessible and returns a successful response.
     */
    public function testFormPageIsAccessible(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Password Toggle Bundle');
    }

    /**
     * Tests that the form contains a username field.
     */
    public function testFormContainsUsernameField(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('input[name="form[username]"]');
    }

    /**
     * Tests that the form contains a password field with toggle.
     */
    public function testFormContainsPasswordFieldWithToggle(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('input[name="form[password]"][type="password"]');
        $this->assertSelectorExists('.form-password-toggle');
        $this->assertSelectorExists('.input-group-text.cursor-pointer');
    }

    /**
     * Tests that the form can be submitted successfully.
     */
    public function testFormCanBeSubmitted(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('Submit')->form([
            'form[username]' => 'testuser',
            'form[password]' => 'testpassword123',
        ]);

        $client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.alert-success', 'Form Submitted Successfully');
    }

    /**
     * Tests that the toggle button has the correct icons.
     */
    public function testToggleButtonHasIcons(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.icon-hidden');
        $this->assertSelectorExists('.icon-visible');
    }

    /**
     * Tests that the form contains a submit button.
     */
    public function testFormContainsSubmitButton(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('button[type="submit"]');
        $this->assertSelectorTextContains('button[type="submit"]', 'Submit');
    }
}

