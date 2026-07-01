# SimplyFact - Create expense claims for Fédération Française de Spéléologie

SimplyFact is a school group project made in the first semester of 2026.

It aims to help an French association's member to claim their expenses made during some activities or other.

We made this project around a flow that simplify the process to the utmost necessary details.
To complete the expenses claim, members will have first to declare wich expenses they are interested to reclaim and then follow the flow as they guide them from one step to another.

## Technologies

The project was made with the Starter pack of Laravel and use the following library :

- React Js
- Inertia
- TypeScript
- Tailwind CSS
- Material UI

To start with clone this repository and run the following command :

- php artisan:migrate
- composer run dev

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

## S3 storage

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
