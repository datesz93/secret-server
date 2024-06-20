# CodeIgniter 4 Secret server

## Setup

Copy `env` to `.env`, the `database.default.database,database.default.username,database.default.password` database settings and remove the `#` at the beginning of the lines.

## Server Requirements

PHP version 8.1 or higher is required, the extensions installed `intl`, `mbstring`

## Run

$ composer update
$ php spark migrate -all

## Test server

$ php spark serve

## Link

POST http://localhost:8080/secret
GET http://localhost:8080/secret/$hash
