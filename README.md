EXPERIMENT SHARING SESSION BETWEEN CODEIGNITER & LARAVEL
========================================================

This repository serve as an experiment at sharing session between different PHP frameworks: Laravel & CodeIgniter 3. CI3 is a pretty old frameworks, and often its desirable to move away from it. Altough CI3 still used in many projects, however the support and features is severely lacking compared to Laravel's ecosystems & community.

When moving between different version, some of the desirable strategy is to move gradually by using gracefull migration. Some the technique is to use partial migration. For example you could develop the website under new frameworks one-by-one for each routes. This usually could be solved by using some kind of proxy/routing server in front of both old & new systems. However when using this strategy, one of the concern is regarding the session data which usually being held under the frameworks mechanism.

PHP by default provided a native [Session handling mechanism](https://www.php.net/manual/en/book.session.php). Basically each client which connected to a PHP server would be assigned a session ID which would be stored on the clients cookie storage. This session ID would persists between page loads, and could be used to look up session values stored by the server. This session ID would be shared by different paths under the same subdomain, thus theoritically could by accessed by different websites as long they shares the same subdomain. The remaining requirement would be to share the session data storages, which could be achieved by centralized session storage such as a Redis server.

## Project description
- 2 different website in PHP language utilizing different frameworks:
  - Laravel 9.0 under PHP 8.1
	- CodeIgniter 3.1.8 under PHP 7.2
- an Nginx server that acted as a public router to both websites
- a Redis instance as shared session storage

All services managed using docker compose


