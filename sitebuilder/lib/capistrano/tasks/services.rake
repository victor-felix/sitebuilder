def absolute_services_paths
  linked_dirs = fetch(:linked_dirs)
  fetch(:services_paths).map do |d|
    Array(linked_dirs).include?(d) ? shared_path.join(d) : release_path.join(d)
  end
end

namespace :app do
  namespace :services do
    desc "Setup app services cronjobs"
    task :cronjobs do
      on roles(:app) do
        within release_path do 
          execute :bundle, :exec, :'whenever -w' 
        end
      end
    end

    desc "Stop and prevent app services from running"
    task :stop do
      next unless any? :services_paths
      on roles(:app) do
        absolute_services_paths.each do |path|
            execute :flock, '-x', path , '-c echo'
        end
      end
    end
  end
end

namespace :load do
  task :defaults do
    set :services_paths, []
  end
end
