<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<form class="contacts" id="form-contacts" name="frmContact" novalidate="novalidate">
    <div class="contacts-send">Success!<br>Thank you for your message!</div>
    <div class="title title-xxl center">Contacts</div>
    <div class="contacts-text">Do you have any questions or suggestions? Write to us!</div>
    <div class="contacts-input"><input placeholder="Your email" id="email" name="email"></div>
    <div class="contacts-textarea"><textarea placeholder="Write something..." id="message" name="message"></textarea></div>
    <div class="contacts-bottom">
        <button class="button" type="submit"><span>Submit<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.1503 16.9283C13.3612 16.9283 13.5656 16.8427 13.7435 16.6647L18.0677 12.3536C18.2325 12.1888 18.3314 11.9581 18.3314 11.7274C18.3314 11.4967 18.2325 11.2594 18.0677 11.1012L13.7567 6.79016C13.5721 6.60559 13.3612 6.5199 13.1503 6.5199C12.6625 6.5199 12.3197 6.86926 12.3197 7.3241C12.3197 7.57458 12.4252 7.77234 12.5768 7.93054L14.0731 9.43347L15.7408 10.9628L14.2577 10.8837H6.5387C6.01794 10.8837 5.66858 11.2264 5.66858 11.7274C5.66858 12.2284 6.01794 12.5712 6.5387 12.5712H14.2577L15.7408 12.4855L14.0731 14.0214L12.5768 15.5177C12.4252 15.6759 12.3197 15.8737 12.3197 16.1241C12.3197 16.5856 12.6625 16.9283 13.1503 16.9283Z" fill="white/"></path></svg></span></button>

    </div>
</form>
<script
        src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js">

</script>
<script>
    $("#form-contacts").validate({
        errorElement: "span",
        rules: {name: {required: !0}, email: {required: !0, email: !0}, message: {required: !0}},
        messages: {
            name: "Please provide a valid name.",
            email: {required: "Please enter your email", minlength: "Please enter a valid email address"},
            phone: {
                required: "Please provide a phone number",
                minlength: "Phone number must be min 10 characters long",
                maxlength: "Phone number must not be more than 10 characters long"
            },
            subject: "Please enter subject",
            message: "Please enter your message"
        },
        highlight: function (e, t) {
            $(e).closest(".contacts-input, .contacts-textarea").addClass("is-error")
        },
        unhighlight: function (e, t, n) {
            $(e).closest(".contacts-input, .contacts-textarea").removeClass("is-error")
        },
        submitHandler: function (e) {
            var t = $("#form-contacts").serializeArray();

            $.ajax({
                type: "POST",
                url: "/main.php",
                data: t,
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        $(".contacts-send").addClass('is-active');

                        setTimeout(() => {
                            $(".contacts-send").removeClass('is-active');
                        }, 3000);
                        $("#form-contacts").get(0).reset();
                    } else {
                        alert(response.error);
                    }
                },
            })
        }
    })
</script>
</body>
</html>