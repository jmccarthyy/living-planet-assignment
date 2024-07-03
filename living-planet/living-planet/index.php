<!DOCTYPE html>
<!-- This is the code for the 'Home' page, or the index, which is the assignment's home page and the page that opens on initial load of the website: -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Living Planet</title>
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
</head>
<body>
    <!-- Inclusion of the navbar component: -->
    <?php include './components/navbar.php'; ?>
    
    <section class="hero hero-home">
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-title">Living Planet</h1>
                <h2 class="hero-subtitle">Improving Air Quality</h2>
                <p class="hero-text">
                    Living Planet aims to improve the air quality in cities around the world. We provide air quality data and maps to help you view the air quality in the United Kingdom.
                </p>
                <div class="hero-buttons">
                    <a href="/living-planet/about.php" class="hero-button-solid">Learn More</a>
                </div>
            </div>
        </div>
        <picture class="hero-background">
            <img loading="lazy" decoding="async" src="./assets/living-hero.jpg" alt="restaurant" width="1920" height="1200">
        </picture>
    </section>

    <!-- Inclusion of interactable Google Maps map: -->
    <section class="content">
        <div class="content-weather">
            <h1>Weather forecast at Living Planet</h1>
            <p>Please select a location on the map to view the weather forecast. This will be shown below the map.</p>
            <div id="default-weather" class="content-weather-forecast"></div>
        </div>
    </section>
    <div id="map" style="width: 95vw; height: 80vh; margin: auto;"></div>

    <!-- The forecast weather for the currently selected location: -->
    <section class="content">
        <div class="content-weather">
            <h1>Weather forecast at Selected Location</h1>
            <div id="map-weather" class="content-weather-forecast">Data will show here when selecting location on the map</div>
        </div>
    </section>
    <section class="content">
        <div class="left-container">
            <img src="./assets/windmill.jpg" alt="windmill">
        </div>

        <div class="right-container">
            <h2>About Us</h2>
            <p>
                At living planet, our goal is to improve the air quality in cities around the world. We provide air quality data and maps to help you view the air quality in the United Kingdom. Our data is sourced from reliable APIs and we aim to provide you with the most accurate information.
            </p>
            <p>
                We believe that by providing accurate air quality data, we can help people make informed decisions about their health and the environment. We are committed to improving air quality and reducing pollution in cities around the world.
            </p>
        </div>
    </section>

    <!-- Inclusion of the footer component: -->
    <?php include './components/footer.php'; ?>

    <!-- javascript code for the Google Maps API: -->
    <script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
        ({key: "AIzaSyDYONZS5Wz68iBeRrFioXTe4ftlQH0nwOY", v: "weekly"});</script>

    <script src="./js/jquery.js"></script>
</body>
</html>