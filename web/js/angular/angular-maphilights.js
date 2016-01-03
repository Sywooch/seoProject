/*
* maphilights AngularJS Directive v1.0
*
* Original Copyright (c) 2013 David Lynch
* https://github.com/kemayo/maphilight
* http://davidlynch.org/blog
* Licensed under the MIT license
*
* angular-maphilights.js (by Philip Saa)
* https://github.com/cowglow/
* @cowglow
*/

angular.module('maphilights', [])
	.directive('maphilight', function(){
		return {
			restrict: 'CA',
			link: function(scope, element, attr){
				var has_VML,
					has_canvas,
					create_canvas_for,
					add_shape_to,
					clear_canvas,
					shape_from_area,
					canvas_style,
					hex_to_decimal,
					css3color,
					is_image_loaded,
					options_from_area;
					
				$(element).bind('mouseover', function(){
					console.log($(element).title);
				});
				
				$(element).bind('mouseout', function(){
				});
			}
		}
	});
