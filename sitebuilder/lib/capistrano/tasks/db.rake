namespace :db do
  desc "Create db settings file"
  task :settings do
    on roles(:app) do
      within release_path do 
        execute :cp, 'config/connections.sample.php config/connections.php'
      end
    end
  end

  desc "Run database migrations"
  task :migrate do
    on roles(:app) do
      within release_path do 
        execute :php, 'sitebuilder/script/migrate.php'
      end
    end
  end
end
