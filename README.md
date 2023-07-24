# LGTVMessenger

## Requirements

This app requires both PHP *and* Python.  You must have the following versions:

|Software|Version|
|--------|-------|
|PHP     |8.2    |
|Python  |3.11.4 |

## Instructions

This service must be run on the same network as the TVs you wish to message.  You cannot run this on a remote service like a droplet or AWS.

1. Run `composer install`, `npm install`, and `npm run build`
2. Copy the `.env.example` file in the root directory to a file called `.env`, and update the `APP_URL` fields to appropriate values
3. Get the client key from your TV by running `php artisan lg:key [TV IP]` and following the instructions
4. Update the `config/lgtvs.php` file with an appropriate IP, friendly name, and ENV entry.  More TVs can be added by adding more TV blocks in that file and corresponding client keys in the `.env` file.
5. Point your local webserver to the `/public` folder in this distribution.
