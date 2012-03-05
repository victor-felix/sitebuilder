set :application, 'meumobi'

set :scm, :git
set :deploy_via, :remote_cache
set :git_enable_submodules, true

set :use_sudo, false
ssh_options[:forward_agent] = true

set :normalize_asset_timestamps, false
set :shared_children, %w(public/uploads meu-site-builder/log)

set :php_env, 'production'

namespace :deploy do
  task :permissions do
    run "chmod -R 777 #{latest_release}/meu-site-builder/tmp"
  end

  task :shared do
    shared_children.map { |d|
      run "rm -rf #{latest_release}/#{d} && ln -fs #{shared_path}/#{d} #{latest_release}/#{d}"
    }
  end

  task :environment do
    run "chmod -R 777 #{shared_path}/meu-site-builder/log"
    put php_env, "#{shared_path}/environment"
  end

  task :symlinks do
    run "ln -s #{shared_path}/environment #{release_path}/config/ENVIRONMENT"
    run "cp #{release_path}/config/connections.sample.php #{release_path}/config/connections.php"
  end

  task :platform_check do
    run "#{release_path}/meu-site-builder/script/check_platform.php"
  end

  namespace :db do
    task :migrate do
      run "#{release_path}/meu-site-builder/script/migrate.php"
    end
  end
end

after 'deploy:setup', 'deploy:environment'
after 'deploy:update_code', 'deploy:symlinks'
after 'deploy:finalize_update', 'deploy:shared'
after 'deploy:finalize_update', 'deploy:permissions'
after 'deploy:finalize_update', 'deploy:db:migrate'
