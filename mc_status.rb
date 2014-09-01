require_relative 'models/helpers'

require_relative 'routes/index'
require_relative 'routes/api'

require_relative 'helpers/redis_helper'
require_relative 'helpers/server_helper'

module MCStatus
  class Application < Sinatra::Application
    register Sinatra::AssetPack

    register MCStatus::Models::Helpers
    register MCStatus::Routes::Index
    register MCStatus::Routes::API

    helpers MCStatus::Helpers::RedisHelper
    helpers MCStatus::Helpers::ServerHelper

    set :views, Proc.new { File.join(Dir.pwd, "views") }

    assets do
      serve '/assets/javascripts', :from => 'assets/javascripts'
      js :application, [
        '/assets/javascripts/jquery-2.1.1.min.js',
        '/assets/javascripts/bootstrap.min.js',
        '/assets/javascripts/d3.min.js',
        '/assets/javascripts/c3.min.js',
        '/assets/javascripts/graph.js'
      ]

      serve '/assets/stylesheets', :from => 'assets/stylesheets'
      css :application, [
        '/assets/stylesheets/bootstrap.min.css',
        '/assets/stylesheets/application.css',
        '/assets/stylesheets/c3.css'
      ]

      js_compression :coffeescript
      css_compression :sass
    end

    configure do
      enable :partial_underscores
    end
  end
end
