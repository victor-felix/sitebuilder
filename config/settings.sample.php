<?php

Config::write('App.environment', 'production');
Config::write('App.encoding', 'utf-8');
Config::write('Security.salt', '0b693e040f5c7ffd13d62330d6c8f901');
Config::write('Debug.level', 3);

require 'config/environments/' . Config::read('App.environment') . '.php';
require 'config/app/segments.php';
require 'config/app/business_items.php';

Debug::reportErrors(Config::read('Debug.level'));

Config::write('Mailer.transport', 'mail');

Config::write('Articles.limit', 20);

Config::write('SiteLogos.resizes', array('200x200'));
Config::write('BusinessItems.resizes', array('80x80#'));

Config::write('States', array(
    'AC' => 'Acre',
    'AL' => 'Alagoas',
    'AP' => 'Amapá',
    'AM' => 'Amazonas',
    'BA' => 'Bahia',
    'CE' => 'Ceará',
    'DF' => 'Distrito Federal',
    'ES' => 'Espírito Santo',
    'GO' => 'Goiás',
    'MA' => 'Maranhão',
    'MT' => 'Mato Grosso',
    'MS' => 'Mato Grosso do Sul',
    'MG' => 'Minas Gerais',
    'PA' => 'Pará',
    'PB' => 'Paraíba',
    'PR' => 'Paraná',
    'PE' => 'Pernambuco',
    'PI' => 'Piauí',
    'RJ' => 'Rio de Janeiro',
    'RN' => 'Rio Grande do Norte',
    'RS' => 'Rio Grande do Sul',
    'RO' => 'Rondônia',
    'RR' => 'Roraima',
    'SC' => 'Santa Catarina',
    'SP' => 'São Paulo',
    'SE' => 'Sergipe',
    'TO' => 'Tocantins',
    'XX' => 'Exterior'
));
Config::write('Countries', array(
    'BR' => 'Brasil',
    'XX' => 'Exterior'
));
