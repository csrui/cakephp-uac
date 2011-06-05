

function userGeo(autoUpdate, options) {
    
	this.options = options;
	
	var that = this;

    // Check for geoLocation Support
    if (navigator.geolocation) {

		if (autoUpdate === true) {
			
			navigator.geolocation.watchPosition(function(pos) {
				
				that.renderPosition(pos);
				
			}, function() {
				
				that.renderError();
				
			});
			
		} else {
			
			navigator.geolocation.getCurrentPosition(function(pos) {
				
				$('#' + that.options.notification_container).html('Searching for your location...');	
				that.renderPosition(pos);
				
			}, function() {
				
				that.renderError();
				
			});
			
		}
        
    } else {

        $('#' + this.options.notification_container).html('Your browser does not support geolocation.');

    }

	this.renderPosition = function(position) {

	    var urlJSON = 'http://api.geonames.org/findNearbyPlaceNameJSON?lat='+position.coords.latitude+'&lng='+position.coords.longitude+'&username=planamatch';

		// $user_location = json_decode(file_get_contents("http://api.geonames.org/findNearbyPlaceNameJSON?lat={$point['lat']}&lng={$point['lng']}&username=planamatch"));				
		// pr($user_location);


		$('#' + that.options.input).val(position.coords.latitude + ',' + position.coords.longitude);

	    // $('#' + this.options.notification_container).html('<div><p>'
	    //                 + 'Latitude: ' + position.coords.latitude + '<br />'
	    //                 + 'Longitude: ' + position.coords.longitude + '<br />'
	    //                 + 'Accuracy: ' + position.coords.accuracy + '<br />'
	    //                 + 'Altitude: ' + position.coords.altitude + '<br />'
	    //                 + 'Altitude accuracy: ' + position.coords.altitudeAccuracy + '<br />'
	    //                 + 'Heading: ' + position.coords.heading + '<br />'
	    //                 + 'Speed: ' + position.coords.speed + '<br />'
	    //                 + '</p></div>');

	    // // now get the XML reverse geo data
	    $.getJSON(urlJSON, function(json) {
	    
	        /* Parse JSON objects */
	        $.each(json.geonames,function(i,item) {

	            $('#' + that.options.location_name_input).val(item.name);
				$('#' + that.options.notification_container).html('Ok, now you can find stuff near you.');	
				
	        });
	    
	    });
	
		
	
	};

	this.renderError = function() {
	    $('#' + that.options.notification_container).html('Sorry, we could not get your location. Please try again later.');
	};

};