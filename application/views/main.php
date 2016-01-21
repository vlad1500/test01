<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
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
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #000;
            box-shadow: 0 0 8px #000;
            width: 90%;
            min-height: 300px;
            background: #fff;
            max-width: 700px;
            position: relative;
        }

        h3 span {
            text-transform: capitalize;
        }

        #logout {
            position: absolute;
            right: 20px;
            top: 10px;
            color: #000;
            text-decoration: none;
        }
        #tweetsTable {
            border: 2px solid;
            margin: 25px;
            width: 80%;
        }
        #tweetsTable td {
            border-bottom: 1px solid;
        }
        #popupTwits {
            position:relative;
        }
        .addPadding {
            padding:10px;
        }

    </style>
    <script>
        $(document).ready(function() {
            loadFriends();
        });
        var friedsArr = [];
        function loadFriends() {
            $.ajax({
                type: "GET",
                url: "./main/getfriends",
                data: "id=<?php echo $this->session->userdata('id'); ?>",
                success: function(html) {
                    friedsArr = JSON.parse(html);
                    showFriends(friedsArr);
                }
            });
        }

        function showFriends(friedsArr) {
            console.log(friedsArr);
            var count = 0;
            friedsArr.forEach(function(entry) {
                var thisTr = $("<tr id='" + count + "'></tr>");
                var thisTd1 = $("<td>" + entry.name + "</td>");
                var thisTd2 = $("<td>" + entry.email + "</td>");
                var thisTd3 = $("<td><a href='javascript:void(0);' onclick='getTweets(this)' target='_blank' id='" + entry.twitter + "'>" + entry.twitter + "</a></td>");
                var thisTd4 = $("<td><a href='javascript:void(0);' onclick='popEditFriend(this)' id='" + entry.twitter + "'>Edit</a> | <a href='javascript:void(0);' onclick='delFriend(this)' id='" + entry.twitter + "'>Delete</a></td>");
                thisTr.append(thisTd1, thisTd2, thisTd3, thisTd4);
                $("#appendToThis").append(thisTr);
                count++;
            }, this);
        }
        function delFriend(ele) {
            var thisID = $(ele).parent().parent().attr("id");
            var thisData = friedsArr[thisID];
            var friendID = thisData['id'];
            $.ajax({
                type: "POST",
                url: './main/delfriend',
                data: "id=" + friendID,
                success: function(res) {
                    if(res){
                        $("#popupEditFriend").popup("close");
                        $("#appendToThis").html("");
                        loadFriends();
                    }
                }
            });
        }
        function popEditFriend(ele) {
            var thisID = $(ele).parent().parent().attr("id");
            var thisData = friedsArr[thisID];
            var name = (friedsArr[thisID].name).split(" ");
            var id = $("#popupEditFriend #id").val(friedsArr[thisID].id);
            var fname = $("#popupEditFriend #fname").val(name[0]);
            var lname = $("#popupEditFriend #lname").val(name[1]);
            var email = $("#popupEditFriend #email").val(friedsArr[thisID].email);
            var twitter = $("#popupEditFriend #twitter").val(friedsArr[thisID].twitter);
            if(name && email && twitter) {
                $("#popupEditFriend").popup("open");
            }
        }
        function doneEditFriend() {
            var fname = $("#popupEditFriend #fname").val();
            var lname = $("#popupEditFriend #lname").val();
            var name = fname+" "+lname;
            var id = $("#popupEditFriend #id").val();
            var email = $("#popupEditFriend #email").val();
            var twitter = $("#popupEditFriend #twitter").val();
            if(name && email && twitter) {
                $.ajax({
                    type: "POST",
                    url: "./main/editfriend",
                    data: "id=" + id + "&name=" + name + "&email=" + email + "&twitter=" + twitter,
                    success: function(html) {
                        if(html){
                            $("#popupEditFriend").popup("close");
                            $("#appendToThis").html("");
                            loadFriends();
                        }
                    }
                });
            }
            return false;
        }
        function getTweets(ele) {
            var user = $(ele).attr("id");
            $.ajax({
                type: "GET",
                url: './main/gettweets',
                data: "user=" + user,
                success: function(res) {
                    var tweets = JSON.parse(res);
                    popTweets(tweets);
                }
            });
        }

        function popTweets(tweets) {
            $("#tweetsHere").html("");
            tweets.forEach(function(entry) {
                var thisTr = $("<tr><td>" + entry.text + "</td></tr>");
                $("#tweetsHere").append(thisTr);
            }, this);
            $("#popupTwits").popup("open");
        }

        function logout() {
            $.ajax({
                type: "GET",
                url: "./login/logout",
                success: function(html) {
                    window.location = "./";
                }
            });
        }
        function addFriend(){
            var fname = $("#popupAddFriend #fname").val();
            var lname = $("#popupAddFriend #lname").val();
            var name = fname+" "+lname;
            var email = $("#popupAddFriend #email").val();
            var twitter = $("#popupAddFriend #twitter").val();
            if(name && email && twitter) {
                $.ajax({
                    type: "POST",
                    url: "./main/addfriend",
                    data: "name=" + name + "&email=" + email + "&twitter=" + twitter,
                    success: function(html) {
                        if(html){
                            $("#popupAddFriend").popup("close");
                            $("#appendToThis").html("");
                            loadFriends();
                        }
                    }
                });
            }
            return false;
        }
    </script>
</head>

<body>

    <div id="container">
        <div data-demo-html="true">
            <a href="javascript:void(0);" id="logout" onclick="logout();">Logout</a>
            <h3>Hello <span><?php echo $this->session->username ?>,</span></h3>
            <h3>Here's a list of your friends</h3>
            <table data-role="table" id="temp-table" data-mode="reflow" class="ui-responsive table-stroke">
                <thead>
                    <tr>
                        <th data-priority="1">Name</th>
                        <th data-priority="1">Email</th>
                        <th data-priority="2">Twitter</th>
                        <th data-priority="2">Action</th>
                    </tr>
                </thead>
                <tbody id="appendToThis"></tbody>
            </table>
            <a href="#popupAddFriend" data-rel="popup" class="ui-btn ui-corner-all ui-shadow ui-btn-inline" data-transition="pop">Add New Friend</a>
        </div>
    </div>
    <div data-role="popup" id="popupTwits" data-overlay-theme="a" data-theme="b" data-corners="false">
        <h3>Hi <span><?php echo $this->session->username ?>,</span></h3>
        <h3>Here are the tweets</h3>
        <table data-role="table" id="tweetsTable" data-mode="reflow" class="ui-responsive table-stroke">
            <tbody id="tweetsHere"></tbody>
        </table>
    </div>
    <div data-role="popup" id="popupAddFriend" class="addPadding" data-overlay-theme="a" data-theme="b" data-corners="false">
        <input type="text" name="fname" id="fname" placeholder="Last name" value="">
        <input type="text" name="lname" id="lname" placeholder="First name" value="">
        <input type="text" name="email" id="email" placeholder="E-mail" value="">
        <input type="text" name="twitter" id="twitter" placeholder="Twitter" value="">
        <button class="ui-shadow ui-btn ui-corner-all" id="addFriendBtn" onclick="addFriend();">Add New</button>
    </div>
    <div data-role="popup" id="popupEditFriend" class="addPadding" data-overlay-theme="a" data-theme="b" data-corners="false">
        <input type="hidden" id="id" />
        <input type="text" name="fname" id="fname" placeholder="Last name" value="">
        <input type="text" name="lname" id="lname" placeholder="First name" value="">
        <input type="text" name="email" id="email" placeholder="E-mail" value="">
        <input type="text" name="twitter" id="twitter" placeholder="Twitter" value="">
        <button class="ui-shadow ui-btn ui-corner-all" id="doneEditFriend" onclick="doneEditFriend();">Done</button>
    </div>
</body>

</html>