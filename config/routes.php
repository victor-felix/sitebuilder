<?php

Mapper::root('sites');
Mapper::prefix('api');
Mapper::connect('/api/:fragment/:fragment', '/api/$2/index/$1');
Mapper::connect('/api/:fragment/:any', '/api/$2/$1');