set :output, File.expand_path('log/whenever.log')

every 30.minute do
  command "php #{File.expand_path 'sitebuilder/script/publish_items.php'}"
end

every 15.minute do
  command "php #{File.expand_path 'sitebuilder/script/run_worker.php'} --worker=UpdateFeeds --priority=low --lock=updatefeeds-low"
end

every 1.minute do
  command "php #{File.expand_path 'sitebuilder/script/run_worker.php'} --worker=UpdateFeeds --priority=high --lock=updatefeeds-high"
end

every 10.minutes do
  command "php #{File.expand_path 'sitebuilder/script/import_csv.php'}"
end

every 60.minutes do
  command "php #{File.expand_path 'sitebuilder/script/run_worker.php'} --worker=UpdateEventsFeed --priority=low --lock=updateevents-low"
end

every 1.minute do
  command "php #{File.expand_path 'sitebuilder/script/run_worker.php'} --worker=UpdateEventsFeed --priority=high --lock=updateevents-high"
end

every 1.minute do
  command "php #{File.expand_path 'sitebuilder/script/perform_works.php'}"
end
