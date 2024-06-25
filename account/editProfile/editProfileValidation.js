const form = document.getElementById('editProfileForm');
const nameInput = document.getElementById('name');
const phoneInput = document.getElementById('phone_num');

form.addEventListener('submit', function(event){
	const errors = [];
	
	if (!isEmpty(nameInput) && !isValidName(nameInput.value.trim())){
		errors.push('Please enter a valid name');
		nameInput.nextElementSibling.innerHTML = 'Only alphabets and white space are allowed';
		nameInput.nextElementSibling.style.color = 'red';
	}
	
	if (!isEmpty(phoneInput) && !isValidPhone(phoneInput.value.trim())){
		errors.push('Please enter a valid phone');
		phoneInput.nextElementSibling.innerHTML = 'Please enter a valid phone according to the format';
		phoneInput.nextElementSibling.style.color = 'red';
	}
	
	// Submit the form if there are no errors
	if (errors.length === 0){
		form.submit();
	}else{
		// Prevent to submit the form if there is any error
		event.preventDefault();
	}
});

function isEmpty(inputElement){
	return inputElement.value.trim() === '';
}

function isValidName(name){
	const nameRegex = /^[a-zA-Z ]*$/;
	return nameRegex.test(name);
}

function isValidPhone(phone){
	const phoneRegex = /^[0-9]{3}\-[0-9]{7,8}$/;
	return phoneRegex.test(phone);
}