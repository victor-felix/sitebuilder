namespace :meumobi do
  task :pull do
    sh 'env GIT_DIR=meu-site-builder/.git git pull origin master'
  end

  task :push do
    sh 'env GIT_DIR=.git git push origin master'
  end

  task :update do
    unless `git diff meu-site-builder`.empty?
      rev = `env GIT_DIR=meu-site-builder/.git git rev-parse HEAD`.chomp
      sh "git commit meu-site-builder -m 'Updated meu-site-builder to #{rev}'"
    else
      puts 'Nothing to update'
    end
  end

  task :autoupdate => [:pull, :update, :push]
end

task :default => 'meumobi:autoupdate'
