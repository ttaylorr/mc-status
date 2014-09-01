require 'redis'
require 'redis-namespace'

module MCStatus
  module Helpers
    module RedisHelper
      # Public - returns the _namespaced_ Redis connection
      def redis
        @redis_connection ||= Redis.new(:host => 'localhost')
        @ns_redis ||= Redis::Namespace.new(:mc_status, :redis => @redis_connection)
      end
    end
  end
end
