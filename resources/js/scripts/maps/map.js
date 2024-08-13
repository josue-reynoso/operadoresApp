import 'ol/ol.css';

import Map from 'ol/Map';
import OSM from 'ol/source/OSM';
import Overlay from 'ol/Overlay';
import TileLayer from 'ol/layer/Tile';
import View from 'ol/View';
import { fromLonLat, toLonLat } from 'ol/proj';
import { toStringHDMS } from 'ol/coordinate';
import ZoomToExtent from 'ol/control/ZoomToExtent';
import Point from 'ol/geom/Point';

import Feature from 'ol/Feature';
import VectorSource from 'ol/source/Vector';
import VectorLayer from 'ol/layer/Vector';
import LineString from 'ol/geom/LineString';
import Fill from 'ol/style/Fill';
import RegularShape from 'ol/style/RegularShape';
import Style from 'ol/style/Style';
import Stroke from 'ol/style/Stroke';
import { DEVICE_PIXEL_RATIO } from 'ol/has';

var map = null;
var view = new View({
    center: [0, 0],
    zoom: 2,
});
const layer = new TileLayer({
    source: new OSM(),
});

const markers = [];
const stopMarkers = [];
var popup = null;
let veclay_lines = [];
let veclay_linesTransparent = [];
let veclay_line = null;

var my_map = {
    display: function() {
        map = new Map({
            target: 'divMap',
            layers: [layer],
            view: view,
        });
        map.on('click', function(evt) {
            if (popup == null) return;
            popup.setPosition(undefined);
        })
    },
    goToPosition: function(lat, lon, z = null) {
        if (z == null) z = view.getZoom();
        view.animate({ center: fromLonLat([lon, lat]) }, { zoom: z });
    },
    placeMarker: function(lat, lon, elementId) {
        var pos = fromLonLat([lon, lat]);
        markers.push(new Overlay({
            position: pos,
            positioning: 'center-center',
            element: document.getElementById(elementId),
            //stopEvent: false,
        }));
        map.addOverlay(markers[markers.length - 1]);
        return markers.length - 1;
    },
    moveMarker: function(lat, lon, markerIndex) {
        var pos = fromLonLat([lon, lat]);
        markers[markerIndex].setPosition(pos);
    },
    addPopUp: function(popupId) {
        popup = new Overlay({
            element: document.getElementById(popupId),
        });
        popup.setPosition(undefined);
        map.addOverlay(popup);
    },
    openPopUp: function(lat, lon) {
        if (popup == null) return;

        const element = popup.getElement();
        if (lon == undefined) {
            popup.setPosition(undefined);
        } else {
            var pos = fromLonLat([lon, lat]);
            popup.setPosition(pos);
        }

        element.style.display = "block";

        $(element).popover('dispose');
        $(element).popover({
            container: element,
            placement: 'top',
            animation: false,
            html: true,
        });
    },
    isPopUpVisible: function() {
        if (popup == null) return false;
        //
        return popup.getPosition() != undefined;
    },
    centerMap: function() {
        var bottomLeftX, bottomLeftY, topRightX, topRightY;
        for (var i = 0; i < markers.length; i++) {
            if (i == 0) {
                bottomLeftX = markers[i].getPosition()[0];
                bottomLeftY = markers[i].getPosition()[1];
                topRightX = markers[i].getPosition()[0];
                topRightY = markers[i].getPosition()[1];
            }
            if (markers[i].getPosition()[0] < bottomLeftX) {
                bottomLeftX = markers[i].getPosition()[0];
            }
            if (markers[i].getPosition()[1] < bottomLeftY) {
                bottomLeftY = markers[i].getPosition()[1];
            }

            if (markers[i].getPosition()[0] > topRightX) {
                topRightX = markers[i].getPosition()[0];
            }
            if (markers[i].getPosition()[1] > topRightY) {
                topRightY = markers[i].getPosition()[1];
            }
        }
        /*var zoomToExtentControl = new ZoomToExtent({
            extent: [bottomLeftX, bottomLeftY, topRightX, topRightY]
        });
        map.addControl(zoomToExtentControl);*/
        //-6234927.992570928, -4144400.7440630314
        //-11260071.381437615, 2239389.547906981
        //-11260071.381437615,-4144400.7440630314    -6234927.992570928,2239389.547906981
        map.getView().fit([bottomLeftX, bottomLeftY, topRightX, topRightY], map.getSize());
        if (markers.length > 1 && map.getView().getZoom() > 2) {
            map.getView().setZoom(view.getZoom() - 0.2);
        }
        if (map.getView().getZoom() > 18) {
            map.getView().setZoom(18);
        }
    },
    centerMapCoordinates: function(coordinates) {
        if (coordinates.length == 0) return;
        var bottomLeftX, bottomLeftY, topRightX, topRightY;
        let coor = new Overlay({ element: null });
        for (var i = 0; i < coordinates.length; i++) {
            coor.setPosition(fromLonLat([coordinates[i][0], coordinates[i][1]]));
            if (i == 0) {
                bottomLeftX = coor.getPosition()[0];
                bottomLeftY = coor.getPosition()[1];
                topRightX = coor.getPosition()[0];
                topRightY = coor.getPosition()[1];
            }
            if (coor.getPosition()[0] < bottomLeftX) {
                bottomLeftX = coor.getPosition()[0];
            }
            if (coor.getPosition()[1] < bottomLeftY) {
                bottomLeftY = coor.getPosition()[1];
            }

            if (coor.getPosition()[0] > topRightX) {
                topRightX = coor.getPosition()[0];
            }
            if (coor.getPosition()[1] > topRightY) {
                topRightY = coor.getPosition()[1];
            }
        }
        map.getView().fit([bottomLeftX, bottomLeftY, topRightX, topRightY], map.getSize());
        if (coordinates.length > 1 && map.getView().getZoom() > 2) {
            map.getView().setZoom(view.getZoom() - 0.2);
        }
        if (map.getView().getZoom() > 18) {
            map.getView().setZoom(18);
        }
    },
    drawRoute: function(coordinates, color, width, r = null, g = null, b = null) {
        /* https://openlayers.org/en/latest/apidoc/module-ol_style_Stroke.html */
        /*const lonlat0 = [-55.988733333333336, -34.8156]; //UY*/
        var points = []; //[fromLonLat(lonlat0), fromLonLat(lonlat1), fromLonLat(lonlat2)];
        var lastLonlat = null;
        for (let i = 0; i < coordinates.length; i++) {
            if (lastLonlat != null && lastLonlat[0] != coordinates[i][0] && lastLonlat[1] != coordinates[i][1])
                points.push(fromLonLat([coordinates[i][0], coordinates[i][1]]));
            lastLonlat = coordinates[i];
        }

        var line_feat1 = new Feature({
            geometry: new LineString(points),
            name: "route"
        });
        var line_vsrc = new VectorSource({
            features: [line_feat1],
            wrapX: false
        });
        var style = null;
        switch (color) {
            case 'rainbow':
                //ARCOIRIS
                const pixelRatio = DEVICE_PIXEL_RATIO;
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                const gradient = context.createLinearGradient(0, 0, 1024 * pixelRatio, 0);
                gradient.addColorStop(0, 'red');
                gradient.addColorStop(1 / 6, 'orange');
                gradient.addColorStop(2 / 6, 'yellow');
                gradient.addColorStop(3 / 6, 'green');
                gradient.addColorStop(4 / 6, 'aqua');
                gradient.addColorStop(5 / 6, 'blue');
                gradient.addColorStop(1, 'purple');
                var rainbowStyle = new Style({
                    stroke: new Stroke({
                        color: gradient,
                        width: width,
                        //lineDash: [4, 4, 4],
                        lineCap: "round" //square, butt
                    })
                });
                style = rainbowStyle;
                break;
            case 'glow':
                //GLOW
                if (r > 255) r = 255;
                if (r < 0) r = 0;
                if (g > 255) g = 255;
                if (g < 0) g = 0;
                if (b > 255) b = 255;
                if (b < 0) b = 0;
                var steps = 13;
                var glowStyles = [];
                for (var i = 0; i < steps; i++) {
                    glowStyles.push(
                        new Style({
                            stroke: new Stroke({
                                color: [r, g, b, 1 / (steps - i)],
                                width: (steps - i) * 2 - 1
                            })
                        })
                    );
                }
                style = glowStyles;
                break;
            case 'rgb':
                //RGB COLOR
                if (r > 255) r = 255;
                if (r < 0) r = 0;
                if (g > 255) g = 255;
                if (g < 0) g = 0;
                if (b > 255) b = 255;
                if (b < 0) b = 0;
                var colorStyle = new Style({
                    stroke: new Stroke({
                        color: [r, g, b, 1],
                        width: width,
                        //lineDash: [4, 4, 4],
                        lineCap: "round" //square, butt                    
                    })
                });
                style = colorStyle;
                break;
            default:
                //COLOR
                var colorStyle = new Style({
                    stroke: new Stroke({
                        color: color,
                        width: width,
                        //lineDash: [4, 4, 4],
                        lineCap: "round" //square, butt                    
                    })
                });
                style = colorStyle;
        }

        veclay_line = new VectorLayer({
            source: line_vsrc,
            style: style
        });

        map.addLayer(veclay_line);
    },
    removeRoute: function() {
        if (veclay_line != null) {
            map.removeLayer(veclay_line);
            veclay_line = null;
        }
        if (veclay_lines != []) {
            for (let i = 0; i < veclay_lines.length; i++)
                map.removeLayer(veclay_lines[i]);
            veclay_lines = [];
        }
        if (veclay_linesTransparent != []) {
            for (let i = 0; i < veclay_linesTransparent.length; i++)
                map.removeLayer(veclay_linesTransparent[i]);
            veclay_linesTransparent = [];
        }
    },
    drawRouteRGBtoRGB: function(coordinates, ri, gi, bi, re, ge, be, width, maxSeparationDistance) {
        if (coordinates.length == 0) return;
        if (ri > 255) ri = 255;
        if (ri < 0) ri = 0;
        if (gi > 255) gi = 255;
        if (gi < 0) gi = 0;
        if (bi > 255) bi = 255;
        if (bi < 0) bi = 0;
        if (re > 255) re = 255;
        if (re < 0) re = 0;
        if (ge > 255) ge = 255;
        if (ge < 0) ge = 0;
        if (be > 255) be = 255;
        if (be < 0) be = 0;
        //ri, gi, bi: initial color
        //re, ge, be: end color
        var points = []; //[fromLonLat(lonlat0), fromLonLat(lonlat1), fromLonLat(lonlat2)];
        var lastLonlat = null;
        var lastLonlatValid = null;
        for (let i = 0; i < coordinates.length; i++) {
            let distance = 0;
            if (lastLonlatValid != null) {
                distance = Math.abs(Math.sqrt(Math.pow(lastLonlatValid[0] - coordinates[i][0], 2) + Math.pow(lastLonlatValid[1] - coordinates[i][1], 2)));
            }
            if (lastLonlat == null || (lastLonlat[0] != coordinates[i][0] && lastLonlat[1] != coordinates[i][1] && distance <= maxSeparationDistance)) {
                points.push(fromLonLat([coordinates[i][0], coordinates[i][1]]));
                lastLonlatValid = coordinates[i];
            }
            lastLonlat = coordinates[i];
        }
        let changes = parseInt(10 * points.length / 255);
        for (let i = 0; i < changes; i++) {
            var divPoints = [];
            for (let j = parseInt(i * points.length / changes); j <= parseInt((i + 1) * points.length / changes); j++) {
                if (points[j] != undefined)
                    divPoints.push(points[j]);
            }
            var line_feat1 = new Feature({
                geometry: new LineString(divPoints),
                name: "route" + i
            });
            var line_vsrc = new VectorSource({
                features: [line_feat1],
                wrapX: false
            });
            var style = new Style({
                stroke: new Stroke({
                    color: [
                        ri + (i * parseInt((re - ri) / changes)),
                        gi + (i * parseInt((ge - gi) / changes)),
                        bi + (i * parseInt((be - bi) / changes)),
                        1 //i % 2 == 0 ? 1 : 0.2
                    ],
                    width: width,
                    //lineDash: [4, 4, 4],
                    lineCap: "round" //square, butt                    
                })
            });

            //####### Añadir flechas en cada tramo

            var geometry = line_feat1.getGeometry();
            var styles = [
                // linestring
                new Style({
                    stroke: new Stroke({
                        color: [
                            ri + (i * parseInt((re - ri) / changes)),
                            gi + (i * parseInt((ge - gi) / changes)),
                            bi + (i * parseInt((be - bi) / changes)),
                            1 //i % 2 == 0 ? 1 : 0.2
                        ],
                        width: width,
                        lineCap: "round" //square, butt           
                    })
                })
            ];

            geometry.forEachSegment(function(start, end) {
                var dx = end[0] - start[0];
                var dy = end[1] - start[1];
                var rotation = Math.atan2(dy, dx);

                styles.push(new Style({
                    geometry: new Point(end),
                    image: new RegularShape({
                        fill: new Fill({
                            color: [
                                ri + (i * parseInt((re - ri) / changes)),
                                gi + (i * parseInt((ge - gi) / changes)),
                                bi + (i * parseInt((be - bi) / changes)),
                                1 //i % 2 == 0 ? 1 : 0.2
                            ]
                        }),
                        points: 3,
                        radius: 8,
                        rotation: -rotation,
                        angle: Math.PI / 2 // rotate 90°
                    })
                }));
            });


            let veclay_lineTmp = new VectorLayer({
                source: line_vsrc,
                style: styles
            });
            veclay_lines.push(veclay_lineTmp);
        }

        for (let i = 0; i < veclay_lines.length; i++) {
            map.addLayer(veclay_lines[i]);
        }

    },
    drawRouteRGBtoRGBDistance: function(coordinates, ri, gi, bi, re, ge, be, distance, width) {
        if (coordinates.length == 0) return;
        if (ri > 255) ri = 255;
        if (ri < 0) ri = 0;
        if (gi > 255) gi = 255;
        if (gi < 0) gi = 0;
        if (bi > 255) bi = 255;
        if (bi < 0) bi = 0;
        if (re > 255) re = 255;
        if (re < 0) re = 0;
        if (ge > 255) ge = 255;
        if (ge < 0) ge = 0;
        if (be > 255) be = 255;
        if (be < 0) be = 0;
        //ri, gi, bi: initial color
        //re, ge, be: end color
        var points = []; //[fromLonLat(lonlat0), fromLonLat(lonlat1), fromLonLat(lonlat2)];
        var lastLonlat = null;
        var splitPoints = [];
        let coordinateDistance = null;

        splitPoints.push(0);
        for (let i = 0; i < coordinates.length; i++) {
            if (coordinateDistance == null) coordinateDistance = coordinates[i];
            if (lastLonlat == null || (lastLonlat[0] != coordinates[i][0] && lastLonlat[1] != coordinates[i][1]))
                points.push(fromLonLat([coordinates[i][0], coordinates[i][1]]));
            if (Math.abs(coordinates[i][0]) - Math.abs(coordinateDistance[0]) >= distance ||
                Math.abs(coordinates[i][1]) - Math.abs(coordinateDistance[1]) >= distance) {
                splitPoints.push(points.length - 1);
                coordinateDistance = coordinates[i];
            }
            lastLonlat = coordinates[i];
        }
        splitPoints.push(points.length - 1);

        let rc = ri;
        let gc = gi;
        let bc = bi;
        let direction = 1;
        for (let i = 0; i < splitPoints.length - 1; i++) {
            var divPoints = [];
            for (let j = splitPoints[i]; j <= splitPoints[i + 1]; j++) {
                if (points[j] != undefined)
                    divPoints.push(points[j]);
            }
            var line_feat1 = new Feature({
                geometry: new LineString(divPoints),
                name: "route" + i
            });
            var line_vsrc = new VectorSource({
                features: [line_feat1],
                wrapX: false
            });
            if (rc <= ri && gc <= gi && bc <= bi) {
                direction = 1;
            }
            if (rc >= re && gc >= ge && bc >= be) {
                direction = -1;
            }
            rc = rc + direction * (parseInt((re - ri) / 5));
            gc = gc + direction * (parseInt((ge - gi) / 5));
            bc = bc + direction * (parseInt((be - bi) / 5));
            if (rc > 255) rc = 255;
            if (rc < 0) rc = 0;
            if (gc > 255) gc = 255;
            if (gc < 0) gc = 0;
            if (bc > 255) bc = 255;
            if (bc < 0) bc = 0;
            var style = new Style({
                stroke: new Stroke({
                    color: [
                        rc,
                        gc,
                        bc,
                        1
                    ],
                    width: width,
                    //lineDash: [4, 4, 4],
                    lineCap: "round" //square, butt                    
                })
            });
            let veclay_lineTmp = new VectorLayer({
                source: line_vsrc,
                style: style
            });
            veclay_lines.push(veclay_lineTmp);
        }

        for (let i = 0; i < veclay_lines.length; i++) {
            map.addLayer(veclay_lines[i]);
        }

    },
    drawRouteRGBtoRGBHours: function(coordinates, ri, gi, bi, re, ge, be, width) {
        if (coordinates.length == 0) return;
        if (ri > 255) ri = 255;
        if (ri < 0) ri = 0;
        if (gi > 255) gi = 255;
        if (gi < 0) gi = 0;
        if (bi > 255) bi = 255;
        if (bi < 0) bi = 0;
        if (re > 255) re = 255;
        if (re < 0) re = 0;
        if (ge > 255) ge = 255;
        if (ge < 0) ge = 0;
        if (be > 255) be = 255;
        if (be < 0) be = 0;
        //ri, gi, bi: initial color
        //re, ge, be: end color
        var points = []; //[fromLonLat(lonlat0), fromLonLat(lonlat1), fromLonLat(lonlat2)];
        var lastLonlat = null;
        var splitPoints = [];
        var splitHour = [];
        let hour = null; // 15/09/2022 - 16:07        

        splitPoints.push(0);
        for (let i = 0; i < coordinates.length; i++) {
            let curHour = coordinates[i][2].replace(' - ', '/').replace(':', '/');
            curHour = parseInt(curHour.split('/')[3]);
            if (hour == null) hour = curHour;
            if (lastLonlat == null || (lastLonlat[0] != coordinates[i][0] && lastLonlat[1] != coordinates[i][1])) {
                points.push(fromLonLat([coordinates[i][0], coordinates[i][1]]));
            }
            if (hour != curHour) {
                splitPoints.push(points.length - 1);
                splitHour.push(curHour);
                hour = curHour;
            }
            lastLonlat = coordinates[i];
        }
        splitPoints.push(points.length - 1);
        splitHour.push(splitHour[splitHour.length - 1]);
        splitHour.push(splitHour[splitHour.length - 1]);

        for (let i = 0; i < splitPoints.length - 1; i++) {
            var divPoints = [];
            var currentHour = 0;
            for (let j = splitPoints[i]; j <= splitPoints[i + 1]; j++) {
                if (points[j] != undefined) {
                    divPoints.push(points[j]);
                    currentHour = splitHour[i];
                }
            }
            var line_feat1 = new Feature({
                geometry: new LineString(divPoints),
                name: "route" + i
            });
            var line_vsrc = new VectorSource({
                features: [line_feat1],
                wrapX: false
            });
            var rc = parseInt((re - ri) / 24) * currentHour;
            var gc = parseInt((ge - gi) / 24) * currentHour;
            var bc = parseInt((be - bi) / 24) * currentHour;
            if (rc > 255) rc = 255;
            if (rc < 0) rc = 0;
            if (gc > 255) gc = 255;
            if (gc < 0) gc = 0;
            if (bc > 255) bc = 255;
            if (bc < 0) bc = 0;
            var style = new Style({
                stroke: new Stroke({
                    color: [
                        rc,
                        gc,
                        bc,
                        1
                    ],
                    width: width,
                    //lineDash: [4, 4, 4],
                    lineCap: "round" //square, butt                    
                })
            });
            let veclay_lineTmp = new VectorLayer({
                source: line_vsrc,
                style: style
            });
            veclay_lines.push(veclay_lineTmp);
        }

        for (let i = 0; i < veclay_lines.length; i++) {
            map.addLayer(veclay_lines[i]);
        }

    },
    drawWhenTrue: function(coordinates, ri, gi, bi, width) {
        if (coordinates.length == 0) return;
        if (ri > 255) ri = 255;
        if (ri < 0) ri = 0;
        if (gi > 255) gi = 255;
        if (gi < 0) gi = 0;
        if (bi > 255) bi = 255;
        if (bi < 0) bi = 0;
        //ri, gi, bi: initial color
        var points = []; //[fromLonLat(lonlat0), fromLonLat(lonlat1), fromLonLat(lonlat2)];
        var speedLimit = [];
        var lastLonlat = null;

        for (let i = 0; i < coordinates.length; i++) {
            let curSpeedLimit = coordinates[i][3];
            if (lastLonlat == null || (lastLonlat[0] != coordinates[i][0] && lastLonlat[1] != coordinates[i][1])) {
                points.push(fromLonLat([coordinates[i][0], coordinates[i][1]]));
                speedLimit.push(curSpeedLimit);
            }
            lastLonlat = coordinates[i];
        }

        for (let i = 0; i < points.length - 1; i++) {
            var divPoints = [];
            if (speedLimit[i]) {
                if (points[i] != undefined) {
                    if (i != 0 && points[i - 1] != undefined)
                        divPoints.push(points[i - 1]);
                    divPoints.push(points[i]);
                }
            }
            var line_feat1 = new Feature({
                geometry: new LineString(divPoints),
                name: "routeMark" + i
            });
            var line_vsrc = new VectorSource({
                features: [line_feat1],
                wrapX: false
            });

            var style = new Style({
                stroke: new Stroke({
                    color: [ri, gi, bi, 1],
                    width: width,
                    lineDash: [4, 4, 4],
                    lineCap: "round" //square, butt                    
                })
            });
            let veclay_lineTmp = new VectorLayer({
                source: line_vsrc,
                style: style
            });
            veclay_linesTransparent.push(veclay_lineTmp);
        }

        for (let i = 0; i < veclay_linesTransparent.length; i++) {
            map.addLayer(veclay_linesTransparent[i]);
        }

    },
};
window.my_map = my_map;