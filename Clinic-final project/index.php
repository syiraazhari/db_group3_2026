<!DOCTYPE html>
<html>
<head>
    <title>Clinic System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Tahoma;
            background-image: url('zCLinic1.jpeg');
            background-size: cover;
            background-position: center;
            background-color: #CCFFFF;
            background-attachment: fixed;
            min-height: 100vh;
        }
        .overlay {
            background-color: rgba(0, 0, 0, 0.1);
            min-height: 100vh;
            padding: 50px 20px;
        }
        
        h1 {
            text-align: center;
            color: #66FFFF;
            font-size: 40px;
            margin-bottom: 40px;
        }
        
        p {
            text-align: center;
            color: black;
            font-size: 15px;
        }
        
        a {
            color: #e67e22;
            font-family: Times new roman;
        }
        
        a:hover {
            text-decoration: underline;
            color: #f39c12;
        }
        
        .content {
            background: rgba(255,255,255,255);
            max-width: 500px;
            margin: 50px auto;
            padding: 10px;
            border-radius: 10px;
		}
		h3{
			font-family:times new roman;
			color:black;
			font-size: 25px;
			text-align:center;
		}
		 .bttn {
            display: inline-block;
            background-color: #e67e22;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            text-align: center;
            font-size: 15px;
        }
    </style>
</head>
<body>

<div class="overlay">
    <div class="content">
        <h1>Welcome to Clinic Appointment System</h1>
        <h3>Manage your health with us.</h3>
        
			<p style="font-family:times new roman">Already have an account? <span class="bttn" onclick="location.href='login.php'">Login here</span><br><br>
			Do not have an account? <span class="bttn" onclick="location.href='pRegister.php'">Register here</span></p>
	</div>
</div>

</body>
</html>