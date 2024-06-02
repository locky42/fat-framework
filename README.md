# Fat Framework
###### [Based by slim-framework](https://www.slimframework.com/)

## Commands:
- `php bin/console {command}` - starting console command (use symfony command core)
- `php bin/migrate` - update db structure from Doctrine ORM

## Routing
Routing is built like this:
- `controller/method`
- `controller/method/{id:int}`

The names of the methods in the class should be written according to the formula `{method URL}{HTTP method}`, for example `updatePost` or `updatePut`.

### Examples
For `App\Controllers\UserController:createPost` use `/user/create` rout.