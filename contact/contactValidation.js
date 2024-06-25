const form = document.getElementById('contactForm');
const nameInput = document.getElementById('name');
const emailInput = document.getElementById('email');
const phoneInput = document.getElementById('phone_num');
const toeInput = document.getElementById('toe');
const subjectInput = document.getElementById('subject');

// Client-side scripting
// Validation of all the input field when user is typing

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

subjectInput.addEventListener('input', function(event){
	if (subjectInput.value.trim() === ''){
		subjectInput.nextElementSibling.innerHTML = 'Subject field is required';
		subjectInput.nextElementSibling.style.color = 'red';
		subjectInput.classList.remove('validInput');
		subjectInput.classList.add('invalidInput');
	}else{
		subjectInput.nextElementSibling.innerHTML = '';
		subjectInput.classList.remove('invalidInput');
		subjectInput.classList.add('validInput');
	}
});

// Validation before user successfully submit the registration form

form.addEventListener('submit', function(event){
	const errors = [];
	
	if (nameInput.value.trim() === ''){
		errors.push('Name field is required');
		nameInput.nextElementSibling.innerHTML = 'Name field is required';
		nameInput.nextElementSibling.style.color = 'red';
		nameInput.classList.remove('validInput');
		nameInput.classList.add('invalidInput');
	}else if (!isValidName(nameInput.value.trim())){
		errors.push('Please enter a valid name');
	}
	
	if (emailInput.value.trim() === ''){
		errors.push('Email field is required');
		emailInput.nextElementSibling.innerHTML = 'Email field is required';
		emailInput.nextElementSibling.style.color = 'red';
		emailInput.classList.remove('validInput');
		emailInput.classList.add('invalidInput');
	}else if (!isValidEmail(emailInput.value.trim())){
		errors.push('Please enter a valid email');
	}
	
	if (phoneInput.value.trim() === ''){
		errors.push('Phone field is required');
		phoneInput.nextElementSibling.innerHTML = 'Phone field is required';
		phoneInput.nextElementSibling.style.color = 'red';
		phoneInput.classList.remove('validInput');
		phoneInput.classList.add('invalidInput');
	}else if (!isValidPhone(phoneInput.value.trim())){
		errors.push('Please enter a valid phone');
	}
	
	if (toeInput.value == ''){
		errors.push('Please select a type of enquiry');
		toeInput.nextElementSibling.innerHTML = 'Please select a type of enquiry';
		toeInput.nextElementSibling.style.color = 'red';
		toeInput.classList.add('invalidInput');
	}
	
	if (subjectInput.value.trim() === ''){
		errors.push('Subject field is required');
		subjectInput.nextElementSibling.innerHTML = 'Subject field is required';
		subjectInput.nextElementSibling.style.color = 'red';
		subjectInput.classList.add('invalidInput');
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

function isValidName(name){
	const nameRegex = /^[a-zA-Z ]*$/;
	return nameRegex.test(name);
}

function isValidPhone(phone){
	const phoneRegex = /^[0-9]{3}\-[0-9]{7,8}$/;
	return phoneRegex.test(phone);
}