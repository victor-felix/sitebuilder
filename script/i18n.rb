Dir.glob("app/**/*.php").each do |filename|
  File.open(filename, 'r').map { |line|
    line.scan(/\b(?:s|__)\(["'](.*?)["'](?:, .*?)?\)/).flatten.each do |match|
      puts "'" + match + "': ''"
    end
  }
end
