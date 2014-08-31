require_relative 'models/helpers'
require_relative 'models/service'
require_relative 'models/server'

require_relative 'config/initializers/seeds/services'
require_relative 'config/initializers/seeds/servers'

namespace :minecraft do
  task :services do
    MCStatus::ServiceUpdater.new(MCStatus::Models::Service.all).update!
  end

  task :servers do
    MCStatus::ServerUpdater.new(MCStatus::Models::Server.all).update!
  end
end

namespace :db do
  task :seed do
    MCStatus::Seeds::Services.new.seed!
    MCStatus::Seeds::Servers.new.seed!
  end

  task :dump do
    MCStatus::Models::Service.delete_all
    MCStatus::Models::Server.delete_all
  end
end
