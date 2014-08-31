require_relative '../models/server'
require_relative '../models/service'

module MCStatus
  module Routes
    module Index
      def self.registered(application)
        application.get '/' do
          rendered_services = ['minecraft.net', 'auth.mojang.com', 'sessionserver.mojang.com', 'skins.minecraft.net']

          get_latest_ping(nil)

          haml :index, :locals => {
            :servers => MCStatus::Models::Server.all,
            :services => MCStatus::Models::Service.where(:api_name.in => rendered_services)
          }
        end
      end
    end
  end
end
