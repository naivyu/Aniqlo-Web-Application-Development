<form id="contactForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >    
	
	<!--Name field-->
	<div class="nameInput, form-group">
	<label for = "name" ><b>Name:</b><span class="asterisk">*</span></label><br>
    <input id="name" type="text" class="input-box" name="name" placeholder="Enter name">
	<span class="error"></span> 
	</div>
	<br>
    
	<!--Email field-->
	<div class="emailInput, form-group">
	<label for = "email"><b>Email:</b><span class="asterisk">*</span></label><br>
    <input id="email" type="text" class="input-box" name="email" placeholder="Enter email">
	<span class="error"></span> 
	</div>
    <br>  
	
    <!--Phone field-->
	<div class="phoneInput, form-group">
	<label for = "phone_num"><b>Phone No:</b><span class="asterisk">*</span></label><br>
    <input id="phone_num" type="text" class="input-box" name="phone_num" placeholder="Enter phone number">
	<span class="error"></span>  
	</div>
    <br>    
    
	<!--Type of enquiry drop down box-->
	<div class= "toeInput, form-group">
	<label for ="toe"><b>Type of Enquiry:</b><span class="asterisk">*</span></label><br>
	<select id="toe" name = "toe" class="input-box" title="toe">
		<option value="" disabled selected>Type of Enquiry</option> 
		<option value = "Products">Products</option>
		<option value = "Online Store">Online Store</option>
		<option value = "Stores">Stores</option>
		<option value = "Others">Others</option>
	</select>
    <span class="error"></span>  
	</div>
    <br>  
    
	<!--Subject field-->
	<div id="subjectInput, form-group">
	<label for = "subject"><b>Subject:</b><span class="asterisk">*</span></label><br>
	<textarea id="subject" name="subject" rows = "10" cols = "50" placeholder="Subject"></textarea>  
	<span class="error"></span>
    <br><br> 
	
	<div id="form-group">
    <input id="button" type="submit" name="submit" value="Submit">
	</div>
    <br><br>                             
</form>