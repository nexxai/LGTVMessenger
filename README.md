# LGTVMessenger

![Run Tests](https://github.com/nexxai/LGTVMessenger/actions/workflows/RunTests.yml/badge.svg) [![codecov](https://codecov.io/gh/nexxai/LGTVMessenger/branch/main/graph/badge.svg?token=OYkZZnFyKc)](https://codecov.io/gh/nexxai/LGTVMessenger)

## What in the...

Have you ever needed to send a message to someone in another room of your house who was watching TV with headphones on but wasn't great at checking their phone?  Or maybe you just want to annoy the everliving crap out of your spouse?  Then this app might be for you.  Run it on a server in your house, connect to it from your phone, and have fun spamming your friends and family for years to come!

## What it looks like

### Interface

![Screenshot of the app interface](resources/images/interface.png)

### Result

![Screenshot of message at the bottom of an LG webOS TV](resources/images/screenshot.jpg)

### Notes

- This service *must* be run on the same network as the TVs you wish to message.  You cannot run this on a remote service like a droplet or AWS, unless you have a permanent VPN between the two sites.
- If you decide to expose this service to the world (e.g. via some kind of port forward), be aware that anyone who accesses the page will have unfettered access to the bottom of your TVs forever.  If you absolutely must expose it to the internet, use something like a Cloudflare tunnel and then create a Cloudflare Access application, and only allow certain email addresses to browse to it (this is an exercise left up to the reader; PRs for better documentation are greatly appreciated.)

## Instructions

1. Run `composer install`, `npm install`, and `npm run build`
2. Run `php artisan lg:first-time`
3. Open the newly created `.env` file, and update the `APP_URL` fields to appropriate values
4. Point your web server's root directory to the `/public` folder in this distribution.

If you ever need to add another TV to the list, you can run `php artisan lg:add` and follow the prompts

## Precanned Messages

If you have any messages that you think you will send often (e.g. "Check your phone"), you can add them by running `php artisan lg:add-response "Check your phone"` - these will show up in a dropdown on the main page.

If you need to subsequently remove any of these messages, run `php artisan lg:remove-response` and follow the prompts to remove the appropriate one.

## Requirements

This app requires PHP, NodeJS *and* Python.  You must have the following versions:

|Software|Version|
|--------|-------|
|PHP     |8.2    |
|Python  |3.11.4 |
|NodeJS  |20.5.0 |

## Contributing

All PRs are welcome and very much appreciated.  I whipped this together in a single evening, so there will *definitely* be places where this code could be improved.  Criticisms will be accepted when provided with proof of a $10 donation to your local food bank.

## Credits

App icon by [Freepik - Flaticon](https://www.flaticon.com/free-icons/smart-tv)

[AIOWEBOSTV](https://github.com/home-assistant-libs/aiowebostv) python package
