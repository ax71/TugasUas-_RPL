<?php
header("Content-type: text/css; charset: UTF-8");
?>

:root {
--navy-blue: #1B1B3A;
--light-navy: #2D2D4A;
--soft-white: #F5F5F7;
--pure-white: #FFFFFF;
--grey-blue: #8E8EA0;
}

* {
margin: 0;
padding: 0;
box-sizing: border-box;
}

body {
display: flex;
flex-direction: column;
font-family: 'Poppins', Arial, sans-serif;
line-height: 1.6;
min-height: 100vh;
background-color: var(--soft-white);
color: var(--navy-blue);
}

.search-box-container {
display: flex;
justify-content: center;
align-items: center;
margin-top: -1.5rem;
margin-bottom: 1rem;
padding: 1rem 0;
}

.search-container {
background-color: white;
border-radius: 10px;
box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
padding: 0.8rem;
display: flex;
gap: 0.5rem;
width: 800px;
max-width: 80%;
}

.search-container input {
flex: 1;
padding: 0.6rem;
border: 1px solid #ccc;
border-radius: 5px;
font-size: 1rem;
}

.search-container button {
background-color: #2d2e60;
color: white;
border: none;
border-radius: 5px;
padding: 0.6rem 1.2rem;
cursor: pointer;
font-weight: bold;
font-size: 1rem;
transition: background-color 0.3s ease;
}


#movieResults {
text-align: center;
margin-top: 1rem;
font-size: 1.1rem;
color: #333;
}

#searchInput {
padding: 10px;
width: 300px;
border-radius: 8px;
border: 2px solid #ddd;
}

button {
padding: 10px 20px;
border: none;
background-color: var(--navy-blue);
color: var(--pure-white);
border-radius: 8px;
cursor: pointer;
margin-left: none;
}

button:hover {
background-color: var(--light-navy);
}

#movieResults {
text-align: center;
margin-top: 20px;
font-size: 1.2rem;
color: var(--navy-blue);
}

.carousel {
width: 100%;
max-width: 100%;
height: auto;
margin: 0 auto;
overflow: hidden;
display: flex;
margin-bottom: 1rem;
justify-content: center;
position: relative;
}

.carousel img {
width: 100%;
height: 100%;
object-fit: cover;
border-bottom-left-radius: 20px;
border-bottom-right-radius: 20px;
}

.container {
flex: 1;
width: 90%;
max-width: 1200px;
margin: 0 auto;
padding: 4px;
}

.navbar {
background: var(--navy-blue);
box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
padding: 1rem 0;
}

.navbar .container {
display: flex;
justify-content: space-between;
align-items: center;
}

.logo {
color: var(--pure-white);
text-decoration: none;
font-size: 1.5rem;
font-weight: bold;
letter-spacing: 1px;
}

.nav-links {
display: flex;
list-style: none;
}

.nav-links li a {
color: var(--soft-white);
text-decoration: none;
padding: 0.8rem 1.5rem;
transition: all 0.3s ease;
border-radius: 20px;
border: 2px solid transparent;
}

.nav-links li a:hover {
color: var(--pure-white);
border-color: var(--pure-white);
background: transparent;
}

.welcome-section {
text-align: center;
padding: 3rem 0;
}

.welcome-section h1 {
color: var(--navy-blue);
font-size: 2.5rem;
margin-bottom: 1rem;
}

/* Form Styles */
.login-form,
.register-form {
max-width: 400px;
margin: 2rem auto;
padding: 2rem;
background: var(--pure-white);
border-radius: 8px;
box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.form-group {
margin-bottom: 1.5rem;
}

.form-group label {
display: block;
margin-bottom: 0.5rem;
color: var(--navy-blue);
font-weight: 500;
}

.form-group input {
width: 100%;
padding: 0.8rem;
background: var(--soft-white);
border: 1px solid #E0E0E0;
border-radius: 4px;
color: var(--navy-blue);
transition: all 0.3s ease;
}

.form-group input:focus {
outline: none;
border-color: var(--navy-blue);
box-shadow: 0 0 0 2px rgba(27, 27, 58, 0.1);
}

.btn {
display: inline-block;
padding: 0.8rem 1.5rem;
border: none;
border-radius: 4px;
cursor: pointer;
transition: all 0.3s ease;
font-weight: 500;
}

.btn-primary {
background: var(--navy-blue);
color: var(--pure-white);
}

.btn-primary:hover {
background: var(--light-navy);
}

.footer {
background: var(--navy-blue);
color: var(--soft-white);
text-align: center;
padding: 1rem 0;
width: 100%;
margin-top: auto;
}

.error {
color: #DC3545;
margin-bottom: 1rem;
background: #FFF3F4;
padding: 0.8rem;
border-radius: 4px;
border-left: 4px solid #DC3545;
}

@media (max-width: 768px) {
.navbar .container {
flex-direction: column;
text-align: center;
}

.nav-links {
margin-top: 1rem;
flex-wrap: wrap;
justify-content: center;
}

.welcome-section h1 {
font-size: 2rem;
}

.login-form,
.register-form {
margin: 2rem 1rem;
}
}