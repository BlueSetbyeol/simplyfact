# SimplyFact - Create expense claims for FFS

## Send email

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
MAIL_TO_ACCOUNTANT="comptable@ffs.fr"
```

- Start Mailpit in a terminal:

```bash
mailpit
```

- Mailpit UI available at http://localhost:8025

## s3 storage
Our app uses s3 storage and signed url to stock uploaded files.
You need to :

- configure your own s3 bucket and add a user with appropriate policies in IAM.
- complete env variables as needed

```env
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=tour_secret_access_key
AWS_DEFAULT_REGION=eu-west-3
AWS_BUCKET=simplyfact
AWS_USE_PATH_STYLE_ENDPOINT=false

```
