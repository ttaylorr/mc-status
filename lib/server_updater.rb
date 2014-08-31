require "json"

module MCStatus
  class ServerUpdater < Struct.new(:servers)
    @@script_location = File.expand_path("server_ping.php", File.dirname(__FILE__))

    def update!
      puts @@script_location
      start = Time.now

      self.servers.each do |server|
        ping = get_ping_data(server)

        unless ping.nil?
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
