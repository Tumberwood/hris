<script>
    // BEGIN geolocation html5
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Geolocation is not supported by this browser!");
        }
    }

    function showPosition(position) {
        $('#lat').val(position.coords.latitude);
        $('#lng').val(position.coords.longitude);
    }

    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                geo_error = "User denied the request for Geolocation."
                break;
            case error.POSITION_UNAVAILABLE:
                geo_error = "Location information is unavailable."
                break;
            case error.TIMEOUT:
                geo_error = "The request to get user location timed out."
                break;
            case error.UNKNOWN_ERROR:
                geo_error = "An unknown error occurred."
                break;
        }
        edtproyek_t.error(geo_error);
    }
    // END geolocation html5
</script>