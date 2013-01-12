load 'deploy' if respond_to?(:namespace)
load 'sitebuilder/Capfile'

role :app, 'bonita.ipanemax.com'
set :deploy_to, '/home/meumobi/PROJECTS/partners.meumobilesite.com'
set :repository, 'git@repos.ipanemax.com:partners.meumobi.git'
set :user, 'meumobi'

task :integration do
   set :php_env, 'integration'
end
