set :output, File.expand_path('log/whenever.log')

every 10.minutes do
  command "php #{File.expand_path 'sitebuilder/script/update_feeds.php'}"
end

every 10.minutes do
  command "php #{File.expand_path 'sitebuilder/script/run_import.php'}"
end

every 1.hour do
  command "php #{File.expand_path 'sitebuilder/script/run_geocode.php'}"
end

every 15.days, :at => '1am' do
  command "php #{File.expand_path 'sitebuilder/script/images/clean.php'}"
end
