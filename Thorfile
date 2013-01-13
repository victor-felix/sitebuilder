require 'digest/sha1'
require 'active_support/core_ext/string'

module Meumobi
  class Instance < Thor
    include Thor::Actions

    GitIgnoreTemplate = <<-TEMPLATE
uploads/
config/ENVIRONMENT
config/connections.php
log/
tmp/
    TEMPLATE

    CapfileTemplate = <<-TEMPLATE
load 'deploy' if respond_to?(:namespace)
load 'sitebuilder/Capfile'

# change the config below to match your server's location
set :repository, 'git@repos.ipanemax.com:YOUR_INSTANCE.git'
set :deploy_to, '/home/meumobi/PROJECTS/YOUR_INSTANCE.com'
set :user, 'meumobi'
role :app, 'bonita.ipanemax.com'
    TEMPLATE

    ConnectionsTemplate = <<-TEMPLATE
<?php

use lithium\\data\\Connections;

$mysql = array(
    'development' => array(
        'driver' => 'MySql',
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'database' => 'meumobi',
        'prefix' => ''
    ),
    'production' => array(
        'driver' => 'MySql',
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'database' => 'meumobi',
        'prefix' => ''
    )
);

$mongodb = array(
    'development' => array(
        'type' => 'MongoDb',
        'host' => 'localhost',
        'database' => 'meumobi'
    ),
    'production' => array(
        'type' => 'MongoDb',
        'host' => 'localhost',
        'database' => 'meumobi'
    )
);

$env = Config::read('App.environment');
Connection::add($mysql);
Connection::add('default', $mysql[$env]);
Connections::add('default', $mongodb[$env]);
    TEMPLATE

    SettingsTemplate = <<-TEMPLATE
<?php

Config::write('App.environment', trim(Filesystem::read(__DIR__ . '/ENVIRONMENT')));
Config::write('Security.salt', 'a5d5d5be3c69dbc8b49e3342db0f8952f6328abd67076bf65d7c3c67a1fbfcab4946aa0fbd6b506db958449ac5e81637d0f5b8ee88e4d0760909cabe2e78137c');
Config::write('Mailer.transport', 'mail');

Config::write('Sites.blacklist', array());

Config::write('Geocode.urls', array(
  'http://maps.googleapis.com',
  'http://elefante.ipanemax.com',
  'http://laguna.ipanemax.com',
  'http://branca.ipanemax.com',
  'http://bonita.ipanemax.com',
));

Config::write('Preview.url', 'http://placeholder.meumobi.com');
Config::write('SiteLogos.resizes', array('200x200'));
Config::write('SitePhotos.resizes', array('139x139#', '314x220'));
Config::write('BusinessItems.resizes', array('80x60#', '80x80#', '139x139#', '314x220'));

Config::write('Sites.domain', 'int-meumobi.com');
Config::write('multiInstances', 1);

Config::write('Themes.url', 'http://meu-cloud-db.int-meumobilesite.com/configs.json');
Config::write('TemplateEngine.url', 'http://meu-template-engine.int-meumobi.com');

require 'config/environments/' . Config::read('App.environment') . '.php';
    TEMPLATE

    DevEnvTemplate = <<-TEMPLATE
<?php

ini_set('error_reporting', -1);
ini_set('display_errors', 'On');

Config::write('Mail.preventSending', true);
Config::write('Debug.showErrors', true);
Config::write('Api.ignoreAuth', true);
Config::write('Themes.ignoreTag', true);
Config::write('SiteManager.url', 'http://meu-site-manager.meumobilesite.com');
    TEMPLATE

    ProdEnvTemplate = <<-TEMPLATE
<?php

ini_set('error_reporting', 0);
ini_set('display_errors', 'Off');

Config::write('Yaml.cache', true);

Config::write('SiteManager.url', 'http://meu-site-manager.int-meumobilesite.com');
    TEMPLATE

    desc "init", "initializes a new meumobi instace"
    def init
      create_link '../Gemfile', 'sitebuilder/Gemfile'
      create_link '../Gemfile.lock', 'sitebuilder/Gemfile.lock'
      create_link '../Thorfile', 'sitebuilder/Thorfile'
      create_link '../config/schedule.rb', '../sitebuilder/config/schedule.rb'
      empty_directory '../segments'
      empty_directory '../uploads'
      empty_directory '../config'
      empty_directory '../config/environments'
      empty_directory '../config/locales'

      create_file '../Capfile', CapfileTemplate
      create_file '../config/connections.sample.php', ConnectionsTemplate
      create_file '../config/settings.php', SettingsTemplate
      create_file '../config/environments/development.php', DevEnvTemplate
      create_file '../config/environments/production.php', ProdEnvTemplate
    end
  end

  class Segment < Thor
    include Thor::Actions

    SegmentTemplate = <<-TEMPLATE
<?php

Config::write('Segment', array(
  'id' => '%{name}',
  'title' => '%{title}',
  'items' => array('%{item_types}'),
  'extensions' => array('%{extensions}'),
  'root' => '%{root}',
  'email' => array('%{email}' => '%{title}'),
  'hideCategories' => %{hide_categories},
  'enableSignup' => %{enable_signup},
  'primaryColor' => '%{primary_color}'
));
    TEMPLATE

    desc "create SEGMENT_NAME", "creates a new segment"
    def create(name=nil)
      options = { name: name }
      options[:title] = ask "title:"
      options[:email] = ask "email:"
      options[:user_first] = ask "user's first name:"
      options[:user_last] = ask "user's last name:"
      options[:user_email] = ask "user's email:"
      options[:user_password] = ask "user's password:"
      options[:root] = ask "root category title:"
      options[:primary_color] = ask "primary color:"
      options[:hide_categories] = yes?("hide categories? (y/n)").to_s
      options[:enable_signup] = yes?("enable signup? (y/n)").to_s
      options[:item_types] = ask "item types (separated by spaces):"
      options[:extensions] = ask "extensions (separated by spaces):"
      options[:item_types] = options[:item_types].split(" ").join("', '")
      options[:extensions] = options[:extensions].split(" ").join("', '")

      directory "sitebuilder/segment", "segments/#{name}/public"
      empty_directory "segments/#{name}/public"
      empty_directory "segments/#{name}/public/scripts"

      [:images, :styles, :scripts].each do |dir|
        create_link "segments/#{name}/public/#{dir}/shared", "../../../../sitebuilder/assets/#{dir}"
      end

      create_link "segments/#{name}/public/uploads", "../../../uploads"

      create_file "segments/#{name}/strings.yaml"
      create_file "segments/#{name}/config.php", SegmentTemplate % options

      run "php #{self.class.source_root}/sitebuilder/script/create_user.php '#{options[:user_first]}' '#{options[:user_last]}' '#{options[:user_email]}' '#{options[:user_password]}'"
      say "Your email is: #{options[:user_email]}"
      say "Your password is: #{options[:user_password]}"
    end

    def self.source_root
      File.dirname(__FILE__)
    end

  end

  class Item < Thor
    include Thor::Actions

    ItemTemplate = <<-TEMPLATE
<?php

namespace app\\models\\items;

require_once 'lib/geocoding/GoogleGeocoding.php';
use GoogleGeocoding;

use app\\models\\Items;

class %{type} extends Items {
    protected $type = '%{type}';

    protected $fields = array(
      %{fields}
    );

    public static function __init() {
        parent::__init();

        $self = static::_object();
        $parent = parent::_object();

        $self->_schema = $parent->_schema + array(
          %{schema}
        );
    }
}

%{type}::applyFilter('save', function($self, $params, $chain) {
    return Items::addTimestamps($self, $params, $chain);
});

%{type}::applyFilter('save', function($self, $params, $chain) {
    return Items::addGeocode($self, $params, $chain);
});

%{type}::finder('nearest', function($self, $params, $chain) {
    return Items::nearestFinder($self, $params, $chain);
});

%{type}::finder('within', function($self, $params, $chain) {
    return Items::withinFinder($self, $params, $chain);
});
    TEMPLATE

    FieldTemplate = <<-TEMPLATE
        '%{name}' => array(
            'title' => '%{title}',
            'type' => '%{type}'
        ),
    TEMPLATE

    SchemaTemplate = "'%s' => array('type' => '%s', default => %s)"

    desc "create TYPENAME FIELD=TYPE...", "creates a new type"
    def create(type=nil, *args)
      fields = args.map { |i|
        name, type = i.split ":"
        { name: name, title: name.humanize, type: type }
      }

      schemas = fields.each_with_object([]) do |field, schema|
        unless ['string', 'richtext', 'boolean', 'geo'].include? field[:type]
          raise ArgumentError, "Invalid type #{field[:type]}"
        end

        if field[:type] == 'geo'
          schema << [:geo, :array, 0]
          field[:type] = 'string'
        end

        schema << [field[:name], field[:type], "''"]
      end

      options = { type: type.camelize }
      options[:fields] = fields.map { |f| FieldTemplate % f }.join "\n"
      options[:schema] = schemas.map { |s| SchemaTemplate % s }.join "\n"

      create_file "sitebuilder/app/models/items/#{type.underscore}.php", ItemTemplate % options
      say "Don't forget to enable this item type in a segment!"

    end
  end
end
