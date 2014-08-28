require_relative '../../../models/helpers'
require_relative '../../../models/service'

# Seed data for creating initial services
services = [{:api_name => 'minecraft.net', :display_name => 'minecraft.net', :status => 'green'},
            {:api_name => 'session.minecraft.net', :display_name => 'Session Server (Legacy)', :status => 'green'},
            {:api_name => 'account.mojang.com', :display_name => 'Account Server', :status => 'green'},
            {:api_name => 'auth.mojang.com', :display_name => 'Login Server', :status => 'green'},
            {:api_name => 'skins.minecraft.net', :display_name => 'Skin Server', :status => 'green'},
            {:api_name => 'authserver.mojang.com', :display_name => 'Legacy Auth Server', :status => 'green'},
            {:api_name => 'sessionserver.mojang.com', :display_name => 'Session Server', :status => 'green'},
            {:api_name => 'api.mojang.com', :display_name => 'API', :status => 'green'},
            {:api_name => 'textures.minecraft.net', :display_name => 'Skin Server (Legacy)', :status => 'green'}]

services.each do |service_info|
  print "  Seeding service '#{service_info[:api_name]}' with status: #{service_info[:status]}... "

  MCStatus::Models::Service.create(service_info).save

  puts "done"
end

puts "\n  Created #{services.size} documents!"
