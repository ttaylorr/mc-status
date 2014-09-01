require_relative '../models/server'
require_relative '../models/service'

module MCStatus
  module Routes
    module Index
      def self.registered(application)
        application.get '/' do
          rendered_services = ['minecraft.net', 'auth.mojang.com', 'sessionserver.mojang.com', 'skins.minecraft.net']

          servers = MCStatus::Models::Server.all
          pings = Hash.new
          servers.map do |server|
            pings[server] = get_latest_ping(server)
          end

          haml :index, :locals => {
            :servers => servers,
            :pings => pings,
            :services => MCStatus::Models::Service.where(:api_name.in => rendered_services)
          }
        end
      end
    end
  end
end
