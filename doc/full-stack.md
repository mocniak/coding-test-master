# Full Stack Coding Test

## Setup

* Set up MySQL
* Set MySQL credentials in `.env.local`

Backend:
* Install dependencies: `composer install`
* Create database: `bin/console doctrine:database:create`
* Update database: `bin/console doctrine:migration:migrate`
* Create test database: `APP_ENV=test bin/console doctrine:database:create`
* Update test database: `APP_ENV=test bin/console doctrine:migration:migrate`
* Install Symfony CLI: `curl -sS https://get.symfony.com/cli/installer | bash`
* Start Symfony Web server: `cd <project-dir> && symfony server:start -d`

Frontend:
* Install dependencies: `yarn install`
* Build and watch: `yarn dev-server`

Some dummy test data is created along with a test user:
```
email: user@lingoda.com
password: almafa
```

### Login improvements

When user is trying to access a url that needs a user, please redirect them to login, and after login redirect them back to the originally requested page. The default post-login page should be Classes.


### Privacy breach

Somebody said that students can access email addresses of other students. 
Probably they shouldn't even see user ids.
Please fix it.


### New feature: Popup after 5 classes

When the user has already attended 5 classes, and at least 24 hours passed since that 5th class has ended, 
we need to show them a notification that asks for a rating of Lingoda: 1 to 5 stars.

If the user dismisses the notification, show it again after 15 classes were attended plus 24 hours.

If the user submits a rating, show it again after 25 classes were attended plus 24 hours.

Do not show the notification to any user more than twice.

This feature has nothing to do with class ratings.


### Layout bug

The class cards in certain cases – when some of the titles breaks into multiple lines – look quite messy. 
Please make them equal height in all cases.


### New class status

When a class has ended, we need to set its status to ended.


### Questions

* Oh, we haven't implemented XSRF protection. Do I need to worry?
* How can we prevent bugs coming from missing null checks?
