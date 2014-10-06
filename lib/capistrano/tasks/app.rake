namespace :app do
  desc "Set app environment mode on configured on :php_env"
  task :environment do
    on roles(:app) do
      within shared_path do 
        execute :echo, "#{fetch(:php_env)} > ENVIRONMENT"
      end
    end
  end

  desc "Check if the app is configured right and in the correct environment"
  task :platform_check do
    on roles(:app) do
      within release_path do 
        execute :php, "sitebuilder/script/check_platform.php"
      end
    end
  end

  desc "Parse api documentation and create html page"
  task :api_doc do
    on roles(:app) do
      within release_path do 
        execute :ruby, "sitebuilder/script/parse_docs.rb sitebuilder/doc/api_tech_spec.md segments/meumobi/public/docs/api.html 'MeuMobi: Tech Spec'"
      end
    end
  end
end
