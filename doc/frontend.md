# Frontend Coding Test

## Setup

Database
* Run `docker-compose up`

Backend:
* Install php and composer
    * MacOS: Assuming [homebrew available](https://brew.sh/) `brew install php@7.4 composer`
    * Ubuntu: `apt install php7.4-fpm composer`
* Install Symfony CLI: `curl -sS https://get.symfony.com/cli/installer | bash`
* `cd <project-dir>`
* Install dependencies: `composer install`
* Start db `docker-compose up`
* Update database: `symfony php bin/console doctrine:migration:migrate`
* Start Symfony Web server: `symfony server:start -d`

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


### New feature: Next class

Create a new area on the home page that shows dynamic content based on the student’s current status:
* If the user has a class in the future, display the first one with a date. If it's closer than 6 hours, then display a countdown
* If the user doesn't have a future class, encourage them to book by displaying the class with the closest time


### New feature: Class feedback

If the user has past classes without ratings, show a popup notification for each to rate them with 1 to 5 stars, 
and post it to /api/classes/{id}/rating with payload containing a single `rating` field.

If a class already has a rating, don't allow changing it, but display it. 


### Layout bug

The class cards in certain cases – when some of the titles breaks into multiple lines – look quite messy. 
Please make them equal height in all cases.


### Questions

* Oh, we haven't implemented XSRF protection. Do I need to worry?
* How can we prevent bugs coming from missing null checks?
