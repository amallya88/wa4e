function loginForm_doValidate(email_id, pw_id) {
    try {
        var email = document.getElementById(email_id).value;
		var pw = document.getElementById(pw_id).value;
        
        if (email == null || pw == null || email == "" || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
		
		return email_doValidate(email);				
    } catch(e) {
        return false;
    }
    return false;
}

function email_doValidate(email_str) {
    var count = (email_str.match(/@/g) || []).length;
	if (count != 1) {
		alert("Invalid email address");
		return false;
	}		
	return true;
}