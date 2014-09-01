require 'json'

module MCStatus
  module Helpers
    module ServerHelper
      def get_latest_ping(server)
        key = "#{slug(server)}:last_ping"
        result = redis.get(key)

        if result.nil?
          last_ping = server.server_pings.first
          result = {
            :max_players => last_ping.max_players,
            :players_online => last_ping.players_online,
            :fraction => last_ping.to_frac
          }.to_json

          redis.set(key, result)
          redis.expire(key, 1.minute)
        end

        result
      end

      private
      def slug(server)
        server.name.downcase.gsub(/\w/, '-')
      end
    end
  end
end
