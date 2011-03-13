

function userGeo(autoUpdate, options) {
    
	this.options = options;

    // Check for geoLocation Support
    if (navigator.geolocation) {

		if (autoUpdate === true) {
			
			navigator.geolocation.watchPosition(function(pos) {
				
				this.renderPosition(pos);
				
			}, function() {
				
				this.renderError();
				
			});
			
		} else {
			
			navigator.geolocation.getCurrentPosition(function(pos) {
				
				this.renderPosition(pos);
				
			}, function() {
				
				this.renderError();
				
			});
			
		}
        
    } else {

        $('#' + this.options.notification_container).html('Your browser does not support geolocation.');

    }

	this.renderPosition = function(position) {

	    //var urlJSON = 'http://ws.geonames.org/findNearbyPlaceNameJSON?lat='+position.coords.latitude+'&lon='+position.coords.longitude;

		$('#' + this.options.input).val(position.coords.latitude + ',' + position.coords.longitude);
		$('#' + this.options.notification_container).html('Ok, now you can find stuff near you.');

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
	    // $.getJSON(urlJSON, function(json) {
	    // 
	    //     /* Parse JSON objects */
	    //     $.each(json.geonames,function(i,item) {
	    //         // get the name - which is the suburb - and update the page
	    //         // alert('name: ' + item.name);
	    //         $('#' + that.options.notification_container).html('You live in: ' + item.name);
	    //     });
	    // 
	    // });
	};

	this.renderError = function() {
	    $('#' + this.options.notification_container).html('Sorry, we could not get your location.');
	};

};