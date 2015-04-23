namespace :db do
  desc "Create database settings file"
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

  desc "Backup database"
  task :backup do
    on roles(:app) do
      within release_path do 
        execute :php, 'sitebuilder/script/backup_database.php'
      end
    end
  end

end
