$(document).ready () -> 
  chart = c3.generate {
    data: {
      x: 'x'
      columns: [],
      type: 'area'
    },
    axis: {
      x: {
        type: 'timeseries',
        tick: {
          count: 16,
          format: '%Y-%m-%d'
        }
      }
    }
    subchart: { show: true },
    size: { height: 480 },
    point: { show: false },
  }

  last_update = (Date.now() - 86400000) / 1000

  $.getJSON '/api/servers', (servers) -> 
    for server in servers
      $.getJSON '/api/servers/' + server._id + '?since=' + last_update, (server_data) ->
        dates = server_data.pings.map (ping) -> Date.parse(ping.created_at)
        pings = server_data.pings.map (ping) -> ping.players_online

        x_axis = ['x'].concat dates
        data = [server_data.server.name].concat pings

        chart.load {
          columns: [ x_axis, data ]
        }
