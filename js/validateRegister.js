//checks when u click submit then runs through all the functions to check if input is valid, if not it prevents the form to be sent to database
$('#regSubmit').click(function(e){
	if (validateForm() === false) {
        e.preventDefault();
        console.log("Form failed, sending prevented.");
    }
});

// Username check
function isUsernameValid() {

    var name = $('#username').val();
    var filter = /[^a-zA-ZæøåÆØÅ ]/;

    if (name.length == 0) {
        
        $("#usernameError").text("This field can not be empty!");
        return false;
        
    } else if (name.length < 3) {
        
        $("#usernameError").text("Name is too short! Must be more than 2 letters");
        return false;
        
    } else if (name.match(filter)){
        
        $("#usernameError").text("No numbers in a username!");
        return false;
    
    } else {
		
		$("#usernameError").text(" ");
        return true;
    }
}

// Password check
function isPasswordTheSame() {
    
    var pass1 = $("#pass1").val();
	var pass2 = $("#pass2").val();
    if (!(pass1 === pass2)) {
        
        $("#passError").text("Passwords are not the same!");
		return false;
    
    }else {
        
        $("#passError").text(" ");
		return true;

    }
}

// Validation, if both are true you can submit your form and make an account.
function validateForm() {
    return isUsernameValid() && isPasswordTheSame();
}