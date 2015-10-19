lock '3.2.1'

set :application, 'meumobi'
set :repo_url, 'git@github.com:meumobi/sitebuilder.git'

set :scm, :git

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
#set :pty, true

# Default value for :linked_files is []
#set :linked_files, %w{config/ENVIRONMENT}

# Default value for linked_dirs is []
set :linked_dirs, %w{uploads log tmp}
set :file_permissions_paths, %w{uploads log tmp} #tmp/cache/yaml tmp/cache/html_purifier}

# Allows deploy to a specific branch
ask :branch, proc { `git tag`.split("\n").last }

#Sitebuilder services lock files
set :services_paths, [
  'tmp/update_feeds_low.pid',
  'tmp/update_feeds_high.pid',
  'tmp/update_merchant_products_low.pid',
  'tmp/update_merchant_products_high.pid',
  'tmp/import_csv.pid',
  'tmp/geocode_items_high.pid',
  'tmp/geocode_items_low.pid'
  ]

# Default value for default_env is {}
# set :default_env, { path: '/opt/ruby/bin:$PATH' }

# Default value for keep_releases is 5
set :keep_releases, 5

set :ssh_options, {
  user: 'meumobi',
  #verbose: :debug # add this to find exact issue when your deployment fails
}

namespace :deploy do
  before 'deploy:symlink:shared', 'app:environment'
  before 'deploy:updated', 'deploy:permissions:chmod'
  before 'deploy:updated', 'service:cron:stop'
  before 'deploy:updated', 'app:services:stop'
  before 'deploy:updated', 'service:apache:stop'
  after 'deploy:updated', 'app:services:cronjobs'
  after 'deploy:updated', 'app:api_doc'
  after 'deploy:updated', 'db:settings'
  after 'deploy:updated', 'db:backup'
  after 'deploy:updated', 'db:migrate'
  after 'deploy:updated', 'app:platform_check'
  after 'deploy:updated', 'service:apache:start'
  after 'deploy:updated', 'service:cron:start'
end
