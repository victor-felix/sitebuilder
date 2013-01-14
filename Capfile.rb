require 'bundler/capistrano'

set :application, 'meumobi'

set :scm, :git
set :deploy_via, :remote_cache
set :git_enable_submodules, true

set :use_sudo, false

set :normalize_asset_timestamps, false
set :shared_children, %w(uploads log)

set :php_env, 'production'

namespace :deploy do
  task :permissions do
    run "chmod -Rf 777 #{release_path}/tmp"
  end

  task :shared do
    shared_children.map { |d|
      run "rm -rf #{release_path}/#{d} && ln -fs #{shared_path}/#{d} #{release_path}/#{d}"
    }
  end

  task :shared_setup do
    shared_children.map { |d|
      run "mkdir -p #{shared_path}/#{d}"
    }
  end

  task :environment do
    run "chmod -Rf 777 #{shared_path}/log"
    run "chmod -Rf 777 #{shared_path}/uploads"
    put php_env, "#{shared_path}/environment"
  end

  task :symlinks do
    run "ln -s #{shared_path}/environment #{release_path}/config/ENVIRONMENT"
    run "cp #{release_path}/config/connections.sample.php #{release_path}/config/connections.php"
  end
  
  task :cronfile do
    run "cd #{release_path} && bundle exec whenever -w"
  end

  task :platform_check do
    run "php #{release_path}/sitebuilder/script/check_platform.php"
  end

  namespace :db do
    task :migrate do
      run "php #{release_path}/sitebuilder/script/migrate.php"
    end
  end
end

after 'deploy:setup', 'deploy:shared_setup'
after 'deploy:setup', 'deploy:environment'
after 'deploy:update_code', 'deploy:shared'
after 'deploy:update_code', 'deploy:symlinks'
after 'deploy:update_code', 'deploy:permissions'
after 'deploy:update_code', 'deploy:cronfile'
after 'deploy:update_code', 'deploy:db:migrate'
after 'deploy:update_code', 'deploy:platform_check'
