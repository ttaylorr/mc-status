require 'json'

require_relative '../models/server'
require_relative '../models/service'
require_relative '../models/server'

module MCStatus
  module Routes
    module API
      def self.registered(application)
        application.before "/api/?*" do
          content_type :json
        end

        application.get '/api/?' do
          {
            :status => 200,
            :routes => {
              :server => "/api/servers/:id",
              :servers => "/api/servers/"
            }
          }.to_json
        end

        application.get '/api/servers' do
          result = MCStatus::Models::Server.all.map do |server|
            server.to_api_format
          end

          result.to_json
        end

        application.get '/api/servers/:id' do
          granularity = 4 
          since = Time.at(
            if params[:since]
              params[:since].to_i
            else
              0
            end
          ).to_datetime

          server = MCStatus::Models::Server.find(params[:id])
          pings = MCStatus::Models::Ping.where(:server => server, :created_at.gt => since)

          pings = pings.map { |ping| ping.to_api_format }
          pings = pings.each_slice(granularity).map(&:last)

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
