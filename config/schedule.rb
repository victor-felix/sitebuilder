set :output, File.expand_path('log/whenever.log')

every 15.minute do
  command "php #{File.expand_path 'sitebuilder/script/update_feeds.php'} low"
end

every 1.minute do
  command "php #{File.expand_path 'sitebuilder/script/update_feeds.php'} high"
end

every 10.minutes do
  command "php #{File.expand_path 'sitebuilder/script/import_csv.php'}"
end

every 30.minutes do
  command "php #{File.expand_path 'sitebuilder/script/geocode_items.php high'}"
end

every 1.hour do
  command "php #{File.expand_path 'sitebuilder/script/geocode_items.php low'}"
end

every 1.day do
  command "php #{File.expand_path 'sitebuilder/script/update_merchant_products.php'} low"
end

every 1.minute do
  command "php #{File.expand_path 'sitebuilder/script/update_merchant_products.php'} high"
end

every 1.day do
  command "php #{File.expand_path 'sitebuilder/script/update_events.php'} low"
end

every 15.minute do
  command "php #{File.expand_path 'sitebuilder/script/update_events.php'} high"
end
