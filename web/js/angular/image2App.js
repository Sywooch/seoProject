'use strict';
var app = angular.module('app', [
    'ngRoute', //$routeProvider
    'mgcrea.ngStrap' //bs-navbar, data-match-route directives,
]);
app.factory('canvasDrawer', function () {
    var registry = {};

    var canvasDrawer = {
        /**
         * draw the square on the canvas
         */
        draw: function (height, width) {
            console.log("square to draw: " + height + "x" + width);

            var canvas = document.getElementById("myimage_canvas");
            if (canvas.getContext) {
                console.log("drawing");
                var ctx = canvas.getContext("2d");
                //clear the canvas
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                ctx.fillRect(0, 0, width, height);
            }
        },
        // draw area in canvas
        drawArea: function (points) {
            var canvas = document.getElementById("myimage_canvas");
            if (canvas.getContext) {
                console.log("add point");
                var ctx = canvas.getContext("2d");
                ctx.fillStyle = 'rgba(0,172,239,0.2)';
                ctx.lineWidth = 1;
                ctx.strokeStyle = 'rgba(0,172,239,0.8)';
                ctx.beginPath();
                for (var i = 0, l = points.length; i < l; i++) {
                    if (i == 0) {
                        ctx.moveTo(points[i]['x'], points[i]['y']);
                    } else {
                        ctx.lineTo(points[i]['x'], points[i]['y']);
                    }
                }
                ctx.closePath();
                ctx.fill();
                ctx.stroke();
                // Draw points
                ctx.fillStyle = 'rgba(0,139,191,0.8)';
                for (var i = 0, l = points.length; i < l; i++) {
                    ctx.fillRect(points[i]['x'] - 2, points[i]['y'] - 2, 4, 4);
                }
            }
        },
        // clear full canvas
        clearCanvas: function () {
            var canvas = document.getElementById("myimage_canvas");
            if (canvas.getContext) {
                console.log("clear");
                var ctx = canvas.getContext("2d");
                //clear the canvas
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }

        },
        // clear partiotion in canvas
        clearPartition: function (points) {
            var canvas = document.getElementById("myimage_canvas");
            var minX = points[0].x, maxX = points[0].x,
                    minY = points[0].y, maxY = points[0].y;
            //calculate minX, maxX, minY, maxY
            for (var i = 0, l = points.length; i < l; i++) {
                if (minX > points[i]['x']) {
                    minX = points[i]['x'];
                } else if (maxX < points[i]['x']) {
                    maxX = points[i]['x'];
                }
                if (minY > points[i]['y']) {
                    minY = points[i]['y'];
                } else if (maxY < points[i]['y']) {
                    maxY = points[i]['y'];
                }
            }
            if (canvas.getContext) {
                console.log("clear");
                var ctx = canvas.getContext("2d");
                //clear the canvas
                ctx.clearRect(minX, minY, maxX, maxY);
            }
        },
        /**
         * Get offset for element
         * 
         * @param {type} elm
         * @returns {image2App_L6.canvasDrawer.offset.image2AppAnonym$1}
         */
        offset: function (elm) {
            try {
                return elm.offset();
            } catch (e) {
            }
            var _x = 0;
            var _y = 0;
            var body = document.documentElement || document.body;
            var scrollX = window.pageXOffset || body.scrollLeft;
            var scrollY = window.pageYOffset || body.scrollTop;
            _x = elm.getBoundingClientRect().left + scrollX;
            _y = elm.getBoundingClientRect().top + scrollY;
            return {left: _x, top: _y};
        }
    };
    return canvasDrawer;
});
app.controller('Image2Ctrl', ['$scope', '$http', 'canvasDrawer',
    function ($scope, $http, canvasDrawer) {
        $scope.areas = [[{x: 110, y: 71}, {x: 210, y: 71}, {x: 210, y: 151}, {x: 110, y: 151}]];//areas array
        $scope.buttonTitle = 'Add area';
        $scope.active = false;
        $scope.getCoordinats = function (area) {
            var result = '';
            for (var i = 0; i < area.length; i++) {
                if (i > 0) {
                    result += ',';
                }
                result += area[i].x + ',' + area[i].y;
            }
            return result;
        };
        $scope.points = [];
        // button clear
        $scope.clear = function () {
            $scope.points = [];
            canvasDrawer.clearCanvas();
        };
        //button add 
        $scope.addBtn = function () {
            $scope.active = !$scope.active;
        };
        $scope.saveBtn = function () {
            $scope.active = !$scope.active;
            $scope.areas.push($scope.points);
            $scope.points = [];
            canvasDrawer.clearCanvas();
        };
        // add point 
        $scope.addPoint = function ($event) {
            if ($scope.active) {
                var e = $event,
                        offset = canvasDrawer.offset($event.target);
                var body = document.documentElement || document.body;
                // Push point to area array
                var scrollX = window.pageXOffset || body.scrollLeft;
                var scrollY = window.pageYOffset || body.scrollTop;
                var x = e.clientX + scrollX - offset.left;
                var y = e.clientY + scrollY - offset.top;
                canvasDrawer.clearCanvas();
                $scope.points.push({'x': x, 'y': y});
                // Draw point
                canvasDrawer.drawArea($scope.points);
                // Prevent drag event
                e.preventDefault && e.preventDefault();
                return false;
            }
        };
        //   canvasDrawer.clearCanvas();
    }
]);