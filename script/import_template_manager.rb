require 'json'
require 'open-uri'
require 'mongo'

# development
url = 'http://meu-cloud-db.int-meumobilesite.com/configs.json'
db = 'meumobi_partners'

# integration
# url = 'http://meu-cloud-db.int-meumobilesite.com/configs.json'
# db = 'int_partners'

# production
# url = 'http://meu-cloud-db.meumobi.com/configs.json'
# db = 'meumobi_partners'

require 'rubygems'
require 'mongo'

collection = Mongo::MongoClient.new('localhost', 27017)[db]['skins']

open(url) do |data|
  json = JSON.parse(data.read)
  themes = json.map { |t|
    t.select { |key, value|
      ['name', 'thumbnails', 'assets', 'colors'].include? key
    }.tap { |theme|
      theme['id'] = theme['name']
      theme['colors'] = theme['colors'].first[1].keys
      theme['assets'] = theme['assets'].keys
      theme['thumbnails'] = theme['thumbnails'].map { |t| "http://meu-template-engine.meumobi.com#{t}" }
    }
  }

  json.each { |t|
    t['colors'].each { |main_color, colors|
      skin = { theme_id: t['name'], main_color: main_color, assets: Hash[t['assets'].map { |key, value|
        [key, "http://meu-template-engine.meumobi.com#{value}"]
      }], colors: colors }
      collection.insert skin
    }
  }

  File.open File.expand_path(File.join(__FILE__, '../../config/themes.json')), 'w+' do |f|
    f.write JSON.pretty_generate(themes)
  end
end
