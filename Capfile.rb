require 'bundler/capistrano'

set :application, 'meumobi'

set :scm, :git
set :deploy_via, :remote_cache
set :git_enable_submodules, true

default_run_options[:pty] = true
set :use_sudo, true

set :normalize_asset_timestamps, false
set :shared_children, %w(uploads log tmp/cache/yaml tmp/cache/html_purifier)
set :shared_links, %w(uploads log tmp)

set :php_env, 'production'

namespace :deploy do
  task :shared_setup do
    shared_children.map { |d|
      run "mkdir -p #{shared_path}/#{d}"
    }
  end

  task :environment do
    put php_env, "#{shared_path}/environment"
  end

  task :permissions do
    shared_links.map { |d|
      run "chmod 777 #{shared_path}/#{d}"
    }
  end

  task :shared do
    shared_links.map { |d|
      run "rm -rf #{release_path}/#{d} && ln -fs #{shared_path}/#{d} #{release_path}/#{d}"
    }
  end

  task :symlinks do
    run "ln -s #{shared_path}/environment #{release_path}/config/ENVIRONMENT"
    run "cp #{release_path}/config/connections.sample.php #{release_path}/config/connections.php"
  end

  task :cronfile do
    run "cd #{release_path} && bundle exec whenever -w"
  end

  task :parse_api_doc do
    run "ruby #{release_path}/sitebuilder/script/parse_docs.rb #{release_path}/sitebuilder/doc/api_tech_spec.md #{release_path}/segments/meumobi/public/api.html 'MeuMobi: Tech Spec'"
  end

  task :platform_check do
    run "php #{release_path}/sitebuilder/script/check_platform.php"
  end

  namespace :db do
    task :migrate do
      run "php #{release_path}/sitebuilder/script/migrate.php"
    end
  end

  namespace :cron do
    task :start do
      invoke_command "/etc/init.d/cron start", via: run_method
    end

    task :stop do
      invoke_command "/etc/init.d/cron stop", via: run_method
    end
  end

  namespace :server do
    task :start do
      invoke_command "/etc/init.d/apache2 start", via: run_method
    end

    task :stop do
      invoke_command "/etc/init.d/apache2 stop", via: run_method
    end
  end

  task :wait do
    run "flock -x #{shared_path}/tmp/update_feeds_low.pid -c echo"
    run "flock -x #{shared_path}/tmp/update_feeds_high.pid -c echo"
  end
end

after 'deploy:setup', 'deploy:shared_setup'
after 'deploy:setup', 'deploy:environment'
after 'deploy:setup', 'deploy:permissions'
before 'deploy:update_code', 'deploy:cron:stop'
before 'deploy:update_code', 'deploy:wait'
before 'deploy:update_code', 'deploy:server:stop'
after 'deploy:update_code', 'deploy:shared'
after 'deploy:update_code', 'deploy:symlinks'
after 'deploy:update_code', 'deploy:cronfile'
after 'deploy:update_code', 'deploy:parse_api_doc'
after 'deploy:update_code', 'deploy:db:migrate'
after 'deploy:update_code', 'deploy:platform_check'
after 'deploy:update_code', 'deploy:server:start'
after 'deploy:update_code', 'deploy:cron:start'
