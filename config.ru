require 'bundler'
Bundler.require

require './mc_status'

Rack::Server.start(:app => MCStatus::Application.new)
