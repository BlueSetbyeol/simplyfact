import { test, expect } from '@playwright/test';

test('testing user info filling', async ({ page }) => {
    await page.goto('http://localhost:8000/users');
    await page.getByRole('textbox', { name: 'Nom', exact: true }).click();
    await page
        .getByRole('textbox', { name: 'Nom', exact: true })
        .fill('Durand');
    await page.getByRole('textbox', { name: 'Nom', exact: true }).press('Tab');
    await page.getByRole('textbox', { name: 'Prénom' }).fill('Anne');
    await page.getByRole('textbox', { name: 'Prénom' }).press('Tab');
    await page
        .getByRole('textbox', { name: 'Adresse' })
        .fill('12 rue de la lisière');
    await page.getByRole('textbox', { name: 'Adresse' }).press('Tab');
    await page.getByRole('textbox', { name: 'Code postal' }).fill('69007');
    await page.getByRole('textbox', { name: 'Code postal' }).press('Tab');
    await page.getByRole('textbox', { name: 'Ville' }).fill('Lyon');
    await page.getByRole('textbox', { name: 'Ville' }).press('Tab');
    await page
        .getByRole('textbox', { name: 'Email' })
        .fill('anne.durand@gmail.com');
    await page.getByRole('textbox', { name: 'Email' }).press('Tab');
    await page.getByRole('textbox', { name: 'Téléphone' }).fill('0912873465');
    await page.getByRole('textbox', { name: 'Téléphone' }).press('Tab');
    await page.getByRole('button', { name: 'Suivant' }).click();

    await expect(
        page.getByRole('heading', { name: 'Informations complémentaires' }),
    ).toBeVisible();
});

test('testing user page using tab', async ({ page }) => {
    await page.goto('http://localhost:8000/users');
    await page.getByRole('button', { name: '← Retour' }).press('Tab');
    await page
        .getByRole('link', { name: 'Logo Fédération Française de' })
        .press('Tab');
    await page.getByRole('textbox', { name: 'Nom', exact: true }).press('Tab');
    await page.getByRole('textbox', { name: 'Prénom' }).press('Tab');
    await page.getByRole('textbox', { name: 'Adresse' }).press('Tab');
    await page.getByRole('textbox', { name: 'Code postal' }).press('Tab');
    await page.getByRole('textbox', { name: 'Ville' }).press('Tab');
    await page.getByRole('textbox', { name: 'Email' }).press('Tab');
    await page.getByRole('textbox', { name: 'Téléphone' }).press('Tab');
    await page.getByRole('button', { name: 'Suivant' }).press('Enter');
    await page.getByRole('button', { name: 'Suivant' }).click();

    await expect(
        page.getByRole('heading', { name: 'Vos informations' }),
    ).toBeVisible();
});
