<?php

I18n::path('test/fixtures');
I18n::locale('i18n');

YamlDictionary::path('test/fixtures');
YamlDictionary::dictionary('dictionary');

describe('I18n', function() {
    it('should translate an existing token from yaml', function() {
        assert_equal('string', I18n::translate('token'));
    });

    it('should return token if it doesn\'t exist in yaml', function() {
        assert_equal('not_a_token', I18n::translate('not_a_token'));
    });
});
