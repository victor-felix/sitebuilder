<?php

foreach(new GlobIterator(dirname(__DIR__) . '/initializers/*.php') as $file) {
	require $file->getPathname();
}
