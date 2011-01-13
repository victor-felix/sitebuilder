<?php

Mapper::root('sites');
Mapper::connect('/articles/view/:fragment', '/articles/view/$1');
Mapper::connect('/articles/:fragment', '/articles/index/$1');