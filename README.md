# plux

## introduction

**plux** is a PHP library created to answer a simple question:
> *Does implementing a [Flux](http://facebook.github.io/flux/docs/overview.html)-like, one-direction flow architecture **server-side** make any sense?*

## quick how-to

### installation
```
composer require mattjmattj/plux
```

### initialization
Somewhere in the bootstrap of your application/framework you need to call
```php
\Plux\Plux::initialize();
```
Plux will then create a Dispatcher that will have to handle the Actions and pass
them to your registerd Stores.


### stores
Stores are like the ones in Flux. They should do the business part according to
an action that is given to them through their `handle` method. Note that using
the `handle` method is just a shortcut provided by the StoreTrait, but any
*callable* can be used with the Dispatcher.

You must register your Stores to Plux and give them a name :
```php
\Plux\Plux::addStore('Foo', new \My\Super\Store\Bar());
```
The name will allow other parts of your application to call 
```php
\Plux\Plux::getStore('Foo');
```

Stores are supposed to emit events whenever they perform an action. Plux depends
on [evenement/evenement](https://github.com/igorw/evenement) to implement Store 
events.

### actions

Actions must be created and dispatched to allow registered Stores to do their
job. Actions are composed of two elements:

* a *type*
* a *data* array

After creating an action, you must dispatch it :
```php
$action = new \Plux\Action ('type', ['foo' => 'bar']);
\Plux\Plux::getDispatcher()->dispatch($action);
```

### components

Theoretically everybody can listen to Store events, but to make things easier
Plux provides a Component trait with a single `render` function. When `render`
is called the Component should output whatever it needs to. This part is left
very opened and you are not forced to use the Component trait at all.

## example

A small but complete example of implementation is available [here](https://github.com/mattjmattj/plux-demo)

## next ?

* implement a [`waitFor`](http://facebook.github.io/flux/docs/dispatcher.html#content) equivalent to represent per-action store interdependency

## debate

You think that even in a per-request context like in PHP, implementing a one-direction
flow architecture is a great idea? You think that it is absurd? Do not hesitate 
and leave a comment in the [dedicated issue](https://github.com/mattjmattj/plux/issues/1)

## license

plux is released under the [FreeBSD License](http://opensource.org/licenses/BSD-2-Clause)