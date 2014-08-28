require "net/http"
require "uri"
require "json"

module MCStatus
  class ServiceUpdater < Struct.new(:services)
    API_STATUS_URL = 'http://status.mojang.com/check'

    def update!
      get_api_status.each do |entry|
        service = service_for_name(entry[:name])
        service.status = entry[:status]
        service.save!
      end
    end

    private

    def get_api_status
      uri = URI.parse(API_STATUS_URL)

      http = Net::HTTP.new(uri.host, uri.port)
      request = Net::HTTP::Get.new(uri.request_uri)

      response = http.request(request)
      
      values = []
      JSON.parse(response.body).each do |entry|
        entry.each do |key, value|
          values << {:name => key, :status => value}
        end
      end

      values
    end

    def service_for_name(name)
      self.services.each do |service|
        return service if service.api_name == name
      end
    end
  end
end
