namespace :deploy do
  namespace :app do
    desc "Set app environment mode on configured on :php_env"
    task :environment do
      on roles fetch(:app) do
        within shared_path do
          execute :echo, php_env, '>', 'ENVIRONMENT'
        end
      end
    end
  end
end


