app.controller('ImageCtrl', ['$scope', '$http', 'canvasDrawer', 'serversite', 'imageModel',
    function ($scope, $http, canvasDrawer, serversite, imageModel) {
        $scope.showBar = false;
        if (!angular.isUndefined(imageModel.id)) {
            $scope.id = imageModel.id;
            $scope.name = imageModel.name;
            $scope.file = imageModel.file;
        }
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
                $scope.file = response.image;
                $scope.areas = [];
                for(var i=0;i<Object.keys(response.areas).length ;i++)  {
                    $scope.areas.push({title: response.areas[i].title, points: response.areas[i].points})
                }
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