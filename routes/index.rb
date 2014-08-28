require_relative '../models/service'

module MCStatus
  module Routes
    module Index
      def self.registered(application)
        application.get '/' do
          rendered_services = ['minecraft.net', 'auth.mojang.com', 'sessionserver.mojang.com', 'skins.minecraft.net']

          haml :index, :locals => {
            :services => MCStatus::Models::Service.where(:api_name.in => rendered_services)
          }
        end
      end
    end
  end
end
