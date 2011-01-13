<?php

Mapper::root('home');
Mapper::connect('/articles/view/:fragment', '/articles/view/$1');
Mapper::connect('/articles/:fragment', '/articles/index/$1');