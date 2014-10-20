require 'bundler'
Bundler.require

require './mc_status'
run MCStatus::Application.new
