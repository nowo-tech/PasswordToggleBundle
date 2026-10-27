import { defineConfig } from 'vitest/config';

/**
 * Vitest configuration for the shipped JavaScript (web component script + Stimulus controller).
 * REQ-TEST-003: `vitest run --coverage` exits non-zero below the thresholds.
 */
export default defineConfig({
  test: {
    environment: 'happy-dom',
    include: ['tests/js/**/*.test.js'],
    coverage: {
      provider: 'v8',
      reporter: ['text', 'text-summary', 'html'],
      reportsDirectory: './coverage-js',
      include: [
        'src/Resources/public/js/nowo-password-toggle.js',
        'assets/controllers/password_toggle_controller.js',
      ],
      thresholds: {
        lines: 90,
        statements: 90,
        functions: 90,
        branches: 90,
      },
    },
  },
});
