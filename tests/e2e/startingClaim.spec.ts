import { test, expect } from '@playwright/test';

test('testing starting on claim and arriving to the first claim', async ({
    page,
}) => {
    await page.goto('http://localhost:8000/');
    await page.getByRole('button', { name: 'Faire une note de frais' }).click();
    await page.getByRole('textbox', { name: 'Nom', exact: true }).click();
    await page
        .getByRole('textbox', { name: 'Nom', exact: true })
        .fill('Durand');
    await page.getByRole('textbox', { name: 'Nom', exact: true }).press('Tab');
    await page.getByRole('textbox', { name: 'Prénom' }).fill('Anne');
    await page.getByRole('textbox', { name: 'Prénom' }).press('Tab');
    await page
        .getByRole('textbox', { name: 'Adresse' })
        .fill('12 rue de la liberté');
    await page.getByRole('textbox', { name: 'Adresse' }).press('Tab');
    await page.getByRole('textbox', { name: 'Code postal' }).fill('69006');
    await page.getByRole('textbox', { name: 'Code postal' }).press('Tab');
    await page.getByRole('textbox', { name: 'Ville' }).fill('Lyon');
    await page.getByRole('textbox', { name: 'Ville' }).press('Tab');
    await page
        .getByRole('textbox', { name: 'Email' })
        .fill('anne.durand@gmail.com');
    await page.getByRole('textbox', { name: 'Email' }).press('Tab');
    await page.getByRole('textbox', { name: 'Téléphone' }).fill('0192837465');
    await page.getByRole('textbox', { name: 'Téléphone' }).press('Tab');
    await page.getByRole('button', { name: 'Suivant' }).click();

    await expect(
        page.getByRole('heading', { name: 'Informations complémentaires' }),
    ).toBeVisible();

    await page
        .getByRole('textbox', { name: 'Commission', exact: true })
        .click();
    await page.getByRole('textbox', { name: 'Commission' }).fill('ESF');
    await page.getByRole('textbox', { name: 'Commission' }).press('Tab');
    await page
        .getByRole('textbox', { name: "Objet de l'action" })
        .fill('Formation des stagiaires');
    await page.getByRole('textbox', { name: "Objet de l'action" }).press('Tab');
    await page
        .getByRole('textbox', { name: "Dates de l'action" })
        .fill('Les 12 et 26 Juin ');
    await page.getByRole('button', { name: 'Suivant' }).click();

    await expect(
        page.getByRole('heading', { name: 'Choix des étapes' }),
    ).toBeVisible();

    await page.getByRole('button', { name: 'Oui' }).nth(2).click();
    await page.getByRole('button', { name: 'Oui' }).nth(4).click();
    await page.getByRole('button', { name: 'Suivant' }).click();

    await expect(
        page.getByRole('heading', {
            name: 'Vous allez faire une note de frais pour:',
        }),
    ).toBeVisible();

    await page.getByRole('button', { name: 'Commencer' }).click();
    await expect(
        page.getByRole('heading', { name: 'Vos nuits en hébergements' }),
    ).toBeVisible();
});
