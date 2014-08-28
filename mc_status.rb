require_relative 'models/helpers'
require_relative 'models/service'

module MCStatus
  class Application < Sinatra::Application
    register Sinatra::AssetPack
    register MCStatus::Models::Helpers

    assets do
      serve '/assets/javascripts', :from => 'assets/javascripts'
      js :application, [
        '/assets/javascripts/jquery-2.1.1.min.js',
        '/assets/javascripts/bootstrap.min.js'
      ]

      serve '/assets/stylesheets', :from => 'assets/stylesheets'
      css :application, [
        '/assets/stylesheets/bootstrap.min.css',
        '/assets/stylesheets/application.css'
      ]

      css_compression :sass
    end

    configure do
      enable :partial_underscores
    end

    get '/' do
      haml :index
    end
  end
end
