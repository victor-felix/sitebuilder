<?php

YamlDictionary::path(__DIR__ . '/../segments');
I18n::path(__DIR__ . '/../locales');

if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $languages = strstr($_SERVER['HTTP_ACCEPT_LANGUAGE'], ';', true);
    $languages = explode(',', $languages);

    while(list($i, $language) = each($languages)) {
        if(Filesystem::exists(I18n::path() . '/' . $language . '.yaml')) {
            I18n::locale($language);
            break;
        }
    }
}

if(!I18n::locale()) {
    I18n::locale('en');
}
