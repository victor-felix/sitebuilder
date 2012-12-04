<?php

Mapper::root('home');
Mapper::prefix('api');

Mapper::connect("/settings", "/sites/edit");
Mapper::connect("/settings/account", "/users/edit");
Mapper::connect("/settings/general", "/sites/general");
Mapper::connect("/settings/custom_domain", "/sites/custom_domain");
Mapper::connect("/settings/customize", "/sites/customize_edit");
Mapper::connect("/register", "/users/register");
Mapper::connect("/login", "/users/login");
Mapper::connect("/logout", "/users/logout");
Mapper::connect("/dashboard", "/categories");

Mapper::connect('/api/:fragment', '/api/home/index/$1$3');
Mapper::connect('/api/:fragment/([\w\d_-]+)(\.[\w]+)?', '/api/$2/index/$1$3');
Mapper::connect('/api/:fragment/:fragment/:fragment(:any)?', '/api/$2/$3/$1$4');