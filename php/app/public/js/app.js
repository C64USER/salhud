$(document).ready(function() {

    var gauges = [];
    var disease = null;
    var year = null;

    $('#make-map-button').click(function() {
        disease = $('#select-disease').val();
        year = $('#select-year').val();

        if (disease !== null && year !== null) {
            generateMap(disease, year);
            generateEpidemicCurve(disease, year);
        } else {
            $.snackbar({
                content: "Please select a disease and a year before creating the graph.",
                style: "alert-error",
                timeout: 5000
            });
        }
    });

    generateMap(1, 2014);
    generateEpidemicCurve(1, 2014);

    createGauge("death", "Pueblo", 0, 200);

    $.getJSON("/diseases", function(data) {
        var items = [];
        $.each(data, function(k, v) {
            console.log(data);
            console.log(v);
            $("<option value='" + v.id + "'>" + v.name + "</option>").appendTo("#select-disease");
        });
    });

    d3.json("/public/tmp/region.json", function(data) {
        dashboard('#region-bar-graph', data);
    });

    $('#regions').hide();
    $('#epidemic-curve').hide();

    $('#regions-button').click(function() {
        $("#epidemic-curve").hide();
        $("#map-gauge-view").hide();
        $('#regions').show();
    });

    $('#map-gauge-button').click(function() {
        $("#epidemic-curve").hide();
        $('#regions').hide();
        $("#map-gauge-view").show();
    });

    $('#curve-button').click(function() {
        $('#regions').hide();
        $("#map-gauge-view").hide();
        $("#epidemic-curve").show();
    });

    function generateMap(diseaseId, year) {

        var my_node = document.getElementById("map");
        my_node.innerHTML = "";

        var datamap = {}; //object that stores municipality id and cases
        var datapercent = {}; //object that stores municipality and percent of cases.

        var map;

        //d3.json function to read json files 
        d3.json("/map/" + diseaseId + "/" + year, function(data) {

            //loop for aplication a function to each "pueblo"
            data.forEach(function(pueblo) {

                //id of each "pueblo" for amount of cases
                var fips_3digits = pueblo.fips.toString().length == 1 ? ("00" + pueblo.fips) : (pueblo.fips.toString().length == 2 ? ("0" + pueblo.fips) : pueblo.fips);

                //id of each "pueblo" for percent of cases
                var fips_3digits_2 = pueblo.fips.toString().length == 1 ? ("00" + pueblo.fips) : (pueblo.fips.toString().length == 2 ? ("0" + pueblo.fips) : pueblo.fips);

                // select which cause of death to plot
                var rv = pueblo.disease;

                // switch (Number(diseaseId)) {

                //     case 1:
                //         rv = pueblo.ypll;
                //         break;
                //     case 2:
                //         rv = pueblo.cardio;
                //         break;
                //     case 3:
                //         rv = pueblo.tumor;
                //         break;
                //     case 4:
                //         rv = pueblo.diabetes;
                //         break;
                //     case 5:
                //         rv = pueblo.alzheimer;
                //         break;
                //     case 6:
                //         rv = pueblo.cerebrovascular;
                //         break;
                //     case 7:
                //         rv = pueblo.respiratory;
                //         break;
                //     case 8:
                //         rv = pueblo.accident;
                //         break;
                //     case 9:
                //         rv = pueblo.nefritis;
                //         break;
                //     case 10:
                //         rv = pueblo.homicide;
                //         break;
                //     case 11:
                //         rv = pueblo.pneumonia;
                //         break;
                //     case 12:
                //         rv = pueblo.other;
                //         break;

                //     case 13:
                //         rv = pueblo.dengue;
                //         break; // new disease added
                //         // default : rv = pueblo.ypll; 

                // } //final of switch

                datamap[fips_3digits] = rv; // extract json file cases and municipality id
                datapercent[fips_3digits_2] = pueblo.percent; //extract json file percent and municipality id

                //alert(datamap[fips_3digits] );
            });

            //DEBUG OBJECT
            //console.log(datamap);

            //Create a new Map Of Puerto Rico based in the amount cases of Dengue Fever
            map = new AtlasPR({
                node: my_node,
                tiles: 'pueblos', //style of Puerto Rico's map
                size: "medium", //Size of the Map
                on_ready: function() {
                    map.encode_quan(datamap); //function wchich color the map
                },

                //event that show the percet of cases 
                events: {
                    on_click: function(feature, svg_path) {
                            /*alert(feature.properties.NAME + "\n" + 
                                                        Math.floor(datamap[feature.properties.COUNTY]) + "\n" + 
                                                        datapercent[feature.properties.COUNTY]);*/

                            //gauges["death"].config.label = feature.properties.NAME;
                            //salert(gauges["death"].config.label);
                            //gauges["death"].redraw(datapercent[feature.properties.COUNTY]*100.0);

                            //function that will draw the gauges based in the number of (cases/population) * 200
                            gauges["death"].ourRedraw(datapercent[feature.properties.COUNTY], feature.properties.NAME);

                        } //final of function
                } //final of event

            }); //final of newAtlas

        }); //final of d3.json

    }

    function createGauge(name, label, min, max) {

            //object config of the gauges 
            var config = {
                size: 200, //gauges size
                label: label, // gauges label
                min: undefined !== min ? min : 0, // min value of the gauges
                max: undefined !== max ? max : 200, // max value of the gauges
                minorTicks: 5 // tick of gauges

            }; //final config

            var range = config.max - config.min; // range of the gauges for value

            config.greenZones = [{
                from: config.min,
                to: config.min + range * 0.25
            }]; //safe zone
            config.yellowZones = [{
                from: config.min + range * 0.25,
                to: config.min + range * 0.75
            }]; // Warning Zone
            config.redZones = [{
                from: config.min + range * 0.75,
                to: config.max
            }]; //Danger zone

            //new object of Gauges with configuration
            gauges[name] = new Gauge(name + "GaugeContainer", config);

            gauges[name].render(); //repaint names of gauges

        } //final of createGauges function helper


    function dashboard(id, fData) {

        var barColor = '#201B7D';

        //function segColor(c){ return {low:"#807dba", mid:"#e08214",high:"#41ab5d"}[c]; }

        // compute total for each region.
        fData.forEach(function(d) {
            parseFloat(d.total = d.freq.low + d.freq.mid + d.freq.high);
        });

        // function to handle histogram.
        function histoGram(fD) {
            var hG = {},
                hGDim = {
                    t: 60,
                    r: 0,
                    b: 30,
                    l: 0
                };
            hGDim.w = 500 - hGDim.l - hGDim.r,
                hGDim.h = 300 - hGDim.t - hGDim.b;

            //create svg for histogram.
            var hGsvg = d3.select(id).append("svg")
                .attr("width", hGDim.w + hGDim.l + hGDim.r)
                .attr("height", hGDim.h + hGDim.t + hGDim.b).append("g")
                .attr("transform", "translate(" + hGDim.l + "," + hGDim.t + ")");

            // create function for x-axis mapping.
            var x = d3.scale.ordinal().rangeRoundBands([0, hGDim.w], 0.1)
                .domain(fD.map(function(d) {
                    return d[0];
                }));

            // Add x-axis to the histogram svg.
            hGsvg.append("g").attr("class", "x axis")
                .attr("transform", "translate(0," + hGDim.h + ")")
                .call(d3.svg.axis().scale(x).orient("bottom"));

            //alert(d3.max(fD, function(d) { return d[1]; }));
            // Create function for y-axis map.
            var y = d3.scale.linear().range([hGDim.h, 0])
                .domain([0, d3.max(fD, function(d) {
                    return d[1];
                })]);

            /*var y = d3.scale.linear().range([hGDim.h, 0])
                          .domain([0, 300]);    */

            // Create bars for histogram to contain rectangles and freq labels.
            var bars = hGsvg.selectAll(".bar")
                .data(fD).enter()
                .append("g")
                .attr("class", "bar");

            //create the rectangles.
            bars.append("rect")
                .attr("x", function(d) {
                    return x(d[0]);
                })
                .attr("y", function(d) {
                    return y(d[1]);
                })
                .attr("width", x.rangeBand())
                .attr("height", function(d) {
                    return hGDim.h - y(d[1]);
                })
                .attr('fill', barColor);
            /* .on("mouseover",mouseover)// mouseover is defined below.
             .on("mouseout",mouseout);// mouseout is defined below.*/

            //Create the frequency labels above the rectangles.
            bars.append("text").text(function(d) {
                    return d3.format(",.2f")(d[1]);
                })
                .attr("x", function(d) {
                    return x(d[0]) + x.rangeBand() / 2;
                })
                .attr("y", function(d) {
                    return y(d[1]) - 5;
                })
                .attr("text-anchor", "middle");


            // utility function to be called on mouseover.
            function mouseover(d) {
                    // filter for selected region.
                    var st = fData.filter(function(s) {
                        return s.region == d[0];
                    })[0]; //,
                } //final of mouseover

            // utility function to be called on mouseout.
            function mouseout(d) {
                    // update frecuency in bar 
                    leg.update(tF);

                } //final de mouse Out

            // create function to update the bars. This will be used by pie-chart.
            hG.update = function(nD, color) {
                // update the domain of the y-axis map to reflect change in frequencies.
                y.domain([0, d3.max(nD, function(d) {
                    return d[1];
                })]);

                // Attach the new data to the bars.
                var bars = hGsvg.selectAll(".bar").data(nD);

                // transition the height and color of rectangles.
                bars.select("rect").transition().duration(1000)
                    .attr("y", function(d) {
                        return y(d[1]);
                    })
                    .attr("height", function(d) {
                        return hGDim.h - y(d[1]);
                    })
                    .attr("fill", color);

                // transition the frequency labels location and change value.
                bars.select("text").transition().duration(500)
                    .text(function(d) {
                        return d3.format(",")(d[1]);
                    })
                    .attr("y", function(d) {
                        return y(d[1]) - 5;
                    });
            };
            return hG;
        }

        // calculate total frequency by segment for all region.
        var tF = ['low', 'mid', 'high'].map(function(d) {
            return {
                type: d,
                freq: d3.sum(fData.map(function(t) {
                    return t.freq[d];
                }))
            };
        });

        // calculate total frequency by region for all segment.
        var sF = fData.map(function(d) {
            return [d.region, parseFloat(d.total)];
        });

        //console.log(JSON.stringify(sF));

        var hG = histoGram(sF); // create the histogram.

    }

    function generateEpidemicCurve(diseaseId, year) {
        var margin = {
                top: 20,
                right: 20,
                bottom: 30,
                left: 50
            },
            width = 960 - margin.left - margin.right,
            height = 500 - margin.top - margin.bottom;

        var parseDate = d3.time.format("%d-%M-%Y").parse;

        var x = d3.time.scale()
            .range([0, width]);

        var y = d3.scale.linear()
            .range([height, 0]);

        var xAxis = d3.svg.axis()
            .scale(x)
            .orient("bottom");

        var yAxis = d3.svg.axis()
            .scale(y)
            .orient("left");

        var line = d3.svg.line()
            .x(function(d) {
                return x(d.year);
            })
            .y(function(d) {
                return y(d.percent);
            });
        $("#epidemic-curve-graph").empty();
        var svg = d3.select("#epidemic-curve-graph").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        d3.json("/curve/" + diseaseId, function(error, data1) {
            data1.forEach(function(d) {
                d.year = parseDate("01-01-" + d.year);
                d.percent = +d.percent;
            });
            x.domain(d3.extent(data1, function(d) {
                return d.year;
            }));
            y.domain(d3.extent(data1, function(d) {
                return parseFloat(d.percent);
            }));

            svg.append("g")
                .attr("class", "x axis")
                .attr("transform", "translate(0," + height + ")")
                .call(xAxis);

            svg.append("g")
                .attr("class", "y axis")
                .call(yAxis)
                .append("text")
                .attr("transform", "rotate(-90)")
                .attr("y", 6)
                .attr("dy", ".71em")
                .style("text-anchor", "end")
                .text("Percent");

            svg.append("path")
                .datum(data1)
                .attr("class", "line")
                .attr("d", line);
        });

    }


});