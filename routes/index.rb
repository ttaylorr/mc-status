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

          servers.each do |server|
            latest_ping = get_latest_ping(server)

            pings[server] = if latest_ping.nil?
                              {
                                :max_players => "&inf;",
                                :players_online => 0
                              }
                            else
                              JSON.parse(latest_ping)
                            end
          end

          pings = pings.sort_by { |server, ping| -ping["players_online"] }

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
