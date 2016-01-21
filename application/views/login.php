<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Welcome to CodeIgniter</title>
    <link rel="stylesheet" href="css/themes/default/jquery.mobile-1.4.5.min.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
    <script src="js/jquery.js"></script>
    <script src="js/jquery.mobile-1.4.5.min.js"></script>
    <style type="text/css">
        ::selection {
            background-color: #E13300;
            color: white;
        }

        ::-moz-selection {
            background-color: #E13300;
            color: white;
        }

        body {
            background-color: #000;
            margin: 0;
            padding: 0;
            font: 13px/20px normal Helvetica, Arial, sans-serif;
            color: #000;
        }

        a {
            color: #003399;
            background-color: transparent;
            font-weight: normal;
        }

        h1 {
            color: #444;
            background-color: transparent;
            border-bottom: 1px solid #D0D0D0;
            font-size: 19px;
            font-weight: normal;
            margin: 0 0 14px 0;
            padding: 14px 15px 10px 15px;
        }

        code {
            font-family: Consolas, Monaco, Courier New, Courier, monospace;
            font-size: 12px;
            background-color: #f9f9f9;
            border: 1px solid #D0D0D0;
            color: #002166;
            display: block;
            margin: 14px 0 14px 0;
            padding: 12px 10px 12px 10px;
        }

        #body {
            margin: 0;
            padding: 0;
        }

        p.footer {
            text-align: right;
            font-size: 11px;
            border-top: 1px solid #D0D0D0;
            line-height: 32px;
            padding: 0 10px 0 10px;
            margin: 20px 0 0 0;
        }

        #container {
            margin: 200px auto;
            padding: 20px;
            border: 1px solid #000;
            box-shadow: 0 0 8px #000;
            width: 200px;
            height: 140px;
            background: #fff;
        }
        .error {
            color:red;
            font-size:12px;

        }

    </style>
    <script>
    $(document).ready(function(){
        $("#login").click(function() {
            var username = $("#username").val();
            var password = $("#password").val();
            console.log("login clicked: "+username);
            $.ajax({
                type: "POST",
                url: "./login",
                data: "username=" + username + "&password=" + password,
                success: function(html) {
                    if (html) {
                        window.location = "./";
                    } else {
                        $( "#error" ).popup( "open" );
                        $("#error p").html("Wrong username or password");
                    }
                },
                beforeSend: function() {
                    $( "#error" ).popup( "open" );
                    $("#error p").html("Loading...")
                }
            });
            return false;
        });
    });
    </script>
</head>

<body>
    <div data-role="popup" id="error">
	    <p></p>
	</div>
    <div id="container">
        <input type="text" name="username" id="username" placeholder="E-mail" value="">
        <input type="password" name="password" id="password" placeholder="Password" value="">
        <button class="ui-shadow ui-btn ui-corner-all" id="login">Login</button>
    </div>

</body>

</html>