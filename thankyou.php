<!doctype html>
<html>
    <head>
        <title>THANK YOU</title>
            <!-- Favicon -->
        <link rel="shortcut icon" href="img/fevicon.png">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <style>
            .box{
            margin-top:60px;
            display:flex;
            justify-content:space-around;
            flex-wrap:wrap;
            }

            .alert{
            margin-top:25px;
            background-color:#fff;
            font-size:25px;
            font-family:sans-serif;
            text-align:center;
            width:300px;
            height:auto;
            padding-top: 150px;
            position:relative;
            border: 1px solid #efefda;
            border-radius: 2%;
            box-shadow:0px 0px 3px 1px #ccc;
            }

            .alert::before{
            width:100px;
            height:100px;
            position:absolute;
            border-radius: 100%;
            inset: 20px 0px 0px 100px;
            font-size: 60px;
            line-height: 100px;
            border : 5px solid gray;
            animation-name: reveal;
            animation-duration: 1.5s;
            animation-timing-function: ease-in-out;
            }

            .alert>.alert-body{
            opacity:0;
            animation-name: reveal-message;
            animation-duration:1s;
            animation-timing-function: ease-out;
            animation-delay:1.5s;
            animation-fill-mode:forwards;
            }

            @keyframes reveal-message{
            from{
                opacity:0;
            }
            to{
                opacity:1;
            }
            }

            .success{
            color:green;
            }

            .success::before{
            content: '✓';
            background-color: #eff;
            box-shadow: 0px 0px 12px 7px rgba(200,255,150,0.8) inset;
            border : 5px solid green;
            }

            .error{
            color: red;
            }

            .error::before{
            content: '✗';
            background-color: #fef;
            box-shadow: 0px 0px 12px 7px rgba(255,200,150,0.8) inset;
            border : 5px solid red;
            }

            @keyframes reveal {
            0%{
                border: 5px solid transparent;
                color: transparent;
                box-shadow: 0px 0px 12px 7px rgba(255,250,250,0.8) inset;
                transform: rotate(1000deg);
            }
            25% {
                border-top:5px solid gray;
                color: transparent;
                box-shadow: 0px 0px 17px 10px rgba(255,250,250,0.8) inset;
                }
            50%{
                border-right: 5px solid gray;
                border-left : 5px solid gray;
                color:transparent;
                box-shadow: 0px 0px 17px 10px rgba(200,200,200,0.8) inset;
            }
            75% {
                border-bottom: 5px solid gray;
                color:gray;
                box-shadow: 0px 0px 12px 7px rgba(200,200,200,0.8) inset;
                }
            100%{
                border: 5px solid gray;
                box-shadow: 0px 0px 12px 7px rgba(200,200,200,0.8) inset;
            }
            }
        </style>
    </head>
    <body>
        
        <div class="container" style="margin-top:5%;">
            <div class="row">
                <div class="jumbotron" style="box-shadow: 2px 2px 4px #000000;">
                    <div class="box"> 
                        <div class="success alert">
                            <div class="alert-body">
                            Success !
                            </div>
                        </div>
                    </div>
                        <h2 class="text-center">YOUR PAYMENTS HAS BEEN RECEIVED</h2>
                    <h3 class="text-center">Thank you for your payment</h3>
                    
                    <p class="text-center">Your Transaction ID: <?php echo $_GET['transactionId']; ?></p>
                    <p class="text-center">Your Reference ID: <?php echo $_GET['referenceId']; ?></p>
                     <!-- Database connection parameters-->
                    
                    <center>
                        <div class="btn-group" style="margin-top:50px;">
                            <a href="gokyoanalytics.com" class="btn btn-lg btn-warning" onclick="closeAndReload();" >OK</a>
                        </div>
                    </center>
                </div>
            </div>
        </div>
       <script>
        function closeAndReload() {
            window.close();
            location.reload();
        }
        </script>
    </body>
</html>