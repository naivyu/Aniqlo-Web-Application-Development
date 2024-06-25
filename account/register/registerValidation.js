const form = document.getElementById('registerForm');
const emailInput = document.getElementById('email');
const passwordInput = document.getElementById('password');
const nameInput = document.getElementById('name');
const phoneInput = document.getElementById('phone');

// Client-side scripting
/* Validation for all the input field when user is typing */
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
	}else if (!isValidPassword(passwordInput.value.trim())){
		passwordInput.nextElementSibling.innerHTML = 'Password must be at least 8 characters, and contain both letters and numbers. Only these symbol can be used -_.@';
		passwordInput.nextElementSibling.style.color = 'red';
		passwordInput.classList.remove('validInput');
		passwordInput.classList.add('invalidInput');
	}else{
		passwordInput.nextElementSibling.innerHTML = '';
		passwordInput.classList.remove('invalidInput');
		passwordInput.classList.add('validInput');
	}
});

nameInput.addEventListener('input', function(event){
	if (nameInput.value.trim() === ''){
		nameInput.nextElementSibling.innerHTML = 'Name field is required';
		nameInput.nextElementSibling.style.color = 'red';
		nameInput.classList.remove('validInput');
		nameInput.classList.add('invalidInput');
	}else if(!isValidName(nameInput.value.trim())){
		nameInput.nextElementSibling.innerHTML = 'Please enter a valid name';
		nameInput.nextElementSibling.style.color = 'red';
		nameInput.classList.remove('validInput');
		nameInput.classList.add('invalidInput');
	}else{
		nameInput.nextElementSibling.innerHTML = '';
		nameInput.classList.remove('invalidInput');
		nameInput.classList.add('validInput');
	}
});

phoneInput.addEventListener('input', function(event){
	if (phoneInput.value.trim() === ''){
		phoneInput.nextElementSibling.innerHTML = 'Phone field is required';
		phoneInput.nextElementSibling.style.color = 'red';
		phoneInput.classList.remove('validInput');
		phoneInput.classList.add('invalidInput');
	}else if (!isValidPhone(phoneInput.value.trim())){
		phoneInput.nextElementSibling.innerHTML = 'Please enter a valid phone';
		phoneInput.nextElementSibling.style.color = 'red';
		phoneInput.classList.remove('validInput');
		phoneInput.classList.add('invalidInput');
	}else{
		phoneInput.nextElementSibling.innerHTML = '';
		phoneInput.classList.remove('invalidInput');
		phoneInput.classList.add('validInput');
	}
});
	
/* Validation before user successfully submit the registration form */
form.addEventListener('submit', function(event){
	const errors = [];
	
	if (emailInput.value.trim() === ''){
		errors.push('Email field is required');
	}else if (!isValidEmail(emailInput.value.trim())){
		errors.push('Please enter a valid email');
	}
	
	if (passwordInput.value.trim() === ''){
		errors.push('Password field is required');
	}else if (!isValidPassword(passwordInput.value.trim())){
		errors.push('Please enter a valid password');
	}
	
	if (nameInput.value.trim() === ''){
		errors.push('Name field is required');
	}else if (!isValidName(nameInput.value.trim())){
		errors.push('Please enter a valid name');
	}
	
	if (phoneInput.value.trim() === ''){
		errors.push('Phone field is required');
	}else if (!isValidPhone(phoneInput.value.trim())){
		errors.push('Please enter a valid phone');
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

function isValidPassword(password){
	const passwordRegex = /^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9\-_@.]{8,}$/;
	return passwordRegex.test(password);
}

function isValidName(name){
	const nameRegex = /^[a-zA-Z ]*$/;
	return nameRegex.test(name);
}

function isValidPhone(phone){
	const phoneRegex = /^[0-9]{3}\-[0-9]{7,8}$/;
	return phoneRegex.test(phone);
}