	/* Validation for the email and password input field when user is typing */
	const form = document.getElementById('loginForm');
	const emailInput = document.getElementById('email');
	const passwordInput = document.getElementById('password');

	emailInput.addEventListener('input', function(event){
		if (emailInput.value.trim() === ''){
			emailInput.nextElementSibling.innerHTML = 'Email field is required';
			emailInput.nextElementSibling.style.color = 'red';
			emailInput.classList.remove('validInput');
			emailInput.classList.add('invalidInput');
		}else if (!isValidEmail(emailInput.value.trim())){
			emailInput.nextElementSibling.innerHTML = 'Please enter a valid email';
			emailInput.nextElementSibling.style.color = 'red';
			emailInput.classList.remove('validInput');
			emailInput.classList.add('invalidInput');
		}else{
			emailInput.nextElementSibling.innerHTML = '';
			emailInput.classList.remove('invalidInput');
			emailInput.classList.add('validInput');
		}
	});
	
	passwordInput.addEventListener('input', function(event){
		if (passwordInput.value.trim() === ''){
			passwordInput.nextElementSibling.innerHTML = 'Password field is required';
			passwordInput.nextElementSibling.style.color = 'red';
			passwordInput.classList.remove('validInput');
			passwordInput.classList.add('invalidInput');
		}else{
			passwordInput.nextElementSibling.innerHTML = '';
			passwordInput.classList.remove('invalidInput');
			passwordInput.classList.add('validInput');
		}
	});

	form.addEventListener('submit', function(event){
		const errors = [];
		
		if (emailInput.value.trim() === ''){
			errors.push('Email field is required');
		}else if (!isValidEmail(emailInput.value.trim())){
			errors.push('Please enter a valid email');
		}
		
		if (passwordInput.value.trim() === ''){
			errors.push('Password field is required');
		}
		
		// Submit the form if there are no errors
		if (errors.length === 0){
			form.submit();
		}else{
			// Prevent to submit the form if there is any error
			event.preventDefault();
		}
	});
	
	function isValidEmail(email) {
		const emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
		return emailRegex.test(email);
	}