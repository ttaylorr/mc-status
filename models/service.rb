require_relative '../lib/service_updater'

module MCStatus
  module Models
    class Service
      include Mongoid::Document
      include Mongoid::Timestamps::Updated
      store_in :database => 'mc_services', :collection => 'services'

      field :api_name, :type => String
      field :display_name, :type => String
      field :status, :type => String

      index({ :api_name => 1}, { :unique => true, :name => 'name_index'})

      def update!
        MCStatus::ServiceUpdater.new([self]).fetch_status.save!
      end

      def bootstrapize_name
        return case self.status.downcase
               when 'green'
                 'alert-success'
               when 'yellow'
                 'alert-warning'
               when 'red'
                 'alert-danger'
               end
      end

      def humanize_status
        return case self.status.downcase
               when 'green'
                 'healthy'
               when 'yellow'
                 'having problems'
               when 'red'
                 'offline'
               end
      end
    end
  end
end
