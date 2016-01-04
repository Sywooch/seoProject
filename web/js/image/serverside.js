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