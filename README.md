# OpenCart Project Template

## Install on Opencart

> composer require beyondit/opencart-stripe-extension

## Features

 - Stripe credit card payment integration
 - Interactive credit card based on (https://github.com/jessepollak/card)
 - 3D Secure support
 - Supports Opencart version 2.3

## Development Setup

 1. Clone the git repository
 2. Copy the `.env.sample` file to `.env` and set the configuration parameters respectively
 3. Run `bin/robo opencart:setup` and afterwards `bin/robo opencart:run` on command line (`bin/robo opencart:run &` to run in background)
 4. Run `bin/robo project:deploy` to mirror src/ into www/
 5. Open `http://localhost:8000` in your browser

## Robo Commands

 * `bin/robo opencart:setup` : Install OpenCart with configuration set in `.env` file
 * `bin/robo opencart:run`   : Run OpenCart on a php build-in web server on port 8000
 * `bin/robo project:deploy` : Mirror contents of the src folder to the OpenCart test environment
 * `bin/robo project:watch`  : Redeploy after changes inside the src/ folder or the composer.json file
 * `bin/robo project:package`: Package a `build.ocmod.zip` inside the target/ folder
