

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
				
				that.renderPosition(pos);
				
			}, function() {
				
				that.renderError();
				
			});
			
		}
        
    } else {

        $('#' + this.options.notification_container).html('Your browser does not support geolocation.');

    }

	this.renderPosition = function(position) {

	    var urlJSON = 'http://ws.geonames.org/findNearbyPlaceNameJSON?lat='+position.coords.latitude+'&lon='+position.coords.longitude;

		$('#' + that.options.input).val(position.coords.latitude + ',' + position.coords.longitude);
		$('#' + that.options.notification_container).html('Ok, now you can find stuff near you.');

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
	            // get the name - which is the suburb - and update the page
	            // alert('name: ' + item.name);
	            $('#' + that.options.location_name_input).val(item.name);
	        });
	    
	    });
	
	};

	this.renderError = function() {
	    $('#' + that.options.notification_container).html('Sorry, we could not get your location. Please try again later.');
	};

};