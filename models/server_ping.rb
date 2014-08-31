module MCStatus
  module Models
    class ServerPing
      include Mongoid::Document
      include Mongoid::Timestamps::Created

      store_in :database => 'mc_pings', :collection => 'pings'

      belongs_to :server

      field :version_name, :type => String
      field :max_players, :type => Integer
      field :players_online, :type => Integer
    end
  end
end
