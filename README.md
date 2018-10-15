# my_php_mvc

#public folder: every css and js file will go in public file. To link files from public assests use 
```php
  <?=ROOT_URL . '/public/foldername/filename'?>
```
#database setup will be defined in .env file
```php
  DB_HOST=  your host name
  DB_USER=  your db username
  DB_PASS=  your db password
  DB_NAME=  your db name
```
#make your own helper functions at app/helpers/helpers.php file.

#views should go to app/views directory. You have to put .view.php extension. To get included file from inc folder:
```php
  <?php echo include_once APP_URL . '/views/inc/header.view.php' ?>
```
or you can just put it into a helper function to make it easier

#Routes: define routes in app/routes.php file 
```php
  $route->get('/users', 'UserController@index');
 ```
or using callback
```php
  $route->get('/users/:id', function($id){
    view('users/index', ['id' => $id]);
  })
  ```
Still working on other methods like post, put and delete
  
