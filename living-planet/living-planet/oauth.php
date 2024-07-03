<?php
session_start();

// This is the code for the 'Auth' page, which upon the user logging in via Google OAuth, presents the OAuth inclusion process and description of why it was used:

if (!isset($_SESSION['user'])) {
    // Google OAuth credentials:
    $client_id = getenv('CLIENT_ID');
    $client_secret = getenv('CLIENT_SECRET');
    $redirect_uri = 'https://w20043974.azurewebsites.net/living-planet/oauth.php';

    // Declaration of Authenticaton Token:
    if (isset($_GET['code'])) {
        $code = $_GET['code'];

        $token_url = 'https://oauth2.googleapis.com/token';
        $token_params = [
            'code' => $code,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'redirect_uri' => $redirect_uri,
            'grant_type' => 'authorization_code'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $token_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $token_response = curl_exec($ch);
        curl_close($ch);

        $token_data = json_decode($token_response, true);

        // If authentication token obtained successfully, fetch user information:
        if (isset($token_data['access_token'])) {
            $access_token = $token_data['access_token'];
            $user_info_url = 'https://www.googleapis.com/oauth2/v1/userinfo';
            $user_info_params = [
                'access_token' => $access_token
            ];
            $user_info_response = file_get_contents($user_info_url . '?' . http_build_query($user_info_params));
            $user_info = json_decode($user_info_response, true);

            // Save user information in session:
            $_SESSION['user'] = $user_info;

            // Redirect to a different page or display authenticated content here:
            header('Location: oauth.php');
            exit();
        } else {
            // Handle error:
            echo 'Failed to obtain access token.';
        }
    // If the user is not logged in:
    } else {
        // Redirect to Google OAuth login page:
        $auth_url = 'https://accounts.google.com/o/oauth2/auth?' . http_build_query([
            'client_id' => $client_id,
            'redirect_uri' => $redirect_uri,
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
            'access_type' => 'offline',
            'prompt' => 'consent'
        ]);
        header('Location: ' . $auth_url);
        exit();
    }
}

?>
<!-- Frontend code for the 'Auth' page: -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auth | Living Planet</title>
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
     <!-- Inclusion of the navbar component: -->
    <?php include './components/navbar.php'; ?>
    
    <section class="hero hero-auth">
        <div class="hero-container-center">
            <div class="hero-content-center">
                <h1 class="hero-title-center">Google OAuth</h1>
                <p class="hero-text-center">
                    You are signed in using Google OAuth. Welcome!
                </p>
            </div>
        </div>
        <picture class="hero-background">
            <img loading="lazy" decoding="async" src="./assets/auth-hero.jpg" alt="restaurant" width="1920" height="1200">
        </picture>
    </section>

     <!-- Simple description of the stages of the OAuth inclusion process: -->
    <section class="content" style="flex-direction: column; gap: 20px; justify-content: flex-start">
        <h1>What is OAuth?</h1>
        <p>
            OAuth is an open standard authentication protocol for access delegation, commonly used as a way for Internet users to grant websites or applications access to their information on other websites but without giving them the passwords.
        </p>
        <p>
            This application uses Google's OAuth, which grants users access through the use of authentication tokens. This is a simple process, the steps of which are:
            <ol>
            <li> OAuth functionality is enabled within Living Planet's solution through a Google Developer account</li>
            <li> In doing so, a client authentication token is provided - this provides a 'lock' to implemented sections that the user's authentication token can be used on </li>
            <li> In use, the user clicks sign in (Or the 'Auth' tab in the navigation bar, in this application's case) </li>
            <li> User is presented with a Google Sign in page, where OAuth will check the user's details</li>
            <li> User enters their google credentials and clicks sign in</li>
            <li> These details are sent to the OAuth service, where OAuth authenticates the user's details </li>
            <li> Upon successful authentication from OAuth, the user's sign in details are translated to an authentication token</li>
            <li>  The authention token assigned to the user is recognised by the Auth page, 'unlocking' the user's account</li>
            <li> The user is granted access to the Auth section (this section) of the website as their account is now 'unlocked'</li>
        </ol>
        Google handles the authentication process which makes the login process more simple for both developer implementation and user experience. 
        This is because most users already have Google accounts, so logging in with OAuth feels safer, as well as being less time consuming because they don't have to make new login details.
        Furthermore, when OAuth functionality is enabled in a website through Google Develoepr accounts, vendor code is usually provided which provides a template for OAuth functioanlity implementation
        </p>
        
    </section>

     <!-- Inclusion of the footer component: -->
    <?php include './components/footer.php'; ?>

    <script src="./js/jquery.js"></script>
</body>
</html>