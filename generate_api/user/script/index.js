// signup
$(document).ready(function(){
    $(".signup-form").submit(function(e){
        e.preventDefault();
        $.ajax({
            type : "POST",
            url : "php/signup.php",
            data : new FormData(this),
            processData : false,
            contentType : false,
            cache : false,
            beforeSend : function(){
                $(".signup-notice").html("Signup process..");
            },
            success : function(response){
                $(".signup-notice").html(response);
            }
        });
    });
});

// signin
$(document).ready(function(){
    $(".signin-form").submit(function(e){
        e.preventDefault();
        $.ajax({
            type : "POST",
            url : "php/signin.php",
            data : new FormData(this),
            processData : false,
            contentType : false,
            cache : false,
            beforeSend : function(){
                $(".signin-notice").html("Login process..");
            },
            success : function(response){
                if(response.trim() != "Wrong username or password !"){
                    $("#api-field").val(response.trim());
                    $(".signin-form").addClass("d-none");
                    $(".signin-header").html("Api Key's");
                    $(".api-box").removeClass("d-none");

                    // copy api code 
                    $(".copy-btn").click(function(){
                        $("#api-field").select();
                        document.execCommand('copy');
                        $(this).html("Copied");
                    });
                }
                else{
                    $(".signin-notice").html(response);
                }
            }
        });
    });
});

// fetch data by api
$(document).ready(function(){
    $.ajax({
        type : "POST",
        url : "../server/index.php",
        cache : false,
        data : {
            api_key : "c3e2536a3cc6b44320d7a46dc9ea9405",
            roll_no : "1",
            student_name : "brajesh kumar"
        },
        success : function(response){
            console.log(response);
        }
    });
});