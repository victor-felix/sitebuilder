load 'deploy' if respond_to?(:namespace)
load 'sitebuilder/Capfile'

set :repository, 'git@git-repos.ipanemax.com:partners.meumobi.git'
set :user, 'meumobi'

# production settings. do not change unless PROD env moves. if you need to
# deploy to INT or another env, create or modify a task for it.

task :production do
  set :php_env, 'production'
	set :deploy_to, '/home/meumobi/PROJECTS/meumobi.com'
	role :app, 'elefante.ipanemax.com'
end

task :integration do
  set :php_env, 'integration'
  set :deploy_to, '/home/meumobi/PROJECTS/partners.int-meumobilesite.com'
  role :app, 'laguna.ipanemax.com'
end
