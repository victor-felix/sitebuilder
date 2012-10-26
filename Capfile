load 'deploy' if respond_to?(:namespace)
load 'meu-site-builder/Capfile'

set :repository, 'git@repos.ipanemax.com:partners.meumobi.git'
#elefante
#set :deploy_to, '/home/meumobi/PROJECTS/partners.meumobilesite.com'
#role :app, 'elefante.ipanemax.com'

#laguna
#set :deploy_to, '/home/meumobi/PROJECTS/partners.int-meumobilesite.com'
#role :app, 'laguna.ipanemax.com'

#bonita
set :deploy_to, '/home/meumobi/PROJECTS/partners.meumobilesite.com'
role :app, 'bonita.ipanemax.com'

set :user, 'meumobi'


task :integration do
   set :php_env, 'integration'
end
