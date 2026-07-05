import { test, expect } from '@playwright/test';

test('has title', async ({ page }) => {
    await page.goto('http://localhost:8000/');

    // Expect a title "to contain" a substring.
    await expect(page).toHaveTitle(/Accueil/);
});

test('get started with link', async ({ page }) => {
    await page.goto('http://localhost:8000/');

    // Click the link.
    await page.getByRole('button', { name: 'Faire une note de frais' }).click();

    // Expects page to have a heading with the name of Vos informations.
    await expect(
        page.getByRole('heading', { name: 'Vos informations' }),
    ).toBeVisible();
});
