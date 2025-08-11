<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration with Image Password</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #a8edea, #fed6e3);
        }
        .registration-form {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }
        .registration-form h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .passphrase-feedback {
            font-size: 14px;
            margin-bottom: 10px;
            color: red;
        }
        .passphrase-feedback.valid {
            color: green;
        }
        .carousel-container {
            position: relative;
            margin: 20px auto;
            width: 100%;
            overflow: hidden;
            background:rgb(251, 244, 244);
            border-radius: 10px;
            padding: 10px;
            box-shadow: inset 0px 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }
        .carousel-wrapper {
            overflow: hidden;
            width: 100%;
        }
        .carousel-slide {
            display: flex;
            transition: transform 0.5s ease-in-out;
            width: max-content;
        }
        .carousel-slide img {
            width: 100px;
            height: 100px;
            margin: 10px;
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 8px;
            transition: transform 0.3s ease, border 0.3s ease;
        }
        .carousel-slide img:hover {
            transform: scale(1.1);
        }
        .carousel-slide img.selected {
            border-color: #4CAF50;
        }
        .prev, .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(76, 175, 80, 0.8);
            color: white;
            padding: 10px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            z-index: 2;
            user-select: none;
        }
        .prev { left: 5px; }
        .next { right: 5px; }
        .upload-container {
            margin-top: 20px;
            text-align: center;
        }
        .upload-container button {
            padding: 10px;
            background-color: #008CBA;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .submit-btn {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }

        .email-feedback{
            font-size: 14px;
            margin-bottom: 10px;
            color: red;
        }

        .toggle-password {
            position: absolute;
            right: 5%;
            top: 77%;
            transform: translateY(-50%);
            cursor: pointer;
            user-select: none;
    }

    .password-wrapper {
    position: relative;
    width: 100%;
}

.password-wrapper input {
    width: 100%;
    padding: 10px 40px 10px 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

.toggle-icon1 {
    position: absolute;
    right: 8px;
    top: 53%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 18px;
    user-select: none;
}

.toggle-icon2 {
    position: absolute;
    right: 8px;
    top: 67%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 18px;
    user-select: none;
}

    </style>
</head>
<body>

<div class="registration-form">
    <h2>User Registration</h2>

    <form action="register_user.php" method="POST" enctype="multipart/form-data" onsubmit="return submitForm()">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required oninput="validateEmail()">
        <div id="emailFeedback" class="email-feedback"></div>
        
        <h3>Select at least 3 images as part of your password:</h3>

        <div class="carousel-container">
            <button type="button" class="prev" onclick="moveCarousel(-1)">&#10094;</button>
            <div class="carousel-wrapper">
                <div class="carousel-slide" id="carouselSlide">
                    <img src="images\pic1.webp" alt="Image 1" onclick="selectImage(this)">
                    <img src="images\pic2.jpg" alt="Image 2" onclick="selectImage(this)">
                    <img src="images/pic3.webp" alt="Image 3" onclick="selectImage(this)">
                    <img src="images\pic4.jpg" alt="Image 4" onclick="selectImage(this)">
                    <img src="images\pic5.jpg" alt="Image 5" onclick="selectImage(this)">
                </div>
            </div>
            <button type="button" class="next" onclick="moveCarousel(1)">&#10095;</button>
        </div>

        <div class="upload-container">
            <button type="button" onclick="document.getElementById('fileInput').click()">Upload Images</button>
            <input type="file" id="fileInput" name="uploaded_images[]" multiple accept="image/*" multiple style="display:none" onchange="handleFileSelection(event)">
        </div>

        <label for="passphrase">Passphrase:</label>
        <div class="password-wrapper">
            <input type="password" id="passphrase" name="passphrase" required oninput="validatePassphrase()">
            <span class="toggle-icon1" onclick="togglePassphraseVisibility()">👁️</span>
        </div>
        <div id="passphraseFeedback" class="passphrase-feedback"></div>


        <div class="password-wrapper">
        <label for="confirm_passphrase">Confirm Passphrase:</label>
        <input type="password" id="confirm_passphrase" name="confirm_passphrase" required oninput="validateConfirmPassphrase()">
        <span class="toggle-icon2" onclick="toggleConfirmPassphraseVisibility()">👁️</span>
        <div id="confirmPassphraseFeedback" class="passphrase-feedback"></div> 
        </div>

        


        <input type="hidden" name="image_password" id="imagePassword">
        <button type="submit" class="submit-btn">Register</button>
    </form>
</div>

<!-- chatbot -->
<div style="position: fixed; bottom: 10px; right: 10px; z-index: 1000;">
<iframe
    allow="microphone;"
    width="350"
    height="430"
    src="https://console.dialogflow.com/api-client/demo/embedded/27788420-1890-4eab-824c-f75138f20d65">
</iframe>
</div>

<script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
<df-messenger
  intent="WELCOME"
  chat-title="MyBot"
  agent-id="YOUR_AGENT_ID"
  language-code="en"
></df-messenger>



<script>
    let selectedImages = [];
    let currentIndex = 0;

    function validatePassphrase() {
        const passphrase = document.getElementById("passphrase").value;
        const feedback = document.getElementById("passphraseFeedback");

        if (passphrase.length < 12) {
            feedback.textContent = "Passphrase must be at least 12 characters.";
            feedback.classList.remove("valid");
        } else {
            feedback.textContent = "Strong passphrase!";
            feedback.classList.add("valid");
        }
    }

    function validateEmail() {
        const email = document.getElementById("email").value;
        const feedback = document.getElementById("emailFeedback");
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if (!emailPattern.test(email)) {
            feedback.textContent = "Invalid email format.";
            feedback.classList.remove("valid");
            return false;
        } else {
            feedback.textContent = "Valid email!";
            feedback.classList.add("valid");
            return true;
        }
    }


    function selectImage(imgElement) {
        const src = imgElement.src;
        if (selectedImages.includes(src)) {
            selectedImages = selectedImages.filter(img => img !== src);
            imgElement.classList.remove("selected");
        } else {
            selectedImages.push(src);
            imgElement.classList.add("selected");
        }
    }

    function moveCarousel(direction) {
        const carousel = document.getElementById("carouselSlide");
        const images = document.querySelectorAll(".carousel-slide img");
        const totalImages = images.length;
        const visibleImages = 3;

        if (direction === 1 && currentIndex < totalImages - visibleImages) {
            currentIndex++;
        } else if (direction === -1 && currentIndex > 0) {
            currentIndex--;
        }

        const offset = -(currentIndex * 120);
        carousel.style.transform = `translateX(${offset}px)`;
    }

    function handleFileSelection(event) {
        const files = event.target.files;
        const carousel = document.getElementById("carouselSlide");

        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const newImg = document.createElement("img");
                newImg.src = e.target.result;
                newImg.onclick = () => selectImage(newImg);
                carousel.appendChild(newImg);
            };
            reader.readAsDataURL(file);
        });
    }

    function submitForm() {
    var passphrase = document.getElementById('passphrase').value;
    var confirmPassphrase = document.getElementById('confirm_passphrase').value;

    // Check if at least 3 images are selected
    if (selectedImages.length < 3) {
        alert("Please select at least 3 images.");
        return false;
    }

    // Check if passphrase and confirm passphrase match
    if (passphrase !== confirmPassphrase) {
        alert("Passphrases do not match! Please re-enter.");
        return false;
    }

    // Set imagePassword input value before form submission
    document.getElementById("imagePassword").value = selectedImages.join(",");

    return true; // Allow form submission
}


function togglePassphraseVisibility() {
    const input = document.getElementById('passphrase');
    input.type = input.type === 'password' ? 'text' : 'password';
}

function toggleConfirmPassphraseVisibility() {
    const input = document.getElementById('confirm_passphrase');
    input.type = input.type === 'password' ? 'text' : 'password';
}

</script>

</body>
</html>
