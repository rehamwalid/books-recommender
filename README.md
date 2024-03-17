# Book Recommender

## To install
-  run 
```bash
composer install
```
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



## Routes
- [ ] Submit a user reading interval (api/v1/book) [POST]
- [ ] Get Recommended Books (api/v1/book) [Get] 



## Tests
- Run Tests
```bash
php artisan tests
``` 

 



