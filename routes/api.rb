require 'json'

require_relative '../models/server'
require_relative '../models/service'
require_relative '../models/server'

module MCStatus
  module Routes
    module API
      def self.registered(application)
        application.before "/api/*" do
          content_type :json
        end

        application.get '/api/servers' do
          result = {}

          MCStatus::Models::Server.all.each do |server|
            result[server.name] = {
              :_id => server.id.to_s,
              :website => server.website,
              :minecraft => server.minecraft_ip
            }
          end

          result.to_json
        end

        application.get '/api/servers/:id' do
          since = Time.at(
            if params[:since]
              params[:since].to_i
            else
              0
            end
          ).to_datetime

          server = MCStatus::Models::Server.find(params[:id])
          pings = MCStatus::Models::Ping.where(:created_at.gt => since)

          pings = pings.map do |ping|
            {
              :players_online => ping.players_online,
              :max_players => ping.max_players,
              :created_at => ping.created_at,
              :version_name => ping.version_name
            }
          end

          {
            :since => since,
            :server => server,
            :pings => pings
          }.to_json
        end
      end
    end
  end
end
