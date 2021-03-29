function addUpdateForm_doValidate(fst, lst, email, headline, summary) {
    try {
		var str_fname = document.getElementById(fst).value;
		var str_lname = document.getElementById(lst).value;
		var str_email = document.getElementById(email).value;
        var str_headline = document.getElementById(headline).value;
		var str_summary = document.getElementById(summary).value;
        
        if (str_fname == null || str_fname == "" || 
			str_lname == null || str_lname == "" || 
			str_email == null || str_email == "" || 
			str_headline == null || str_headline == "" ||
			str_summary == null || str_summary == "") {
            alert("All fields must be filled out");
            return false;
        }
			
		return email_doValidate(str_email);				
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