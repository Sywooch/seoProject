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