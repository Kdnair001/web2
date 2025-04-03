


<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            transition: background-image 1s ease-in-out;
        }

        .content {
            position: relative;
            z-index: 2;
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }

        /* Buttons */
        .slideshow-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 15px;
            font-size: 24px;
            cursor: pointer;
            z-index: 10;
            transition: 0.3s;
        }

        .slideshow-btn:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        .prev { left: 20px; }
        .next { right: 20px; }

        /* Slide Indicators */
        .indicators {
            position: absolute;
            bottom: 50px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }

        .indicator {
            width: 12px;
            height: 12px;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .indicator.active {
            background-color: white;
        }

        /* Slide Descriptions */
        .description {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.6);
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 18px;
            opacity: 0.8;
        }
    </style>
</head>
<body>

    <!-- Slideshow Content -->
    <!--<div class="content">
        <h2>Welcome,</h2>
        <p>Main Content Area</p>
    </div>-->

    <!-- Slideshow Navigation Buttons -->
    <button class="slideshow-btn prev" onclick="changeSlide(-1)">&#10094;</button>
    <button class="slideshow-btn next" onclick="changeSlide(1)">&#10095;</button>

    <!-- Slide Description -->
    <div class="description" id="slide-description"></div>

    <!-- Slide Indicators -->
    <div class="indicators"></div>

    <script>
        const slides = [
            { image:  "cesa.jpeg",description: "CESA-Computer Engineering Students Association Inaugration Event" },
            { image: "Gammick.jpg", description: "Gammick-Game Development Workshop" },
            { image: "softwarelab-1.jpeg", description: "Sofware Lab-1" },
            { image: "softwarelab-2.jpeg", description: "Sofware Lab-2" },
            { image: "hardwarelab-1.jpeg", description: "Hardware Lab" },
            { image: "digital-lab.jpeg", description: "Digital Lab" }
        ];
        
        let currentIndex = 0;
        let slideInterval;
        const indicatorsContainer = document.querySelector(".indicators");
        const descriptionBox = document.getElementById("slide-description");

        function changeBackground() {
            document.body.style.backgroundImage = `url('${slides[currentIndex].image}')`;
            document.body.style.backgroundSize = "cover";
            document.body.style.backgroundPosition = "center";
            document.body.style.backgroundAttachment = "fixed";
            descriptionBox.innerText = slides[currentIndex].description;
            updateIndicators();
        }

        function changeSlide(direction) {
            currentIndex += direction;
            if (currentIndex >= slides.length) currentIndex = 0;
            if (currentIndex < 0) currentIndex = slides.length - 1;
            changeBackground();
            resetAutoSlide();
        }

        function autoSlide() {
            changeSlide(1);
            slideInterval = setTimeout(autoSlide, 3000);
        }

        function resetAutoSlide() {
            clearTimeout(slideInterval);
            slideInterval = setTimeout(autoSlide, 3000);
        }

        function createIndicators() {
            slides.forEach((_, index) => {
                const dot = document.createElement("div");
                dot.classList.add("indicator");
                dot.addEventListener("click", () => {
                    currentIndex = index;
                    changeBackground();
                    resetAutoSlide();
                });
                indicatorsContainer.appendChild(dot);
            });
            updateIndicators();
        }

        function updateIndicators() {
            document.querySelectorAll(".indicator").forEach((dot, index) => {
                dot.classList.toggle("active", index === currentIndex);
            });
        }

        createIndicators();
        changeBackground();
        slideInterval = setTimeout(autoSlide, 3000);
    </script>

</body>
</html>
