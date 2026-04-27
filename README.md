#SimplyFact - Create expense claims for FFS

##Send email

To test email sending locally:

- Install Mailpit: https://mailpit.axllent.org/docs/install/

- Change your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="noreply@simplyfact.fr"
MAIL_FROM_NAME="${APP_NAME}"
```

- Start Mailpit in a terminal:

```bash
mailpit
```

- Mailpit UI available at http://localhost:8025
