function rateClick(voteType, commentId) {
    // Tried to add a parameter to differentiate between posts and comments
    // Didn't work so I just copied the exact same function and changed the name

    var string = "ratingText";
    var result = string.concat(commentId);

    var ratingText = "Rating: ";

    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        //alert(this.responseText);
        document.getElementById(result).innerHTML = ratingText.concat(this.responseText) + " ";
    }
    xhttp.open("GET", "ratingHandler.php?type=" + voteType + "&id=" + commentId + "&postType=1");
    xhttp.send();
}

function postRateClick(voteType, postId) {
    var string = "ratingPostText";
    var result = string.concat(postId);

    var ratingText = "Rating: ";

    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        //alert(this.responseText);
        document.getElementById(result).innerHTML = ratingText.concat(this.responseText) + " ";
    }
    xhttp.open("GET", "ratingHandler.php?type=" + voteType + "&id=" + postId + "&postType=2");
    xhttp.send();
}

function logInRedirect() {
    //window.location.href = "https://jtlai.w3.uvm.edu/cs145/final/logIn.php";
    window.location.href = "logIn.php";
}

// After DOM is loaded, register event handlers
window.addEventListener("DOMContentLoaded", function() {
    let usernameInput = document.getElementById("username");
    usernameInput.addEventListener("input", checkUsername);

    let passwordInput = document.getElementById("registerPassword");
    passwordInput.addEventListener("keydown", preventSpaces);
    passwordInput.addEventListener("input", passwordStrength);

    let matchPassword = document.getElementById("confirmPassword");
    matchPassword.addEventListener("input", checkPassword);

    let formSubmit = document.getElementById("registerForm");
    formSubmit.addEventListener("submit", validateForm);
})
registerHandlers();

/********************************   FORMS  ********************************/
function validateForm(event) {
    // Code samples from ZyBooks
    let form = document.getElementById("registerForm");
    
    if (form.email.value === "") {
        form.email.style.backgroundColor = "Red";
        event.preventDefault();
    }

    let username = document.getElementById("username").value;

    if (form.username.value === "" || username.length < 4) {
        form.username.style.backgroundColor = "Red";
        event.preventDefault();
    }

    let password = document.getElementById("confirmPassword").value;
    let password2 = document.getElementById("registerPassword").value;

    if (form.password.value === "" || form.password.value !== form.checkPassword.value) {
        form.password.style.backgroundColor = "Red";
        form.confirmPassword.style.backgroundColor = "Red";
        event.preventDefault();
    }
}

function checkUsername() {
    let username = this.value;

    let usernameResult = "";
    if (username.length < 4) {
        usernameResult += "Username must be at least 4 characters\n";
    }

    if (username !== "") {
        document.getElementById("usernameFeedback").innerHTML = usernameResult;
    }else{
        document.getElementById("usernameFeedback").innerHTML = "";
    }
}

function checkPassword() {
    let password = this.value;
    let password2 = document.getElementById("registerPassword").value;

    if (password != "" && password != password2) {
        document.getElementById("passwordCheck").innerHTML = "Passwords do not match.";
    }else{
        document.getElementById("passwordCheck").innerHTML = "";
    }
}

function preventSpaces(event) {
    if (event.key == " ") {
        event.preventDefault();
    }
}

function passwordStrength() {
    let password = this.value;

    // Password should have at least one digit 
    let containsDigit = false;
    for (let i = 0; i < password.length; i++) {
        let char = password.charCodeAt(i);      
        if (char >= 48 && char <= 57) {
            containsDigit = true;
        }
    }

    let passwordResult = "";

    if (password.length < 5) {
        passwordResult += "Password must be more than 5 characters long\n";
    }

    if (!containsDigit) {
        passwordResult += "Password must contain at least one number\n";
    }

    if (password !== "") {
        document.getElementById("passwordFeedback").innerHTML = passwordResult;
    }else{
        document.getElementById("passwordFeedback").innerHTML = "";
    }
}

function checkForm(event) {
    let form = document.getElementById("registerForm");

    

    if (password !== password2) {
        form.password.style.backgroundColor = "Orange";
        form.confirmPassword.style.backgroundColor = "Orange";
        event.preventDefault();
    }

    let username = document.getElementById("username").value;
    if (username.length < 4) {
        form.username.style.backgroundColor = "Orange";
        event.preventDefault();
    }
}

function registerHandlers() {
    let inputs = document.querySelectorAll("input");

    for (let i = 0; i < inputs.length; i++) {
        let input = inputs[i];
        input.style.border = "1px solid red";
        input.addEventListener("focus", function() {
            this.style.border = "1px solid green";
        });
    
        input.addEventListener("blur", function() {
            this.style.border = "1px solid blue";
        });
    }
}

function editHandler(identifier, content) {
    alert("ran: " + identifier + ", & " + content);
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        document.getElementById(identifier).innerHTML = "<textarea>" + content + "</textarea>";
    }
    xhttp.open("GET", "");
    xhttp.send();
}
/********************************   NAV  ********************************/
function profileButton() {
    document.getElementById("profileDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('#userProfileButton')) {
      var dropdowns = document.getElementsByClassName("dropdown-content");
      var i;
      for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
          openDropdown.classList.remove('show');
        }
      }
    }
  }

/********************************   POSTS  ********************************/
function showEditPost(identifier) {
    var string = "editPostForm";
    var result = string.concat(identifier);

    document.getElementById(result).classList.toggle("show");

    // Hide the original comment's content
    string = "postContent";
    result = string.concat(identifier);
    document.getElementById(result).classList.toggle("hide");

    // Hide the ratings
    string = "ratingPostForm";
    result = string.concat(identifier);
    document.getElementById(result).classList.toggle("hide");

    // Hide the buttons
    string = "postModifyButtons";
    result = string.concat(identifier);
    document.getElementById(result).classList.toggle("hide");
    
    // Hide the comment counters
    string = "numPostComments";
    result = string.concat(identifier);
    document.getElementById(result).classList.toggle("hide");
}

function showDeletePost(identifier) {
    // Show the delete form
    var string = "deletePostForm";
    var result = string.concat(identifier);
    document.getElementById(result).classList.toggle("showDelete");

    // Hide the original comment's content
    string = "postContent";
    result = string.concat(identifier);
    document.getElementById(result).classList.toggle("hide");

    // Hide the ratings
    string = "ratingPostForm";
    result = string.concat(identifier);
    document.getElementById(result).classList.toggle("hide");

    // Hide the buttons
    string = "postModifyButtons";
    result = string.concat(identifier);
    document.getElementById(result).classList.toggle("hide");
    
    // Hide the comment counters
    string = "numPostComments";
    result = string.concat(identifier);
    document.getElementById(result).classList.toggle("hide");
}

/********************************   COMMENTS  ********************************/
function showEditComment(identifier) {
    var string = "editCommentForm";
    var result = string.concat(identifier);
   
    document.getElementById(result).classList.toggle("show");

    // Hide the original comment's content
    string = "commentContent";
    result = string.concat(identifier);
    document.getElementById(result).classList.toggle("hide");

    // Hide the ratings
    string = "ratingCommentForm";
    result = string.concat(identifier);
    document.getElementById(result).classList.toggle("hide");

    // Hide the buttons
    string = "commentModifyButtons";
    result = string.concat(identifier);
    document.getElementById(result).classList.toggle("hide");
}

function showDeleteComment(identifier) {
    var string = "deleteCommentForm";
    var result = string.concat(identifier);
   
    document.getElementById(result).classList.toggle("showDelete");

    // Hide the original comment's content
    string = "commentContent";
    result = string.concat(identifier);
    document.getElementById(result).classList.toggle("hide");

    // Hide the ratings
    string = "ratingCommentForm";
    result = string.concat(identifier);
    document.getElementById(result).classList.toggle("hide");

    // Hide the buttons
    string = "commentModifyButtons";
    result = string.concat(identifier);
    document.getElementById(result).classList.toggle("hide");
}

