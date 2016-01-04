'use strict';
var app = angular.module('app', [
    'ngRoute', //$routeProvider
    'mgcrea.ngStrap' //bs-navbar, data-match-route directives,
]);
app.factory('serversite', function ($http) {
    var serversite = {
        /**
         * upload Image
         * @param {type} file
         * @returns {undefined}
         */
        uploadImage: function (files, success, error) {
            var uploadUrl = '/image/api-image/upload';

            var fd = new FormData();
            //Take the first selected file
            fd.append("file", files[0]);

            var promise = $http.post(uploadUrl, fd, {
                withCredentials: true,
                headers: {'Content-Type': undefined},
                transformRequest: angular.identity
            })
            if (success)
                promise.success(success);
            if (error)
                promise.error(error);
        },
        /**
         * Load all information about image
         * 
         * @param integer id
         * @param function success
         * @param function error
         * 
         * @returns {undefined}
         */
        loadImage: function (id, success, error) {
            if (!angular.isUndefined(id) && id != null) {
                var response = $http.get('/image/api-image/load', {
                    params: {id: id}
                });
                if (success)
                    response.success(success);
                if (error)
                    response.error(error);
            }
        },
        /**
         * Save information about image 
         * 
         * @param integer id
         * @param string name
         * @param string file
         * @param function success
         * @param function error
         */
        saveImage: function (id, name, file, success, error) {
            var params = {name: name, file: file};
            if (!angular.isUndefined(id) && id != null && id != '') {
                params['id'] = id;
            }
            var response = $http.get('/image/api-image/save', {
                params: params
            });
            if (success)
                response.success(success);
            if (error)
                response.error(error);
        },
        /**
         * Save polygon 
         * 
         * @param integer id
         * @param points[] area
         * @param function success
         * @param function error
         */
        saveArea: function (id, area, title, success, error) {
            var params = {area: JSON.stringify(area), title: title};
            if (!angular.isUndefined(id) && id != null && id != '') {
                params['id'] = id;
            }
            var response = $http.get('/image/api-image/add-area', {
                params: params //
            });
            if (success)
                response.success(success);
            if (error)
                response.error(error);
        }
    };
    return serversite;
});
app.factory('canvasDrawer', function () {
    var registry = {};

    var canvasDrawer = {
        /**
         * draw the square on the canvas
         */
        draw: function (height, width) {
            this.showCanvas();
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
        drawArea: function (points, over, hide) {
            this.showCanvas();
            var canvas = document.getElementById("myimage_canvas");
            if (canvas.getContext) {
                console.log("add point");
                var ctx = canvas.getContext("2d");
                if (over == true) {
                    if (hide == true) {
                        ctx.fillStyle = 'rgba(197, 200, 201, 0)';    
                        ctx.strokeStyle = 'rgba(0,0,0, 0)';
                    } else {
                        ctx.fillStyle = 'rgba(197, 200, 201, 0.4)';    
                        ctx.strokeStyle = 'rgba(0, 0, 0, 0.8)';
                    }
                    
                } else {
                    ctx.fillStyle = 'rgba(0,172,239,0.2)';    
                    ctx.strokeStyle = 'rgba(0,172,239,0.8)';
                }
                ctx.lineWidth = 1;
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
                ctx.stroke
                if (over != true) {
                    // Draw points
                    ctx.fillStyle = 'rgba(0,139,191,0.8)';
                    for (var i = 0, l = points.length; i < l; i++) {
                        ctx.fillRect(points[i]['x'] - 2, points[i]['y'] - 2, 4, 4);
                    }
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
            this.hideCanvas();
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
        },
        /**
         * Hide canvas
         * @returns {undefined}
         */
        hideCanvas : function () {
            var canvas = document.getElementById("myimage_canvas");
            canvas.style.display = 'none';
        }, 
        /**
         * Show canvas
         * @returns {undefined}
         */
        showCanvas : function () {
            var canvas = document.getElementById("myimage_canvas");
            var image = document.getElementById('myimage');
            canvas.style.display = 'block';
            canvas.style.width = image.style.width;
            canvas.style.height = image.style.height;
        }
    };
    return canvasDrawer;
});
app.controller('Image2Ctrl', ['$scope', '$http', 'canvasDrawer', 'serversite',
    function ($scope, $http, canvasDrawer, serversite) {
        $scope.showBar = false;
        $scope.file = '';
        canvasDrawer.hideCanvas();
        $scope.areas = [{
                title: 'test',
                points: [
                    {x: 110, y: 71},
                    {x: 210, y: 71},
                    {x: 210, y: 151},
                    {x: 110, y: 151}
                ]
            }];
        $scope.active = false;
        $scope.getCoordinats = function (area) {
            var result = '';
            var points = area.points;
            for (var i = 0; i < points.length; i++) {
                if (i > 0) {
                    result += ',';
                }
                result += points[i].x + ',' + points[i].y;
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
        // button save area
        $scope.saveBtn = function () {
            $scope.active = !$scope.active;
            var tmp = {title: $scope.title};
            tmp['points'] = $scope.points;
            $scope.areas.push(tmp);
            serversite.saveArea($scope.id, $scope.points, $scope.title, function (response) {
                if (response.success) {
                    $scope.points = [];
                    canvasDrawer.clearCanvas();
                }
            });
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
        // upload Image
        $scope.uploadFile = function (file) {
            serversite.uploadImage(file, function (response) {
                $scope.file = response.file;
            });
        }
        //   canvasDrawer.clearCanvas();
        // load all information about image
        if (!angular.isUndefined($scope.id) && $scope.id != '' && $scope.id != null) {
            serversite.loadImage($scope.id, function (response) {
                $scope.image = response.image;
                $scope.areas = response.areas;
                $scope.showBar = true;
            });
        }
        // save image information
        $scope.saveImage = function () {
            serversite.saveImage($scope.id, $scope.name, $scope.file, function (response) {
                if (response.success) {
                    $scope.id = response.id;
                    $scope.name = response.name;
                    $scope.showBar = true;
                }
            })
        };
        // mouse leave area
        $scope.mouseleave = function (area) {
            canvasDrawer.clearCanvas();
        };
        // mouse over area
        $scope.mouseover = function (area) {
            canvasDrawer.drawArea(area.points, true);
        };
    }
]);