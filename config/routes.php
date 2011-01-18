<?php

Mapper::root('sites');
Mapper::prefix('api');

Mapper::connect('/api/:fragment', '/api/home/index/$1$3');
Mapper::connect('/api/:fragment/([\w\d_-]+)(\.[\w]+)?', '/api/$2/index/$1$3');
Mapper::connect('/api/:fragment/:fragment/:fragment(:any)?', '/api/$2/$3/$1$4');