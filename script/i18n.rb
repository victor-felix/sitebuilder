Dir.glob(File.expand_path("../../app/**/*.php", __FILE__)).each do |filename|
  File.open(filename, 'r').map { |line|
    line.scan(/\b(?:s|__)\(["'](.*?)["'](?:, .*?)?\)/).flatten.each do |match|
      puts "'" + match + "': ''"
    end
  }
end
