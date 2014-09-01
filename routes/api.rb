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
          since = params[:since].to_i unless params[:since].nil?
          since ||= 0

          since_datetime = Time.at(since).to_datetime
          server = MCStatus::Models::Server.find(params[:id])
          pings = MCStatus::Models::Ping.where(:created_at.gt => since)

          {
            :since => since_datetime,
            :server => server,
            :pings => pings
          }.to_json
        end
      end
    end
  end
end
