require 'mongoid'

module MCStatus
  module Models
    module Helpers
      Mongoid.load! File.join(File.dirname(__FILE__), '../config/mongoid.yml'), :development
    end
  end
end
