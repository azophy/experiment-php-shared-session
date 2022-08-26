EXPERIMENT SHARING SESSION BETWEEN CODEIGNITER & LARAVEL
========================================================

This repository serve as an experiment at sharing session between different PHP frameworks: Laravel & CodeIgniter 3. CI3 is a pretty old framework, and often its desirable to move away from it. Altough CI3 is still used in many projects, however the supports and features is severely lacking compared to Laravel's ecosystems & community.

When moving between different version, its often desirable to move gradually. Some the technique is to use partial migration. For example you could develop the website under new frameworks one-by-one for each routes. This usually could be solved by using some kind of proxy/routing server in front of both old & new systems. However when using this strategy, one of the concern is regarding the session data which usually managed by the framework's mechanism.

PHP by default provided a native [Session handling mechanism](https://www.php.net/manual/en/book.session.php). Basically each client which connected to a PHP server would be assigned a session ID which would be stored on the client's cookie storage. This session ID would persists between page loads, and could be used to look up session values stored by the server. This session ID would be shared by different paths under the same subdomain, thus theoritically could by accessed by different websites as long they shares the same subdomain. The remaining requirement would be to share the session data storages, which could be achieved by centralized session storage such as a Redis server.

## NOTICE: ALTERNATIVE

The method explored in this repo is quite "dirty" and tighly-coupled. Rather than using solution as demonstrated in this project, I think its better to use Federated Identity Management protocol (such as OAuth 2.0 or OpenID Connection) to synced user session. This should be the cleanest method to achieve this goal. It also allow for separated logics & concerns, thus achieve better encapsulation & modularity.

Other approach we could use is by storing our session directly in browser's cookie. In practice this would employ encrypted cookie storage or by utilizing JWT inside cookie. This approach is suitable especially for distributed applications such as under microservice architecture. However for regular monolith /fullstack application this approach may introduce additional complexity such as session validations & data leakage risks.

## Project description
- 2 different website in PHP language utilizing different frameworks:
  - Laravel 9.0 under PHP 8.1
  - CodeIgniter 3.1.8 under PHP 7.2
- a Redis instance as shared session storage
- CodeIgniter would emulate a reverse proxy to Laravel's Endpoint. In production, you should use proper reverse proxy such Nginx or HAproxy

All services managed using docker compose

## Setup
- `docker compose up`
- website could be accessed at :
  - codeigniter: http://localhost:8000
  - laravel: http://localhost:8008

## Plan

There are a couple of steps required to achieve this goal:
- make sure both apps is served under the same domain/host
  - this is required to make sure both apps could read the same cookie data
- use the same session driver for both frameworks
  - in this project we use Redis
- use the same Redis key format
  - laravel has `database.redis.prefix` config var which could be changed to edit its Redis prefix key which would be used to store session key
- use the same cookie variable name
  - laravel has `session.cookie` config var which could be changed to edit its cookie variable name
- make sure both has the same encryption setting for cookies
  - disable cookie encryption in Laravel for session id cookie, as CodeIgniter doesn't encrypt session id cookie. edit `$except` variable in `App\Http\Middleware\EncryptCookies.php` file.
- use the same serialization method
  - change both framework to use PHP's `serializable()` method for session storage

## Execution
- One of the most challanging things to achieve this is to make sore both frameworks has the same serialization methods.
    - Add the following line to CodeIgniter's `index.php` file:
        ```
        // in this repo, we add it at line 92
        ini_set('session.serialize_handler', 'php_serialize');
        ```
    - Add the folllowing line to Laravel's `config/session.php` file:
        ```
        // undocumented settings to change Laravel's Session Storage serialization strategy
        // ref; https://github.com/laravel/framework/pull/40595
        'serialization' => 'php',
        ```
    - after that, we also need to modify Laravel framework library source file to make avoid repeated serialization, as Laravel by default serializa session data twice in Redis's storage. Edit file file `vendor/laravel/framework/src/Illuminate/Session/CacheBasedSessionHandler.php`, then replace both `read()` & `write()` method with:
        ```PHP
        public function read($sessionId): string
        {
            $result = $this->cache->get($sessionId, '');

            // handle serialized data from codeigniter
            return serialize($result) ;
        }

        public function write($sessionId, $data): bool
        {
            // revert serialized data from input so we avoid repeated serialization
            $unserialized_data = unserialize($data);

            return $this->cache->put($sessionId, $unserialized_data, $this->minutes * 60);
        }
        ```

        unfortunately this changes is temporary as we directly editing the composer's vendor source file. The more permanent method is to fork Laravel's Framework repository and make a new private composer Package

