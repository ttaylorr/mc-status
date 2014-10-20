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
            latest_ping = get_latest_ping(server)

            pings[server] = if latest_ping.nil?
                              nil
                            else
                              JSON.parse(latest_ping)
                            end
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
