# hypersonic

[![Unittests](https://github.com/usox/hypersonic/actions/workflows/php.yml/badge.svg)](https://github.com/usox/hypersonic/actions/workflows/php.yml)
[![Code Coverage](https://scrutinizer-ci.com/g/usox/hypersonic/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/usox/hypersonic/?branch=main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/usox/hypersonic/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/usox/hypersonic/?branch=main)

Library for building APIs following the [subsonic](http://www.subsonic.org/) API definitions.

**Under heavy development; just a bunch of methods is supported at the moment**

## Usage

### Prerequisites

- PHP 8.1
- PSR compliant request-flow (e.g. using a framework like [Slim](https://www.slimframework.com/))
- Free `/rest/`-route in your application (sadly this can't be changed atm)

### Installation

```shell
composer require usox/hypersonic
```

### Initialization

Part of the initialization is the definition of all available API methods. Every method needs a class which implements the methods
DataProviderInterface. Those interfaces declare the format how the data needs to be build to be served using the API.

Also a AuthenticationProvider is needed to provide auth-mechanics for the API. See Authentication for further details.

```php
use Usox\HyperSonic\FeatureSet\V1161\FeatureSetFactory;

$hyperSonic = HyperSonic::init(
    new FeatureSetFactory(),
    new MyAuthenticationProvider(), // implements AuthenticationProviderInterface
    [
        'ping.view' => fn () => new MyPingClass(), // implements PingDataProviderInterface
        'getLicense.view' => fn () => new MyLicenseClass(), // implements LicenseDataProviderInterface
        'getArtists.view' => fn () => new MyArtistListClass(), // implements ArtistListDataProviderInterface
        ...
    ],
);


```

### API routing

Simply use the created Hypersonic as route handler for `/rest/*` routes - e.g. when using Slim:

```php
$app = AppFactory::create();
$app->get('/rest/{methodName}', $hyperSonic);
```

### Authentication

#### Warning

The subsonic protocol is somewhat sloppy when it comes to security.
Prior to version `1.13.0`, the protocol expects the username and the password to be sent as part of the query string -
the password itself is either transferred as plaintext or hex-encoded.

Starting with `1.13.0` the protocol added support for access-tokens - a md5 hash consisting of the password and a salt
(which also gets added to the query params).
Although md5 is considered a unsafe hashing-algo, it's way better than before. Sadly, some apps supporting the protocol still
use the old auth mechanism (or both).

Therefor, hypersonic also supports both ways, and I strongly recommend _not_ to use user passwords you store somewhere in
your server application for api authentication. Please consider using separate api-keys/tokens as user password just for the api. This way, you may
lower any security related impact in case of a security breach.

Also consider not to implement methods which may have access to sensible data and/or modify server data (e.g. starting a music folder scan).

#### Authentication Provider

The `AuthenticationProviderInterface` expects two methods to be implemented, one for the token auth and the second for username+password auth.

To check against the token, use the users api key (or the _plaintext_ user password for god’s sake), append the salt and md5-hash it.

### Protocol-Versions

Please see the official [subsonic api documentation](http://www.subsonic.org/pages/api.jsp) for details.

#### 1.16.1 - Currently supported methods

- getAlbum.view
- getArtists.view
- getArtist.view
- getCoverArt.view
- getGenres.view
- getLicense.view
- getMusicFolders.view
- ping.view




