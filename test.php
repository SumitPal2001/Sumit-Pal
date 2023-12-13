<!doctype html>
<html>
    <head>
        <title>DeltaDash</title>
            <!-- Favicon -->
        <link rel="shortcut icon" href="img/fevicon.png">
    
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap"
            rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Sora:wght@100;200;300;400;500;600;700;800&display=swap"
            rel="stylesheet">
        <link
            href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500;1,600;1,700;1,800&display=swap"
            rel="stylesheet">
    
        <!-- Plugins -->
        <link rel="stylesheet" href="css/plugins.css">
    
        <!-- Core Style Css -->
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
        <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
  
        <script>
            $(document).ready(function(){
                $("#myForm").submit(function(e){
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: "phonpaypayment.php",
                        data: $(this).serialize(),
                        success: function(response){
                            var resp = JSON.parse(response); 
                            console.log(resp);
                            window.open(resp.redirectUrl, '_blank');
                            $("#myForm")[0].reset();
                        }
                    });
                });
            });
        function ApplyingCoupnCode ()
            {
            var couponCode = $("#CouponCode").val();
            $.ajax({
                type: "POST",
                url: "validate_coupon.php",
                data: { couponCode: couponCode },
                success: function(response) {
                    var resp = JSON.parse(response);
                    if (resp.valid) {
                       
                        var originalAmount = 1; // Your original amount 36000
                        var discount = originalAmount * (resp.discount_percentage / 100);
                        var roundedDiscount = Math.round(discount); 
                        var discountedAmount = originalAmount - discount;
                        var discountAmtGST = discountedAmount + ((discountedAmount*18)/100);
                        var roundedAmount = Math.round(discountAmtGST); 
                        $("#hiddenAmount").val(roundedAmount);
                        $("#finalamt").text("₹"+ roundedAmount);
                        $("#couponStatus").text("Coupon applied Successfully You Saved  :₹" + roundedDiscount);
                    }
                    else {
                    $("#couponStatus").text("Invalid coupon code");
                    }
                    $("#finalamt")[0].reset();
                    $("#couponStatus")[0].reset();
                }
            });
        }
        </script>
    </head>
    <body>
        <div class="container contact-crev section-padding">
            <div class="row pt-20">
                <div class="col-lg-8">
                     <div style="text-align:center;">
                        <h3>DeltaDash by Gokyo Analytics</h3>
                     </div>
                    <div>
                      <img src="img/deltadash/3415371.png">
                    </div>
                    <div>
                        <h3>Deltadash</h3>
                        <p>your gateway to cutting-edge market analysis! Transform 𝐓𝐞𝐜𝐡𝐧𝐢𝐜𝐚𝐥 𝐀𝐧𝐚𝐥𝐲𝐬𝐢𝐬 𝐒𝐢𝐠𝐧𝐚𝐥𝐬 into 𝐒𝐂𝐎𝐑𝐈𝐍𝐆 systems, visualize 𝐒𝐢𝐠𝐧𝐚𝐥𝐬, 𝐕𝐨𝐥𝐮𝐦𝐞, and 𝐎𝐩𝐞𝐧 𝐈𝐧𝐭𝐞𝐫𝐞𝐬𝐭 as 𝐒𝐂𝐎𝐑𝐄𝐒. Gain a competitive edge with 𝐃𝐞𝐥𝐭𝐚𝐃𝐚𝐬𝐡's 𝐂𝐨𝐦𝐩𝐫𝐞𝐡𝐞𝐧𝐬𝐢𝐯𝐞 𝐀𝐬𝐬𝐞𝐬𝐬𝐦𝐞𝐧𝐭 and spot market trends effortlessly. Elevate your trading strategies today!</p>
                    </div>
                </div>
                <div class="col-lg-4 ">
                        <div class="full-width">
                            <div>
                                <h6>Please Fill Your Appropriate Detail</h6>
                            </div>
                            <form id="myForm" method="post" action="">

                                <div class="messages"></div>

                                <div class="controls row">

                                                           <div class="col-lg-12">
                                        <div class="form-group mb-30">
                                            <input id="Name" type="text" name="Name" placeholder="Name" required="required" >
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group mb-30">
                                            <input id="MobNo" type="text" name="MobNo" placeholder="Phone No" maxlength="10" pattern="[789][0-9]{9}" data-bv-field="number" required="required">
                                            <small class="help-block" data-bv-validator="notEmpty" data-bv-for="number" data-bv-result="NOT_VALIDATED" style="display: none;">Phone is required. Please enter phone number.</small><small class="help-block" data-bv-validator="regexp" data-bv-for="number" data-bv-result="NOT_VALIDATED" style="display: none;">Please enter a value matching the pattern</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <div class="form-group mb-30">
                                            <input id="EmailId" type="email" name="EmailId" placeholder="Email ID" required="required" >
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group mb-30">
                                            <input id="Address" type="text" name="Address" placeholder="Address" required="required" >
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group mb-30">
                                            <input id="Pincode" type="text" name="Pincode" placeholder="Pincode" maxlength="6" data-bv-field="number" required="required">
                                            <small class="help-block" data-bv-validator="notEmpty" data-bv-for="number" data-bv-result="NOT_VALIDATED" style="display: none;"> Please enter Pin Code .</small><small class="help-block" data-bv-validator="regexp" data-bv-for="number" data-bv-result="NOT_VALIDATED" style="display: none;">Please enter a value matching the pattern</small>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group mb-30">
                                            <select name="State" id="State" class="form-control" required>
                                                <option value="">Select State</option>
                                                <option value="Andhra Pradesh">Andhra Pradesh</option>
                                                <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                                <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                                <option value="Assam">Assam</option>
                                                <option value="Bihar">Bihar</option>
                                                <option value="Chandigarh">Chandigarh</option>
                                                <option value="Chhattisgarh">Chhattisgarh</option>
                                                <option value="Dadar and Nagar Haveli">Dadar and Nagar Haveli</option>
                                                <option value="Daman and Diu">Daman and Diu</option>
                                                <option value="Delhi">Delhi</option>
                                                <option value="Lakshadweep">Lakshadweep</option>
                                                <option value="Puducherry">Puducherry</option>
                                                <option value="Goa">Goa</option>
                                                <option value="Gujarat">Gujarat</option>
                                                <option value="Haryana">Haryana</option>
                                                <option value="Himachal Pradesh">Himachal Pradesh</option>
                                                <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                                <option value="Jharkhand">Jharkhand</option>
                                                <option value="Karnataka">Karnataka</option>
                                                <option value="Kerala">Kerala</option>
                                                <option value="Madhya Pradesh">Madhya Pradesh</option>
                                                <option value="Maharashtra">Maharashtra</option>
                                                <option value="Manipur">Manipur</option>
                                                <option value="Meghalaya">Meghalaya</option>
                                                <option value="Mizoram">Mizoram</option>
                                                <option value="Nagaland">Nagaland</option>
                                                <option value="Odisha">Odisha</option>
                                                <option value="Punjab">Punjab</option>
                                                <option value="Rajasthan">Rajasthan</option>
                                                <option value="Sikkim">Sikkim</option>
                                                <option value="Tamil Nadu">Tamil Nadu</option>
                                                <option value="Telangana">Telangana</option>
                                                <option value="Tripura">Tripura</option>
                                                <option value="Uttar Pradesh">Uttar Pradesh</option>
                                                <option value="Uttarakhand">Uttarakhand</option>
                                                <option value="West Bengal">West Bengal</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group mb-30">
                                            <input id="GSTNo" type="text" name="GSTNo" placeholder="GST Number (Optional)" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                     <div class="row mb-30" >
                                        <div class="col-lg-8">
                                            <div class="form-group ">
                                                <input id="CouponCode" type="text" name="CouponCode" placeholder="Coupon Code">
                                                <span id="couponStatus"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group ">
                                                <input type="button" id="CoupanButton" value="Apply" name="CoupanButton" class="butn butn-md butn-bord radius-10" onclick="ApplyingCoupnCode()">
                                               
                                            </div>
                                        </div>
                                    </div>
                                    
                                     <div class="col-12" style="text-align: center;">
                                            <h6><label>Amount -</label>
                                            <span id="finalamt">₹1</span></h6>
                                    </div> 
      
                                     <input type="hidden" id="hiddenAmount" name="TxnAmount" id="finalamt"  value="1">
                                     <input type="hidden" name="Producttype" id="Producttype"  value="DELTADASH">

                                    <!--<div><span>For more info read our <a href="privacy-policy.php" style=" text-decoration: underline;">Privacy Policy</a> and <a href="terms-conditions.php" style=" text-decoration: underline;">Terms and Conditions</a></span></div>-->
                                   
                                    <div class="mt-30" style="text-align: center;">
                                        <button type="submit" id="Submit" name="Submit" class="butn butn-md butn-bord radius-30">
                                            <span class="text">Pay Now</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
            </div>
        </div>
        <!-- jQuery -->

        
        <script src="js/jquery-3.6.0.min.js"></script>
        <!-- <script src="js/jquery-migrate-3.4.0.min.js"></script> -->
    
        <!-- plugins -->
        <script src="js/plugins.js"></script>
    
        <script src="js/ScrollTrigger.min.js"></script>
    
        <!-- custom scripts -->
        <script src="js/scripts.js"></script>
          
       
    </body>
</html>