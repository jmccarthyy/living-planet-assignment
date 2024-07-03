<!DOCTYPE html>
<!-- This is the code for the 'Map' page, which is where the user can enter a city's name to see where it is on the map, directions to Living Planet HQ from it, and its current weather: -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map | Living Planet</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/map.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
</head>
<body>
    <!-- Inclusion of the navbar component: -->
    <?php include './components/navbar.php'; ?>
    
    <section class="hero hero-map">
        <div class="hero-container-center">
            <div class="hero-content-center">
                <h1 class="hero-title-center">Air Quality and Weather</h1>
                <p class="hero-text-center">
                    View the air quality and weather in cities around the world, as well as additional information about the city you have chosen.
                </p>
            </div>
        </div>
        <picture class="hero-background">
            <img loading="lazy" decoding="async" src="./assets/map-hero.jpg" alt="restaurant" width="1920" height="1200">
        </picture>
    </section>

    <!-- Search bar for searching cities: -->
    <section class="map-content">
        <div class="search-container">
            <h1>Search for a city</h1>
            <form id="city-search-form">
                <input type="text" id="city-input" name="city" placeholder="Enter city name">
                <button type="submit">Search</button>
            </form>
        </div>

        <div id="city-info">
            <h1>City Info</h1>
            <div class="city-info-text">
                <p>
                    Your city information will be displayed here. You'll be able to see temperature and key air quality statistics. Please note, this will be easier to view
                    when hovering over a marker before getting directions.
                </p>
                <br>
                <p>
                    You'll also be able to see a map of the city, and the distance from you using Google Distance Matrix when clicking the get directions button.
                </p>
            </div>
            <div class="city-info-content" style="display:none;"></div>
        </div>

        <!-- Inclusion of the Google Maps map that shows selected city's location and directions to Living Planet HQ: -->
        <div id="map-info">
            <div class="google-container" style="display: none;">
                <div class="google-map" id="map"></div>
                <div class="google-directions" id="directions-panel">
                    <button id="directions-button" style="display: none;">Get Directions</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Inclusion of the footer component: -->
    <?php include './components/footer.php'; ?>

    <!-- javascript code for the Google Maps API: -->
    <script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
        ({key: "AIzaSyDYONZS5Wz68iBeRrFioXTe4ftlQH0nwOY", v: "weekly"});</script>

    <script src="./js/map.js"></script>

</body>
</html>