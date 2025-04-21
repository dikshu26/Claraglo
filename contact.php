<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli('localhost', 'root', '', 'website');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    if (!empty($name) && !empty($email) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $name, $email, $message);
            if ($stmt->execute()) {
                echo "<script>alert('Your message has been sent successfully!');</script>";
            } else {
                echo "<script>alert('Something went wrong. Please try again later.');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Database error.');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact | ClaroGlo</title>
      <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  body {
    font-family: 'Montserrat', sans-serif;
    background-color: #f8f6f0;
    color: #3e3e3e;
  }
  .navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 50px;
    background-color: white;
    border-bottom: 1px solid #ddd;
}

/* LOGO */
.logo {
    font-size: 20px;
    font-weight: bold;
    letter-spacing: 3px;
    flex: 1; /* Makes sure it takes up space on the left */
}

/* NAVIGATION LINKS - CENTERED */
.nav-links {
    list-style: none;
    display: flex;
    gap: 30px;
    justify-content: center;
    flex: 2; /* Makes sure it's centered */
}

.nav-links li {
    display: inline;
}

.nav-links a {
    text-decoration: none;
    color: black;
    font-size: 14px;
    font-weight: 500;
    letter-spacing: 1px;
}

/* SEARCH CONTAINER - MOVED TO RIGHT */

/* NAV RIGHT (Cart, Heart, Login, Logout) */
.nav-right {
    display: flex;
    gap: 20px;
    align-items: center;
}
  
  .contact-wrapper {
    display: flex;
    flex-wrap: wrap;
    padding: 60px;
    background: #fffdf8;
    max-width: 1200px;
    margin: 60px auto;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  }
  
  .contact-left, .contact-right {
    flex: 1;
    min-width: 300px;
    padding: 20px 30px;
  }
  
  .contact-left h1 {
    font-size: 2.5em;
    margin-bottom: 20px;
  }
  
  .contact-left span {
    color: #29923d;
  }
  
  .contact-left p {
    font-size: 1.1em;
    margin-bottom: 30px;
  }
  
  .contact-info li {
    margin-bottom: 15px;
    font-size: 1em;
    line-height: 1.5;
  }
  
  .contact-image {
    margin-top: 20px;
    text-align: center;
  }
  
  .contact-image img {
    max-width: 75%;
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  }
  

  form input,
  form textarea {
    width: 100%;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid #dcdacb;
    border-radius: 10px;
    font-size: 1em;
    background-color: #fafaf7;
  }
  
  form button {
    background-color: #29923d;
    color: white;
    border: none;
    padding: 14px 30px;
    border-radius: 12px;
    font-size: 1em;
    cursor: pointer;
    transition: background 0.3s ease;
  }
  
  form button:hover {
    background-color: #6c8d40;
  }

  .footer {
    background: #111827; 
    color: white; 
    padding: 48px 0; 
    margin-top: auto; 
}

.footer-grid {
    display: grid; 
    grid-template-columns: repeat(4, 1fr); 
    gap: 32px; 
    margin-bottom: 32px; 
}

.footer-section h3 {
    font-size: 1.25rem; 
    font-weight: bold; 
    margin-bottom: 16px; 
}

.footer-section h4 {
    font-weight: 600; 
    margin-bottom: 16px; 
}

.footer-section p {
    color: #9ca3af; 
}

.footer-section ul {
    list-style: none; 
}

.footer-section ul li {
    margin-bottom: 8px; 
}

.footer-section ul a {
    color: #9ca3af; 
    text-decoration: none; 
    transition: color 0.3s; 
}

.footer-section ul a:hover {
    color: white; 
}

.social-links {
    display: flex; 
    gap: 16px; 
}

.social-link {
    color: #9ca3af; 
    transition: color 0.3s; 
}

.social-link:hover {
    color: white; 
}

.footer-bottom {
    border-top: 1px solid #374151; 
    margin-top: 32px; 
    padding-top: 32px; 
    text-align: center; 
    color: #9ca3af; 
}

@media (max-width: 768px) {
    .nav-toggle-label {
        display: block; 
        padding: 20px 0; 
    }

    .nav-toggle-label span,
    .nav-toggle-label span::before,
    .nav-toggle-label span::after {
        display: block; 
        background: #333; 
        height: 2px; 
        width: 24px; 
        position: relative; 
        transition: transform 0.3s; 
    }

    .nav-toggle-label span::before,
    .nav-toggle-label span::after {
        content: ''; 
        position: absolute; 
    }

    .nav-toggle-label span::before {
        bottom: 8px; 
    }

    .nav-toggle-label span::after {
        top: 8px; 
    }

    .nav-links {
        position: absolute; 
        top: 100%; 
        left: 0; 
        right: 0; 
        background: white; 
        flex-direction: column; 
        padding: 0; 
        margin: 0; 
        gap: 0; 
        display: none; 
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
    }

    .nav-links li {
        padding: 16px 20px; 
        border-top: 1px solid #f3f4f6; 
    }

    .nav-toggle:checked ~ .nav-links {
        display: flex; 
    }

    .hero-content {
        flex-direction: column; 
        text-align: center; 
    }

    .hero-text h1 {
        font-size: 2.5rem; 
    }

    .feature-grid {
        grid-template-columns: 1fr; 
    }

    .footer-grid {
        grid-template-columns: 1fr; 
        gap: 40px; 
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .feature-grid {
        grid-template-columns: repeat(2, 1fr); 
    }

    .footer-grid {
        grid-template-columns: repeat(2, 1fr); 
    }
}
  
</style>

</head>

<body>
<nav class="navbar">
        <div class="logo">
           
            <span>C L A R O G L O</span>
        </div>
        <ul class="nav-links">
            <li><a href="home.html">HOME</a></li>
            
            <li><a href="face.html">FACE</a></li>
            <li><a href="body.html">BODY</a></li>
            <li><a href="hair.html">HAIR</a></li>
            
            <li><a href="contact.php">CONTACT</a></li>
       </ul>
                 
            <a href="login.php">LOGIN</a>
            
        </div>
     </nav>
     

  <section class="contact-wrapper">
    <div class="contact-left">
      <h1>Contact <span>ClaroGlo</span></h1>
      <p>Have questions about our organic skincare products? We’d love to hear from you!</p>
      <ul class="contact-info">
        <li><strong>Email:</strong> support@claroglo.com</li>
        <li><strong>Phone:</strong> +1 (555) 123-4567</li>
        <li><strong>Address:</strong> 123 Green Street, Organic City, Earth 10101</li>
      </ul>
      <div class="contact-image">
    <img src="images/pic18.jpg" alt="Organic Skincare" />
  </div>
</div>
    </div>

    <div class="contact-right">
      <form action="contact.php" method="POST">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <textarea name="message" placeholder="Your Message" rows="6" required></textarea>
        <button type="submit">Send Message</button>
      </form>
    </div>
  </section>

  <footer class="footer">
    <div class="container">
       <div class="footer-grid">
       <div class="footer-section">
       <h3>Clara glo</h3>
       <p>Skin care rooted in nature.</p>
     </div>
     <div class="footer-section">
       <h4>Quick Links</h4>
       <ul>
         <li><a href="home.html">Home</a></li>
         <li><a href="home.html#blog">Blogs</a></li>
         <li><a href="about1.html">About</a></li>
         <li><a href="contact.php">Contact</a></li>
       </ul>
     </div>
       <div class="footer-section">
       <h4>Resources</h4>
       <ul>
         <li><a href="privacy.html">Privacy policy</a></li>
         <li><a href="terms.html">Terms & Conditions</a></li>
         <li><a href="returns.html">Delivery and canclation</a></li>
       </ul>
     </div>
       <div class="footer-section">
       <h4>Connect With Us</h4>
       <div class="social-links">
         <ul>
             <li>
             <a href="https://www.linkedin.com/in/clara-glo-b0318135b/" class="social-link" target="_blank" rel="noopener noreferrer">
                    <!-- LinkedIn SVG Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.762 2.239 5 5 5h14c2.762 0 5-2.238 
                               5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-10h3v10zm-1.5-11.269c-.966 
                               0-1.75-.784-1.75-1.75s.784-1.75 1.75-1.75 
                               1.75.784 1.75 1.75-.784 1.75-1.75 
                               1.75zm13.5 11.269h-3v-5.604c0-1.337-.026-3.063-1.867-3.063-1.868 
                               0-2.154 1.459-2.154 2.968v5.699h-3v-10h2.881v1.367h.041c.401-.762 
                               1.379-1.566 2.838-1.566 3.033 0 3.592 1.995 
                               3.592 4.59v5.609z"/>
                    </svg>
                  </a>
             </li>
             <li>
             <a href="https://www.instagram.com/claraglo_skincare/" class="social-link" target="_blank" rel="noopener noreferrer">
                    <!-- Clean Outline Instagram SVG Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M7 2C4.2 2 2 4.2 2 7v10c0 2.8 2.2 5 5 5h10c2.8 0 5-2.2 5-5V7c0-2.8-2.2-5-5-5H7zm10 2c1.7 0 3 1.3 3 3v10c0 1.7-1.3 3-3 3H7c-1.7 0-3-1.3-3-3V7c0-1.7 1.3-3 3-3h10zM12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6zm4.8-2a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                    </svg>
                  </a>
             </li>
         </ul>
     </div>
</div>        
   </div>

   <div class="footer-bottom">
     <p>2025&copy;  Clara glo powered by natural beauty</p>
   </div>
 </div>
</footer> 

</body>
</html>