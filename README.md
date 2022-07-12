<p>Below are the list you are going to do after you clone the project</p>

- Switch to your designated branch or checkout a new one if needed
- Configure your .env file by copying the .env.prod, you may run `cp .env.prod .env`
- Ask either [Alfredo](mailto:alfredo@xerosoft.com) or [Jerome](mailto:jerome@xerosoft.com) for the values of the following keys
  - // define your local url without `/public`, wrong value will not work
  - APP_URL=
  - 
  - // for real time functionalities
  - PUSHER_APP_ID=
  - PUSHER_APP_KEY=
  - PUSHER_APP_SECRET=
  - PUSHER_APP_CLUSTER=
  - 
  - // for quick email verification
  - QEV_API_KEY=
- Set APP_ENV to your space (E.g. local, demo, staging, prod)
- Set APP_DEBUG to true
- Create a database named `xerodesk`
- Install all the php packages by running `composer install`
- Generate your key by running `php artisan key:generate`
- Supply your JWT Secret key by running `php artisan jwt:secret` and choose `yes`
- Make sure the storage is linked, run `php artisan storage:link`
- Populate the database, please run `php artisan migrate --seed`
- Now, install the javascript dependencies by running `npm install`
- You may now run `npm run watch` or `npm run prod`

<p>Happy coding!</p>
