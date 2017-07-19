# Simple PHP Session
___

Simple and effective library for manage session.


## Function
### init()
To initiating the session if not already initiated and will return the session id.
```php
  Session::init();    // Initiating the session and return session id.
```
#### set($key,$value)
To create a new session item or update the existing session item by the given key with value.
```php
  Session::set('name','Shankar Thiyagaraajan');    // Set or update the item "name" to the session.
```

#### get($key, $default)
To get an item from the session with the "$key". if item is not exist, then it will return the "$defaul" value.
```php
  Session::get('name','No Name');    // Return the session item "name". If item is not exist then return "No Name".
```
####  remove($key)
To remove an item from the session with the "$key".
```php
  Session::remove('name');     // Remove an item from the session.
```

# Lisence
___
## MIT
