# Book Recommender

## To install
-  run 
```bash
composer install
```
-  Add `.env` file in the project root directory (use data in `env.example` and update the database connection based in your enviroment)

- Migrate Tables 
```bash
php artisan migrate
```
- Then run seeder to seed some users and books
```bash
php artisan db:seed
```

### For SMS
- Add to `.env` the following variable to add Your required api 
```
SMS_URL=
```
### Run Project 
- Run
```bash
php artisan serve
```



## Routes
- [ ] Submit a user reading interval (api/v1/book) [POST]
- [ ] Get Recommended Books (api/v1/book) [Get] 



## Tests
- Run Tests
```bash
php artisan test
``` 

## API Docs

https://documenter.getpostman.com/view/33671069/2sA2xnyAUZ

 



