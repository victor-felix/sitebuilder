namespace :deploy do
  desc "prompt for branch or tag to deploy"
  task :branch_or_tag do
    on roles(:all) do |host|
      run_locally do
        default_branch = `git tag`.split("\n").last

        execute :git, 'tag'
        set :branch, ask("Type the name or tag of the branch to deploy. Leave empty for the most recent tag.\nMost recent tag in repo: #{default_branch}", default_branch)
      end
    end
  end
end
