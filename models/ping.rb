module MCStatus
  module Models
    class Ping
      include Mongoid::Document
      include Mongoid::Timestamps::Created

      store_in :database => 'mc_pings', :collection => 'pings'
      index({:created_at => 1}, {:unique => false, :name => 'created_at_index'})

      belongs_to :server

      field :version_name, :type => String
      field :max_players, :type => Integer
      field :players_online, :type => Integer

      def to_api_format
        {
          :players_online => self.players_online,
          :max_players => self.max_players,
          :created_at => self.created_at,
          :version_name => self.version_name
        }
      end

      def to_frac
        "#{self.players_online} / #{self.max_players}"
      end
    end
  end
end
