# config valid only for Capistrano 3.1
lock '3.2.1'

set :application, 'meumobi'
set :repo_url, 'git@git-repos.ipanemax.com:partners.meumobi.git'

# Default branch is :master
# ask :branch, proc { `git rev-parse --abbrev-ref HEAD`.chomp }.call

# Default deploy_to directory is /var/www/my_app
# set :deploy_to, '/var/www/my_app'

# Default value for :scm is :git
# set :scm, :git

# Default value for :format is :pretty
# set :format, :pretty

# Default value for :log_level is :debug
# set :log_level, :debug

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
# set :linked_files, %w{config/database.yml}

# Default value for linked_dirs is []
# set :linked_dirs, %w{bin log tmp/pids tmp/cache tmp/sockets vendor/bundle public/system}

# Default value for default_env is {}
# set :default_env, { path: '/opt/ruby/bin:$PATH' }

# Default value for keep_releases is 5
# set :keep_releases, 5

set :ssh_options, {
  user: 'meumobi',
  #verbose: :debug # add this to find exact issue when your deployment fails
}

namespace :deploy do
  before 'deploy:symlink:shared', 'deploy:app:environment'
  before 'deploy:updated', 'deploy:permissions:chmod'
  before 'deploy:updated', 'deploy:cron:stop'
  before 'deploy:updated', 'deploy:services:stop'
  before 'deploy:updated', 'deploy:server:stop'
  after 'deploy:updated', 'deploy:services:cron'
  after 'deploy:updated', 'deploy:app:api_doc'
  after 'deploy:updated', 'deploy:db:connection'
  after 'deploy:updated', 'deploy:db:migrate'
  after 'deploy:updated', 'deploy:platform:check'
  after 'deploy:updated', 'deploy:server:start'
  after 'deploy:updated', 'deploy:cron:start'
end
