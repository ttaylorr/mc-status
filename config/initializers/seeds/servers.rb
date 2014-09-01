require_relative '../../../models/helpers'
require_relative '../../../models/server'

module MCStatus
  module Seeds
    class Servers
      @@servers = [{:name => 'Mineplex', :minecraft_ip => 'us.mineplex.com', :website => 'http://mineplex.com'},
                   {:name => 'Hypixel Network', :minecraft_ip => 'mc.hypixel.net', :website => 'http://hypixel.net'},
                   {:name => 'HiveMC', :minecraft_ip => 'hivemc.eu', :website => 'http://hivemc.eu'},
                   {:name => 'Shotbow', :minecraft_ip => 'us.shotbow.net', :website => 'http://shotbow.net'},
                   {:name => 'Wynncraft', :minecraft_ip => 'play.wynncraft.com', :website => 'http://wynncraft.com'},
                   {:name => 'Overcast Network', :minecraft_ip => 'us.oc.tc', :website => 'https://oc.tc'},
                   {:name => 'PlayMindcrack', :minecraft_ip => 'us.playmindcrack.com', :website => 'http://playmindcrack.com'},
                   {:name => 'Badlion Network', :minecraft_ip => 'mc.badlion.net', :website => 'http://badlion.net'}]

      def seed!
        @@servers.each do |server_info|
          print "  Seeding server '#{server_info[:name]}' with IP: #{server_info[:minecraft_ip]}... "

          MCStatus::Models::Server.create(server_info).save!

          puts "done"
        end

        puts "\n  Created #{@@servers.size} documents!"
      end
    end
  end
end
