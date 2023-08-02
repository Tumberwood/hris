<script>
    // start get location html5
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("Geolocation is not supported by this browser!");
        }
    }

    function showPosition(position) {
        edthtsprtd.field('htsprtd.lat').val(position.coords.latitude);
        edthtsprtd.field('htsprtd.lng').val(position.coords.longitude);
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
        edthtsprtd.error(geo_error);
    }
    // end get location html5
</script>