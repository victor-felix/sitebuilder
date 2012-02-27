task :update do
  unless `git diff meu-site-builder`.empty?
    rev = `env GIT_DIR=meu-site-builder/.git git rev-parse HEAD`.chomp
    sh "git commit meu-site-builder -m 'Updated meu-site-builder to #{rev}'"
  else
    puts 'Nothing to update'
  end
end
