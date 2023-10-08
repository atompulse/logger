# Logger - an exercise

## Notes
* ChainedLogger is an example of implementation without a main loop for handling error processors;
* ChainedLogger provides at the same time an example of extensibility of the solution, 
i.e. it implements the LoggerInterface while it's internal details are different from default Logger implementation

## Runtime Instructions
* This exercise was designed in a containerised environment using docker; bellow are instructions on how to run this code locally
### Build the container:
`docker-compose build`
### Install composer
`docker-compose run php-cli composer install`

## Running demos
### Run unit tests
`docker-compose run php-cli vendor/bin/codecept run`
### Simple Logger
`docker-compose run php-cli php /app/demo.php`
### Chained Logger
`docker-compose run php-cli php /app/democ.php`
