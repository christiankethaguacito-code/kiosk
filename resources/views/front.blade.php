<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Campus Kiosk Front</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            background: black;
            overflow: hidden;
        }
        #slideshow {
            position: fixed;
            top: 0; left: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #slideshow img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            position: absolute;
        }
        #slideshow img.active {
            opacity: 1;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div id="slideshow">
        <img src="{{ asset('images/slideshow/campus1.jpg') }}" class="active">
        <img src="{{ asset('images/slideshow/campus2.jpg') }}">
        <img src="{{ asset('images/slideshow/campus3.jpg') }}">
    </div>

    <script>
    const idleTime = 30000;
    let idleTimer;

    function resetIdleTimer() {
        clearTimeout(idleTimer);
        idleTimer = setTimeout(() => {
            document.getElementById('slideshow').style.display = 'flex';
        }, idleTime);
    }

    function hideSlideshow() {
        document.getElementById('slideshow').style.display = 'none';
    }

    function goToWelcome() {
        window.location.href = "{{ url('welcome') }}";
    }

    ['mousemove', 'keydown', 'touchstart', 'click'].forEach(evt =>
        document.addEventListener(evt, () => {
            hideSlideshow();
            goToWelcome();
        })
    );

    resetIdleTimer();
</script>

</body>
</html>
