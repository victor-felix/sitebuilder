require 'digest/sha1'
require 'active_support/core_ext/string'

module Meumobi
  class Segment < Thor
    include Thor::Actions

    SegmentTemplate = <<-TEMPLATE
<?php

Config::write('Segments', array_merge(Config::read('Segments'), array(
  '%{name}' => array(
    'title' => '%{title}',
    'items' => array('%{item_types}'),
    'root' => '%{root}',
    'email' => array('%{email}' => '%{title}'),
    'hideCategories' => %{hide_categories},
    'enableSignup' => %{enable_signup},
    'primaryColor' => '%{primary_color}'
  )
)));
    TEMPLATE

    desc "create SEGMENT_NAME", "creates a new segment"
    def create(name=nil)
      password = Digest::SHA1.hexdigest(Time.now.to_i.to_s)[0..8]
      options = { name: name }
      options[:title] = ask "title:"
      options[:email] = ask "email:"
      options[:root] = ask "root category title:"
      options[:primary_color] = ask "primary color:"
      options[:hide_categories] = yes?("hide categories? (y/n)") ? 1 : 0
      options[:enable_signup] = yes?("enable signup? (y/n)") ? 1 : 0
      options[:item_types] = ask "item types (separated by spaces):"
      options[:item_types] = options[:item_types].split(" ").join("', '")

      directory "public/example", "public/#{name}"
      empty_directory "public/#{name}/scripts"

      [:images, :styles, :scripts].each do |dir|
        create_link "public/#{name}/#{dir}/shared", "public/#{dir}"
      end

      create_link "public/#{name}/uploads", "public/uploads"

      empty_directory "config/segments"
      create_file "config/segments/#{name}.yml"
      create_file "config/segments/#{name}.php", SegmentTemplate % options

      run "php #{self.class.source_root}/meu-site-builder/script/create_user.php #{options[:email]} #{password}"
      say "Your email is: #{options[:email]}"
      say "Your password is: #{password}"

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

      schema = fields.each_with_object([]) do |field, schema|
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
      options[:schema] = schema.map { |s| SchemaTemplate % s }.join "\n"

      create_file "meu-site-builder/app/models/items/#{type.underscore}.php", ItemTemplate % options
      say "Don't forget to enable this item type in a segment!"

    end
  end
end
