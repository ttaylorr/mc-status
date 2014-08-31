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
      end
    end
  end
end
