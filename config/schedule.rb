set :output, 'log/whenever.log'

every 10.minutes do
  command 'php meu-site-builder/script/run_import.php'
end

every 1.hour do
  command 'php meu-site-builder/script/run_geocode.php'
end
