require 'redis'

module MCStatus
  module Helpers
    module RedisHelper
      def redis
        @redis ||= Redis.new(:host => 'localhost')
      end
    end
  end
end
