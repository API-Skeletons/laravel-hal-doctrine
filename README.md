# Laravel Doctrine HAL

This project is an adaptation of [laravel-hal](https://github.com/API-Skeletons/laravel-hal)
for Doctrine.  Instead of manually creating every hydrator for your entities, this library
will introspect an entity or collection and generate the HAL for it including links to 
other collections for one to many relationships and embedding many to one and one to one
entities.

A grouped minimal configuration file will allow for excluded fields and associations and
configure the routes for each entity.


## Use

```php
public function fetch(Entity\Artist $artist)
{
    return app('DoctrineHAL').extract($artist);
}
```

will result in a HAL response like 
```json
{
  "_links": {
    "self": {
      "href": "https://website/artist/1"
    }
    "album": {
      "href": "https://website/album?filter[artist]=1"
    }
  },
  "id": 1,
  "name": "Legion of Mary"
}
