require 'rubygems'
require 'bundler/setup'

require 'redcarpet'

def read_file(file_path)
  file = File.open(file_path, "r")
  file.read
end

def write_file(file_path, content)
  file = File.open(file_path, "w")
  file.puts content
  file.close
end

def parse_doc(markdown_file_path, doc_file_path, doc_title = "MeuMobi")
  renderer = Redcarpet::Render::HTML.new(:with_toc_data => true)
  parser = Redcarpet::Markdown.new(renderer, :autolink => true, :space_after_headers => true)

  doc_parser = Redcarpet::Markdown.new(Redcarpet::Render::HTML_TOC)

  layout = read_file(File.expand_path("../../doc/layout.html", __FILE__))

  markdown_content = read_file(markdown_file_path)
  doc_content = sprintf(layout,
                        doc_title,
                        doc_parser.render(markdown_content),
                        parser.render(markdown_content))

  write_file(doc_file_path, doc_content)
end

parse_doc(ARGV[0], ARGV[1], ARGV[2])
