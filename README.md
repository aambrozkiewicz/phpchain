# phpchain
Set of classes that help to create chains of steps to minimize clutter in domain logic.

## Quick start
```php
$dispatcher = new ChainDispatcher($dependencyInjectionContainer);
$dispatcher->chain('user.register')->define([
  'Steps\CreateUserStep', Steps\CreateProfileStep::class, 'sendWelcomeEmail'
]); // all of these will be resolved by dependency container unless 
    // there is a concrete ChainStep object ready
    
$dispatcher->chain('user.register')->dispatch()->execute(new \ArrayObject([
  'username' => 'valid-username',
  'email' => 'valid@email.com'
]));
```

`ChainDispatcher` accepts any depenendecy injection container which extends `\ArrayObject` to resolve
each step depenendecy.

## Divide and conquer

The goal is to devide complex domain logic into individual steps that can be reused in future processes.
Create your own steps by inheriting `ChainStep` and implement logic in `process(\ArrayObject $input)`.

## Share variables between steps

Steps can share variables between each other by altering `$input` keys, which is possible because we 
are passing `\ArrayObject` instance reference instead an array which is passed by value. For example
`CreateUserStep` after successful action can set `$input['user'] = $createdUser`, which then can be used
by `sendWelcomeEmail`.
