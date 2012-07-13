require 'digest/sha1'

module Meumobi
  class Segment < Thor
    include Thor::Actions

    Template = <<-TEMPLATE
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
      create_file "config/segments/#{name}.php", Template % options

      run "php #{self.class.source_root}/meu-site-builder/script/create_user.php #{options[:email]} #{password}"
      say "Your email is: #{options[:email]}"
      say "Your password is: #{password}"

    end

    def self.source_root
      File.dirname(__FILE__)
    end

  end

  class Item < Thor
    desc "create TYPENAME FIELD=TYPE...", "creates a new type"
    def create(type=nil)

    end
  end
end
