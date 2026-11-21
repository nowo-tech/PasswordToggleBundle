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
        $this->assertSelectorExists('input[name="password_toggle_demo[username]"]');
    }

    /**
     * Tests that the form contains a password field with toggle.
     */
    public function testFormContainsPasswordFieldWithToggle(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('input[name="password_toggle_demo[password]"][type="password"]');
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
            'password_toggle_demo[username]' => 'testuser',
            'password_toggle_demo[password]' => 'testpassword123',
        ]);

        $client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.nowo-ui-flash--success', 'Form submitted successfully');
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

    /**
     * 2.7: extend_core_password_type gives Symfony's PasswordType (and a RepeatedType of it) the toggle.
     */
    public function testCorePasswordTypeGetsTheToggle(): void
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/core-password');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('nowo-password-toggle input[name="core_password_demo[current]"][type="password"]');
        $this->assertSelectorExists('nowo-password-toggle input[name="core_password_demo[new][first]"][type="password"]');
        $this->assertSelectorExists('nowo-password-toggle input[name="core_password_demo[new][second]"][type="password"]');
        $this->assertSelectorExists('input[name="core_password_demo[pin]"][type="password"]');
        $this->assertSelectorNotExists('nowo-password-toggle input[name="core_password_demo[pin]"]');
        $this->assertCount(3, $crawler->filter('nowo-password-toggle'));
    }
}
