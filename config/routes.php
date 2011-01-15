<?php

Mapper::root('sites');
Mapper::prefix('api');



Mapper::connect('/api/:fragment/([\w\d_-]+)(\.[\w]+)?', '/api/$2/index/$1$3');
Mapper::connect('/api/:fragment/:fragment/:fragment/:any', '/api/$2/$3/$1/$4');



// Mapper::connect('/api/:fragment/:fragment(\.[\w+])', '/api/$2/index/$1$3');
// Mapper::connect('/api/([\w\d._-]+)/:any(\.[\w+])?', '/api/$2/$1$3');
// Mapper::connect('/api/([\w\d._-]+)/:any(\.[\w+])?', '/api/$2/$1$3');
// Mapper::connect('/api/([\w\d._-]+)/:any(\.[\w+])?', '/api/$2/$1$3');
// Mapper::connect('/api/([\w\d._-]+)/:any(\.[\w+])?', '/api/$2/$1$3');