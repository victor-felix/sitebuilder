<?php
use meumobi\sitebuilder\repositories\VisitorsRepository;
use meumobi\sitebuilder\entities\Visitor;

require dirname(__DIR__) . '/config/cli.php';

$_ = array_shift($argv);

$data = [
	'site_id' => 10,
	'email' => 'visitor@mail.com',
	'password' => '123456',
	'groups' => ['vip',	'not_so_vip', 'the_rest'],
	'devices' => []
];

$repo = new VisitorsRepository();

$visitor = new Visitor($data);

$repo->create($visitor);

echo $visitor->id();
