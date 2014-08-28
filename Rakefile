require_relative 'models/helpers'
require_relative 'models/service'

namespace :minecraft do
  task :services do
    MCStatus::ServiceUpdater.new(MCStatus::Models::Service.all).update!
  end
end
