require "json"

require_relative "../models/ping"

module MCStatus
  class ServerUpdater < Struct.new(:servers)
    @@script_location = File.expand_path("server_ping.php", File.dirname(__FILE__))

    def update!
      start = Time.now

      self.servers.each do |server|
        ping = get_ping_data(server)

         unless ping.nil?
          MCStatus::Models::Ping.create(
            :server => server,
            :version_name => ping["version"]["name"],
            :max_players => ping["players"]["max"],
            :players_online => ping["players"]["online"],
            :created_at => start
          ).save!
        end
      end
    end

    private

    def get_ping_data(server)
      begin
        JSON.parse(`php "#{@@script_location}" "#{server.minecraft_ip}"`)
      rescue JSON::ParserError => e
        nil
      end
    end
  end
end
